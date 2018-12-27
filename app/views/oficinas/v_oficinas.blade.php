@extends('plantilla.dashboard')

@section('content')
<?
if(isset($errors)){
	if(count($errors)==0)
		$div_frm="div_inactivo";
	else 
		$div_frm="div_activo";
}
$hashids = new Hashids\Hashids('sisgasystem', 25);
?>
      <div class="row  {{$div_frm}}"  id="div_frm">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Nueva Oficina</h3>
               <div class="box-tools pull-right">              	 
                <button id="btnCerrar" type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove"><i class="fa fa-times"></i></button>                   
               </div>
            </div>
             <div class="row">
	            <div class="box-body">
		            <div class="nuevo-registro">
		 				<form name="frm_1" method="POST" action="{{url('oficinas/nuevodb')}}" class="form-horizontal">
		                  <div class="form-group">
		                    <label for="oficina_ciudad" class="col-sm-4 control-label">Ciudad*</label>
		                    <div class="col-sm-8">
		                      <input name="oficina_ciudad" id="oficina_ciudad" type="text" class="form-control" value="{{Input::old('oficina_ciudad')}}" maxlength="30" >
		                    </div>
							@if($errors->has("oficina_ciudad"))
							<small class="help-block text-red pull-right">{{$errors->first("oficina_ciudad")}}</small>	
							@endif			                    
		                  </div> 
			                  		                  		                                   		
		                  
		                  <div class="form-group">
		                    <label for="oficina_direccion" class="col-sm-4 control-label">Dirección*</label>
		                    <div class="col-sm-8">
		                      <input name="oficina_direccion" id="oficina_direccion" class="form-control" value="{{Input::old('oficina_direccion')}}" maxlength="70">
		                    </div>
							@if($errors->has("oficina_direccion"))
							<small class="help-block text-red pull-right">{{$errors->first("oficina_direccion")}}</small>	
							@endif			                    
		                  </div>		

		                  {{Form::token()}}

		                  <div class="form-group">
		                    <div class="col-sm-offset-4 col-sm-8">
		                    <input id="btn_agregar" type="submit" value="Agregar" class="btn btn-primary">
		                    </div>
		                  </div>
		                </form>	   	            	
		            </div>         
	            </div>
	         </div>                      
          </div>
        </div>
      </div>

<!--LISTA-->
      <div class="row" id="div_lista">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Oficinas</h3>
              <div class="box-tools pull-right">
              	 <button id="btn_abrir_div_frm" type="button"  class="btn btn-primary btn-xs">Nuevo Oficina</button> 
              </div>
            </div>
            <!-- <div class="row">
            	<div class="col-sm-9 pull-right">
	            	<div id="example1_filter" class="dataTables_filter">
		            	<input id="inp_busqueda" name="inp_busqueda" class="form-control input-sm" placeholder="Buscar ..." aria-controls="example1" type="search">		            	
	            	</div>
            	</div>
            </div>-->
            <div class="row">
				<div class="col-sm-12">   
		            <div class="box-body" id="div_tabla_lista">	    
					<table id="tabla_lista" class="table table-bordered table-hover tabla_simple tabla_operaciones">
		                <thead>
		                <tr>
		                  <th>CIUDAD</th>
		                  <th>DIRECCIÓN</th>
		                  <th>OPERACIONES</th>
		                </tr>
		                </thead>
		                <tbody>
		                @foreach($oficinas as $oficina)
		                	<?
		                	$tr_estado="";

		                	if(!is_null($oficina->deleted_at)) {
		                		$tr_estado="class='text-yellow'";
		                		$attr_btn_eliminar=['class' => 'btn btn-warning btn-xs','title'=>'Dar de baja','role' => 'button', 'type' => 'submit','disabled'=>''];
		                	}else $attr_btn_eliminar=['class' => 'btn btn-warning btn-xs','title'=>'Dar de baja','role' => 'button', 'type' => 'submit'];		                	
		                	?>

			                <tr {{$tr_estado}}>
			                  <td>{{$oficina->oficina_ciudad}}</td>
			                  <td>{{$oficina->oficina_direccion}}</td>
			                  <td>
			                  	<a href="{{ URL::to('oficinas/detalle/'. $hashids->encode($oficina->id)) }}" data-skin="skin-blue" class="btn btn-primary btn-xs"><i class="fa fa-eye" title="Ver Detalles"></i></a>
			                  	<a href="{{ URL::to('oficinas/modificar/'.$hashids->encode($oficina->id)) }}" data-skin="skin-blue" class="btn btn-primary btn-xs"><i class="fa fa-pencil" title="Editar"></i></a>
								{{ Form::open(array('method' => 'POST','url' => 'oficinas/eliminardb','name'=>'frm_eliminar1')) }}
									{{ Form::hidden('id', $hashids->encode($oficina->id)) }}
				  					{{ Form::button('<i class="fa fa-trash-o"></i>', $attr_btn_eliminar) }}                     
				                {{ Form::close() }}   
			                  </td>
			                </tr>	
		                @endforeach                                
		                </tbody>
		                <tfoot>
		                <tr>
		                  <th>CIUDAD</th>
		                  <th>DIRECCIÓN</th>
		                  <th>OPERACIONES</th>
		                </tr>
		                </tfoot>
		              </table>	
			           <div class="text-right">
			           		<i class="text-muted">{{$oficinas->count()." registros en lista"}}  </i>
			           </div>			              	           		                      
		            </div>  				         
				</div>          	
            </div>      
          </div>
        </div>
      </div>
@endsection