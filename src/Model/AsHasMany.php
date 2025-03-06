<?php

namespace CodeFusion\src\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait AsHasMany
{
    public function hasMany($related, $foreignKey = null, $localKey = null): HasMany
    {
        $instance   = $this->newRelatedInstance($related);
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey   = $localKey ?: $this->getKeyName();

        return new HasManySynchronizable(
            $instance->newQuery(),
            $this,
            $instance->getTable() . '.' . $foreignKey,
            $localKey
        );
    }
}
