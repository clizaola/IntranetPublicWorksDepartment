<?php
	class OArchivo extends IobrasArchivosobras{
        
    }
    
	class IobrasArchivosobras extends ActiveRecord{
		public static function registrar($obra, $nombre){
			$archivo_obra = new IobrasArchivosobras();

    		$archivo_obra -> obras_id = $obra;
    		$archivo_obra -> nombre = $nombre;

    		$archivo_obra -> save();
			
			//SI LA OBRA ES NUEVA CAMBIARLA A EN PROCESO
			$obra = Obra::consultar($obra);
			
			if($obra -> estado == "NUEVA"){
				$obra -> estado = "EN PROCESO";
				$obra -> save();
			}

    		return $archivo_obra;
    	}

    	public static function consultar($id){
    		$archivo_obra = new IobrasArchivosobras();

    		$archivo_obra = $archivo_obra -> find($id);

    		return $archivo_obra;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasArchivosobras();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
	}
?>
