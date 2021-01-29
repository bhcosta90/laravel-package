<?php


namespace Costa\Package\Abstracts;

use Costa\Package\Traits\Controller\Api\{ApiCreateTrait, ApiDestroyTrait, ApiEditTrait, ApiIndexTrait, ApiShowTrait};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseControllerLaravel;

abstract class ControllerApi extends BaseControllerLaravel
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ApiCreateTrait, ApiDestroyTrait, ApiEditTrait, ApiIndexTrait, ApiShowTrait;
}
