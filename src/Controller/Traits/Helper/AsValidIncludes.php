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

        $arrRequestIncludes = explode('|', $requestIncludes);

        $data = array_intersect_key($allowedIncludes, $arrRequestIncludes);

        $result = [];

        foreach ($arrRequestIncludes as $include) {
            foreach ($data as $item) {
                if (str_contains($item, $include)) {
                    $result[] = $include;
                }
            }
        }

        return $result;
    }
}
