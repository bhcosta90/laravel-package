date:
	date '+%Y-%m-%d %H:%M:%S' > version

dev-delete-tag:
	git fetch
	@if [ -z "$(version)" ]; then \
		echo "Error: You must specify the version as MAJOR.MINOR."; \
		exit 1; \
	fi
	@echo "Removing tags matching 'dev-$(version).*'..."
	@for tag in $(shell git tag -l "dev-$(version).*"); do \
		git tag -d $$tag && git push origin --delete $$tag; \
	done
	@echo "All 'dev-$(version).*' tags have been removed."

all-delete-tag:
	git fetch
	@if [ -z "$(version)" ]; then \
		echo "Error: You must specify the version as MAJOR.MINOR."; \
		exit 1; \
	fi
	@echo "Removing tags matching '$(version).*'..."
	@for tag in $(shell git tag -l "$(version).*"); do \
		git tag -d $$tag && git push origin --delete $$tag; \
	done
	@echo "All '$(version).*' tags have been removed."

help:
	@echo "  make date                   - Creates a version file with the current date and time"
	@echo "  make delete-tag version=0.0 - Removes tags matching 'dev-version.*' (e.g., 'dev-0.0.*')"
