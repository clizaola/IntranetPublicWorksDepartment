<?php
	class OPrograma extends IobrasProgramasobras{
		
	}

	class IobrasProgramasobras extends ActiveRecord{
		public static function registrar($obra, $programa){
			$conexion = new IobrasProgramasobras();

    		$conexion -> obras_id = $obra;
    		$conexion -> programas_id = $programa;

    		$conexion -> save();
			
			//SI LA OBRA ES NUEVA CAMBIARLA A EN PROCESO
			$obra = Obra::consultar($obra);
			
			if($obra -> estado == "NUEVA"){
				$obra -> estado = "EN PROCESO";
				$obra -> save();
			}

    		return $conexion;
    	}

    	public static function consultar($id){
    		$conexion = new IobrasProgramasobras();

    		$conexion = $conexion -> find($id);

    		return $conexion;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasProgramasobras();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$conexion = new IobrasProgramasobras();

			if($sql == "")
    			$conexion = $conexion -> count();
    		else
    			$conexion = $conexion -> count($sql);

    		return $conexion;
    	}
    	
    	public function programa(){
    		return Programa::consultar($this -> programas_id);
    	}
    	
    	public static function reporteDisponibles($obra){
    		$temporal = Programa::reporte();
    		$programas = array();
    		
    		if($temporal) foreach($temporal as $tmp){
    			if(OPrograma::total("obras_id=".$obra." AND programas_id=".$tmp -> id)==0){
    				$programas[$tmp -> id] = $tmp;
    			}
    		}
    		
    		return $programas;
    	}
	}
?>