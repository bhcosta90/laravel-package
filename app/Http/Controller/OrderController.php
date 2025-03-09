<?php

declare(strict_types = 1);

namespace App\Http\Controller;

use App\Models\Order;
use Illuminate\Support\Collection;

class OrderController
{
    public function index(): Collection
    {
        return Order::all();
    }
}
