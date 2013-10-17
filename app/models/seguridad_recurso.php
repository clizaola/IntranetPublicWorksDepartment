<?php 
    class Recurso extends SeguridadRecurso{
        
    }

	class SeguridadRecurso extends ActiveRecord {
		/* FUNCIONES BASICAS DEL OBJETO */
         
        public static function registrar($nombre, $modulo){      
        	Load::lib("aclx");
        	      
            if(SeguridadRecurso::total("nombre='".$nombre."' AND modulo_id=".$modulo)>0){
                return false;
            }
            
            $objeto = new SeguridadRecurso();
            
            $objeto -> nombre = $nombre;
            $objeto -> modulo_id = $modulo;
            
            $objeto -> save();
            
            return $objeto;
        }
		
		public static function consultar($id){
            $objeto = new SeguridadRecurso();
            
            return $objeto -> find($id);
        }
		
		public static function buscar($sql="id>0"){
            $objeto = new SeguridadRecurso();
            
            return $objeto -> find_first($sql);
        }
        
        public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new SeguridadRecurso();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql="id>0"){
            $objeto = new SeguridadRecurso();
            
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
            $objeto = SeguridadRecurso::consultar($id);
            
            if($objeto){
                $objeto -> eliminar($cascada);        
            }
        }
        
        public function guardar(){
            $this -> save();
        }
        
        public static function existe($sql="id=0"){
            $x = SeguridadRecurso::total($sql);
            
            if($x>0) return true;
            return false;
        }
        
        /* --- FUNCIONES QUE DESCRIBEN LAS RELACIONES --- */
        
        public function modulo(){
            return SeguridadModulo::consultar($this -> modulo_id);
        }
        
        public function acciones(){
            return SeguridadAccion::reporte("recurso_id=".$this -> id);
        }
		
	}
?>