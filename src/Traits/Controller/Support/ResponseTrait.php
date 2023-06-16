<?php

namespace BRCas\Laravel\Traits\Controller\Support;

use Illuminate\Http\Response;

trait ResponseTrait
{
    protected function getResponse($obj, $message, $redirect, $status = Response::HTTP_OK, $dataOdd = [])
    {
        if(!empty($dataOdd)) {
            request()->old();
        }

        if (!request()->isJson() && empty(request()->get('__ajax'))) {
            session()->flash('success', __($message));
            return redirect()->to($redirect);
        }

        return response()->json([
            'data' => $obj,
            'message' => __($message),
        ], $status);
    }
}
