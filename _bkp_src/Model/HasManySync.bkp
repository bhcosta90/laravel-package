<?php

declare(strict_types = 1);

namespace CodeFusion\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\{Builder, Model};

class HasManySync extends HasMany
{
    public function __construct(
        Builder $query,
        Model $parent,
        $foreignKey,
        $localKey,
        readonly public bool $isDeleted = false
    ) {
        parent::__construct($query, $parent, $foreignKey, $localKey);
    }

    public function sync($data, $deleting = true): array
    {
        $changes = [
            'deleted' => [],
        ];

        $relatedKeyName = $this->related->getKeyName();
        $current        = $this->newQuery()->pluck($relatedKeyName)->all();

        // Separating rows for update and insert
        [$updateRows, $newRows] = $this->separateRowsForUpdateAndInsert($data, $current, $relatedKeyName);

        // Deleting rows that do not need to be updated
        if ($this->isDeleted
            && $deleting
            && ($deleteIds = $this->getDeleteIds($current, array_keys($updateRows))) !== []
        ) {
            $this->deleteRows($deleteIds);
            $changes['deleted'] = $this->castKeys($deleteIds);
        }

        // Updating rows
        $changes['updated'] = $this->updateRows($updateRows, $relatedKeyName);

        // Inserting new rows
        $changes['created'] = $this->insertNewRows($newRows, $relatedKeyName);

        return $changes;
    }

    protected function separateRowsForUpdateAndInsert($data, $current, $relatedKeyName): array
    {
        $updateRows = [];
        $newRows    = [];

        foreach ($data as $row) {
            if (!empty($row[$relatedKeyName]) && in_array($row[$relatedKeyName], $current, true)) {
                $updateRows[$row[$relatedKeyName]] = $row;
            } else {
                $newRows[] = $row;
            }
        }

        return [$updateRows, $newRows];
    }

    protected function getDeleteIds($current, $updateIds): array
    {
        return array_diff($current, $updateIds);
    }

    protected function deleteRows($deleteIds): void
    {
        $this->getRelated()->destroy($deleteIds);
    }

    protected function updateRows($updateRows, $relatedKeyName): array
    {
        foreach ($updateRows as $id => $row) {
            $this->getRelated()->where($relatedKeyName, $id)
                ->update($row);
        }

        return $this->castKeys(array_keys($updateRows));
    }

    protected function insertNewRows($newRows, $relatedKeyName): array
    {
        $newIds = [];

        foreach ($newRows as $row) {
            $newModel = $this->create($row);
            $newIds[] = $newModel->$relatedKeyName;
        }

        return $this->castKeys($newIds);
    }

    protected function castKeys(array $keys): array
    {
        return array_map([$this, 'castKey'], $keys);
    }

    protected function castKey($key): int | string
    {
        return is_numeric($key) ? (int) $key : (string) $key;
    }
}
