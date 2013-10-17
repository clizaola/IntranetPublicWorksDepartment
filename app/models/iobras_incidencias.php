<?php
	class IobrasIncidencias extends NuevoActiveRecord{
		public static function registrar($obra_id, $empleado_id, $descripcion,$fecha=0){
			$Objeto = new IobrasIncidencias();
			$Objeto -> obras_id = $obra_id;
    		$Objeto -> empleado_id = $empleado_id;
    		$Objeto -> descripcion = $descripcion;
    		
    		if($fecha == 0){
    			$Objeto -> fecha = date("Y-m-d");
    		}else{
    			$Objeto -> fecha = $fecha;
    		}
    		
    		$Objeto -> fecha_in = date("Y-m-d");
    		$Objeto -> fecha_at = date("Y-m-d");
			
			$Objeto -> save();
			
			return $Objeto;
		}
		
		public function obra(){
			return IobrasObras::consultar($this -> obra_id);
		}
		
		public function empleado(){
			return IobrasEmpleados::consultar($this -> empleado_id) -> nombre;
		}
	}
?>