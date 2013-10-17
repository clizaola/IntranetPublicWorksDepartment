<?php
	class IobrasBeneficiarios extends NuevoActiveRecord{
		public static function registrar($obra_id,$nombre,$direccion,$telefono,$total=0){
			if(IobrasBeneficiarios::existe("obra_id=".$obra_id)){
				$beneficiario = IobrasBeneficiarios::consultar("obra_id=".$obra_id);
			}
			else{
				$beneficiario = new IobrasBeneficiarios();
				$beneficiario -> obra_id = $obra_id;
				$beneficiario -> nombre_representante = $nombre;
				$beneficiario -> domicilio_representante = $direccion;
				$beneficiario -> telefono_representante = $telefono;
				$beneficiario -> total = $total;
				
				$beneficiario -> save();
			}
			
			return $beneficiario;
		}
		
		public function agregarAportacion($fecha,$concepto,$monto){
			IobrasAportaciones::registrar($this -> id,$fecha,$concepto,$monto);
			
			//Actualizar el Acumulado de las Aportaciones
			
			$tmp = $this -> aportaciones();
			
			$acumulado = 0;
			
			if($tmp) foreach($tmp as $tmpx){
				$acumulado += $tmpx -> monto;
			}
			
			$this -> acumulado = $acumulado;
			$this -> save();
		}
		
		public function quitarAportacion($id){
			$aportacion = IobrasAportaciones::consultar($id);
			$aportacion -> delete();
			
			//Actualizar el Acumulado de las Aportaciones
			
			$tmp = $this -> aportaciones();
			
			$acumulado = 0;
			
			if($tmp) foreach($tmp as $tmpx){
				$acumulado += $tmpx -> monto;
			}
			
			$this -> acumulado = $acumulado;
			$this -> save();
		}
		
		public function aportaciones(){
			return IobrasAportaciones::reporte("beneficiarios_id=".$this -> id);
		}
		
		public function obra(){
			return IobrasObras::consultar($this -> obra_id);
		}
	}
?>