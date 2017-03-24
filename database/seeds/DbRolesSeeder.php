<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class DbRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin =  new role();
    	$roleAdmin->nombre = "Administrador";
    	$r1 = $roleAdmin->save();

    	$roleEntidad = new role();
    	$roleEntidad->nombre = 'Entidad';
    	$r2 = $roleEntidad->save();

    	return $r1 && $r2;
    }
}
