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
    	DB::table('books')->delete();
    	$header = NULL;
       	$data = array();
        //factory(tipo_entidad::class,4)->create();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        DB::table('books')->insert($data);

    }
}
