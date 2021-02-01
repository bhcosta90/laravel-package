<?php


namespace Costa\Package\Traits\Controller\Web;


use Costa\Package\Traits\Controller\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\FormBuilder;

trait WebCreateTrait
{
    use BaseController;

    public function create(FormBuilder $formBuilder, Request $request)
    {
        $this->request = $request;

        $service = app($this->service());

        $form = $formBuilder->create($this->form(), [
            'method' => 'POST',
            'url' => route($this->getNameRoute() . ".store", $this->request->route()->parameters),
        ])->add('btn', 'submit', [
            "attr" => ['class' => 'btn btn-primary'],
            'label' => __('Enviar')
        ]);

        $data = ['form' => $form];

        if (method_exists($service, 'create')) {
            $data += $service->create(...array_values($this->request->route()->parameters));
        }

        return view($this->getNameView() . "." . __FUNCTION__, $data + [
                'route_name' => $this->getNameRoute()
            ]);
    }

    protected abstract function form();

    public function store(Request $request, FormBuilder $formBuilder)
    {
        $this->request = $request;

        $form = $formBuilder->create($this->form());
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $data = [
            $form->getFieldValues(),
            ...array_values($this->request->route()->parameters)
        ];

        return DB::transaction(function () use ($data) {
            $service = app($this->service());
            $obj = $service->store(...$data);
            return $this->redirectStore($obj);
        });
    }

    protected function redirectStore($obj)
    {
        return redirect()->route($this->getNameRoute() . ".index");
    }
}
