<?php


namespace BRCas\Traits\Controllers\Api;

use BRCas\Traits\Queries\ExecuteApi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ApiDelete
{
    use ExecuteApi;

    public function delete(Request $request, $id)
    {
        return $this->executeAction($request, function () use ($id) {
            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'find')) {
                    $this->object = $objService::find($id);
                }
            }

            $objClass = $this->model();
            if ($this->object == null) {
                $this->object = $objClass::findOrFail($id);
            }

            DB::beginTransaction();
            if ($this->object != null) {
                $this->object->delete();
            }
            DB::commit();

            return response("")
                ->setStatusCode(204);
        });
    }

    protected abstract function model();
}
