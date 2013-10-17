<?php
    class NominaAsistencia extends IobrasNominaasistencia{
        
    }

	class IobrasNominaasistencia extends ActiveRecord{
        public static function registrar($nomina_id, $empleado_id,$categoria_id){
            $asistencia = new IobrasNominaasistencia();
            
            $asistencia -> nomina_id = $nomina_id;
            $asistencia -> empleado_id = $empleado_id;
            $asistencia -> categoria_id = $categoria_id;
            
            $categoria = NominaCategorias::consultar($categoria_id);
            
            $asistencia -> salario = $categoria -> salario;
            
            $asistencia -> save();
        }
        
        public static function consultar($id){
    		$objeto = new IobrasNominaasistencia();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasNominaasistencia();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasNominaasistencia();

    		if($sql == ""){
    			$objeto = $objeto -> count();
    		}
            else{
	    		$objeto = $objeto -> count($sql);
    		}

    		return $objeto;
    	}
        
        public function eliminar(){
            $this -> delete();    
        }
        
        public function empleado(){
            return NominaEmpleados::consultar($this -> empleado_id);
        }
        
        public function categoria(){
            return NominaCategorias::consultar($this -> categoria_id);
        }
        
        public function nomina(){
            return Nomina::consultar($this -> nomina_id);
        }
    }
?>