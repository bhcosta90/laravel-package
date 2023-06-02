<?php

namespace BRCas\Laravel\Abstracts\Traits;

use BRCas\Laravel\Support\FormSupport;
use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

trait PostTrait
{
    use Validation\ServiceTrait, Validation\MethodTrait, FindTrait;

    protected function executePost($form, $action, $message, $model = null)
    {
        $data = request()->all();
        $objService = $this->validateService([$action]);

        if (!empty($form)) {
            $formSupport = app(FormSupport::class);

            $form = str()->camel($form . " Form");
            $this->validateMethod([$form]);

            $data = $formSupport->data($this->$form());
            if ($addArrayInData = $this->getMethod(str()->camel("add data in " . $action))) {
                $data += $this->$addArrayInData(request()->all());
            }
        }

        $data += request()->route()->parameters();

        return DB::transaction(function () use ($objService, $action, $message, $data, $model) {
            if (empty($model)) {
                $obj = $objService->$action($data);
            } else {
                $obj = $objService->$action($model, $data);
            }

            event(RouteSupport::getRouteActual() . '.' . $action, [
                'model' => $obj,
                'data' => $data,
            ]);

            return $this->responsePost($action, $obj, $message);
        });
    }

    protected function responsePost($action, $obj, $message, $attributes = [])
    {
        if ($messageAction = $this->getMethod(str()->camel("message " . $action))) {
            $message = $this->$messageAction($obj);
        }

        $redirect = redirect()->route(RouteSupport::getRouteActual() . ".index", request()->route()->parameters());

        if ($redirectAction = $this->getMethod(str()->camel("redirect " . $action))) {
            $redirect = redirect()->to($this->$redirectAction($obj));
        }

        if (!request()->isJson() && empty(request()->get('__ajax'))) {
            session()->flash('success', __($message));
            return $redirect;
        }

        return response()->json([
            'data' => $obj,
            'msg' => __($message),
        ] + $attributes, $obj ? Response::HTTP_OK : Response::HTTP_CREATED);
    }
}
