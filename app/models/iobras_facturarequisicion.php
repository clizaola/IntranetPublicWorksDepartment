<?php
	class IobrasFacturarequisicion extends NuevoActiveRecord{
		public static function registrar($requisicion,$factura){
			$conexion = new IobrasFacturarequisicion();
			
			if(IobrasFacturarequisicion::existe("requisicion_id=".$requisicion." AND factura_id=".$factura)){
				return false;
			}

            $conexion -> requisicion_id = $requisicion;
            $conexion -> factura_id = $factura;
            $conexion -> fecha = date("Y-m-d");

    		$conexion -> save();

    		return $conexion;
    	}
        
        public function factura(){
            return IobrasFacturas::consultar($this -> factura_id);
        }

        public function requisicion(){
            return IobrasRequisiciones::consultar($this -> requisicion_id);
        } 
	}
?>