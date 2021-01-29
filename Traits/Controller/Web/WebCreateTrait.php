<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

trait WebCreateTrait
{
    use BaseController;

    public function create(FormBuilder $formBuilder, Request $request)
    {
        $service = app($this->service());
        $form = $formBuilder->create($this->form(), [
            'method' => 'POST',
            'url' => route($this->getNameRoute().".store"),
        ])->add('btn', 'submit', [
            "attr" => ['class' => 'btn btn-primary'],
            'label' => __('Enviar')
        ]);

        $data = ['form' => $form];

        if(method_exists($service, 'create')){
            $data += $service->create($request->all());
        }

        return view($this->getNameView(), $data + [
                'route_name' => $this->getNameRoute()
            ]);
    }

    public function store(FormBuilder $formBuilder){
        $form = $formBuilder->create($this->form());
        $service = app($this->service());
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        return $service->webStore($form->getFieldValues(), $this->getNameRoute());
    }

    protected abstract function form();
}
