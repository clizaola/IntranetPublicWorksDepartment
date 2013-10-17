<?php
    class NominaEmpleados extends IobrasNominaempleados{
        
    }

	class IobrasNominaempleados extends ActiveRecord{
        public static function registrar($nombre, $categoria_id, $telefono=""){
            $empleado = new IobrasNominaempleados();
            
            $empleado -> nombre = $nombre;
            $empleado -> categoria_id = $categoria_id;
            
            if($telefono) $empleado -> telefono = $telefono;
            
            $empleado -> save();
            
            return $empleado;
        }
        
        public function modificar($nombre, $categoria_id, $telefono=""){
            $this -> nombre = $nombre;
            $this -> categoria_id = $categoria_id;
            
            if($telefono) $this -> telefono = $telefono;
            
            $this -> save();
        }
        
        public static function consultar($id){
    		$objeto = new IobrasNominaempleados();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}
    	
    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasNominaempleados();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function buscar($sql=""){
    		$objeto = new IobrasNominaempleados();

    		if($sql == ""){
    			$objeto = $objeto -> find_first();
    		}
            else{
	    		$objeto = $objeto -> find_first($sql);
    		}

    		return $objeto;
    	}
        
        public static function total($sql=""){
    		$objeto = new IobrasNominaempleados();

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
        
        public function categoria(){
            return NominaCategorias::consultar($this -> categoria_id);
        }
        
        public function nominas(){
            $asistencia = $this -> asistencia();
            
            $nominas = array();
            $n=0;
            
            if($asistencia) foreach($asistencia as $a){
                $nominas[$n++] = $a -> nomina();
            }
            
            return $nominas;
        }
        
        public function asistencia(){
            return NominaAsistencia::reporte("empleado_id=".$this -> id);
        }
        
        public static function disponibles($nomina_id){
    		$empleados = NominaEmpleados::reporte("id>0","nombre ASC");
            
            $trabajadores = array();
            $t=0;
            
            if($empleados) foreach($empleados as $empleado){
                if(NominaAsistencia::total("nomina_id=".$nomina_id." AND empleado_id=".$empleado -> id)==0){
                    $trabajadores[$t++] = $empleado;   
                }
            }
            
            return $trabajadores;
    	}
    }
?>