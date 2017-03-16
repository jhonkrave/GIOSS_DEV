<?php

use Illuminate\Database\Seeder;
use App\tipo_entidad;

class DBtipoEntidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(tipo_entidad::class,4)->create();
    }
}
