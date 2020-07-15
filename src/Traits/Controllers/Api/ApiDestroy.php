<?php

namespace BRCas\Laravel\Traits\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ApiDestroy
{
    
    public function destroy(Request $request, $id)
    {
        return $this->executeAction($request, function () use ($id) {
            $obj = null;
            
            if (method_exists($this, 'service')) {
                $objService = call_user_func_array([$this, 'service'], []);
                if (method_exists($objService, 'find')) {
                    $obj = $objService::find($id);
                }
            }

            $objClass = $this->model();
            if ($obj == null) {
                $obj = $objClass::findOrFail($id);
            }

            if ($this->object != null) {
                if (method_exists($this, 'service')) {
                    $objService = call_user_func_array([$this, 'service'], []);
                    if (method_exists($objService, 'destroy')) {
                        $objService::destroy($obj);
                        return response("")
                            ->setStatusCode(204);
                    }
                }
                $obj->delete();
            }
            
            return response("")
                ->setStatusCode(204);
        });
    }

    protected abstract function model();
}
