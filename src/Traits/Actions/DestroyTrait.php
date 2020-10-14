<?php


namespace BRCas\Laravel\Traits\Actions;


trait DestroyTrait
{
    /**
     * @return mixed
     */
    public abstract function model();

    /**
     * @return mixed
     */
    public abstract function routeResource();

    /**
     * @param $id
     */
    public function destroy($id)
    {
        return $this->execute(function () use ($id) {
            $model = $this->model();
            $obj = (new $model)->find($id);
            $obj->delete();

            if (!request()->isJson()) {
                request()->session()->flash("success", __("Registro excluído com sucesso"));
                $redirect = route($this->routeResource() . ".index");
                return redirect($redirect);
            }
        });
    }
}
