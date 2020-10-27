<?php

namespace BRCas\Laravel\Traits\Controller\Web;

use BRCas\Laravel\Traits\Support\Execute;
use Exception;

trait Destroy
{
    use Execute;
    
    public abstract function service();

    public abstract function routeBegging();

    public function destroy($id){
        $objService = app($this->service());
        
        if(!method_exists($objService, 'find')) throw new Exception(__('Method find not found in service'));
        if(!method_exists($objService, 'destroy')) throw new Exception(__('Method destroy not found in service'));

        $obj = $objService->find($id);

        return $this->execute(function() use($obj, $objService) {
            $objService->destroy($obj);
            return redirect()->route($this->routeBegging() . ".index");
        });
    }
}