@extends('layouts.menu')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Registro</div>
                <div class="panel-body">
                    <!-- <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}"> -->
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('registro') }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <h3>Informcación de Usuario</h3>
                            <br >
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Nombres</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"  autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Apellidos</label>

                                <div class="col-md-6">
                                    <input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('name') }}"  autofocus>

                                    @if ($errors->has('lastname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lastname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" >

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" >

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('tipo_usuario') ? ' has-error' : '' }}">
                                <label for="tipo_usuario" class="col-md-4 control-label">Tipo de usuario</label>

                                <div class="col-md-6">
                                    <select id="tipo_usuario" class="form-control" required>
                                        <option value="1">Administrador </option>
                                        <option value="2"> Entidad</option>
                                    </select> 

                                    @if ($errors->has('tipo_usuario'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tipo_usuario') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="row" id="info_entidad" style="display:none;">
                            <h3>Informacion de Entidad</h3>
                            <br>
                            <div class="form-group{{ $errors->has('codigo_entidad') ? ' has-error' : '' }}">
                                <label for="codigo_entidad" class="col-md-4 control-label">Codigo de la En</label>

                                <div class="col-md-6">
                                    <input id="codigo_entidad" type="text" class="form-control" name="codigo_entidad" >

                                    @if ($errors->has('codigo_entidad'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('codigo_entidad') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('tipo_entidad') ? ' has-error' : '' }}">
                                <label for="tipo_entidad" class="col-md-4 control-label">Tipo Entidad</label>

                                <div class="col-md-6">
                                    <select id="tipo_entidad" class="form-control" name="tipo_entidad" required >
                                        <option value="1">EPS</option>
                                    </select>

                                    @if ($errors->has('tipo_entidad'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tipo_entidad') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('nombre_entidad') ? ' has-error' : '' }}">
                                <label for="nombre_entidad" class="col-md-4 control-label">Nombre</label>

                                <div class="col-md-6">
                                    <input id="nombre_entidad" type="text" class="form-control" name="nombre_entidad" >

                                    @if ($errors->has('nombre_entidad'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nombre_entidad') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('cod_dept') ? ' has-error' : '' }}">
                                <label for="cod_dept" class="col-md-4 control-label">Departamento</label>

                                <div class="col-md-6">
                                    <select id="cod_dept" class="form-control" name="cod_dept" required >
                                        <option value="1">Cali</option>
                                    </select>

                                    @if ($errors->has('cod_dept'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cod_dep') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                <strong>Exito!</strong> {{session('success')}}
                              </div>
                            @elseif(session()->has('error'))
                                <div class="alert alert-error">
                                    <strong>Success!</strong> {{session('error')}}
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{ Html::script(asset("js/registro.js")) }}
@endsection
