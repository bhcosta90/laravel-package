<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits\Helper;

trait AsAddRequest
{
    abstract protected function request(): array;

    public function getRulesByRequest(array $rules): array
    {
        $response = [];

        foreach ($rules as $key => $rule) {
            if (is_string($rule)) {
                $rule = explode('|', $rule);
            }

            foreach ($rule as $value) {
                if (
                    is_string($value)
                    && preg_match('/\b(required|nullable|numeric)\b/', $value)
                ) {
                    $response[$key][] = $value;
                }
            }
        }

        return $response;
    }
}
