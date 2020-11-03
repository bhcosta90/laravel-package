<?php

namespace BRCas\Laravel\Traits\Controller\Web;

use BRCas\Laravel\Traits\Support\Execute;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilder;

trait Edit
{
    use Execute;

    public abstract function service();

    public abstract function form();

    public abstract function editView();

    public abstract function routeBegging();

    public function edit($id, FormBuilder $formBuilder)
    {
        $objService = app($this->service());

        if (!in_array(\BRCas\Laravel\Contracts\Edit::class, class_implements($objService)))
            throw new Exception(__('Interface '.\BRCas\Laravel\Contracts\Edit::class.' not found in service'));

        $obj = $objService->find($id);

        $form = $formBuilder->create($this->form(), [
            'method' => 'PUT',
            'url' => route($this->routeBegging() . '.update', $obj->id),
            'model' => $obj,
        ])->add('btn', 'submit', [
            "attr" => ['class' => 'btn btn-primary'],
            'label' => __('Update')
        ]);

        return view($this->editView(), compact('form'));
    }

    public function update($id, FormBuilder $formBuilder, Request $request)
    {
        $objService = app($this->service());

        if (!method_exists($objService, 'find')) throw new Exception(__('Method find not found in service'));
        if (!method_exists($objService, 'edit')) throw new Exception(__('Method edit not found in service'));

        $obj = $objService->find($id);

        return $this->execute(function () use ($obj, $objService, $formBuilder, $request) {

            $objForm = $formBuilder->create($this->form());
            if (!$objForm->isValid()) {
                if ($request->isJson()) {
                    return response()->json([
                        'error' => $objForm->getErrors(),
                    ], Response::HTTP_BAD_REQUEST);
                } else {
                    return redirect()->back()->withErrors($objForm->getErrors())->withInput();
                }
            }

            $data = $objForm->getFieldValues();
            $objService->edit($obj, $data);

            if (!$request->isJson()) {
                return method_exists($this, 'redirectEdit') == false ?
                    redirect()->route($this->routeBegging() . ".index") : $this->redirectEdit($obj);
            } else{
                return response()->json([
                    'data' => $obj,
                    'msg' => method_exists($this, 'messageUpdate') ? $this->messageUpdate() : __('Save with success'),
                ], Response::HTTP_OK);
            }
        });
    }
}
