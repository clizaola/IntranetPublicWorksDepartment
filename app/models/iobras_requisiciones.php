<?php
	
	class IobrasRequisiciones extends NuevoActiveRecord{
		public static function registrar($obra, $fecha, $concepto, $supervisor){
			$requisicion = new IobrasRequisiciones();
			
			Load::lib("formato");

            $requisicion -> obras_id = $obra;
			$requisicion -> fecha = Formato::fechaDB($fecha);
			$requisicion -> concepto = $concepto;
			$requisicion -> supervisor_id = $supervisor;
			$requisicion -> estado = "NUEVA";

    		$requisicion -> save();
			
			//SI LA OBRA ES NUEVA CAMBIARLA A EN PROCESO
			$obra = Obra::consultar($obra);
			
			if($obra -> estado == "NUEVA"){
				$obra -> estado = "EN PROCESO";
				$obra -> save();
			}

    		return $requisicion;
    	}
		
		public function obra(){
			return IobrasObras::consultar($this -> obras_id);
		}
		
		public function conceptos(){
			return IobrasConceptorequisicion::reporte("requisicion_id=".$this -> id);
		}
		
		public function facturas(){
			$tmp = IobrasFacturarequisicion::reporte("requisicion_id=".$this -> id);
			
			$facturas = array();
			
			if($tmp) foreach($tmp as $tx){
				$facturas[$tx -> factura_id] = IobrasFacturas::consultar($tx -> factura_id);
			}
			
			return $facturas;
		}
		
		public function supervisor(){
			return Empleado::consultar($this -> supervisor_id);
		}
		
		public function elaboro(){
			return Empleado::consultar($this -> elaboro_id);
		}
		
		public function autorizo(){
			return Empleado::consultar($this -> autorizo_id);
		}		
	}
?>