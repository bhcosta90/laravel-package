<?php

declare(strict_types = 1);

namespace CodeFusion\Resource;

use Illuminate\Http\Resources\ConditionallyLoadsAttributes;

trait ValidateLoaded
{
    use ConditionallyLoadsAttributes;

    protected function verifyLoaded($relationship, $value = null, $default = null): mixed
    {
        $includedRelationships = explode(',', request()->input('includes', ''));

        return $this->when(
            collect($includedRelationships)->contains(fn ($item) => str_contains($item, $relationship)),
            fn () => $this->whenLoaded($relationship, $value, $default)
        );
    }
}
