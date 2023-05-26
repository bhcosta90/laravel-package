<?php

namespace BRCas\Laravel\Abstracts;

use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class LaravelPackageController
{
    use Traits\IndexTrait, Traits\FormTrait, Traits\ViewTrait;
    use Traits\PostTrait, Traits\Validation\MethodTrait, Traits\Validation\ServiceTrait;

    public function index(Request $request)
    {
        return $this->executeTable($request, "index");
    }

    public function create()
    {
        $form = $this->runForm("create", "store", __("New"));
        return view($this->getView("create"), compact('form'));
    }

    public function store()
    {
        return $this->executePost("create", "store", "Register created successfully");
    }

    public function edit()
    {
        $form = $this->runForm("edit", "update", __("Update"), ['model' => $this->getModel()]);
        return view($this->getView("edit"), compact('form'));
    }

    public function update()
    {
        return $this->executePost("edit", "update", "Register updated successfully", $this->getModel());
    }

    public function destroy(Request $request){
        $objService = $this->validateService(['destroy']);


        return DB::transaction(function () use($request, $objService){
            $obj = $this->getModel();
            $action = "destroy";

            $message = "Register deleted successfully";
            if ($messageAction = $this->getMethod(str()->camel("message " . "destroy"))) {
                $message = $this->$messageAction($obj);
            }

            $redirect = redirect()->route(RouteSupport::getRouteActual() . ".index", $request->route()->parameters());
            if ($redirectAction = $this->getMethod(str()->camel("redirect " . $action))) {
                $redirect = $this->$redirectAction($obj);
            }

            $objService->$action($obj);

            if (!$request->isJson() && empty($request->get('__ajax'))) {
                session()->flash('success', $message);
                return $redirect;
            }

            return response()->json([
                'msg' => $message,
            ], Response::HTTP_OK);
        });
    }

    protected function getModel()
    {
        $objService = $this->validateService(['find']);
        $allParameters = request()->route()->parameters();
        $model = $objService->find(end($allParameters));

        if (empty($model)) {
            session()->flash('error', __('Register not found'));
            return redirect()->back();
        }

        return $model;
    }
}
