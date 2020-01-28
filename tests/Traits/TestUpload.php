<?php

declare(strict_types=1);

namespace BRCas\LaravelTests\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\TestResponse;

trait TestUpload
{
    protected abstract function routeStorage();

    protected abstract function routeUpdate();

    public function assertInvalidateFiles($field, $extension, $maxSize, $rule, $ruleParams)
    {
        $routes = [
            [
                'method' => "POST",
                "route" => $this->routeStorage(),
            ],
            [
                'method' => "PUT",
                "route" => $this->routeUpdate(),
            ]
        ];

        foreach ($routes as $route) {
            $file = UploadedFile::fake()->create("$field.1$extension");

            $response = $this->json($route['method'], $route['route'], [
                $field => $file,
            ]);
            $this->assertInvationFields($response, [$field], $rule, $ruleParams);

            $file = UploadedFile::fake()->create("$field.$extension")->size($maxSize + 1);

            $response = $this->json($route['method'], $route['route'], [
                $field => $file,
            ]);

            $this->assertInvationFields($response, [$field], "max.file", ['max' => $maxSize]);
        }
    }
}
