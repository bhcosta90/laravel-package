<?php


namespace BRCas\Laravel\Traits\Database;

use Illuminate\Support\Facades\DB;

trait RegisterPermissionTrait
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (class_exists('Spatie\Permission\Models\Permission')) {
            foreach ($this->permissions() as $permission) {
                \Spatie\Permission\Models\Permission::create(['name' => $permission]);
            }
        }
    }

    abstract public function permissions();

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (class_exists('Spatie\Permission\Models\Permission')) {
            DB::table(with(new \Spatie\Permission\Models\Permission)
                ->getTable())->whereIn('name', $this->permissions())->delete();
        }
    }
}
