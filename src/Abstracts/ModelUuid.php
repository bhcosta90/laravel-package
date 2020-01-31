<?php


namespace BRCas\Laravel\Abstracts;

use BRCas\Laravel\Traits\Models\Uuid;
use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class ModelUuid extends EloquentModel
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];
    protected $hidden = [
        'deleted_at'
    ];
}
