<?php

namespace BRCas\Laravel\Traits\Support;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

trait Execute
{
    public function execute($function)
    {
        try {
            return $function();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getTraceAsString());
            return $this->responseError(Response::HTTP_BAD_REQUEST, $e->getMessage());
        } catch (Exception $e) {
            dump($e);
        }
    }

    private function responseError($status, $message)
    {
        if (!request()->isJson()) {
            session()->flash('error', $message);
            return redirect()->back()->withInput();
        }

        return response()->json([
            'status' => $status,
            'msg' => $message,
        ]);
    }
}