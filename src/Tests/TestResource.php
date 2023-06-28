<?php

declare(strict_types=1);

namespace BRCas\LaravelPackage\Tests;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Testing\TestResponse;

trait TestResource
{
    protected function assertResource(TestResponse $response, JsonResource $resource)
    {
        $response->assertJson($resource->response()->getData(true));
    }
}
