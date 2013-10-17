<?php
    class OAvance extends IobrasAvancesobras{
        
    }
    
    class AvanceObra extends IobrasAvancesobras{
        
    }

	class IobrasAvancesobras extends ActiveRecord{
		public static function registrar($obra, $porcentaje, $descripcion,$fecha=0){
			$avance_obra = new IobrasAvancesobras();

    		$avance_obra -> obras_id = $obra;
    		$avance_obra -> porcentaje = $porcentaje;
    		$avance_obra -> descripcion = $descripcion;
    		
    		
    		if($fecha == 0){
    			$avance_obra -> fecha = date("Y-m-d");
    		}
    		else{
    			$avance_obra -> fecha = $fecha;
    		}
    		
    		$avance_obra -> fecha_in = date("Y-m-d");
    		$avance_obra -> fecha_at = date("Y-m-d");


    		$avance_obra -> save();
			
			//SI LA OBRA ES NUEVA CAMBIARLA A EN PROCESO
			$obra = Obra::consultar($obra);
			
			if($obra -> estado == "NUEVA"){
				$obra -> estado = "EN PROCESO";
				$obra -> save();
			}

    		return $avance_obra;
    	}
        
        public function modificar($porcentaje, $descripcion,$fecha){
			$this -> porcentaje = $porcentaje;
    		$this -> descripcion = $descripcion;
    		$this -> fecha = $fecha;

    		$this -> save();
    	}

    	public static function consultar($id){
    		$avance_obra = new IobrasAvancesobras();

    		$avance_obra = $avance_obra -> find($id);

    		return $avance_obra;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasAvancesobras();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
		
		public static function total($sql=""){
    		$objeto = new IobrasAvancesobras();

    		if($sql == ""){
    			$objeto = $objeto -> count();
    		}
            else{
	    		$objeto = $objeto -> count($sql);
    		}

    		return $objeto;
    	}

    	public static function ultimoAvance($id){
    		$avance_obra = new IobrasAvancesobras();

    		$ultimo = $avance_obra -> find_first("obras_id=".$id." ORDER BY id DESC");

    		return $ultimo;
    	}
        
        public static function penultimoAvance($id){
    		$avance_obra = new IobrasAvancesobras();

    		$ultimos = $avance_obra -> find("obras_id=".$id." ORDER BY id DESC");
            
            $i=0; if($ultimos) foreach($ultimos as $ultimo){
                if($i==1) return $ultimo;
                $i++;    
            }

    		return $ultimos;
    	}

    	public static function totalAvances($id){
    		$avance_obra = new IobrasAvancesobras();

    		$total = $avance_obra -> count("obras_id=".$id);

    		return $total;
    	}
        
        public function obra(){
    		return IobrasObras::consultar($this -> obras_id);
    	}
    	
    	public function fotos(){
    		return IobrasFotosobras::reporte("avance_id=".$this -> id);
    	}
        
        public function recursos(){
    		return IobrasProductosobras::reporte("avances_id=".$this -> id);
    	}
        
        public function ajustes(){
    		return IobrasAjustesobras::reporte("avances_id=".$this -> id);
    	}
        
        public function incrementarCosto($costo){
            $this -> ejecutado += $costo;
            $this -> save();
            
            $obra = Obra::consultar($this -> obras_id);
            $obra -> ejecutado += $costo;
            $obra -> save();    
        }
        
        public function decrementarCosto($costo){
            $this -> ejecutado -= $costo;
            $this -> save();
            
            $obra = Obra::consultar($this -> obras_id);
            $obra -> ejecutado -= $costo;
            $obra -> save();    
        } 
	}
?>