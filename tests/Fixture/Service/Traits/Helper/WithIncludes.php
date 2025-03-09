<?php

declare(strict_types = 1);

namespace CodeFusion\Tests\Fixture\Service\Traits\Helper;

use CodeFusion\Service\Traits\Helper\WithIncludesTrait;

class WithIncludes
{
    use WithIncludesTrait;

    public function transform(array $includes): array
    {
        return $this->transformWithIncludes($includes);
    }
}
