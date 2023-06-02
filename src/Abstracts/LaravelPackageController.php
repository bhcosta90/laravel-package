<?php

namespace BRCas\Laravel\Abstracts;

use BRCas\Laravel\Support\RouteSupport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

abstract class LaravelPackageController extends BaseController
{
    use Traits\IndexTrait, Traits\FormTrait, Traits\ViewTrait;
    use Traits\PostTrait, Traits\DeleteTrait;
    use Traits\Validation\MethodTrait, Traits\Validation\ServiceTrait;
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        return $this->executeTable($request, "index");
    }

    public function create()
    {
        $form = $this->runForm("create", "store", __("New"));

        $data = [
            'form' => $form,
        ];

        if ($addArrayInData = $this->getMethod(str()->camel("add data in create"))) {
            $data += $this->$addArrayInData(request()->all());
        }

        return view($this->getView("create"), $data);
    }

    public function store()
    {
        return $this->executePost("create", "store", "Register created successfully");
    }

    public function edit()
    {
        $form = $this->runForm("edit", "update", __("Update"), ['model' => $this->getModel()]);

        $data = [
            'form' => $form,
        ];

        if ($addArrayInData = $this->getMethod(str()->camel("add data in edit"))) {
            $data += $this->$addArrayInData(request()->all());
        }
        return view($this->getView("edit"), $data);
    }

    public function update()
    {
        return $this->executePost("edit", "update", "Register updated successfully", $this->getModel());
    }

    public function show(){
        $obj = $this->getModel();
        return view($this->getView("show"), compact('obj'));
    }

    public function destroy(){
        return $this->executeDelete('destroy', 'find');
    }

    protected function getFilter(): array{
        return [];
    }

    protected function addDataInCreate(): array
    {
        return [];
    }

    protected function addDataInEdit(): array
    {
        return [];
    }
}
