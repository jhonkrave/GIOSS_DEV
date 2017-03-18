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
                        {{ csrf_field() }}
                        <div class="row form-group">
                            <h3>Periodo de tiempo</h3>
                            <p>Por favor señale el periodo de tiempo</p>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="fecha_ini" class="form-control-label">Desde:</label>
                                <div>
                                    <input type="date" name="fecha_ini" id="fecha_ini">
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="fecha_fin" class="form-control-label">Hasta:</label>
                                <div>
                                    <input type="date" name="fecha_fin" id="fecha_fin">
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
                            <div id="alert" class="form-group " style="display:none;" align="center">
                                <div class="alert alert-danger" style="width: 700px; height: 150px; overflow-y: scroll;">
                                    <h4><strong>Error al cargar los archivos!</strong></h4>
                                    <div id="error_area" style="text-align: left;"></div>
                                  
                                </div>
                            </div>
                            
                        </div>
                    </form>
                    
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    var route = "{{ route('uploading') }}";
</script>
{{ Html::script(asset("js/uploadfiles.js")) }}
@endsection
