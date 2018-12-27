@extends('plantilla.dashboard')

@section('content')
<?
if(isset($oficinamdf)&&!empty($oficinamdf)){
	$hashids = new Hashids\Hashids('sisgasystem', 25);
?>
      <div class="row " id="div_frm_actualizar">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Modificar Oficina</h3>
            </div>

             <div class="row">
	            <div class="box-body">
		            <div class="nuevo-registro">
					{{ Form::model($oficinamdf, array('method' => 'POST','url' => 'oficinas/modificardb/' . $hashids->encode($oficinamdf->id),'class'=>'form-horizontal')) }}
		                  <div class="form-group">
		                    <label for="oficina_ciudad" class="col-sm-4 control-label">Ciudad*</label>
		                    <div class="col-sm-8">
		                      <input name="oficina_ciudad" id="oficina_ciudad"  type="text" class="form-control" value="{{Form::getValueAttribute('oficina_ciudad', null) }}" maxlength="30" >
		                    </div>
							@if($errors->has("oficina_ciudad"))
							<small class="help-block text-red pull-right">{{$errors->first("oficina_ciudad")}}</small>		
							@endif	                            	              
		                  </div>

		                  <div class="form-group">
		                    <label for="oficina_direccion" class="col-sm-4 control-label">Dirección*</label>
		                    <div class="col-sm-8">
		                      <input name="oficina_direccion" id="oficina_direccion"  type="text" class="form-control" value="{{Form::getValueAttribute('oficina_direccion', null) }}" maxlength="70" >
		                    </div>
		                  </div>		
		                  <?
		                  $atributohtml="selected='selected'";
		                  ?>
			              <div class="form-group">
			                <label for="estado" class="col-sm-4 control-label">Estado</label>
			                 <div class="col-sm-8">
				                <select name="estado" id="estado" class="form-control">
				                	<option value="1" <?=$estado_obj==1?$atributohtml:''?>>Activo</option>
				                	@if($estado_obj==0)
				                  	<option value="0" <?=$estado_obj==0?$atributohtml:''?>>Inactivo</option>
				                  	@endif				                  
				                </select>
			                </div>
			              </div>

		                  <div class="form-group">
		                    <div class="col-sm-offset-4 col-sm-8">
		                    <input id="btn_modificar" type="submit" value="Actualizar" class="btn btn-primary">
		                    <a href="{{ URL::to('oficinas') }}" data-skin="skin-blue" class="btn btn-primary">Cancelar</a>

		                    </div>
		                  </div>
		                {{ Form::close() }}
		            </div>         
	            </div>
	         </div>                      
          </div>
        </div>
      </div>      
<?
}
?>
@endsection