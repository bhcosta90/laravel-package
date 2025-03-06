<?php

namespace CodeFusion\src\Resource;

use Illuminate\Http\Resources\ConditionallyLoadsAttributes;

trait ValidateLoaded {

    use ConditionallyLoadsAttributes;

    protected function validateLoaded($relationship, $value = null, $default = null): mixed
    {
        $includedRelationships = explode(',', request()->input('include', ''));

        return $this->when(
            collect($includedRelationships)->contains(fn ($item) => str_contains($item, $relationship)),
            fn() => $this->whenLoaded($relationship, $value, $default)
        );
    }
}