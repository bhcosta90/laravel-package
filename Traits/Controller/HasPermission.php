<?php

namespace Costa\Package\Traits\Controller;

trait HasPermission
{
    public function __construct()
    {
        $permissions = [];
        foreach ($this->permissions() as $key => $permission) {
            if ($permission) {
                $permissions[$permission]['only'][] = $key;
            }
        }
        foreach ($permissions as $k => $p) {
            $this->middleware('can:' . $k, $p);
        }
    }

    protected abstract function permissions();
}
