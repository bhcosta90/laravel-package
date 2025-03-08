<?php

declare(strict_types = 1);

namespace App\Models;

use CodeFusion\Model\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Email extends BaseModel
{
    use HasFactory;

    protected $fillable = ['value'];
}
