<?php
	class IobrasAportaciones extends NuevoActiveRecord{
		public static function registrar($beneficiarios_id,$fecha,$concepto,$monto){
			$aportacion = new IobrasAportaciones();
			
			Load::lib("formato");
			
			$aportacion -> beneficiarios_id = $beneficiarios_id;
			$aportacion -> fecha = Formato::fechaDB($fecha);
			$aportacion -> concepto = $concepto;
			$aportacion -> monto = $monto;
			
			$aportacion -> save();
			
			return $aportacion;
		}
		
		public function beneficiarios(){
			return IobrasBeneficiarios::consultar($this -> beneficiarios_id);
		}
	}
?>