<?php

namespace BRCas\Laravel\Traits\Controller\Write;

use BRCas\Laravel\Support\FormSupport;
use BRCas\Laravel\Support\RouteSupport;
use BRCas\Laravel\Traits\Controller\Validation\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

trait StoreTrait {

    use ValidationService;

    public abstract function formCreate();

    public function store(FormSupport $formSupport, Request $request)
    {
        $action = property_exists($this, 'actionStore')
            ? $this->actionStore
            : "create";

        $objService = $this->validateService([$action]);
        $data = $request->route()->parameters() + $formSupport->data($this->formCreate());

        if (method_exists($this, 'addDataInStore')) {
            $data += $this->addDataInStore($request->all());
        }

        return DB::transaction(function () use ($data, $request, $objService, $action) {

            $obj = $objService->$action($data);

            $actionMessage = str()->camel("message " . $action);
            $message = __(method_exists($this, $actionMessage) ? $this->$actionMessage() : __('Successfully created'));

            if (!$request->isJson() && empty($request->get('__ajax'))) {
                session()->flash('success', $message);
                return method_exists($this, 'redirectCreate')
                ? $this->redirectCreate($obj)
                    : redirect()->route(RouteSupport::getRouteActual() . ".index", $request->route()->parameters());
            }

            return response()->json([
                'data' => $obj,
                'msg' => $message,
            ], Response::HTTP_CREATED);
        });
    }
}
