<form class="form-horizontal" role="form" method="POST" action="{{url('formulario')}}">
  {{ csrf_field() }} <!-- genera un token requerido por laravel para los formualrios -->
  <div class="form-group">
    <label for="archvo" class="col-lg-2 control-label">archivo</label>
    <div class="col-lg-10">
      <input type="file" class="form-control" id="archivo" name="archvo"
             placeholder="Email">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">cargar</button>
    </div>
  </div>
</form>