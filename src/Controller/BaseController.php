<?php

declare(strict_types = 1);

namespace CodeFusion\src\Controller;

use CodeFusion\src\Controller\Traits\{AsControllerUpdateTrait};
use CodeFusion\src\Controller\Traits\AsControllerDeleteTrait;
use CodeFusion\src\Controller\Traits\AsControllerIndexTrait;
use CodeFusion\src\Controller\Traits\AsControllerShowTrait;
use CodeFusion\src\Controller\Traits\AsControllerStoreTrait;

abstract class BaseController
{
    use AsControllerDeleteTrait;
    use AsControllerIndexTrait;
    use AsControllerShowTrait;
    use AsControllerStoreTrait;
    use AsControllerUpdateTrait;
}
