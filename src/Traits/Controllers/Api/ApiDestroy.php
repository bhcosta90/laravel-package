<?php

namespace BRCas\Laravel\Traits\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ApiDestroy
{
    
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

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

            if ($this->object != null) {
                if (method_exists($this, 'service')) {
                    $objService = call_user_func_array([$this, 'service'], []);
                    if (method_exists($objService, 'destroy')) {
                        $objService::destroy($obj);
                        DB::commit();
                        return response("")
                            ->setStatusCode(204);
                    }
                }
                $this->object->delete();
            }
            
            DB::commit();

            return response("")
                ->setStatusCode(204);
        });
    }

    protected abstract function model();
}
