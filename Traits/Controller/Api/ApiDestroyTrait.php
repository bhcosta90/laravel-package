<?php


namespace Costa\Package\Traits\Controller\Api;


use Costa\Package\Traits\Controller\BaseController;

trait ApiDestroyTrait
{
    use BaseController;

    public function destroy($id)
    {
        $service = app($this->service());
        $service->apiDestroy($id);
        return response()->noContent();
    }
}
