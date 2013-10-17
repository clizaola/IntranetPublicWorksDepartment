<?php
	class IobrasConceptorequisicion extends NuevoActiveRecord{
		public static function registrar($requisicion,$producto,$cantidad){
			$conexion = new IobrasConceptorequisicion();

            $conexion -> requisicion_id = $requisicion;
            $conexion -> productos_id = $producto;
            $conexion -> cantidad = $cantidad;
            $conexion -> solicitado = $cantidad;

    		$conexion -> save();

    		return $conexion;
    	}
        
        public function insumo(){
            return Insumo::consultar($this -> productos_id);
        }

        public function requisicion(){
            return IobrasRequisiciones::consultar($this -> requisicion_id);
        } 
	}
?>