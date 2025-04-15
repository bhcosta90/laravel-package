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
        $onlyIncludes       = [];

        foreach ($arrRequestIncludes as $include) {
            $arrDot    = explode('.', $include);
            $resultDot = [];

            foreach ($arrDot as $dot) {
                [$table]     = explode(':', $dot);
                $resultDot[] = $table;
            }

            $onlyIncludes[] = implode('.', $resultDot);
        }

        return array_intersect_key($allowedIncludes, $onlyIncludes);
    }
}
