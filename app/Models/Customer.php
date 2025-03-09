<?php

declare(strict_types = 1);

namespace App\Models;

use CodeFusion\Model\Traits\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\{Builder, Model};

class Customer extends Model
{
    use BaseModel;
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, is_deleted: true);
    }

    public function scopeByName(Builder $builder, array $name): void
    {
        $builder->when(
            $name,
            fn (Builder $builder) => $builder->where('name', $name)
        );
    }

    public function scopeByType(Builder $builder, string | array $type = []): void
    {
        $builder->when(
            $type,
            fn (Builder $builder) => $builder->whereIn('type', (array) $type)
        );
    }
}
