<?php

declare(strict_types = 1);

namespace CodeFusion\Controller;

use CodeFusion\Controller\Traits\{AsControllerDeleteTrait, AsControllerIndexTrait, AsControllerShowTrait, AsControllerStoreTrait, AsControllerUpdateTrait};

abstract class BaseController
{
    use AsControllerDeleteTrait;
    use AsControllerIndexTrait;
    use AsControllerShowTrait;
    use AsControllerStoreTrait;
    use AsControllerUpdateTrait;
}
