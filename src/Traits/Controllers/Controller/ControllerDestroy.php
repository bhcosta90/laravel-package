<?php


namespace BRCas\Laravel\Traits\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ControllerDestroy
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

            if ($obj != null) {
                $obj->delete();
            }

            $this->request->session()->flash('success', __('Registro deletado com sucesso'));
            DB::commit();
            return redirect($this->route());
        });
    }

    protected abstract function model();

    public abstract function route();

}
