<?php 
    class Aplicacion extends SeguridadAplicacion{
        
    }

	class SeguridadAplicacion extends ActiveRecord {
		/* FUNCIONES BASICAS DEL OBJETO */
         
        public static function registrar($codigo, $nombre){            
            if(SeguridadAplicacion::total("codigo='".$codigo."' AND nombre='".$nombre."'")>0){
                return false;
            }
            
            $objeto = new SeguridadAplicacion();
            
            $objeto -> codigo = $codigo;
            $objeto -> nombre = $nombre;
            
            $objeto -> save();
            
            return $objeto;
        }
		
		public static function consultar($id){
            $objeto = new SeguridadAplicacion();
            
            return $objeto -> find($id);
        }
		
		public static function buscar($sql="id>0"){
            $objeto = new SeguridadAplicacion();
            
            return $objeto -> find_first($sql);
        }
        
        public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new SeguridadAplicacion();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql="id>0"){
            $objeto = new SeguridadAplicacion();
            
            return $objeto -> count($sql);
        }
        
        public function eliminar($cascada = false){
            if($cascada){
                //Eliminar registros relacionados en la base de datos.
                $this -> delete();
                return true;
            }
            else{
                //Validaciones opcionales antes de eliminar, para no dejar registros sin relación.
                $this -> delete();
                return true;  
            }
            return false;
        }
        
        public static function eliminarID($id, $cascada = false){
            $objeto = SeguridadAplicacion::consultar($id);
            
            if($objeto){
                $objeto -> eliminar($cascada);        
            }
        }
        
        public function guardar(){
            $this -> save();
        }
        
        public static function existe($sql="id=0"){
            $x = SeguridadAplicacion::total($sql);
            
            if($x>0) return true;
            return false;
        }
        
        /* --- FUNCIONES QUE DESCRIBEN LAS RELACIONES --- */
        
        public function usuarios(){
            return SeguridadUsuario::reporte("app_id=".$this -> id);
        }
        
        public function roles(){
            return SeguridadRol::reporte("app_id=".$this -> id);
        }
		
        public function modulos(){
            return SeguridadModulo::reporte("app_id=".$this -> id);
        }
	}
?>