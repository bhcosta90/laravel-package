<?php

declare(strict_types = 1);

namespace CodeFusion\Service;

use CodeFusion\Service\Traits\{AsServiceDestroyTrait, AsServiceIndexTrait, AsServiceShowTrait, AsServiceStoreTrait, AsServiceUpdateTrait};

abstract class BaseService
{
    use AsServiceIndexTrait;
    use AsServiceShowTrait;
    use AsServiceStoreTrait;
    use AsServiceUpdateTrait;
    use AsServiceDestroyTrait;
}
