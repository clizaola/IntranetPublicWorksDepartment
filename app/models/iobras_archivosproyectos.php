<?php
	class PArchivo extends IobrasArchivosproyectos{
        
    }
    
	class IobrasArchivosproyectos extends ActiveRecord{
		public static function registrar($proyecto, $nombre){
			$archivo_proyecto = new IobrasArchivosProyectos();

    		$archivo_proyecto -> proyectos_id = $proyecto;
    		$archivo_proyecto -> nombre = $nombre;

    		$archivo_proyecto -> save();
			
			//SI EL PROYECTO ES NUEVO CAMBIARLO A EN PROCESO
			$proyecto = Proyecto::consultar($proyecto);
			
			if($proyecto -> estado == "NUEVO"){
				$proyecto -> estado = "EN PROCESO";
				$proyecto -> save();
			}

    		return $archivo_proyecto;
    	}

    	public static function consultar($id){
    		$archivo_proyecto = new IobrasArchivosProyectos();

    		$archivo_proyecto = $archivo_proyecto -> find($id);

    		return $archivo_proyecto;
    	}

    	public static function reporte($sql=""){
    		$archivo_proyectos = new IobrasArchivosProyectos();

			if($sql == "")
    			$archivo_proyectos = $archivo_proyectos -> find();
    		else
    			$archivo_proyectos = $archivo_proyectos -> find($sql);

    		return $archivo_proyectos;
    	}
	}
?>