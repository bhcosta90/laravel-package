<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

trait AsControllerTrait
{
    use AsControllerDeleteTrait;
    use AsControllerIndexTrait;
    use AsControllerShowTrait;
    use AsControllerStoreTrait;
    use AsControllerUpdateTrait;
}
