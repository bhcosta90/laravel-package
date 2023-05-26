<?php

namespace BRCas\Laravel\Abstracts\Traits;

use BRCas\Laravel\Support\FormSupport;
use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Support\Facades\DB;

trait PostTrait
{
    use Validation\ServiceTrait, Validation\MethodTrait;

    protected function executePost($form, $action, $message, $model = null)
    {
        $formSupport = app(FormSupport::class);
        $objService = $this->validateService([$action]);

        $form = str()->camel($form . " Form");
        $this->validateMethod([$form]);

        $data = $formSupport->data($this->$form());
        if ($addArrayInData = $this->getMethod(str()->camel("add data in " . $action))) {
            $data += $this->$addArrayInData(request()->all());
        }

        return DB::transaction(function () use ($objService, $action, $message, $data, $model) {
            $request = request();

            $obj = $objService->$action($model, $data);
            if (empty($model)) {
                $obj = $objService->$action($data);
            }

            if ($messageAction = $this->getMethod(str()->camel("message " . $action))) {
                $message = $this->$messageAction($obj);
            }

            $redirect = redirect()->route(RouteSupport::getRouteActual() . ".index", $request->route()->parameters());

            if ($redirectAction = $this->getMethod(str()->camel("redirect " . $action))) {
                $redirect = $this->$redirectAction($obj);
            }

            if (!$request->isJson() && empty($request->get('__ajax'))) {
                session()->flash('success', $message);
                return $redirect;
            }

            return response()->json([
                'data' => $obj,
                'msg' => $message,
            ], $model ? Response::HTTP_OK : Response::HTTP_CREATED);
        });
    }
}
