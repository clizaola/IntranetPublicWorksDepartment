<?php
	class PContratista extends IobrasContratistasproyectos{
		
	}

	class IobrasContratistasproyectos extends ActiveRecord{
		public static function registrar($obra, $contratista){
			$conexion = new IobrasContratistasproyectos();

    		$conexion -> proyectos_id = $obra;
    		$conexion -> contratistas_id = $contratista;

    		$conexion -> save();

    		return $conexion;
    	}

    	public static function consultar($id){
    		$conexion = new IobrasContratistasproyectos();

    		$conexion = $conexion -> find($id);

    		return $conexion;
    	}

    	public static function reporte($sql=""){
    		$conexion = new IobrasContratistasproyectos();

			if($sql == "")
    			$conexion = $conexion -> find();
    		else
    			$conexion = $conexion -> find($sql);

    		return $conexion;
    	}
        
        public static function total($sql=""){
    		$conexion = new IobrasContratistasproyectos();

			if($sql == "")
    			$conexion = $conexion -> count();
    		else
    			$conexion = $conexion -> count($sql);

    		return $conexion;
    	}
    	
    	public function contratista(){
    		return Contratista::consultar($this -> contratistas_id);
    	}
    	
    	public static function reporteDisponibles($proyecto){
    		$temporal = Contratista::reporte();
    		$contratistas = array();
    		
    		if($temporal) foreach($temporal as $tmp){
    			if(PContratista::total("proyectos_id=".$proyecto." AND contratistas_id=".$tmp -> id)==0){
    				$contratistas[$tmp -> id] = $tmp;
    			}
    		}
    		
    		return $contratistas;
    	}
	}
?>