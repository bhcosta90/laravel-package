<?php

declare(strict_types = 1);

namespace CodeFusion\src\Service;

use CodeFusion\src\Service\Traits\{AsServiceIndexTrait};
use CodeFusion\src\Service\Traits\AsServiceDestroyTrait;
use CodeFusion\src\Service\Traits\AsServiceShowTrait;
use CodeFusion\src\Service\Traits\AsServiceStoreTrait;
use CodeFusion\src\Service\Traits\AsServiceUpdateTrait;

abstract class BaseService
{
    use AsServiceIndexTrait;
    use AsServiceShowTrait;
    use AsServiceStoreTrait;
    use AsServiceUpdateTrait;
    use AsServiceDestroyTrait;
}
