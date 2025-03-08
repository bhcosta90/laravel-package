<?php

declare(strict_types = 1);

namespace CodeFusion\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait AsHasMany
{
    public function hasMany($related, $foreignKey = null, $localKey = null, bool $is_deleted = false): HasMany
    {
        $instance   = $this->newRelatedInstance($related);
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey   = $localKey ?: $this->getKeyName();

        return new HasManySynchronizable(
            $instance->newQuery(),
            $this,
            $instance->getTable() . '.' . $foreignKey,
            $localKey,
            $is_deleted
        );
    }
}
