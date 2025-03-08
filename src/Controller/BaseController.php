<?php

declare(strict_types = 1);

namespace CodeFusion\Controller;

use CodeFusion\Controller\Traits\{AsControllerUpdateTrait};
use CodeFusion\Controller\Traits\AsControllerDeleteTrait;
use CodeFusion\Controller\Traits\AsControllerIndexTrait;
use CodeFusion\Controller\Traits\AsControllerShowTrait;
use CodeFusion\Controller\Traits\AsControllerStoreTrait;

abstract class BaseController
{
    use AsControllerDeleteTrait;
    use AsControllerIndexTrait;
    use AsControllerShowTrait;
    use AsControllerStoreTrait;
    use AsControllerUpdateTrait;
}
