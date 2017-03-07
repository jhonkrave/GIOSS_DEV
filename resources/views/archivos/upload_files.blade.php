@extends('layouts.menu')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1 class="page-header">Gestion archivos</h1>  
    
            <div class="panel panel-default">
                <div class="panel-heading">Carga de Archivos</div>

                <div class="panel-body">
                    <form id="cargaArchivos" role="form">
                        <div class="row form-group">
                            <h3>Periodo de tiempo</h3>
                            <p>Por favor digite el periodo de tiempo</p>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="fecha_ini" class="form-control-label">Desde:</label>
                                <div>
                                    <input type="date" name="fecha_ini" id="fecha_ini">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="fecha_ini" class="form-control-label">Hasta:</label>
                                <div>
                                    <input type="date" name="fecha_ini" id="fecha_ini">
                                </div>
                            </div>
                        </div>
                        <div class="row" id="files_div">
                            <button type="button" id="add_file" class="col-md-3 btn btn-success btn-m">
                              <span class="glyphicon glyphicon-plus-sign"></span> Adicionar Archivo
                            </button>
                            <button type="button" id="btnUpload" class="col-md-3 col-md-offset-6 btn btn-info btn-m">
                              <span class="glyphicon glyphicon-plus-sign"></span> Cargar Archivos
                            </button>
                            <br>
                            <br>
                            <div id="alert" style="display:none;">
                                <div class="alert alert-danger">
                                  <strong>Error!</strong> Error al cargar los archivos
                                </div>
                            </div>
                            
                        </div>
                    </form>
                    
                </div>
            </div>

        </div>
    </div>
</div>
{{ Html::script(asset("js/uploadfiles.js")) }}
@endsection
