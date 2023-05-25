<?php

declare(strict_types=1);

namespace BRCas\Laravel\Tests;

use Illuminate\Testing\TestResponse;

trait TestValidate
{
    protected function assertValidationInStorageAction(
        array $data,
        string $rule,
        array $params = []
    )
    {
        $response = $this->json('POST', $this->routeStorage(), $data);
        foreach ($this->removeArrayData() as $rs) {
            unset($data[$rs]);
        }
        $fields = array_keys($data);
        $this->assertInvationFields($response, $fields, $rule, $params);
    }

    protected abstract function routeStorage();

    protected function removeArrayData(): array
    {
        return [
            'token'
        ];
    }

    protected function assertInvationFields(
        TestResponse $response,
        array $fields,
        string $rule,
        array $params = []
    )
    {
        if ($response->status() != 422) {
            throw new \Exception("Response status must be 201, given {$response->status()}\n{$response->content()}");
        }

        $response->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $fieldName = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                \Lang::get("validation.{$rule}", ['attribute' => $fieldName] + $params)
            ]);
        }
    }

    protected function assertValidationInUpdateAction(
        array $data,
        string $rule,
        array $params = []
    )
    {
        $response = $this->json('PUT', $this->routeUpdate(), $data);
        foreach ($this->removeArrayData() as $rs) {
            unset($data[$rs]);
        }
        $fields = array_keys($data);
        $this->assertInvationFields($response, $fields, $rule, $params);
    }

    protected abstract function routeUpdate();
}
