<?php 
    class Permisos extends SeguridadPermisos{
        
    }

	class SeguridadPermisos extends ActiveRecord {
		/* FUNCIONES BASICAS DEL OBJETO */
         
        public static function registrar($rol, $recurso){      
        	Load::lib("aclx");
        	      
            if(SeguridadPermisos::total("rol_id='".$rol."' AND recurso_id=".$recurso)>0){
                return false;
            }
            
            $objeto = new SeguridadPermisos();
            
            $objeto -> rol_id = $rol;
            $objeto -> recurso_id = $recurso;
            
            $objeto -> save();
            
            return $objeto;
        }
		
		public static function consultar($id){
            $objeto = new SeguridadPermisos();
            
            return $objeto -> find($id);
        }
		
		public static function buscar($sql="id>0"){
            $objeto = new SeguridadPermisos();
            
            return $objeto -> find_first($sql);
        }
        
        public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new SeguridadPermisos();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql="id>0"){
            $objeto = new SeguridadPermisos();
            
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
            $objeto = SeguridadPermisos::consultar($id);
            
            if($objeto){
                $objeto -> eliminar($cascada);        
            }
        }
        
        public function guardar(){
            $this -> save();
        }
        
        public static function existe($sql="id=0"){
            $x = SeguridadPermisos::total($sql);
            
            if($x>0) return true;
            return false;
        }
        
        /* --- FUNCIONES QUE DESCRIBEN LAS RELACIONES --- */
        
        public function usuario(){
            return SeguridadRol::consultar($this -> rol_id);
        }
        
        public function recurso(){
            return SeguridadRecurso::reporte($this -> recurso_id);
        }
		
	}
?>