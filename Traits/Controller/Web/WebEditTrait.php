<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\FormBuilder;

trait WebEditTrait
{
    use BaseController;

    public function edit(FormBuilder $formBuilder, Request $request, ...$params)
    {
        $this->request = $request;

        $id = array_pop($params);

        $service = app($this->service());
        $routeParams = $request->route()->parameters;
        $obj = $service->find($id, ...$params);

        $form = $formBuilder->create($this->form(), [
            'model' => $obj,
            'method' => 'PUT',
            'url' => route($this->getNameRoute() . ".update", $routeParams),
        ])->add('btn', 'submit', [
            "attr" => ['class' => 'btn btn-primary'],
            'label' => __('Enviar')
        ]);

        $data = ['form' => $form];

        if (method_exists($service, 'edit')) {
            $data += $service->edit(...array_values($this->request->route()->parameters));
        }

        return view($this->getNameView() .".". __FUNCTION__, $data + [
                'route_name' => $this->getNameRoute()
            ]);
    }

    protected abstract function form();

    public function update(Request $request, FormBuilder $formBuilder, ...$params)
    {
        $this->request = $request;

        $id = array_pop($params);

        $form = $formBuilder->create($this->form());
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $data = [
            $id,
            $form->getFieldValues(),
            ...$params
        ];

        return DB::transaction(function () use ($data) {
            $service = app($this->service());
            $obj = $service->update(...$data);
            return $this->redirectUpdate($obj);
        });
    }

    protected function redirectUpdate($obj){
        return redirect()->route($this->getNameRoute() . ".index");
    }
}
