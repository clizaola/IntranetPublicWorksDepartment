<?php
    class Insumo extends IobrasInsumos{
        
    }

	class IobrasInsumos extends ActiveRecord{
		public static function registrar($nombre,$medida,$precio=0){
            if($nombre == ""){
  		        return null;
    		}
            
            if(Insumo::total("nombre='".$nombre."'")>0){
                return null;
            }
          
    		$objeto = new IobrasInsumos();
    		
            $objeto -> nombre = $nombre;
            $objeto -> medida = $medida;
            $objeto -> precio = $precio;
            
            $objeto -> save();

			return $objeto;
    	}
        
        public function modificar($nombre,$medida){
            $this -> nombre = $nombre;
            $this -> medida = $medida;
            
            $this -> save();
    	}
        
        public static function consultar($id){
    		$objeto = new IobrasInsumos();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}

    	public static function buscar($sql="id>0", $orden="id ASC"){
            $objeto = new IobrasInsumos();
            
            $sql .= " ORDER BY ".$orden;
            
            return $objeto -> find_first($sql);
        }
        
        public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasInsumos();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasInsumos();

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