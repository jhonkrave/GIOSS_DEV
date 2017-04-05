<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Classes\AAC;
use App\Models\FileStatus;
use Illuminate\Support\Facades\DB;

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

        $consecutive = $request->consecutive;
        
        $files = $request->file('archivo');
        $fileTypes = $request->tipo_file;
        
        
        $count = 0;
        foreach($files as $file) {
            $rules = array('file' => 'required'); //'required|mimes:png,gif,jpeg,txt,pdf,doc'
            $validator = Validator::make(array('file'=> $file), $rules);
            $validator->validate();
            $folder = '/'.$consecutive.'/'.$fileTypes[$count].'/';

            $routeFolder = storage_path('archivos').$folder;
            $routeFile = $folder.$file->getClientOriginalName();
            $fileName = $file->getClientOriginalName();
            Storage::disk('archivos')->put($routeFile, \File::get($file));
            
            //try {
                switch ($fileTypes[$count]) {
                    case 'AAC':
                        $aac = new AAC($routeFolder,$fileName, $consecutive);

                        $aac->manageContent();
                        break;
                    
                    default:
                        $aac = new AAC($routeFolder,$routeFile);
                        $aac->manageContent();
                        break;
                }
                $count++;

            // } catch (\Exception $e) {
            //     $response = new \stdClass();
            //     $response->error = $e->getMessage().'r';
            //     echo $e;
            // }
            
          
          
        }

        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //dd($request->consecutive);
        $result = DB::table('file_statuses')
                    ->join('archivo','archivo.id_archivo_seq','=','file_statuses.archivoid')
            ->where('file_statuses.consecutive', $request->consecutive)->get();
        echo json_encode($result);
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
