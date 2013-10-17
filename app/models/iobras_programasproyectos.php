<?php
	class PPrograma extends IobrasProgramasproyectos{
		
	}

	class IobrasProgramasproyectos extends ActiveRecord{
		public static function registrar($proyecto, $programa){
			$conexion = new IobrasProgramasproyectos();

    		$conexion -> proyectos_id = $proyecto;
    		$conexion -> programas_id = $programa;

    		$conexion -> save();
			
			//SI EL PROYECTO ES NUEVO CAMBIARLO A EN PROCESO
			$proyecto = Proyecto::consultar($proyecto);
			
			if($proyecto -> estado == "NUEVO"){
				$proyecto -> estado = "EN PROCESO";
				$proyecto -> save();
			}

    		return $conexion;
    	}

    	public static function consultar($id){
    		$conexion = new IobrasProgramasproyectos();

    		$conexion = $conexion -> find($id);

    		return $conexion;
    	}

    	public static function reporte($sql=""){
    		$conexion = new IobrasProgramasproyectos();

			if($sql == "")
    			$conexion = $conexion -> find();
    		else
    			$conexion = $conexion -> find($sql);

    		return $conexion;
    	}
        
        public static function total($sql=""){
    		$conexion = new IobrasProgramasproyectos();

			if($sql == "")
    			$conexion = $conexion -> count();
    		else
    			$conexion = $conexion -> count($sql);

    		return $conexion;
    	}
    	
    	public function programa(){
    		return Programa::consultar($this -> programas_id);
    	}
    	
    	public static function reporteDisponibles($proyecto){
    		$temporal = Programa::reporte();
    		$programas = array();
    		
    		if($temporal) foreach($temporal as $tmp){
    			if(PPrograma::total("proyectos_id=".$proyecto." AND programas_id=".$tmp -> id)==0){
    				$programas[$tmp -> id] = $tmp;
    			}
    		}
    		
    		return $programas;
    	}
	}
?>