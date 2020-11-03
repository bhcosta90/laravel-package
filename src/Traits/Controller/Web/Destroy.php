<?php

namespace BRCas\Laravel\Traits\Controller\Web;

use BRCas\Laravel\Traits\Support\Execute;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait Destroy
{
    use Execute;

    public abstract function service();

    public abstract function routeBegging();

    public function destroy($id, Request $request)
    {
        $objService = app($this->service());

        if (!in_array(\BRCas\Laravel\Contracts\Destroy::class, class_implements($objService)))
            throw new Exception(__('Interface '.\BRCas\Laravel\Contracts\Destroy::class.' not found in service'));

        $obj = $objService->find($id);

        return $this->execute(function () use ($obj, $objService, $request) {
            $old = clone $obj;
            $objService->destroy($obj);

            if (!$request->isJson()) {
                return method_exists($this, 'redirectDestroy') == false ?
                    redirect()->route($this->routeBegging() . ".index") : $this->redirectDestroy($old);
            } else {
                return response()->json([
                    'msg' => method_exists($this, 'messageDelete') ? $this->messageDelete() : __('Save with success'),
                ], Response::HTTP_NO_CONTENT);
            }
        });
    }
}
