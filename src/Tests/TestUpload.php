<?php

declare(strict_types=1);

namespace BRCas\Package\Tests;

use Illuminate\Http\UploadedFile;

trait TestUpload
{
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

    protected abstract function routeStorage();

    protected abstract function routeUpdate();
}
