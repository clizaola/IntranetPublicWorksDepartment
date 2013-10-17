<?php
    class NominaCategorias extends IobrasNominacategorias{
        
    }

	class IobrasNominacategorias extends ActiveRecord{
        public static function registrar($nombre, $salario){
            $categoria = new IobrasNominacategorias();
            
            $categoria -> nombre = $nombre;
            $categoria -> salario = $salario;
            
            $categoria -> save();
            
            return $categoria;
        }
        
        public function modificar($nombre, $salario){
            $this -> nombre = $nombre;
            $this -> salario = $salario;
            
            $this -> save();
        }
        
        public static function consultar($id){
    		$objeto = new IobrasNominacategorias();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}
    	
    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasNominacategorias();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasNominacategorias();

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
        
        public function empleados(){
            return NominaEmpleados::reporte("categoria_id=".$this -> id);
        }
    }
?>