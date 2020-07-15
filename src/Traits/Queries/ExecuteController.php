<?php

namespace BRCas\Laravel\Traits\Queries;

use Exception;
use Illuminate\Support\Facades\DB;

trait ExecuteController
{
    protected function executeAction($request, $funcao)
    {
        $this->request = $request;
        DB::beginTransaction();
        try {
            $ret = $funcao();
            DB::commit();
            return $ret;
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $request->session()->flash('error_validate', json_encode($e->errors()));
            return redirect()
                ->back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
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
