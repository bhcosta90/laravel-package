<?php

declare(strict_types=1);

namespace BRCas\Package\Tests;

use Illuminate\Testing\TestResponse;

trait TestList
{
    protected abstract function routeStorage();

    protected abstract function model();

    protected function assertIndex($data, array $sendData = []): TestResponse
    {
        $response = $this->json('GET', $this->routeIndex(), $sendData);
        $response->assertStatus(200);
        $response->assertJson(["data" => [$data]]);
        return $response;
    }

    protected abstract function routeIndex();

    protected function assertShow($data, array $sendData = []): TestResponse
    {
        $response = $this->json('GET', $this->routeShow(), $sendData);
        $response->assertStatus(200);
        $response->assertJson(["data" => $data]);
        return $response;
    }

    protected abstract function routeShow();

}