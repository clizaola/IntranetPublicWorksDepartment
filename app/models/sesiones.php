<?php
    class Cabildo extends Sesiones{
        
    }

	class Sesiones extends ActiveRecord{
        public static function consultar($id){
    		$objeto = new Sesiones();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}

    	public static function reporte($sql=""){
    		$objeto = new Sesiones();

    		if($sql == ""){
    			$objeto = $objeto -> find();
    		}
            else{
	    		$objeto = $objeto -> find($sql);
    		}

    		return $objeto;
    	}
        
        public static function total($sql=""){
    		$objeto = new Sesiones();

    		if($sql == ""){
    			$objeto = $objeto -> count();
    		}
            else{
	    		$objeto = $objeto -> count($sql);
    		}

    		return $objeto;
    	}
	}
?>