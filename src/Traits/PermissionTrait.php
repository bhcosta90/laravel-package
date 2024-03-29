<?php

declare(strict_types=1);

namespace BRCas\Laravel\Traits;

trait PermissionTrait
{
    protected abstract function permissions(): array;

    public function __construct()
    {
        foreach ($this->permissions() as $key => $permission) {
            if ($permission) {
                switch ($key) {
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
                    default:
                        if(!is_array($permission)) {
                            $permission = [$permission];
                        }
                        foreach($permission as $pUnique) {
                            $this->middleware('can:' . $pUnique, ['only' => [$key]]);
                        }

                        break;
                }
            }
        }
    }
}
