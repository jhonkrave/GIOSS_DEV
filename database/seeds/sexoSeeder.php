<?php

use Illuminate\Database\Seeder;
use App\Models\GenerosUser;

class sexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GenerosUser::create([
        	'id_genero' => 'F',
        	'descripcion' => 'Femenino'
        ]);

        GenerosUser::create([
        	'id_genero' => 'M',
        	'descripcion' => 'Masculino'
        ]);

        GenerosUser::create([
        	'id_genero' => 'I',
        	'descripcion' => 'Indeterminado'
        ]);

        return true;
    }
}
