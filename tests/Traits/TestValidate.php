<?php

declare(strict_types=1);

namespace BRCas\LaravelTests;

use Illuminate\Foundation\Testing\TestResponse;

trait TestValidate{
    protected abstract function routeStorage();
    
    protected abstract function routeUpdate();

    protected function assertValidationInStorageAction(
        array $data,
        string $rule,
        array $params = []
    )
    {
        $response = $this->json('POST', $this->routeStorage(), $data);
        $fields = array_keys($data);
        $this->assertInvationFields($response, $fields, $rule, $params);
    }

    protected function assertValidationInUpdateAction(
        array $data,
        string $rule,
        array $params = []
    )
    {
        $response = $this->json('PUT', $this->routeUpdate(), $data);
        $fields = array_keys($data);
        $this->assertInvationFields($response, $fields, $rule, $params);
    }

    protected function assertInvationFields(
        TestResponse $response, 
        array $fields,
        string $rule,
        array $params = [])
    {
        if($response->status() != 422){
            throw new \Exception("Response status must be 201, given {$response->status()}\n{$response->content()}");
        }

        $response->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach($fields as $field){
            $fieldName = str_replace('_', ' ', $field);
            $response->assertJsonFragment([
                \Lang::get("validation.{$rule}", ['attribute' => $fieldName] + $params)
            ]);
        }
    }
}