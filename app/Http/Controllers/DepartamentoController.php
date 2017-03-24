<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\departamento;
use App\municipio;

class DepartamentoController extends Controller
{
    public function getMunicipios(Request $request){
    	$municipios = municipio::where('cod_depto', $request->departamento)->orderBy('nombre','asc')->get();
    	
    	echo json_encode($municipios);
    }
}
