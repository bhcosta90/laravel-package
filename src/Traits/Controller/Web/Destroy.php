<?php

namespace BRCas\Laravel\Traits\Controller\Web;

use BRCas\Laravel\Traits\Support\Execute;
use Exception;

trait Destroy
{
    use Execute;

    public abstract function service();

    public abstract function routeBegging();

    public function destroy($id)
    {
        $objService = app($this->service());

        if (!in_array(\BRCas\Laravel\Contracts\Destroy::class, class_implements($objService)))
            throw new Exception(__('Interface '.\BRCas\Laravel\Contracts\Destroy::class.' not found in service'));

        $obj = $objService->find($id);

        return $this->execute(function () use ($obj, $objService) {
            $objService->destroy($obj);
            return redirect()->route($this->routeBegging() . ".index");
        });
    }
}