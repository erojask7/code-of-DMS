<?php
class Oficina extends Eloquent {
	use \Venturecraft\Revisionable\RevisionableTrait;
	use SoftDeletingTrait;
	protected $table= 'oficinas';
	protected $fillable=array('oficina_direccion','oficina_ciudad','deleted_at');	
	protected $revisionEnabled = true;
	protected $revisionCreationsEnabled = true;
	protected $keepRevisionOf = array(
			'oficina_ciudad',
			'oficina_direccion',
			'deleted_at',
	);	

	public function estantes()
	{
		return $this->hasMany('Estante')->withTrashed();
	}	
	protected static function boot()
	{
		parent::boot();
	
		static::deleted(function($oficina) { // before delete() method call this
			$idsEstantes=Estante::where("oficina_id","=",$oficina->id)->whereNull("deleted_at")->lists("id");
			if(count($idsEstantes)>0){
				Estante::whereIn("id",$idsEstantes)->whereNull("deleted_at")->delete();//Eliminar Estantees
				$idsArchivadors=Archivador::whereIn("estante_id",$idsEstantes)->whereNull("deleted_at")->lists("id");
				if(count($idsArchivadors)>0){
					Archivador::whereIn("id",$idsArchivadors)->whereNull("deleted_at")->delete();//Eliminar archivadores
					Expediente::whereIn("archivador_id",$idsArchivadors)->whereNull("deleted_at")->delete();//Eliminar Expedientes
				}
			}
		});
	}	
}
?>