<?php


namespace Costa\Package\Abstracts;

use Illuminate\Routing\Controller as BaseControllerLaravel;
use Costa\Package\Traits\Controller\Web\{WebCreateTrait, WebDestroyTrait, WebEditTrait, WebIndexTrait, WebShowTrait};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class ControllerResource extends BaseControllerLaravel
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use WebIndexTrait, WebCreateTrait, WebEditTrait, WebShowTrait, WebDestroyTrait;
}
