<?php

declare(strict_types=1);

namespace BRCas\LaravelTests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestList{
    protected abstract function routeStorage();
    
    protected abstract function routeIndex();
    
    protected abstract function routeShow();

    protected abstract function model();

    protected function assertIndex($data, array $sendData = []): TestResponse
    {
        $response = $this->json('GET', $this->routeIndex(), $sendData);
        $response->assertJson(["data" => [$data]]);
        return $response;
    }

    protected function assertShow($data, array $sendData = []): TestResponse
    {
        $response = $this->json('GET', $this->routeShow(), $sendData);
        $response->assertJson(["data" => $data]);
        return $response;
    }

}