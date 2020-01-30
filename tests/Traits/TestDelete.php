<?php
namespace BRCas\LaravelTests\Traits;

trait TestDelete
{
    protected abstract function routeDelete();
    
    public function assertDelete($id){
        $response = $this->delete($this->routeDelete());
        $response->assertStatus(204);
        
        $model = $this->model();
        $table = (new $model())->getTable();
        
        $this->assertDatabaseMissing($table, ["id" => $id]);
    }
}

