<?php

declare(strict_types=1);

namespace BRCas\LaravelTests;

use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves{
    protected abstract function routeStorage();
    
    protected abstract function routeUpdate();

    protected abstract function model();

    protected function assertStore(array $sendData, array $testDatabase, array $testJson = null) :TestResponse{
        /** @var TestResponse $response */
        $response = $this->json('POST', $this->routeStorage(), $sendData);
        if($response->status() != 201){
            throw new \Exception("Response status must be 201, given {$response->status()}\n{$response->content()}");
        }

        $model = $this->model();
        $table = (new $model())->getTable();
        $this->assertDatabaseHas($table, ["id" => $response->json('id')] + $testDatabase);
        $testResponse = $testJson ? $testJson : $testDatabase;
        $response->assertJsonFragment(["id" => $response->json('id')] + $testResponse);

        return $response;
    }

    protected function assertUpdate(array $sendData, array $testDatabase, array $testJson = null) :TestResponse{
        /** @var TestResponse $response */
        $response = $this->json('PUT', $this->routeUpdate(), $sendData);
        if($response->status() != 200){
            throw new \Exception("Response status must be 201, given {$response->status()}\n{$response->content()}");
        }

        $model = $this->model();
        $table = (new $model())->getTable();
        $this->assertDatabaseHas($table, ["id" => $response->json('id')] + $testDatabase);
        $testResponse = $testJson ? $testJson : $testDatabase;
        $response->assertJsonFragment(["id" => $response->json('id')] + $testResponse);

        return $response;
    }
}