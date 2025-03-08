<?php

declare(strict_types = 1);

namespace CodeFusion\Service;

use CodeFusion\Service\Traits\{AsServiceIndexTrait};
use CodeFusion\Service\Traits\AsServiceDestroyTrait;
use CodeFusion\Service\Traits\AsServiceShowTrait;
use CodeFusion\Service\Traits\AsServiceStoreTrait;
use CodeFusion\Service\Traits\AsServiceUpdateTrait;

abstract class BaseService
{
    use AsServiceIndexTrait;
    use AsServiceShowTrait;
    use AsServiceStoreTrait;
    use AsServiceUpdateTrait;
    use AsServiceDestroyTrait;
}
