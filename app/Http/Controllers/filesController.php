<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Classes\AAC;

class filesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function upload()
    {
        return view('archivos.upload_files');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $files = $request->file('archivo');
        $fileTypes = $request->tipo_file;

        $count = 0;
        $routeFile = null;
        // $name = $files->getClientOriginalName();

        // $routeFile =  Storage::disk('archivos')->put($name, \File::get($files));
        foreach($files as $file) {
            $rules = array('file' => 'required'); //'required|mimes:png,gif,jpeg,txt,pdf,doc'
            $validator = Validator::make(array('file'=> $file), $rules);
            $validator->validate();
            $folder = '/'.$fileTypes[$count].time().'/';

            $routeFolder = storage_path('archivos').$folder;
            $fileName = $folder.$file->getClientOriginalName();
            $routeFile = $routeFolder.$file->getClientOriginalName();
            Storage::disk('archivos')->put($fileName, \File::get($file));
            
            try {
                switch ($fileTypes[$count]) {
                    case 'AAC':
                        $aac = new AAC($routeFolder,$routeFile);
                        $aac->manageContent();
                        break;
                    
                    default:
                        # code...
                        break;
                }

            } catch (\Exception $e) {
                $response = new \stdClass();
                $response->error = $e->getMessage();
                echo $e;
            }
            
          $count++;
          
        }

        // if ($routeFile != null){
        //     echo json_encode('si');
        // }else{
        //     echo json_encode('NO');
        // }
        

        

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
