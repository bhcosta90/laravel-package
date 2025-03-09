<?php

declare(strict_types = 1);

namespace App\Models;

use CodeFusion\Crypt\Model\AsHashId;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use AsHashId;

    protected $fillable = [
        'customer_id',
        'type',
    ];
}
