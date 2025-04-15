<?php

declare(strict_types = 1);

namespace CodeFusion\Resource;

use Illuminate\Http\Resources\ConditionallyLoadsAttributes;

trait ValidateLoaded
{
    use ConditionallyLoadsAttributes;

    protected function whenLoaded($relationship, $value = null, $default = null): mixed
    {
        $includedRelationships = explode(',', request()->input('includes', ''));

        return $this->when(
            collect($includedRelationships)->contains(fn ($item): bool => str_contains((string) $item, (string) $relationship)),
            fn () => parent::whenLoaded($relationship, $value, $default)
        );
    }
}
