<?php

namespace BRCas\Package\Traits\Support;

trait Permission {
    public abstract function permissions();

    public function __construct()
    {
        foreach($this->permissions() as $key => $permission){
            if($permission){
                switch($key){
                    case "index":
                        $this->middleware('can:' . $permission, ['only' => ['index']]);
                    break;
                    case "create":
                        $this->middleware('can:' . $permission, ['only' => ['create', 'store']]);
                    break;
                    case "edit":
                        $this->middleware('can:' . $permission, ['only' => ['edit', 'update']]);
                    break;
                    case "delete":
                        $this->middleware('can:' . $permission, ['only' => ['destroy']]);
                    break;
                }
            }
        }
    }
}