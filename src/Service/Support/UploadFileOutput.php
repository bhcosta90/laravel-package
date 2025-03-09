<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Support;

class UploadFileOutput
{
    public function __construct(
        public string $path,
    ) {
    }
}
