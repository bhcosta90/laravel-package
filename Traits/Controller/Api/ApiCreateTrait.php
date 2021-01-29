<?php


namespace Costa\Package\Traits\Controller\Api;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilder;

trait ApiCreateTrait
{
    use BaseController;

    public function store(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create($this->form());
        $service = app($this->service());
        if (!$form->isValid()) {
            return response()->json($form->getErrors())->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
        }

        $resource = $this->resource();
        return new $resource($service->apiStore($form->getFieldValues()));
    }

    protected abstract function form();

    protected abstract function resource();
}
