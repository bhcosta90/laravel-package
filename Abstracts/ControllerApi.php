<?php


namespace Costa\Package\Abstracts;

use Illuminate\Routing\Controller as BaseControllerLaravel;
use Costa\Package\Traits\Controller\Api\{ApiCreateTrait, ApiDestroyTrait, ApiEditTrait, ApiIndexTrait, ApiShowTrait};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class ControllerApi extends BaseControllerLaravel
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ApiCreateTrait, ApiDestroyTrait, ApiEditTrait, ApiIndexTrait, ApiShowTrait;
}
