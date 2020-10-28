<?php

namespace BRCas\Laravel\Traits\Controller\Api;

use BRCas\Laravel\Traits\Support\Execute;
use Exception;
use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilder;

trait Store
{
    use Execute;

    public function store(FormBuilder $formBuilder)
    {
        $objService = app($this->service());

        if (!method_exists($objService, 'create')) throw new Exception(__('Method create not found in service'));

        return $this->execute(function () use ($objService, $formBuilder) {
            $objForm = $formBuilder->create($this->form());
            if (!$objForm->isValid()) {
                return response()->json([
                    'msg' => $objForm->getErrors(),
                ], Response::HTTP_BAD_REQUEST);
            }

            $data = $objForm->getFieldValues();
            $objService->create($data);

            return response()->json([
                'msg' => $this->messageRegister(),
            ], Response::HTTP_CREATED);
        });
    }

    public abstract function service();

    public abstract function form();

    public function messageRegister()
    {
        return __('Register with successfully');
    }

}