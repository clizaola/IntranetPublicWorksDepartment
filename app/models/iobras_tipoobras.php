<?php
    class TipoObra extends IobrasTipoobras{
        
    }

	class IobrasTipoobras extends ActiveRecord{
		public static function registrar($nombre){
            if($nombre == ""){
  		        return null;
    		}
            
            if(TipoObra::total("nombre='".$nombre."'")>0){
                return null;
            }  
          
    		$objeto = new IobrasTipoobras();
    		
            $objeto -> nombre = $nombre;
            
            $objeto -> save();

			return $objeto;
    	}
        
        public function modificar($nombre){
            $this -> nombre = $nombre;
            
            $this -> save();
    	}
        
        public static function consultar($id){
    		$objeto = new IobrasTipoobras();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasTipoobras();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasTipoobras();

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
        
        public static function alcance($tipo){
            switch($tipo){
                case 'XXX': $r = 'Sin Alance';
                            break;
                case 'LXX': $r = 'Local';
                            break;
                case 'XMX': $r = 'Municipal';
                            break;
                case 'XXR': $r = 'Regional';
                            break;
                default: $r = 'Sin Alance';
            }
            
            return $r;
        }
        
        public static function nombre($tipo){
            $TipoObras = new IobrasTipoobras();
            $TipoObras -> find_first('id = '.$tipo);
            
            return $TipoObras -> nombre;
        }
	}
?>