<?php	
	class EscritorioController extends ApplicationController {
		public function index(){			
			$empleado_id = Usuario::consultar(Session::get("usuario_id")) -> empleado_id;
			
			Load::lib('formato');
			$cont = 0;
			$valor = time() - (60 * 60 * 24 * 1);
			$fecha = Formato::fechaDB($valor);
			$this -> avisos = IobrasAvisos::reporte("id > 0 and fecha_limite >= '".date("Y-m-d")."' and (dirigido_usuario_id = 0 or dirigido_usuario_id = ".Session::get("usuario_id").")",'fecha_limite ASC',0,10);
			$this -> Ptareas = PTarea::reporte("id > 0 and estado = 'KO' and empleado_id = ".$empleado_id,'limite ASC',0,10);
			$this -> Otareas = OTarea::reporte("id > 0 and estado = 'KO' and empleado_id = ".$empleado_id,'limite ASC',0,10);			
			$this -> solicitudes = Solicitud::reporte("id > 0 and fecha_in < '".$fecha."' ",'fecha_in ASC',0,10);
			$this -> proyectos = Proyecto::reporte("id > 0 and fecha_in < '".$fecha."' ",'fecha_in ASC',0,10);
			$this -> obras = Obra::reporte("id > 0 and fecha_in < '".$fecha."' ",'fecha_in ASC',0,10);
		}
		
		public function busquedaGeneral($busqueda = "", $filtro = "obras"){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			if($this -> has_post("busqueda")) $busqueda = $this -> post("busqueda");
			
			$busqueda = str_replace("-"," ",$busqueda);
			
			if($filtro == "obras"){
				$this -> sql = EscritorioController::sqlObras($busqueda);
				$sql_obras = EscritorioController::sqlObras($busqueda);
				$this -> nobras = Obra::total($sql_obras);
				$this -> obras = Obra::reporte($sql_obras);
			}
			
			if($filtro == "proyectos"){
				$this -> sql = EscritorioController::sqlProyectos($busqueda);
				$sql_proyectos = EscritorioController::sqlProyectos($busqueda);
				$this -> nproyectos = Proyecto::total($sql_proyectos);
				$this -> proyectos = Proyecto::reporte($sql_proyectos);
			}
			
			if($filtro == "solicitudes"){
				$this -> sql = EscritorioController::sqlSolicitudes($busqueda);
				$sql_solicitudes = EscritorioController::sqlSolicitudes($busqueda);
				$this -> nsolicitudes = Solicitud::total($sql_solicitudes);
				$this -> solicitudes = Solicitud::reporte($sql_solicitudes);
			}
			
			$this -> filtro = $filtro;
			$this -> busqueda = $busqueda;
		}
		
		public static function sqlObras($filtro){
			$sql = "";
            
            $localidades = Localidad::reporte("nombre LIKE '%".$filtro."%'");
            $sql_localidades = "(localidades_id = 0";
                
            if($localidades) foreach($localidades as $localidad){
            	$sql_localidades .= " OR localidades_id=".$localidad -> id;
            }
                
            $sql_localidades .= ")";
                
            $tipos = TipoObra::reporte("nombre LIKE '%".$filtro."%'");
            $sql_tipos = "(tipoobra_id = 0";
                
            if($tipos) foreach($tipos as $tipo){
            	$sql_tipos .= " OR tipoobra_id=".$tipo -> id;
            }
                
            $sql_tipos .= ")";
                
            $empleados = Empleado::reporte("nombre LIKE '%".$filtro."%'");
            $sql_empleados = "(supervisores_id = 0";
                
            if($empleados) foreach($empleados as $empleado){
            	$sql_empleados .= " OR supervisores_id=".$empleado -> id;
            }
                
            $sql_empleados .= ")";
            
            $sql = " OR ".$sql_localidades." OR ".$sql_tipos." OR ".$sql_empleados;
			
			return "codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR objetivo LIKE '%".$filtro."%'".$sql;
		}

		public static function sqlProyectos($filtro){
			$sql = "";
            
            $localidades = Localidad::reporte("nombre LIKE '%".$filtro."%'");
            $sql_localidades = "(localidades_id = 0";
                
            if($localidades) foreach($localidades as $localidad){
            	$sql_localidades .= " OR localidades_id=".$localidad -> id;
            }
                
            $sql_localidades .= ")";
                
            $tipos = TipoObra::reporte("nombre LIKE '%".$filtro."%'");
            $sql_tipos = "(tipoobra_id = 0";
                
            if($tipos) foreach($tipos as $tipo){
            	$sql_tipos .= " OR tipoobra_id=".$tipo -> id;
            }
                
            $sql_tipos .= ")";
                
            $empleados = Empleado::reporte("nombre LIKE '%".$filtro."%'");
            $sql_empleados = "(supervisores_id = 0";
                
            if($empleados) foreach($empleados as $empleado){
            	$sql_empleados .= " OR supervisores_id=".$empleado -> id;
            }
                
            $sql_empleados .= ")";
            
            $sql = " OR ".$sql_localidades." OR ".$sql_tipos." OR ".$sql_empleados;
			
			return "codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR objetivo LIKE '%".$filtro."%'".$sql;
		}

		public static function sqlSolicitudes($filtro){
			return "codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR solicitante_nombre LIKE '%".$filtro."%'";
		}
	}
?>