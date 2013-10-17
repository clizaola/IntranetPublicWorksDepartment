<?php
    class TareaProyecto extends IobrasTareasproyectos{
        
    }
    
    class PTarea extends IobrasTareasproyectos{
        
    }

	class IobrasTareasproyectos extends ActiveRecord{
        public static function registrar($proyecto, $empleado, $nombre, $descripcion, $creacion, $limite){
			$tarea = new IobrasTareasproyectos();

    		$tarea -> proyectos_id = $proyecto;
            $tarea -> empleado_id = $empleado;
            $tarea -> nombre = $nombre;
            $tarea -> descripcion = $descripcion;
            $tarea -> creacion = $creacion;
            $tarea -> limite = $limite;

    		$tarea -> save();
			
			//SI EL PROYECTO ES NUEVO CAMBIARLO A EN PROCESO
			$proyecto = Proyecto::consultar($proyecto);
			
			if($proyecto -> estado == "NUEVO"){
				$proyecto -> estado = "EN PROCESO";
				$proyecto -> save();
			}

    		return $tarea;
    	}

    	public static function consultar($id){
    		$tarea = new IobrasTareasproyectos();

    		$tarea = $tarea -> find($id);

    		return $tarea;
    	}
		
		public static function buscar($id){
			$objeto = new IobrasTareasproyectos();
			
			if($objeto -> count($id)==0){
				return false;
			}
			
			return $objeto -> find_first($id);
		}

    	public static function reporte($sql=""){
    		$tarea = new IobrasTareasproyectos();

			if($sql == "")
    			$tarea = $tarea -> find();
    		else
    			$tarea = $tarea -> find($sql);

    		return $tarea;
    	}
    	
    	public static function total($sql=""){
    		$tarea = new IobrasTareasproyectos();

			if($sql == "")
    			$tarea = $tarea -> count();
    		else
    			$tarea = $tarea -> count($sql);

    		return $tarea;
    	}
    	
    	public function empleado(){
    		return Empleado::consultar($this -> empleado_id);
    	}
    	
    	public function proyecto(){
    		return Proyecto::consultar($this -> proyectos_id);
    	}
	}
?>