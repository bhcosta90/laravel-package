<?php

namespace BRCas\Laravel\Abstracts\Traits;

trait FindTrait
{
    use Validation\ServiceTrait, Validation\MethodTrait;

    protected function getModel($action = "find")
    {
        $objService = $this->validateService([$action]);
        $allParameters = request()->route()->parameters();
        $model = $objService->$action(end($allParameters));

        if (empty($model)) {
            session()->flash('error', __('Register not found'));
            return redirect()->back();
        }

        return $model;
    }
}
