<?php
namespace BRCas\LaravelTests\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

trait TestDelete
{
    protected abstract function model();
    
    protected abstract function routeDelete();
    
    public function assertDelete($id){
        $response = $this->delete($this->routeDelete());
        $response->assertStatus(204);
        
        $model = $this->model();
        $table = (new $model())->getTable();
        
        $dados = ["id" => $id];
        if(in_array(SoftDeletes::class, class_uses($model))){
            $dados += ["deleted_at" => null];
        }
        
        
        $this->assertDatabaseMissing($table, $dados);
    }
}

