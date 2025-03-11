<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits\Helper;

trait AsAddRequest
{
    abstract protected function request(): array;
}
