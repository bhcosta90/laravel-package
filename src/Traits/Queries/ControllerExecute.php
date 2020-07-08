<?php

namespace BRCas\Laravel\Traits\Queries;

use Exception;
use Illuminate\Support\Facades\DB;

trait ControllerExecute
{
    protected function executeAction($request, $funcao)
    {
        $this->request = $request;
        DB::beginTransaction();
        try {
            return $funcao();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $request->session()->flash('error_validate', json_encode($e->errors()));
            return redirect()
                ->back();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()
                ->back()
                ->withErrors('Erro na base de dados')
                ->withInput();
        } catch (\Exception $e) {
            \Log::error($e);
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
    }
}
