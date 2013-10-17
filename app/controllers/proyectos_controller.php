<?php
	class ProyectosController extends ApplicationController {
		
		public function buscar(){
            $this -> render(null,null);
            
            Load::lib("utilerias");
            
            $filtro = str_replace(" ","-",ajaxizar($this -> post("filtro")));
            $filtro = str_replace("á","a",$filtro);
            $filtro = str_replace("é","e",$filtro);
            $filtro = str_replace("í","i",$filtro);
            $filtro = str_replace("ó","o",$filtro);
            $filtro = str_replace("ú","u",$filtro);
            $filtro = str_replace("Á","A",$filtro);
            $filtro = str_replace("É","E",$filtro);
            $filtro = str_replace("Í","I",$filtro);
            $filtro = str_replace("Ó","O",$filtro);
            $filtro = str_replace("Ú","U",$filtro);
            $filtro = str_replace("ñ","n",$filtro);
            $filtro = str_replace("Ñ","N",$filtro);
            
            $this -> redirect("proyectos/reporte/".$filtro);
        }
		
        public function reporte($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        	
        	$this -> render("reporte","default");
        	
 	        //Inicializacion, siempre irá igual en todos los controladores
            $this -> mensaje = "";
            $filtro = str_replace("-"," ",$filtro);
            $orden = str_replace("-"," ",$orden);

            //Número de registros a mostrar por página.
            $pp = SAM_REGISTROS_REPORTE;

            //Si llegara una variable con un texto a buscar será marcada como filtro.
            if($this -> post("busqueda")!=""){ $filtro = $this -> post("busqueda"); }
            
            //Configuración de los mensajes que llegan como filtos y le indicamos a la vista el mensaje a mostrar
            
            switch($filtro){
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "El Proyecto no pudo ser registrado correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Proyecto fue eliminado correctamente."; $filtro = "todos"; break;	    		 
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
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
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = Proyecto::total(); $this -> registros = Proyecto::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "nuevos": $this -> nregistros = Proyecto::total("estado = 'NUEVO'"); $this -> registros = Proyecto::reporte("estado = 'NUEVO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "proceso": $this -> nregistros = Proyecto::total("estado = 'EN PROCESO'"); $this -> registros = Proyecto::reporte("estado = 'EN PROCESO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "obra": $this -> nregistros = Proyecto::total("estado = 'EN OBRA'"); $this -> registros = Proyecto::reporte("estado = 'EN OBRA'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = Proyecto::total("codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR objetivo LIKE '%".$filtro."%'".$sql); $this -> registros = Proyecto::reporte("codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR objetivo LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		public function reporteExcel($filtro="todos", $orden="id-ASC", $pagina=1){
			$this -> render("excel",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$filtro = str_replace("-"," ",$filtro);
            $orden = str_replace("-"," ",$orden);
            
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

            switch($filtro){
                case "todos": $this -> registros = Proyecto::reporte("id>0",$orden.", id ASC"); break;
                case "nuevos": $this -> registros = Proyecto::reporte("estado = 'NUEVA'",$orden.", id ASC"); break;
                case "obra": $this -> registros = Proyecto::reporte("estado = 'EN OBRA'",$orden.", id ASC"); break;
                default: $this -> registros = Proyecto::reporte("codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR objetivo LIKE '%".$filtro."%'".$sql,$orden.", id ASC"); break;
            }
		}
		
		public function registro($solicitud=0){
			Load::lib("aclx"); 
			if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){
        		if(Session::get("usuario_id")>0){ 
        			$this -> redirect(""); return;
        		} else { 
        			$this -> render(null,null);
        			$this -> redirect(""); return;
        		} 
        	}
			
			if($solicitud > 0){
				$this -> proyectando = Solicitud::consultar($solicitud);
			}
        	
			$this -> proyecto = false;
			
			$this -> mensaje = "";
        }
        
        public function registrar(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        	
        	Load::lib("utilerias");
        	
        	$alcance = "";

			if($this -> post("l")=="on") $alcance .= "L"; else $alcance .= "X";
			if($this -> post("mu")=="on") $alcance .= "M"; else $alcance .= "X";
			if($this -> post("r")=="on") $alcance .= "R"; else $alcance .= "X";
			
			$pfederal = quitarFormatoDinero($this -> post("f"));
        	$pestatal = quitarFormatoDinero($this -> post("e"));
        	$pmunicipal = quitarFormatoDinero($this -> post("m"));
        	$pbeneficiarios = quitarFormatoDinero($this -> post("b"));
        	$costo = quitarFormatoDinero($this -> post("costo"));
            
            $total = $pfederal+$pestatal+$pmunicipal+$pbeneficiarios;
            
            $presupuesto = formatoNumero(round($pfederal/$total*100),3)."|";
            $presupuesto .= formatoNumero(round($pestatal/$total*100),3)."|";
            $presupuesto .= formatoNumero(round($pmunicipal/$total*100),3)."|";
            $presupuesto .= formatoNumero(round($pbeneficiarios/$total*100),3);
			
			$proyecto = Proyecto::registrar($this -> post("solicitud"),$this -> post("codigo"),$this -> post("nombre"),$alcance,$this -> post("localidad"),$this -> post("descripcion"),$this -> post("objetivo"),$this -> post("tipo"),$this -> post("supervisor"),$costo);
        	
        	if(!$proyecto){
        		$this -> redirect("proyectos/reporte/no_registrado"); return;
        	}
        	
        	$proyecto -> georeferencia = substr($this -> post("mapa"),1,strlen($this -> post("mapa"))-2);
        	$proyecto -> presupuesto = $presupuesto == "" ? "000|000|000|000" : $presupuesto;
        	$proyecto -> pfederal = $pfederal;
        	$proyecto -> pestatal = $pestatal;
        	$proyecto -> pmunicipal = $pmunicipal;
        	$proyecto -> pbeneficiarios = $pbeneficiarios;
        	
        	$cabildo = Cabildo::consultar($this -> post("cabildo"));
        	
        	$proyecto -> cabildo_id = $cabildo ? $cabildo -> id : 0;
        	$proyecto -> acta_cabildo = $cabildo ? $cabildo -> nombre : "";
			
			$proyecto -> avance = $this -> post("avance");
        	
        	$proyecto -> save();
			
			$proyecto -> generarMapa();
        	
        	if($this -> post("solicitud")){
                $solicitud = Solicitud::consultar($this -> post("solicitud"));
    			if($solicitud)
    			{
    				$solicitud -> estado = "EN PROYECTO";
    				$solicitud -> comentarios = $solicitud -> comentarios == "" ? "LA SOLICITUD HA SIDO UTILIZADA PARA INICIAR EL PROYECTO: ".$proyecto -> nombre : $solicitud -> comentarios . "<br /><br />LA SOLICITUD HA SIDO UTILIZADA PARA INICIAR EL PROYECTO: ".$proyecto -> nombre;
    				$solicitud -> save();
    			}
            }
        	
        	$this -> redirect("proyectos/consulta/".$proyecto -> id."/registrada");
        }
        
        public function consulta($id,$tmp=""){
        	$this -> render("proyecto");
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        	
        	$this -> proyecto = Proyecto::consultar($id);
                
			if(!$this -> proyecto){
				$this -> redirect("proyectos/reporte"); return;
			}
			
			$this -> mensaje = "";
            
            switch($tmp){
	    		case "modificado": $this -> mensaje = "El Proyecto fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Proyecto no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Proyecto fue registrado correctamente."; break;
	    	}
            
            $this -> id = $id;
        }
        
        public function modificar(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        	
        	Load::lib("utilerias");
        	
        	$proyecto = Proyecto::consultar($this -> post("id"));
        	
        	$alcance = "";
        	
        	if($this -> post("l")=="on") $alcance .= "L"; else $alcance .= "X";
			if($this -> post("mu")=="on") $alcance .= "M"; else $alcance .= "X";
			if($this -> post("r")=="on") $alcance .= "R"; else $alcance .= "X";
        	
        	$pfederal = quitarFormatoDinero($this -> post("f"));
        	$pestatal = quitarFormatoDinero($this -> post("e"));
        	$pmunicipal = quitarFormatoDinero($this -> post("m"));
        	$pbeneficiarios = quitarFormatoDinero($this -> post("b"));
        	$costo = quitarFormatoDinero($this -> post("costo"));
        	
            $total = $pfederal+$pestatal+$pmunicipal+$pbeneficiarios;
            
            $presupuesto = formatoNumero(round($pfederal/$total*100),3)."|";
            $presupuesto .= formatoNumero(round($pestatal/$total*100),3)."|";
            $presupuesto .= formatoNumero(round($pmunicipal/$total*100),3)."|";
            $presupuesto .= formatoNumero(round($pbeneficiarios/$total*100),3);
        	
        	$proyecto -> modificar($this -> post("codigo"),$this -> post("nombre"),$alcance,$this -> post("localidad"),$this -> post("descripcion"),$this -> post("objetivo"),$this -> post("tipo"),$this -> post("supervisor"),$costo);
        	
        	$proyecto -> georeferencia = substr($this -> post("mapa"),1,strlen($this -> post("mapa"))-2);
        	$proyecto -> presupuesto = $presupuesto == "" ? "000|000|000|000" : $presupuesto;
        	$proyecto -> pfederal = $pfederal;
        	$proyecto -> pestatal = $pestatal;
        	$proyecto -> pmunicipal = $pmunicipal;
        	$proyecto -> pbeneficiarios = $pbeneficiarios;
        	
        	$cabildo = Cabildo::consultar($this -> post("cabildo"));
        	
        	$proyecto -> cabildo_id = $cabildo ? $cabildo -> id : 0;
        	$proyecto -> acta_cabildo = $cabildo ? $cabildo -> nombre : "";
        	
        	if($this -> post("solicitud")){
        		if($this -> post("solicitud") != $proyecto -> solicitudes_id){
	                $solicitud = Solicitud::consultar($this -> post("solicitud"));
	    			
	                if($solicitud)
	    			{
	    				$solicitud -> estado = "EN PROYECTO";
	    				$solicitud -> comentarios = $solicitud -> comentarios == "" ? "LA SOLICITUD HA SIDO UTILIZADA PARA INICIAR EL PROYECTO: ".$proyecto -> nombre : $solicitud -> comentarios . "<br /><br />LA SOLICITUD HA SIDO UTILIZADA PARA INICIAR EL PROYECTO: ".$proyecto -> nombre;
	    				$solicitud -> save();
	    			}
	    			
	    			$proyecto -> solicitudes_id = $this -> post("solicitud");
        		}
            }
			
			$proyecto -> avance = $this -> post("avance");
        	
        	$proyecto -> save();
			
			$proyecto -> generarMapa();
        	        	
        	$this -> redirect("proyectos/consulta/".$proyecto -> id."/modificado");
        }
		
		public function eliminar($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$proyecto = Proyecto::consultar($id);
			
			$archivos = $proyecto -> archivos();
			
			if($archivos) foreach($archivos as $archivo){
				$file = strtolower(APP_PATH."public/files/archivos_proyectos/".$archivo -> archivo);
			
				@unlink($file);
				
				$archivo -> delete();
			}
			
			$programas = $proyecto -> programas();
			
			if($programas) foreach($programas as $programa){
				$programa -> delete();
			}
			
			$contratistas = $proyecto -> contratistas();
			
			if($contratistas) foreach($contratistas as $contratista){
				$contratista -> delete();
			}
			
			$avances = $proyecto -> avances();
			
			if($avances) foreach($avances as $avance){
				$avance -> delete();
			}
			
			$tareas = $proyecto -> tareas();
			
			if($tareas) foreach($tareas as $tarea){
				$tarea -> delete();
			}
			
			$proyecto -> delete();
			
			$this -> redirect("proyectos/reporte/eliminado");
		}
		
		public function estado($id){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("estado",null);
			
			$this -> registro = Proyecto::consultar($id);
		}
		
		public function cambiarEstado(){
			$this -> render("cambio",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$proyecto = Proyecto::consultar($this -> post("proyecto"));
			
			$proyecto -> estado = $this -> post("estado");
			$proyecto -> save();
			
			$this -> registro = $proyecto;
		}
		
		public function archivos($proyecto){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("archivos",null);
			
			$this -> proyecto = Proyecto::consultar($proyecto);
		}
		
		public function adjuntar(){
			$this -> render("archivos",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> proyecto = Proyecto::consultar($this -> post("id"));
			
			$archivo_proyecto = PArchivo::registrar($this -> post("id"),$this -> post("nombre"));
       		
       		if($_FILES["archivo"]["name"]!=""){
       			$tmp = $_FILES["archivo"]["name"];
                
	            $ext = "";
	        	
	        	for($i=0;$i<strlen($tmp);$i++){
	        		if($tmp[$i]=="."){
	        			$ext = "";
	        		}
	        		else{
	        			$ext .= $tmp[$i];
	        		}
	        	}
                
				$file = strtolower($this -> proyecto -> codigo . "-" .$archivo_proyecto -> id.".".$ext);
                
				$archivo = strtolower(APP_PATH."public/files/archivos_proyectos/".$file);

				$archivo_proyecto -> archivo = $file;

				move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo);
			}

			$archivo_proyecto -> save();
		}
		
		public function desadjuntar($id){
			$this -> render("archivos",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$archivo_proyecto = PArchivo::consultar($id);
			$this -> proyecto = Proyecto::consultar($archivo_proyecto -> proyectos_id);
			
			$archivo = strtolower(APP_PATH."public/files/archivos_proyectos/".$archivo_proyecto -> archivo);
			
			@unlink($archivo);
			
			$archivo_proyecto -> delete();
		}

		public function programas($proyecto){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("programas",null);
			
			$this -> proyecto = Proyecto::consultar($proyecto);
		}
		
		public function programar(){
			$this -> render("programas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> proyecto = Proyecto::consultar($this -> post("id"));
			
			$programa = PPrograma::registrar($this -> post("id"),$this -> post("programa"));
		}
		
		public function desprogramar($id){
			$this -> render("programas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$programa = PPrograma::consultar($id);
			$this -> proyecto = Proyecto::consultar($programa -> proyectos_id);
			
			$programa -> delete();
		}
		
		public function contratistas($proyecto){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("contratistas",null);
			
			$this -> proyecto = Proyecto::consultar($proyecto);
		}
		
		public function contratar(){
			$this -> render("contratistas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> proyecto = Proyecto::consultar($this -> post("id"));
			
			$contratista = PContratista::registrar($this -> post("id"),$this -> post("contratista"));
		}
		
		public function descontratar($id){
			$this -> render("contratistas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$contratista = PContratista::consultar($id);
			$this -> proyecto = Proyecto::consultar($contratista -> proyectos_id);
			
			$contratista -> delete();
		}
		
		public function avances($proyecto){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("avances",null);
			
			$this -> proyecto = Proyecto::consultar($proyecto);
		}
		
		public function avanzar(){
			$this -> render("avances",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> proyecto = Proyecto::consultar($this -> post("id"));
			
			$contratista = PAvance::registrar($this -> post("id"),$this -> post("porcentaje"),$this -> post("descripcion"),formatoFechaDB($this -> post("fecha")));
			
			$this -> proyecto -> avance = $this -> proyecto -> porcentaje();
			$this -> proyecto -> save();
		}
		
		public function desavanzar($id){
			$this -> render("avances",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$avance = PAvance::consultar($id);
			$this -> proyecto = Proyecto::consultar($avance -> proyectos_id);
			
			$avance -> delete();
			
			$this -> proyecto -> avance = $this -> proyecto -> porcentaje();
			$this -> proyecto -> save();
		}
		
		public function tareas($proyecto){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("tareas",null);
			
			$this -> proyecto = Proyecto::consultar($proyecto);
		}
		
		public function tareasTerminadas($proyecto){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("tareasTerminadas",null);
			
			$this -> proyecto = Proyecto::consultar($proyecto);
		}
		
		public function asignarTarea(){
			$this -> render('tareas',null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> proyecto = Proyecto::consultar($this -> post("id"));
			
			$empleado = Empleado::buscar("nombre LIKE '".$this -> post("empleado")."'");
			
			$correo = $empleado -> correo ? $empleado -> correo : "opydu1012@gmail.com";
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
			$headers .= "From: Departamento de Obras Públicas <opydu1012@gmail.com>\r\n";
			
			$tarea = PTarea::registrar($this -> post("id"),$empleado -> id,$this -> post("nombre"),$this -> post("descripcion"),formatoFechaDB($this -> post("creacion")),formatoFechaDB($this -> post("limite")));
			
			mail($correo,"Tienes una nueva tarea por realizar.",$empleado -> nombre."<br /><br />Tienes una nueva tarea por realizar, a continuación se muestran los detalles de la misma:<br /><br /><b>Proyecto: </b>".$this -> proyecto -> nombre."<br /><b>Responsable del Proyecto: </b>".$this -> proyecto -> supervisor() -> nombre."<br /><b>Nombre de la Tarea: </b>".$tarea -> nombre."<br /><b>Descripción: </b>".$tarea -> descripcion."<br /><b>Fecha de Asignación: </b>".formatoFecha($tarea -> creacion)."<br /><b>Fecha Límite: </b>".formatoFecha($tarea -> limite)."<br /><br />Este mensaje fue enviado de manera automática desde el sistema de obras públicas de Ameca Jalisco.",$headers);
		}
		
		public function consultarTarea($id){
			$this -> render("tarea",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> tarea = PTarea::consultar($id);
		}
		
		public function cancelarTarea($id){
			$this -> render("tareas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$tarea = PTarea::consultar($id);
			
			$this -> proyecto = Proyecto::consultar($tarea -> proyectos_id);
			
			$tarea -> delete();
		}
		
		public function terminarTarea($id){
			$this -> render("tareasTerminadas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$tarea = PTarea::consultar($id);
			
			$this -> proyecto = Proyecto::consultar($tarea -> proyectos_id);
			
			$tarea -> estado = "OK";
			$tarea -> save();
		}
		
		public function proyectarObra($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$proyecto = Proyecto::consultar($id);
			
			if($proyecto){
				$this -> redirect("obras/registro/".$proyecto -> id); return;
			}
			else{
				$this -> redirect("proyectos/reporte"); return;
			}
		}
		
		public function descargar($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$proyecto = Proyecto::consultar($id);
			
			$archivos = $proyecto -> archivos();
			
			Load::lib("pclzip");
			
			$nombre = $proyecto -> codigo.".zip";
			
			$directorio = substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
			$url = $directorio."/".$nombre;
	
			if(file_exists($url)){
				unlink($url);
			}
			
			$zip = new PclZip($nombre);
			
			if($archivos) foreach($archivos as $archivo){
				$zip -> add($directorio."/files/archivos_proyectos/".$archivo -> archivo,PCLZIP_OPT_REMOVE_ALL_PATH);
			}
			
			header ("Content-Disposition: attachment; filename=".$nombre."\n\n"); 
			header ("Content-Type: application/octet-stream");
			header ("Content-Length: ".filesize($url));
			readfile($url);
			
			unlink($url);
		}
	}
?>