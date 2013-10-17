<?php
    class Localidad extends IobrasLocalidades{
        
    }

	class IobrasLocalidades extends ActiveRecord{
		public static function registrar($nombre){
            if($nombre == ""){
  		        return null;
    		}
            
            if(Localidad::total("nombre='".$nombre."'")>0){
                return null;
            }
          
    		$objeto = new IobrasLocalidades();
    		
            $objeto -> nombre = $nombre;
            
            $objeto -> save();

			return $objeto;
    	}
        
        public function modificar($nombre){
            $this -> nombre = $nombre;
            
            $this -> save();
    	}
        
        public static function consultar($id){
    		$objeto = new IobrasLocalidades();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasLocalidades();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasLocalidades();

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
        
        public static function nombre($tipo){
            $Localidades = new IobrasLocalidades();
            $Localidades -> find_first('id = '.$tipo);
            
            return $Localidades -> nombre;
        }
	}
?>