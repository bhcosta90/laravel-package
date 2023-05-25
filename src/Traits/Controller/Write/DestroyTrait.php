<?php

namespace BRCas\Laravel\Traits\Controller\Write;

use BRCas\Laravel\Support\RouteSupport;
use BRCas\Laravel\Traits\Controller\Validation\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

trait DestroyTrait
{
    use ValidationService;

    public function destroy(Request $request)
    {
        $dataParameters = $request->route()->parameters();
        $id = end($dataParameters);

        $actionFind = property_exists($this, 'actionFind')
            ? $this->actionFind
            : "find";

        $actionDelete = property_exists($this, 'actionDelete')
            ? $this->actionDelete
            : "destroy";

        $objService = $this->validateService([$actionFind, $actionDelete]);

        $obj = $objService->$actionFind($id);

        if (empty($obj)) {
            session()->flash('error', __('Register not found'));
            return redirect()->back();
        }

        return DB::transaction(function () use ($obj, $objService, $request, $actionDelete) {
            $old = clone $obj;
            $objService->$actionDelete($obj);

            $actionMessage = str()->camel("message " . $actionDelete);
            $message = __(method_exists($this, $actionMessage)
                ? $this->$actionMessage()
                : __('Registration removed successfully'));

            if (!$request->isJson()) {
                session()->flash('success', $message);

                return method_exists($this, 'redirectDestroy')
                    ? $this->redirectDestroy($old)
                    : redirect()->route(RouteSupport::getRouteActual() . ".index");
            }

            return response()->json([
                'msg' => $message,
            ], Response::HTTP_OK);
        });
    }
}
