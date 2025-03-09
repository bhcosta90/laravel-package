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
        'photo',
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
        $builder->when($name, function (Builder $builder) use ($name) {
            $builder->where('name', $name);
        });
    }
}
