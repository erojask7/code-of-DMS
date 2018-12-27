<?
class OficinaController extends BaseController {

	public function index()
    {
		$arr_js= array(asset('/js/v_oficinas.js'));
		$objetos=Oficina::withTrashed()->get();
		$data=array(
			'menu_principal'=>'Configuración',
			'menu_secundario' => 'Oficinas',
			'id_menu'=>1,
			'oficinas'=>$objetos,
			'js_adicional'=>$arr_js
			);
		return View::make('oficinas.v_oficinas',$data);
    }	

    public function nuevodb()
    {
    	try{
			$objeto=Input::all();
			$reglas =  array(
				'oficina_direccion'=>array('required'),
				'oficina_ciudad'=>array('required'),
		    );	    
		    $validacion = Validator::make($objeto, $reglas);
			if ( $validacion->fails() ){
		    	return Redirect::back()
		    	->withErrors($validacion)
		    	->withInput();
		    }else{		    	
				DB::transaction(function() use ($objeto) {
					$objetoCreado=Oficina::create($objeto);						
				});
				Session::flash("msj_sistema_exito", "La oficina".(!empty($objeto["oficina_direccion"])?" '".$objeto['oficina_direccion']."'":'')." se ha registrado con éxito");
				return Redirect::back();
		    }
    	}catch(Exception $e){
			Session::flash("msj_sistema_error", "No se ha podido realizar la operación. Inténtelo de nuevo");
			return Redirect::to('oficinas');
    	}	    	
    }
	
   public function modificar($id){
		try {
		   	$hashids = new Hashids\Hashids('sisgasystem', 25);
		   	$id=$hashids->decode($id)[0];
			$var=Input::old();
			$estado_obj=1;//Inicializando estado_obj='Activo'
			if(count($var)>0){
				//si Input::old tiene elementos( a causa de Validacion->fails) se pasan los datos al form
				$oficinamdf=(object) $var;	
				$oficinamdf->id=$id;
				if($oficinamdf->estado==0) $estado_obj=0;//Si estado tiene 0, estado_obj='Inactivo'
			}else{
				$oficinamdf = Oficina::withTrashed()->find($id);	
				if(!is_null($oficinamdf->deleted_at)) $estado_obj=0;//Si estado tiene una fecha, estado_obj='Inactivo'
			}		
			$arr_js= array(asset('/js/v_ oficinas.js'));
			$data=array(
				'menu_principal'=>'Configuración',
				'menu_secundario' => 'Oficinas',
				'id_menu'=>1,
				'js_adicional'=>$arr_js,
				'estado_obj'=>$estado_obj
			);			        	
		     return View::make('oficinas.v_oficinasmdf',$data)
		             ->with('oficinamdf', $oficinamdf);		 
		} catch (Exception $e) {
			Session::flash("msj_sistema_error", "Ocurrió un error");
			return Redirect::to('oficinas');
		}		     
    }

    public function modificardb($id){
	    try {
	    	$hashids = new Hashids\Hashids('sisgasystem', 25);
	    	$id=$hashids->decode($id)[0];
			$objeto=Input::all();// recibe datos de Oficina a modificar
			$reglas =  array(
				'oficina_direccion'=>array('required'),
				'oficina_ciudad'=>array('required')
		    );	    
		    $validacion = Validator::make($objeto, $reglas);
			if($validacion->fails() ){
		    	return Redirect::to("oficinas/modificar/".$hashids->encode($id)."")
		    	->withErrors($validacion)
		    	->withInput();
		    }else{    		    			    				
				if($objeto["estado"]==1){// Dar de alta (A Estado:activo[1])
					$objeto["deleted_at"]=NULL;
				}
				unset($objeto['estado'],
					$objeto['_token']);
				DB::transaction(function() use ($id,$objeto) {
					$objetodb=Oficina::withTrashed()->find($id);
					$objetodb->fill($objeto);
					$attr_cambiados=$objetodb->getDirty();
					$objetodb->save();
					if(array_key_exists("oficina_direccion", $attr_cambiados)){//DENORMALIZACION
						$arr['oficina_direccion']=$objetodb->oficina_direccion;
					}
					if(isset($arr)){//DENORMALIZACION
						$idsEstantes=Estante::withTrashed()->where("oficina_id","=",$id)->lists("id");
						if(count($idsEstantes)>0)
							Archivador::withTrashed()->whereIn("estante_id",$idsEstantes)->update($arr);//Eliminar archivadores
					}							
				});				
				Session::flash("msj_sistema_exito", "La oficina".(!empty($objeto["oficina_direccion"])?" '".$objeto['oficina_direccion']."'":'')." se ha actualizado correctamente");
				return Redirect::to('oficinas');   
		     }
		} catch (Exception $e) {
			Session::flash("msj_sistema_error", "No se ha podido realizar la operación. Inténtelo de nuevo");
			return Redirect::to('oficinas');			
		}	       	
    }

    public function detalle($id){
    	try{
    		$hashids = new Hashids\Hashids('sisgasystem', 25);
    		$id=$hashids->decode($id)[0];
			$oficinamdf = Oficina::withTrashed()->find($id);	
			$arr_js= array(asset('/js/v_oficinas.js'));
			$data=array(
				'menu_principal'=>'Configuración',
				'menu_secundario' => 'Oficinas',
				'id_menu'=>1,
				'js_adicional'=>$arr_js,
			);			        	
		    return View::make('oficinas.v_oficinasdetalle',$data)
		             ->with('oficinamdf', $oficinamdf);	
    	} catch (Exception $e) {
			Session::flash("msj_sistema_error", "Ocurrió un error");
			return Redirect::to('oficinas');			
		}
    }            

    public function eliminardb(){
		try {
			$hashids = new Hashids\Hashids('sisgasystem', 25);
			$id=$hashids->decode(Input::get("id"))[0];
    		
			DB::transaction(function() use ($id) {
				$nroafectados=Oficina::destroy($id);				
			});
			Session::flash("msj_sistema_exito", "La oficina se ha dado de baja correctamente");
			return Redirect::back();					
		} catch (Exception $e) {
			Session::flash("msj_sistema_error", "No se ha podido realizar la operación. Inténtelo de nuevo");
			return Redirect::to('oficinas');			
		}
    }    
    
    public function verdependencias(){
    	$hashids = new Hashids\Hashids('sisgasystem', 25);
    	$id=$hashids->decode(Input::get("id"))[0];    	
    	if(Request::ajax()){
    		$nrohijos=Estante::where("oficina_id","=",$id)->count();
    		return Response::json(array(
    				'nrohijos' => $nrohijos,
    		));
    	}
    }    
}
?>