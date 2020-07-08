<?php

namespace BRCas\Traits\Queries;

use ErrorException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait ExecuteApi
{
    protected function executeAction($request, $funcao)
    {
        $this->request = $request;
        DB::beginTransaction();
        try {
            return $funcao();
        } catch (ErrorException $e) {
            DB::rollback();
            return response([
                'success' => false,
                'message' => sprintf("%s | %s | %s", $e->getLine(), $e->getFile(), $e->getMessage()),
            ])->setStatusCode(400);
        } catch (ValidationException $e) {
            DB::rollback();
            return response([
                'success' => false,
                'errors' => $e->errors(),
            ])->setStatusCode(400);
        } catch (Exception $e) {
            DB::rollback();
            return response([
                'success' => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(400);
        }
    }
}
