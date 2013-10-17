<?php
	class SolicitudesController extends ApplicationController {
		
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
            
            $this -> redirect("solicitudes/reporte/".$filtro);
        }
		
        public function reporte($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
        	
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
	    		case "no_registrada": $this -> msg = "error"; $this -> mensaje = "La Solicitud no pudo ser registrada correctamente."; $filtro = "todos"; break;
	    		case "eliminada": $this -> msg = "success"; $this -> mensaje = "La Solicitud fue eliminada correctamente."; $filtro = "todos"; break;	    		 
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = Solicitud::total(); $this -> registros = Solicitud::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "nuevas": $this -> nregistros = Solicitud::total("estado = 'NUEVA'"); $this -> registros = Solicitud::reporte("estado = 'NUEVA'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "proyecto": $this -> nregistros = Solicitud::total("estado = 'EN PROYECTO'"); $this -> registros = Solicitud::reporte("estado = 'EN PROYECTO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "obra": $this -> nregistros = Solicitud::total("estado = 'EN OBRA'"); $this -> registros = Solicitud::reporte("estado = 'EN OBRA'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = Solicitud::total("codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR solicitante_nombre LIKE '%".$filtro."%'".$sql); $this -> registros = Solicitud::reporte("codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR solicitante_nombre LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		public function reporteExcel($filtro="todos", $orden="id-ASC", $pagina=1){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			$this -> render("excel",null);
        	
 	        $filtro = str_replace("-"," ",$filtro);
            $orden = str_replace("-"," ",$orden);

            switch($filtro){
                case "todos": $this -> registros = Solicitud::reporte("id>0",$orden.", id ASC"); break;
                case "nuevas": $this -> registros = Solicitud::reporte("estado = 'NUEVA'",$orden.", id ASC"); break;
                case "proyecto": $this -> registros = Solicitud::reporte("estado = 'EN PROYECTO'",$orden.", id ASC"); break;
                case "obra": $this -> registros = Solicitud::reporte("estado = 'EN OBRA'",$orden.", id ASC"); break;
                default: $this -> registros = Solicitud::reporte("codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR solicitante_nombre LIKE '%".$filtro."%'".$sql,$orden.", id ASC"); break;
            }
		}
		
		public function registro(){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			$this -> render("solicitud");
        	
			$this -> solicitud = false;
			
			$this -> mensaje = "";
        }
        
        public function registrar(){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
        	
        	$this -> render(null,null);
        	
        	Load::lib("utilerias");
        	
        	$solicitud = Solicitud::registrar($this -> post("nombre"),$this -> post("codigo"),$this -> post("medio"),$this -> post("descripcion"),$this -> post("localizacion"),$this -> post("localidad"),$this -> post("tipo"),$this -> post("emisor"),$this -> post("receptor"),$this -> post("solicitante"),$this -> post("direccion"),$this -> post("telefono"),$this -> post("celular"),$this -> post("correo"));
        	
        	$solicitud -> agenda = formatoFechaDB($this -> post("limite"));
        	$solicitud -> comentarios = $this -> post("comentarios");
        	$solicitud -> save();
        	
        	$this -> redirect("solicitudes/consulta/".$solicitud -> id."/registrada");
        }
        
        public function consulta($id,$tmp=""){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
        	
        	$this -> render("solicitud");
        	
        	$this -> solicitud = Solicitud::consultar($id);
                
			if(!$this -> solicitud){
				$this -> redirect("solicitudes/reporte"); return;
			}
			
			$this -> mensaje = "";
            
            switch($tmp){
	    		case "modificada": $this -> mensaje = "La Solicitud fue modificada correctamente."; break;
	    		case "no_modificada": $this -> mensaje = "La Solicitud no pudo ser modificada."; break;
	    		case "registrada": $this -> mensaje = "La Solicitud fue registrada correctamente."; break;
	    	}
            
            $this -> id = $id;
        }
        
        public function modificar(){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
        	
        	$this -> render(null,null);
        	
        	Load::lib("utilerias");
        	
        	$solicitud = Solicitud::consultar($this -> post("id"));
        	
        	$solicitud -> modificar($this -> post("nombre"),$this -> post("codigo"),$this -> post("medio"),$this -> post("descripcion"),$this -> post("localizacion"),$this -> post("localidad"),$this -> post("tipo"),$this -> post("emisor"),$this -> post("receptor"),$this -> post("solicitante"),$this -> post("direccion"),$this -> post("telefono"),$this -> post("celular"),$this -> post("correo"));
        	
        	$solicitud -> agenda = formatoFechaDB($this -> post("limite"));
        	$solicitud -> comentarios = $this -> post("comentarios");
        	
        	$solicitud -> responsable_id = $this -> post("responsable");
        	
        	if($this -> post("estado")!="") $solicitud -> estado = $this -> post("estado");
        	
        	$solicitud -> save();
        	        	
        	$this -> redirect("solicitudes/consulta/".$solicitud -> id."/modificada");
        }
		
		public function eliminar($id){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			$this -> render(null,null);
			
			$solicitud = Solicitud::consultar($id);
			
			$archivos = $solicitud -> archivos();
			
			if($archivos) foreach($archivos as $archivo){
				$file = strtolower(APP_PATH."public/files/archivos_solicitudes/".$archivo -> archivo);
			
				@unlink($file);
				
				$archivo -> delete();
			}
			
			$solicitud -> delete();
			
			$this -> redirect("solicitudes/reporte/eliminada");
		}
		
		public function estado($id){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			$this -> render("estado",null);
			
			$this -> registro = Solicitud::consultar($id);
		}
		
		public function cambiarEstado(){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			$this -> render("cambio",null);
			
			$solicitud = Solicitud::consultar($this -> post("solicitud"));
			
			if($this -> post("estado")!="") $solicitud -> estado = $this -> post("estado");
			$solicitud -> save();
			
			$this -> registro = $solicitud;
		}
		
		public function adjuntar(){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			$this -> render("archivos",null);
			
			$this -> solicitud = Solicitud::consultar($this -> post("id"));
			
			$archivo_solicitud = SArchivo::registrar($this -> solicitud -> id,$this -> post("nombre"));
       		
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
                
                $file = strtolower($this -> solicitud -> codigo . "-" .$archivo_solicitud -> id.".".$ext);
                
				$archivo = strtolower(APP_PATH."public/files/archivos_solicitudes/".$file);

				$archivo_solicitud -> archivo = $file;

				move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo);
			}

			$archivo_solicitud -> save();
		}
		
		public function desadjuntar($id){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			$this -> render("archivos",null);
			
			$archivo_solicitud = SArchivo::consultar($id);
			$this -> solicitud = Solicitud::consultar($archivo_solicitud -> solicitud_id);
			
			$archivo = strtolower(APP_PATH."public/files/archivos_solicitudes/".$archivo_solicitud -> archivo);
			
			@unlink($archivo);
			
			$archivo_solicitud -> delete();
		}
		
		public function proyectar($id){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			$this -> render(null,null);
			
			$solicitud = Solicitud::consultar($id);
			
			if($solicitud){
				$this -> redirect("proyectos/registro/".$solicitud -> id); return;
			}
			else{
				$this -> redirect("solicitudes/reporte"); return;
			}
		}

		public function descargar($id){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			$this -> render(null,null);
			
			$solicitud = Solicitud::consultar($id);
			
			$archivos = SArchivo::reporte("solicitud_id=".$solicitud -> id);
			
			Load::lib("pclzip");
			
			$nombre = $solicitud -> codigo.".zip";
			
			$directorio = substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
			$url = $directorio."/".$nombre;
	
			if(file_exists($url)){
				unlink($url);
			}
			
			$zip = new PclZip($nombre);
			
			if($archivos) foreach($archivos as $archivo){
				$zip -> add($directorio."/files/archivos_solicitudes/".$archivo -> archivo,PCLZIP_OPT_REMOVE_ALL_PATH);
			}
			
			header ("Content-Disposition: attachment; filename=".$nombre."\n\n"); 
			header ("Content-Type: application/octet-stream");
			header ("Content-Length: ".filesize($url));
			readfile($url);
			
			unlink($url);
		}
	}
?>