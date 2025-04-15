<?php

declare(strict_types = 1);

use CodeFusion\Tests\Fixture\Controller\Traits\Helper\AsValidIncludes;

beforeEach(fn (): AsValidIncludes => $this->class = new AsValidIncludes());

test('it returns an empty array for empty input', function (): void {
    $response = $this->class->getValidIncludes([], "");
    expect($response)->toBe([]);
});

test('it returns the valid includes for valid input', function (): void {
    $response = $this->class->getValidIncludes([
        'contacts',
    ], "contacts.customer");

    expect($response)->toBe([
        'contacts',
    ]);
});

test('it returns the valid includes for nested input', function (): void {
    $response = $this->class->getValidIncludes([
        'contacts.customer',
    ], "contacts:id,name.customer");

    expect($response)->toBe([
        'contacts.customer',
    ]);
});
