<?php 
    class Modulo extends SeguridadModulo{
        
    }

	class SeguridadModulo extends ActiveRecord {
		/* FUNCIONES BASICAS DEL OBJETO */
         
        public static function registrar($nombre){           
        	Load::lib("aclx");
        	 
        	if(SeguridadModulo::total("nombre='".$nombre."' AND app_id=".Acl::aplicacion())>0){
                return false;
            }
            
            $objeto = new SeguridadModulo();
            
            $objeto -> nombre = $nombre;
            $objeto -> app_id = Acl::aplicacion();
            
            $objeto -> save();
            
            return $objeto;
        }
		
		public static function consultar($id){
            $objeto = new SeguridadModulo();
            
            return $objeto -> find($id);
        }
		
		public static function buscar($sql="id>0"){
            $objeto = new SeguridadModulo();
            
            return $objeto -> find_first($sql);
        }
        
        public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new SeguridadModulo();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql="id>0"){
            $objeto = new SeguridadModulo();
            
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
            $objeto = SeguridadModulo::consultar($id);
            
            if($objeto){
                $objeto -> eliminar($cascada);        
            }
        }
        
        public function guardar(){
            $this -> save();
        }
        
        public static function existe($sql="id=0"){
            $x = SeguridadModulo::total($sql);
            
            if($x>0) return true;
            return false;
        }
        
        /* --- FUNCIONES QUE DESCRIBEN LAS RELACIONES --- */
        
        public function aplicacion(){
            return SeguridadAplicacion::consultar($this -> app_id);
        }
        
        public function recursos(){
            return SeguridadRecurso::reporte("modulo_id=".$this -> id);
        }
		
	}
?>