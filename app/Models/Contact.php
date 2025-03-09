<?php

declare(strict_types = 1);

namespace App\Models;

use CodeFusion\Model\Traits\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Builder, Model};

class Contact extends Model
{
    use HasFactory;
    use BaseModel;

    protected $fillable = [
        'name',
        'is_principal',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopePrincipal(Builder $builder): void
    {
        $builder->where('is_principal', true);
    }
}
