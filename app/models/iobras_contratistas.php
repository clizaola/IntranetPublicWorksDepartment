<?php
    class Contratista extends IobrasContratistas{
        
    }

	class IobrasContratistas extends ActiveRecord{
		public static function registrar($nombre,$telefono="",$celular=""){
		  
            if($nombre == ""){
  		        return null;
    		}
            
            if(Contratista::total("nombre='".$nombre."'")>0){
                return null;
            }
          
    		$objeto = new IobrasContratistas();
    		
            $objeto -> nombre = $nombre;
            $objeto -> telefono = $telefono;
            $objeto -> celular = $celular;
            
            $objeto -> save();

			return $objeto;
    	}
        
        public function modificar($nombre,$telefono="",$celular=""){
    		
            $this -> nombre = $nombre;
            $this -> telefono = $telefono;
            $this -> celular = $celular;
            
            $this -> save();
    	}
        
        public static function consultar($id){
    		$objeto = new IobrasContratistas();
    		$objeto = $objeto -> find_first('id = '.$id);

    		return $objeto;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasContratistas();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasContratistas();

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