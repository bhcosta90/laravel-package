<?php

declare(strict_types = 1);

namespace App\Models;

use CodeFusion\Model\Traits\AsHashId;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use AsHashId;

    protected $fillable = [
        'customer_id',
        'type',
    ];
}
