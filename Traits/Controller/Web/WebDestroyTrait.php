<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait WebDestroyTrait
{
    use BaseController;

    public function destroy(Request $request, ...$params)
    {
        $this->request = $request;

        $id = array_pop($params);

        return DB::transaction(function () use ($id, $params) {
            $service = app($this->service());
            return $this->redirectDestroy($service->destroy($id, ...$params));
        });
    }

    protected function redirectDestroy($obj)
    {
        return redirect()->back();
    }
}
