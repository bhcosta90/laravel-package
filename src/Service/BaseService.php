<?php

declare(strict_types = 1);

namespace CodeFusion\Service;

use CodeFusion\Service\Traits\{AsServiceDestroyTrait, AsServiceShowTrait, AsServiceStoreTrait, AsServiceUpdateTrait};
use CodeFusion\Service\Traits\{AsServiceIndexTrait};

abstract class BaseService
{
    use AsServiceIndexTrait;
    use AsServiceShowTrait;
    use AsServiceStoreTrait;
    use AsServiceUpdateTrait;
    use AsServiceDestroyTrait;
}
