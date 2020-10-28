<?php

namespace BRCas\Laravel\Traits\Controller\Api;

use BRCas\Laravel\Traits\Support\Execute;
use Exception;
use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilder;

trait Store
{
    use Execute;

    public abstract function service();

    public abstract function form();

    public function messageRegister()
    {
        return __('Register with successfully');
    }

    public function store(FormBuilder $formBuilder)
    {
        $objService = app($this->service());

        if (!in_array(\BRCas\Laravel\Contracts\Create::class, class_implements($objService)))
            throw new Exception(__('Interface '.\BRCas\Laravel\Contracts\Create::class.' not found in service'));

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

}