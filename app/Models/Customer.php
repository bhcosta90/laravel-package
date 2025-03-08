<?php

declare(strict_types = 1);

namespace App\Models;

use CodeFusion\Model\{AsHasMany, Filterable};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use AsHasMany;
    use HasFactory;
    use Filterable;

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
}
