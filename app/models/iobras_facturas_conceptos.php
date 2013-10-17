<?php
	class IobrasFacturasConceptos extends NuevoActiveRecord{
		public static function registrar($id,$cantidad,$conceptox,$precio,$importe){
			if(IobrasFacturasConceptos::existe("factura_id=".$id." AND concepto='".$conceptox."'")){
				$concepto = IobrasFacturasConceptos::consultar("factura_id=".$id." AND concepto='".$conceptox."'");
				$concepto -> cantidad += $cantidad;
			}
			else{
				$concepto = new IobrasFacturasConceptos();
				$concepto -> factura_id = $id;
				$concepto -> cantidad = $cantidad;
				$concepto -> concepto = $conceptox;	
			}
			
			$concepto -> precio_unitario = $precio;
			$concepto -> importe = $concepto -> cantidad * $concepto -> precio_unitario;
			
			$concepto -> save();
			
			return $concepto;
		}
	}
?>