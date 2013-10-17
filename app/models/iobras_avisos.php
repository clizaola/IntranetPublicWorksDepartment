<?php
	class IobrasAvisos extends NuevoActiveRecord{
		public static function registrar($aviso,$fecha_limite,$creado_usuario_id,$dirigido_usuario_id){
			$objeto = new IobrasAvisos();			
			//Load::lib("formato");
			$objeto -> aviso = $aviso;
			$objeto -> fecha_limite = $fecha_limite;
			$objeto -> creado_usuario_id = $creado_usuario_id;
			$objeto -> dirigido_usuario_id = $dirigido_usuario_id;
			$objeto -> fecha_at = $objeto -> fecha_in = date('Y-m-d H:i:s');  
			$objeto -> save();
			
			return $objeto;
		}
	}
?>