<?php
    class Factura extends IobrasFacturas{
        
    }

	class IobrasFacturas extends ActiveRecord{
		public static function registrar($obra){
			$conexion = new IobrasFacturas();

            $conexion -> obras_id = $obra;
            $conexion -> estado = "NUEVA";
            
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
    		$conexion = new IobrasFacturas();
			
			if($conexion -> count($id)==0){
				return false;
			}
		
			return $conexion -> find_first($id);

    		return $conexion;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasFacturas();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public function obra(){
            $obra = Obra::consultar($this -> obras_id);
            return $obra;
        }
        
        public function conceptos(){
           return IobrasFacturasConceptos::reporte("factura_id=".$this -> id);
        }
		
		public function requisiciones(){
			$tmp = IobrasFacturarequisicion::reporte("factura_id=".$this -> id);
			
			$requisiciones = array();
			
			if($tmp) foreach($tmp as $tx){
				$requisiciones[$tx -> requisicion_id] = IobrasRequisiciones::consultar($tx -> requisicion_id);
			}
			
			return $requisiciones;
		}
		
		public static function total($sql=""){
    		$objeto = new IobrasFacturas();

    		if($sql == ""){
    			$objeto = $objeto -> count();
    		}
            else{
	    		$objeto = $objeto -> count($sql);
    		}

    		return $objeto;
    	}
	}
?>