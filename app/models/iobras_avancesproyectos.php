<?php
    class AvanceProyecto extends IobrasAvancesproyectos{
        
    }
    
    class PAvance extends IobrasAvancesproyectos{
        
    }

	class IobrasAvancesproyectos extends ActiveRecord{
		public static function registrar($proyecto, $porcentaje, $descripcion, $fecha=0){
			$avance_proyecto = new IobrasAvancesproyectos();

    		$avance_proyecto -> proyectos_id = $proyecto;
    		$avance_proyecto -> porcentaje = $porcentaje;
    		$avance_proyecto -> descripcion = $descripcion;
    		
    		if($fecha == 0){
    			$avance_proyecto -> fecha = date("Y-m-d");
    		}
    		else{
    			$avance_proyecto -> fecha = $fecha;
    		}
    		
    		$avance_proyecto -> fecha_in = date("Y-m-d");
    		$avance_proyecto -> fecha_at = date("Y-m-d");
    		
    		$avance_proyecto -> save();
			
			//SI EL PROYECTO ES NUEVO CAMBIARLO A EN PROCESO
			$proyecto = Proyecto::consultar($proyecto);
			
			if($proyecto -> estado == "NUEVO"){
				$proyecto -> estado = "EN PROCESO";
				$proyecto -> save();
			}

    		return $avance_proyecto;
    	}
        
        public function modificar($porcentaje, $descripcion,$fecha){
			$this -> porcentaje = $porcentaje;
    		$this -> descripcion = $descripcion;
    		$this -> fecha = $fecha;

    		$this -> save();
    	}

    	public static function consultar($id){
    		$avance_proyecto = new IobrasAvancesproyectos();

    		$avance_proyecto = $avance_proyecto -> find($id);

    		return $avance_proyecto;
    	}

    	public static function reporte($sql=""){
    		$avances_proyecto = new IobrasAvancesproyectos();

			if($sql == "")
    			$avances_proyecto = $avances_proyecto -> find();
    		else
    			$avances_proyecto = $avances_proyecto -> find($sql);

    		return $avances_proyecto;
    	}

    	public static function ultimoAvance($id){
    		$avances_proyecto = new IobrasAvancesproyectos();

    		$ultimo = $avances_proyecto -> find_first("proyectos_id=".$id." ORDER BY id DESC");

    		return $ultimo;
    	}

    	public static function totalAvances($id){
    		$avances_proyecto = new IobrasAvancesproyectos();

    		$total = $avances_proyecto -> count("proyectos_id=".$id);

    		return $total;
    	}
        
        public static function penultimoAvance($id){
    		$avances_proyecto = new IobrasAvancesproyectos();

    		$ultimos = $avances_proyecto -> find("proyectos_id=".$id." ORDER BY id DESC");
            
            $i=0; if($ultimos) foreach($ultimos as $ultimo){
                if($i==1) return $ultimo;
                $i++;    
            }

    		return $ultimos;
    	}
        
        public function fotos(){
    		return IobrasFotosproyectos::reporte("avance_id=".$this -> id);
    	}
	}
?>