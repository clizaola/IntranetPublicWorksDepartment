<?php 
    class Accion extends SeguridadAccion{
        
    }

	class SeguridadAccion extends ActiveRecord {
		/* FUNCIONES BASICAS DEL OBJETO */
         
        public static function registrar($recurso, $controlador, $accion){            
            if(SeguridadAccion::total("controlador='".$controlador."' AND accion='".$accion."' AND recurso_id=".$recurso)>0){
                return false;
            }
            
            $objeto = new SeguridadAccion();
            
            $objeto -> controlador = $controlador;
            $objeto -> accion = $accion;
            $objeto -> recurso_id = $recurso;
            
            $objeto -> save();
            
            return $objeto;
        }
		
		public static function consultar($id){
            $objeto = new SeguridadAccion();
            
            return $objeto -> find($id);
        }
		
		public static function buscar($sql="id>0"){
            $objeto = new SeguridadAccion();
            
            return $objeto -> find_first($sql);
        }
        
        public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new SeguridadAccion();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql="id>0"){
            $objeto = new SeguridadAccion();
            
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
            $objeto = SeguridadAccion::consultar($id);
            
            if($objeto){
                $objeto -> eliminar($cascada);        
            }
        }
        
        public function guardar(){
            $this -> save();
        }
        
        public static function existe($sql="id=0"){
            $x = SeguridadAccion::total($sql);
            
            if($x>0) return true;
            return false;
        }
        
        /* --- FUNCIONES QUE DESCRIBEN LAS RELACIONES --- */
        
        public function recurso(){
            return SeguridadRecurso::consultar($this -> recurso_id);
        }
		
	}
?>