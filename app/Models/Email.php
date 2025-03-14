<?php

declare(strict_types = 1);

namespace App\Models;

use CodeFusion\Model\Traits\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = ['value'];
}
