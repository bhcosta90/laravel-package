<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Request;

trait WebDestroyTrait
{
    use BaseController;

    public function destroy(Request $request, ...$params)
    {
        $this->request = $request;

        $id = array_pop($params);

        $service = app($this->service());
        return $this->redirectDestroy($service->destroy($id, ...$params));
    }

    protected function redirectDestroy($obj){
        return redirect()->route($this->getNameRoute() . ".index");
    }
}
