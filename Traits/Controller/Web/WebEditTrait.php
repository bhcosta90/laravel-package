<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

trait WebEditTrait
{
    use BaseController;

    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $service = app($this->service());
        $obj = $service->find($id);
        $form = $formBuilder->create($this->form(), [
            'model' => $obj,
            'method' => 'PUT',
            'url' => route($this->getNameRoute() . ".update", $id),
        ])->add('btn', 'submit', [
            "attr" => ['class' => 'btn btn-primary'],
            'label' => __('Enviar')
        ]);

        $nameView = str_replace(".{$id}", "", $this->getNameView());
        $data = ['form' => $form];

        if (method_exists($service, 'edit')) {
            $data += $service->edit($request->all());
        }

        return view($nameView, $data + [
                'route_name' => $this->getNameRoute()
            ]);
    }

    protected abstract function form();

    public function update($id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create($this->form());
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $service = app($this->service());
        return $service->webUpdate($id, $form->getFieldValues(), $this->getNameRoute());
    }
}
