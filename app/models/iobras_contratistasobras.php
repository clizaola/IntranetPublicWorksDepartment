<?php
	class OContratista extends IobrasContratistasobras{
		
	}
	class IobrasContratistasobras extends ActiveRecord{
		public static function registrar($obra, $contratista){
			$conexion = new IobrasContratistasobras();

    		$conexion -> obras_id = $obra;
    		$conexion -> contratistas_id = $contratista;

    		$conexion -> save();

    		return $conexion;
    	}

    	public static function consultar($id){
    		$conexion = new IobrasContratistasobras();

    		$conexion = $conexion -> find($id);

    		return $conexion;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasContratistasobras();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$conexion = new IobrasContratistasobras();

			if($sql == "")
    			$conexion = $conexion -> count();
    		else
    			$conexion = $conexion -> count($sql);

    		return $conexion;
    	}
    	
    	public function contratista(){
    		return Contratista::consultar($this -> contratistas_id);
    	}
    	
    	public static function reporteDisponibles($obra){
    		$temporal = Contratista::reporte();
    		$contratistas = array();
    		
    		if($temporal) foreach($temporal as $tmp){
    			if(OContratista::total("obras_id=".$obra." AND contratistas_id=".$tmp -> id)==0){
    				$contratistas[$tmp -> id] = $tmp;
    			}
    		}
    		
    		return $contratistas;
    	}
	}
?>