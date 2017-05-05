<?php

use Illuminate\Database\Seeder;
use App\Models\AyudasDiagnosticasPrueba;
class topsPruebasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '01',
        	'descripcion' => 'Prueba Sanguínea'
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '02',
        	'descripcion' => 'Cultivos'
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '03',
        	'descripcion' => 'Prueba de excretas'
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '04',
        	'descripcion' => 'Prueba de Orina'
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '05',
        	'descripcion' => 'Prueba de Punción'
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '06',
        	'descripcion' => 'Endoscopia'
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '07',
        	'descripcion' => 'Radiológica '
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '08',
        	'descripcion' => 'Medicina Nuclear'
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '09',
        	'descripcion' => 'Ultrasonido'
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '10',
        	'descripcion' => 'MRI'
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '11',
        	'descripcion' => 'Pruebas de función eléctrica'
        ]);

        AyudasDiagnosticasPrueba::create([
        	'id_prueba' => '12',
        	'descripcion' => 'Otro'
        ]);

        return true;
    }
}
