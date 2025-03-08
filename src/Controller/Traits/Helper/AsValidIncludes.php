<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits\Helper;

trait AsValidIncludes
{
    public function getValidIncludes(array $allowedIncludes, string $requestIncludes): array
    {
        if (blank($requestIncludes)) {
            return [];
        }

        $arrRequestIncludes = explode('|', $request->includes ?? '');

        return array_intersect_key($allowedIncludes, $arrRequestIncludes);
    }
}
