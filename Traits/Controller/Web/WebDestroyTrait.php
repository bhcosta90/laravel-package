<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;

trait WebDestroyTrait
{
    use BaseController;

    public function destroy($id)
    {
        $service = app($this->service());
        return $service->webDestroy($id, $this->getNameRoute());
    }
}
