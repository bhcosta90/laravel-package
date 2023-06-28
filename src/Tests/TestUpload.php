<?php

declare(strict_types=1);

namespace BRCas\Laravel\Tests;

use App\Models\Traits\UploadFiles;
use Illuminate\Http\UploadedFile;

trait TestUpload
{
    use TestValidation;

    protected function assertInvalidationFile(
        string $field,
        string $extension,
        ?int $maxSize,
        string $rule,
        array $ruleParams = []
    ) {
        $routes = [
            [
                'method' => 'POST',
                'route' => $this->routeStore()
            ],
            [
                'method' => 'PUT',
                'route' => $this->routeUpdate()
            ],
        ];

        $file = UploadedFile::fake()->create("$field.1$extension");

        foreach ($routes as $route) {
            $response = $this->json($route['method'], $route['route'], [
                $field => $file
            ]);

            $this->assertInvalidationFields($response, [$field], $rule, $ruleParams);

            if ($maxSize) {
                $file = UploadedFile::fake()->create("$field.$extension")->size($maxSize + 1);
                $response = $this->json($route['method'], $route['route'], [
                    $field => $file
                ]);

                $this->assertInvalidationFields($response, [$field], 'max.file', ['max' => $maxSize]);
            }
        }
    }

    protected function assertFilesExistsInStorage($model, array $files)
    {
        /** @var UploadFiles $model */
        foreach ($files as $file) {
            \Storage::assertExists($model->relativeFilePath($file->hashName()));
        }
    }
}
