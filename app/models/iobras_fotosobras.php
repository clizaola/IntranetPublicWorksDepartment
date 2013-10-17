<?php
	
	class FotosObras extends IobrasFotosobras{
        
    }
	
	class IobrasFotosobras extends ActiveRecord{
        public static function registrar($avance){
            $foto = new IobrasFotosobras();
            
            $foto -> avance_id = $avance;
            $foto -> save();
            
            return $foto;
        }
       
		public static function consultar($id){
    		$fotos = new IobrasFotosobras();
    		$fotos -> find($id);

    		return $fotos;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasFotosobras();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
	}
?>