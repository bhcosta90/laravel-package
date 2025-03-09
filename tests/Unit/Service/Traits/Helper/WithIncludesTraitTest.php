<?php

declare(strict_types = 1);

use CodeFusion\Tests\Fixture\Service\Traits\Helper\WithIncludes;

beforeEach(function () {
    $this->class = new WithIncludes();
});

test('transform with contact customer', function () {
    $response = $this->class->transform([
        "contact.customer",
    ]);

    expect($response)->toBe([
        'contact.customer',
    ]);
});

test('transform with contact customer id and name', function () {
    $response = $this->class->transform([
        "contact.customer:id,name",
    ]);

    expect($response)->toBe([
        'contact',
        'contact.customer:id,name',
    ]);
});

test('transform with complex nested structure', function () {
    $response = $this->class->transform([
        "contact:id,name.customer:id,name.email:value.xablau:id,name",
    ]);

    expect($response)->toBe([
        'contact:id,name',
        'contact.customer:id,name',
        'contact.customer.email:value',
        'contact.customer.email.xablau:id,name',
    ]);
});
