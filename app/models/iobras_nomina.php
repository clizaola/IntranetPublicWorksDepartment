<?php
    class Nomina extends IobrasNomina{
        
    }

	class IobrasNomina extends ActiveRecord{
        public static function registrar($obra, $inicio="", $fin="", $estado="KO"){
            $nomina = new IobrasNomina();
            
            $nomina -> obra_id = $obra;
            $nomina -> fecha_inicio = $inicio;
            $nomina -> fecha_fin = $fin;
            $nomina -> estado = $estado;
            
            $nomina -> save();
			
			//SI LA OBRA ES NUEVA CAMBIARLA A EN PROCESO
			$obra = Obra::consultar($obra);
			
			if($obra -> estado == "NUEVA"){
				$obra -> estado = "EN PROCESO";
				$obra -> save();
			}
            
            return $nomina;
        }
        
        public function modificar($obra, $inicio="", $fin="", $estado="KO"){
            $this -> obra_id = $obra;
            if($inicio) $this -> fecha_inicio = $inicio;
            if($fin) $this -> fecha_fin = $fin;
            $this -> estado = $estado;
            
            $this -> save();
        }
        
        public static function consultar($id){
    		$objeto = new IobrasNomina();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasNomina();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasNomina();

    		if($sql == ""){
    			$objeto = $objeto -> count();
    		}
            else{
	    		$objeto = $objeto -> count($sql);
    		}

    		return $objeto;
    	}
        
        public function eliminar(){
            $asistencia = $this -> asistencia();
            
            if($asistencia) foreach($asistencia as $a){
                $a -> delete();
            }
            
            $this -> delete();    
        }
        
        public function empleados(){
            $asistencia = $this -> asistencia();
            
            $empleados = array();
            $n=0;
            
            if($asistencia) foreach($asistencia as $a){
                $empleados[$n++] = $a -> empleado();
            }
            
            return $empleados;
        }
        
        public function asistencia(){
            return NominaAsistencia::reporte("nomina_id=".$this -> id,"id ASC");
        }
        
        public function importe(){
            $pagos = NominaAsistencia::reporte("nomina_id=".$this -> id,"id ASC");
            
            $importe = 0;
            
            if($pagos) foreach($pagos as $pago){
            	$importe += $pago -> importe;
            }
            
            $this -> total = $importe;
            $this -> save();
            
            return $this -> total;
        }
        
        public function obra(){
            return Obra::consultar($this -> obra_id);
        }
    }
?>