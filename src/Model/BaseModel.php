<?php

declare(strict_types = 1);

namespace CodeFusion\Model;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use AsHasMany;
}
