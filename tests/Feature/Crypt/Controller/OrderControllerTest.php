<?php

declare(strict_types = 1);

use App\Http\Controller\{OrderController};
use App\Models\{Order};
use CodeFusion\Crypt\Facade\HashId;
use CodeFusion\Crypt\Provider\CryptServiceProvider;
use Illuminate\Support\Facades\{Config, Route};

use function Pest\Laravel\get;

beforeEach(function (): void {
    putenv('APP_KEY=mocked-app-key');
    Config::set('app.key', 'mocked-app-key');
    $this->app->register(CryptServiceProvider::class);

    Route::apiResource('order', OrderController::class);

    $this->order = Order::factory()->create();
});

test('order index returns correct data', function (): void {
    $id = HashId::encode($this->order->id);

    Config::set('hashids.enable', true);

    $response = get(route('order.index'))
        ->assertStatus(200)
        ->assertJsonFragment([
            'id' => $this->order->id,
        ]);

    expect($id)->toBe($response->json('0.id'));
});
