<?php


namespace BRCas\Laravel\Traits\Query;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

trait ExecuteTrait
{
    protected $message = 'Registro salvo com sucesso';

    protected $redirect = null;

    protected $status = Response::HTTP_OK;

    public abstract function routeResource();

    public function execute($function)
    {
        try {
            return $function();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getTraceAsString());
            return $this->responseError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        } catch (\Exception $e) {
            dd($e);
        }
    }

    private function responseError($status, $message)
    {
        if (!request()->isJson()) {
            return redirect()->back()->withErrors($message)->withInput();
        }

        return response()->json([
            'status' => $status,
            'msg' => $message,
        ]);
    }
}
