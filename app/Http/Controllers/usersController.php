<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

use App\tipo_entidad;
use App\User;
use App\entidades_sector_salud;
use App\departamento;
use App\user_entidad;

class usersController extends Controller
{

    /*
    *namasepade para enivar correo
    *
    */

    use SendsPasswordResetEmails;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departamentos = departamento::orderBy('nombre', 'asc')->get();
        $tipo_entidades = tipo_entidad::all();
        return view('auth.register')->with(['departamentos' => $departamentos,'tipo_entidades' =>$tipo_entidades]);
    }

 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $data)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(), 
            [
                'name' => 'required | max: 255 |',
                'lastname' => 'required | max: 255 |',
                'email' => 'required| email | max:255 | unique:users,email',
                //'password' => 'required|min:6|confirmed',
                'tipo_usuario' => 'required | integer | between:1,2 | exists:roles,id',
            ]
        );

        $validator->setAttributeNames([
            'name'=>'Nombres',
            'email' => "Email",
            //'password' => 'Password',
        ]);

        $validator->validate();

        DB::beginTransaction();

        try {
            
            //se crea al usuario
            $newUser = new User();

            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->password = Hash::make('0000');
            $newUser->roleid = $request->tipo_usuario; 

            $saveUser = $newUser->save();
            if(!$saveUser) throw new Exception("Error al crear El Usuario");

            $saveEntidad = $saveRelation = true;
        
            if($request->tipo_usuario == 2){ //tipo de ususario entidad
                $validator = Validator::make(
                    $request->all(),
                    [
                        'num_identificacion' => 'required | integer | digits: 12', //cambiar tipo de dato para 12 digitos
                        'tipo_entidad' => 'required | integer | exists:tipo_entidad,codigo_tipo_entidad',
                        'nombre_entidad' => 'required | max:255',
                        'cod_muni' => 'required | integer | exists:municipios,cod_divipola',
                        'cod_habilitacion' => 'required | integer | digits: 12',
                    ]
                );


                $validator->setAttributeNames([
                    'num_identificacion'=>'Número de identificación',
                    'tipo_entidad' => "Tipo de entidad",
                    'nombre_entidad' => 'Nombre de entidad',
                    'cod_muni' => 'Municipio',
                    'cod_habilitacion' => 'codigo de habilitacion',
                ]);

                $validator->validate();

                //se crea la entidad
                $newEntidad =  new entidades_sector_salud();
                
                $newEntidad->cod_tipo_entidad = $request->tipo_entidad;
                $newEntidad->nombre_de_la_entidad = $request->nombre_entidad;
                $newEntidad->num_identificacion = $request->num_identificacion;
                $newEntidad->cod_habilitacion = $request->cod_habilitacion;
                $newEntidad->cod_mpio = $request->cod_muni;

                $saveEntidad = $newEntidad->save();
                if(!$saveEntidad) throw new Exception("Error Al crear la entidad");
                

                //se crea la relacion usuario - entidad
                $newRelation = new user_entidad();
                $newRelation->id_entidad = $newEntidad->id_entidad;
                $newRelation->userid = $newUser->id;

                $saveRelation = $newRelation->save();
                if(!$saveRelation) throw new Exception("Error al crear la relacion usuario entidad");
                

            }

            
            //SE ENIVA EL CORREO de validacion de contraseña

            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );

            DB::commit();

            if($saveEntidad && $saveUser && $saveRelation){
                $msgemail = '';

                if($response == Password::RESET_LINK_SENT) $msgemail =' Se  ha enviado un mensaje al correo '.$request->email.' para restablecer la contraseña';
                return back()->with('success', 'Usuario guardado con exito.'.$msgemail);
            }else{
                return back()->with('error', 'Error al guradar el ussuario');
            }


            
        } catch (Exception $e) {
            
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
