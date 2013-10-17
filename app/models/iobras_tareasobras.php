<?php
    class OTarea extends IobrasTareasobras{
        
    }

	class TareaObra extends IobrasTareasobras{
        
    }

	class IobrasTareasobras extends ActiveRecord{
		public static function registrar($obra, $empleado, $nombre, $descripcion, $creacion, $limite){
			$tarea = new IobrasTareasobras();

    		$tarea -> obras_id = $obra;
            $tarea -> empleado_id = $empleado;
            $tarea -> nombre = $nombre;
            $tarea -> descripcion = $descripcion;
            $tarea -> creacion = $creacion;
            $tarea -> limite = $limite;

    		$tarea -> save();
			
			//SI LA OBRA ES NUEVA CAMBIARLA A EN PROCESO
			$obra = Obra::consultar($obra);
			
			if($obra -> estado == "NUEVA"){
				$obra -> estado = "EN PROCESO";
				$obra -> save();
			}

    		return $tarea;
    	}

    	public static function consultar($id){
    		$tarea = new IobrasTareasobras();

    		$tarea = $tarea -> find($id);

    		return $tarea;
    	}
		
		public static function buscar($id){
			$objeto = new IobrasTareasobras();
			
			if($objeto -> count($id)==0){
				return false;
			}
			
			return $objeto -> find_first($id);
		}
		
    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasTareasobras();
            
            $sql .= " ORDER BY ".$orden;
 
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }		
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$tarea = new IobrasTareasobras();

			if($sql == "")
    			$tarea = $tarea -> count();
    		else
    			$tarea = $tarea -> count($sql);

    		return $tarea;
    	}
        
        public function empleado(){
    		return Empleado::consultar($this -> empleado_id);
    	}
    	
    	public function obra(){
    		return Obra::consultar($this -> obras_id);
    	}
	}
?>