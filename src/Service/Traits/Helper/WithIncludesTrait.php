<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits\Helper;

use Illuminate\Database\Eloquent\Builder;

trait WithIncludesTrait
{
    protected function withIncludes(Builder $model, array $includes = []): void
    {
        $includes = $this->transformWithIncludes($includes);

        $model->with($includes);
    }

    protected function transformWithIncludes(array $input = []): array
    {
        $output = [];

        foreach ($input as $string) {
            $arrOnlyTable = "";

            $existDot = explode(':', $string);

            $parts = explode('.', $string);

            $temp = [];

            foreach ($parts as $part) {
                $temp[] = empty($temp) ? $part : end($temp) . '.' . $part;
            }

            if (count($existDot) === 1) {
                $output[] = $string;

                continue;
            }

            foreach ($temp as $value) {
                $datDot = explode('.', $value);

                if (count($datDot) === 1) {
                    $output[] = $datDot[0];

                    continue;
                }

                $lastTable = array_pop($datDot);

                $prefix = array_map(fn ($valueDot) => explode(':', $valueDot)[0], $datDot);

                $output[] = implode('.', $prefix) . "." . $lastTable;

            }
        }

        return array_unique($output);
    }
}
