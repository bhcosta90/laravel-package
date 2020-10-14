<?php

declare(strict_types=1);

namespace BRCas\LaravelTests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{
    protected function assertStore(array $sendData, array $testDatabase, array $testJson = null): TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('POST', $this->routeStorage(), $sendData);
        if ($response->status() != 201) {
            throw new \Exception("Response status must be 201, given {$response->status()}\n{$response->content()}");
        }

        $model = $this->model();
        $table = (new $model())->getTable();

        if (method_exists($this, 'removeArrayData')) {
            foreach ($this->removeArrayData() as $rs) {
                unset($testDatabase[$rs]);
            }
        }

        $this->assertDatabaseHas($table, ["id" => $this->getIdFromResponse($response)] + $testDatabase);
        $testResponse = $testJson ? $testJson : $testDatabase;
        $response->assertJsonFragment(["id" => $this->getIdFromResponse($response)] + $testResponse);

        return $response;
    }

    protected abstract function routeStorage();

    protected abstract function model();

    private function getIdFromResponse(TestResponse $response)
    {
        return $response->json('id') ?? $response->json('data.id');
    }

    protected function assertUpdate(array $sendData, array $testDatabase, array $testJson = null): TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('PUT', $this->routeUpdate(), $sendData);
        if ($response->status() != 200) {
            throw new \Exception("Response status must be 201, given {$response->status()}\n{$response->content()}");
        }

        $model = $this->model();
        $table = (new $model())->getTable();

        if (method_exists($this, 'removeArrayData')) {
            foreach ($this->removeArrayData() as $rs) {
                unset($testDatabase[$rs]);
            }
        }

        $this->assertDatabaseHas($table, ["id" => $this->getIdFromResponse($response)] + $testDatabase);
        $testResponse = $testJson ? $testJson : $testDatabase;
        $response->assertJsonFragment(["id" => $this->getIdFromResponse($response)] + $testResponse);

        return $response;
    }

    protected abstract function routeUpdate();
}
