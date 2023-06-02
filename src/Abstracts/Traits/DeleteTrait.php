<?php

namespace BRCas\Laravel\Abstracts\Traits;

use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

trait DeleteTrait
{
    use Validation\ServiceTrait, Validation\MethodTrait, FindTrait;

    protected function executeDelete($action, $find, $message = "Register deleted successfully")
    {
        $objService = $this->validateService([$action, $find]);

        return DB::transaction(function () use ($objService, $action, $find, $message) {
            $request = request();
            $obj = $this->getModel($find);

            if ($messageAction = $this->getMethod(str()->camel("message " . "destroy"))) {
                $message = $this->$messageAction($obj);
            }

            $redirect = redirect()->route(RouteSupport::getRouteActual() . ".index", $request->route()->parameters());
            if ($redirectAction = $this->getMethod(str()->camel("redirect " . $action))) {
                $redirect = redirect()->to($this->$redirectAction($obj));
            }

            $objService->$action($obj);

            event(RouteSupport::getRouteActual() . '.' . $action, [
                'model' => $obj,
            ]);

            if (!$request->isJson() && empty($request->get('__ajax'))) {
                session()->flash('success', __($message));
                return $redirect;
            }

            return response()->json([
                'msg' => __($message),
            ], Response::HTTP_OK);
        });
    }
}
