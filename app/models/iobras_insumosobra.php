<?php
	class OInsumo extends IobrasInsumosobra{
	
	}

    class InsumoObra extends IobrasInsumosobra{
        
    }

	class IobrasInsumosobra extends ActiveRecord{
		public static function registrar($obra,$producto,$precio){
			$conexion = new IobrasInsumosobra();

            $conexion -> obras_id = $obra;
            $conexion -> productos_id = $producto;
            $conexion -> precio = $precio;

    		$conexion -> save();
			
			//SI LA OBRA ES NUEVA CAMBIARLA A EN PROCESO
			$obra = Obra::consultar($obra);
			
			if($obra -> estado == "NUEVA"){
				$obra -> estado = "EN PROCESO";
				$obra -> save();
			}

    		return $conexion;
    	}

    	public static function consultar($id){
    		$conexion = new IobrasInsumosobra();

    		$conexion = $conexion -> find($id);

    		return $conexion;
    	}

    	public static function buscar($sql="id>0"){
            $objeto = new IobrasInsumosobra();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find_first($sql);
        }
        
        public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasInsumosobra();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$conexion = new IobrasInsumosobra();

			if($sql == "")
    			$conexion = $conexion -> count();
    		else
    			$conexion = $conexion -> count($sql);

    		return $conexion;
    	}
        
        public function producto(){
            return Insumo::consultar($this -> productos_id);
        } 
        
        public static function conceptosPendientes($obra){
            $productos = Insumo::reporte();
            
            $conceptos = null;
            
            if($productos) foreach($productos as $producto){
                $x = InsumoObra::total("obras_id=".$obra." AND productos_id=".$producto -> id);
                
                if($x==0){
                    $conceptos[$producto -> id] = $producto;
                }     
            }
            
            return $conceptos;
        }
	}
?>