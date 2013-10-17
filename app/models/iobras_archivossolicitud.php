<?php
	class SArchivo extends IobrasArchivossolicitud{
        
    }
    
	class IobrasArchivossolicitud extends ActiveRecord{
		public static function registrar($solicitud, $nombre){
			$archivo_solicitud = new IobrasArchivossolicitud();

    		$archivo_solicitud -> solicitud_id = $solicitud;
    		$archivo_solicitud -> nombre = $nombre;

    		$archivo_solicitud -> save();

    		return $archivo_solicitud;
    	}

    	public static function consultar($id){
    		$archivo_solicitud = new IobrasArchivossolicitud();

    		$archivo_solicitud = $archivo_solicitud -> find($id);

    		return $archivo_solicitud;
    	}

    	public static function reporte($sql=""){
    		$archivo_solicitud = new IobrasArchivossolicitud();

			if($sql == "")
    			$archivo_solicitud = $archivo_solicitud -> find();
    		else
    			$archivo_solicitud = $archivo_solicitud -> find($sql);

    		return $archivo_solicitud;
    	}
        
        public static function total($sql=""){
    		$archivo_solicitud = new IobrasArchivossolicitud();

			if($sql == "")
    			$archivo_solicitud = $archivo_solicitud -> count();
    		else
    			$archivo_solicitud = $archivo_solicitud -> count($sql);

    		return $archivo_solicitud;
    	}
	}
?>