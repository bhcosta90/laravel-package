<?php

namespace BRCas\Laravel\Traits\Controller\Write;

use BRCas\Laravel\Support\FormSupport;
use BRCas\Laravel\Support\RouteSupport;
use BRCas\Laravel\Traits\Controller\Validation\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

trait UpdateTrait {

    use ValidationService;

    public abstract function formEdit();

    public function update(FormSupport $formSupport, Request $request)
    {
        $actionFind = property_exists($this, 'actionFind')
        ? $this->actionFind
            : "find";

        $actionEdit = property_exists($this, 'actionEdit')
        ? $this->actionEdit
            : "edit";

        $objService = $this->validateService([$actionFind, $actionEdit]);

        $dataParameters = $request->route()->parameters();
        $id = end($dataParameters);

        $data = $request->route()->parameters() + $formSupport->data($this->formEdit());

        $obj = $objService->$actionFind($id);

        if (empty($obj)) {
            session()->flash('error', __('Register not found'));
            return redirect()->back();
        }

        return DB::transaction(function () use ($objService, $request, $obj, $data, $actionEdit) {
            $obj = $objService->$actionEdit($obj, $data);

            $actionMessage = str()->camel("message " . $actionEdit);
            $message = __(method_exists($this, $actionMessage) ? $this->$actionMessage() : __('Successfully updated'));

            if (!$request->isJson() && empty($request->get('__ajax'))) {
                session()->flash('success', $message);
                return method_exists($this, 'redirectEdit')
                ? $this->redirectEdit($obj)
                    : redirect()->route(RouteSupport::getRouteActual() . ".index", $request->route()->parameters());
            }

            return response()->json([
                'data' => $obj,
                'msg' => $message,
            ], Response::HTTP_CREATED);
        });
    }
}
