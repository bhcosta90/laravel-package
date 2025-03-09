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

        $tableFields = [];

        foreach ($input as $string) {

            $existDot = explode(':', $string);

            $parts = explode('.', $string);

            $temp = [];

            foreach ($parts as $part) {
                $temp[] = $table = empty($temp) ? $part : end($temp) . '.' . $part;

                $tableFields[$table] = ["*"];
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

        if (method_exists($this, 'filterInclude')) {

            foreach ($output as $valueOutput) {
                $arrValueOutput = explode(':', $valueOutput);

                if (empty($arrValueOutput[1])) {
                    $arrValueOutput[1] = "*";
                }

                $tableFields[$arrValueOutput[0]] = explode(',', $arrValueOutput[1]);
            }

            foreach ($this->filterInclude($tableFields) as $keyInclude => $valueInclude) {

                foreach ($output as $keyOutput => $valueOutput) {
                    [$tableValueOutput] = explode(':', $valueOutput);

                    if ($tableValueOutput === $keyInclude) {
                        unset($output[$keyOutput]);
                        $stringKeyInclude = $keyInclude;

                        $output[$stringKeyInclude] = $valueInclude;
                    }
                }

                if (!isset($output[$keyInclude])) {
                    $output[$keyInclude] = $valueInclude;
                }
            }
        }

        return $output;
    }
}
