<?php
    class Programa extends IobrasProgramas{
        
    }

	class IobrasProgramas extends ActiveRecord{
		public static function registrar($nombre, $dependencia = "0"){
            if($nombre == ""){
  		        return null;
    		}
            
            if(Programa::total("nombre='".$nombre."'")>0){
                return null;
            }
          
    		$objeto = new IobrasProgramas();
    		$objeto -> nombre = $nombre;
            $objeto -> dependencias_id = $dependencia;
    		$objeto -> save();

            return $objeto;
    	}
        
        public function modificar($nombre, $dependencia = "0"){
    		$this -> nombre = $nombre;
            $this -> dependencias_id = $dependencia;
            
    		$this -> save();
    	}

    	public static function consultar($id){
    		$objeto = new IobrasProgramas();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasProgramas();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasProgramas();

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
        
        public function dependencia(){
            return Dependencia::consultar($this -> dependencias_id);
        }
	}
?>