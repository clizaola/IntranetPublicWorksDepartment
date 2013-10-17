<?php
    class Empleado extends IobrasEmpleados{
        
    }

	class IobrasEmpleados extends ActiveRecord{
		public static function registrar($nombre,$puesto,$direccion="",$telefono="",$celular=""){
            if($nombre == ""){
  		        return null;
    		}
            
            if(Empleado::total("nombre='".$nombre."'")>0){
                return null;
            }
          
    		$objeto = new IobrasEmpleados();
    		
            $objeto -> nombre = $nombre;
            $objeto -> puesto = $puesto;
            $objeto -> direccion = $direccion;
            $objeto -> telefono = $telefono;
            $objeto -> celular = $celular;
            
            $objeto -> save();

			return $objeto;
    	}
        
        public function modificar($nombre,$puesto,$direccion="",$telefono="",$celular=""){
            $this -> nombre = $nombre;
            $this -> puesto = $puesto;
            $this -> direccion = $direccion;
            $this -> telefono = $telefono;
            $this -> celular = $celular;
            
            $this -> save();
    	}
        
        public static function consultar($id){
    		$objeto = new IobrasEmpleados();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}
    	
    	public static function buscar($sql="id>0"){
            $objeto = new IobrasEmpleados();
            
            return $objeto -> find_first($sql);
        }

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasEmpleados();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasEmpleados();

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
	}
?>