<?php
	class SeguridadController extends ApplicationController {

		public $template = "default";
		
		function index($w='',$h=''){
			$this -> set_response("view");			
			$this -> width = $w;
			$this -> height = $h;
			
			Session::set('usuario_id','');
			Session::set('rol_id','');
		}	
	
		function login(){
			$this -> render(null,null);
			
			Load::lib("aclx");
			
			if(Acl::validarUsuario($this -> post("usuario"),$this -> post("password"))){
				$usuario = Acl::obtenerUsuario(Session::get("usuario_id"));
				
				$usuario -> ultimo_acceso = date("Y-m-d H:i:s");
                $usuario -> accesos++;
                
                $usuario -> save();                
                //Debera cambiar al momento de definir cual será la URL de inicio
                $this -> redirect("escritorio/index");
			}
			else{
				Session::set('mensajeE','Tu usuario y/o contraseña no son correctos vuelve a intentar');
				$this -> redirect("");
			}
			
			Session::set('ancho',$this -> post('prueba'));
		}
		
		function logout(){
			$this -> render(null,null);
			
			Session::set("usuario_id","");
			Session::set("rol_id","");
			
			$this -> redirect("");
		}
		
		/* *** ADMINISTRACION DE RECURSOS *** */
		
		function acl(){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> modulos = SeguridadModulo::reporte("app_id=".Acl::aplicacion());
			
			$modulo = Session::get("modulo");
			
			$modulo = $modulo ? $modulo : 0;
			
			$this -> recursos = SeguridadRecurso::reporte("modulo_id=".$modulo);
		}
		
		function acciones($modulo=0){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("acciones",null);
			
			unset($this -> recursos);
			
			if($modulo>0){
				Session::set("modulo",$modulo);
			}
			else{
				Session::set("modulo",$this -> post("modulo"));
			}
			
			$this -> recursos = SeguridadRecurso::reporte("modulo_id=".Session::get("modulo"));
		}
		
		function registrarModulo(){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$modulo = Modulo::registrar($this -> post("nombre"));
			
			if($modulo) Session::set("modulo",$modulo -> id);
			
			$this -> redirect("seguridad/acl");
		}
		
		function registrarRecurso(){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$recurso = Recurso::registrar($this -> post("nombre"), $this -> post("modulo"));
			
			if($recurso){ Session::set("modulo",$recurso -> modulo_id); Session::set("recurso",$recurso -> id); }
			
			$this -> redirect("seguridad/acl");
		}
				
		function registrarAccion(){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$accion = Accion::registrar($this -> post("recurso"), $this -> post("controlador"), $this -> post("accion"));
			
			$this -> redirect("seguridad/acciones/".$accion -> recurso() -> modulo_id);
		}
		
		/* *** ADMINISTRACION DE ROLES *** */
		
		public function buscarRol(){
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
            
            $this -> redirect("seguridad/roles/".$filtro);
        }
        
        public function roles($filtro="todos", $orden="id-ASC", $pagina=1){
 	        Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($this -> is_ajax()){
                $this -> render("roles",null);
            }

             //Inicializacion, siempre irá igual en todos los controladores
            $this -> mensaje = "";
            $filtro = str_replace("-"," ",$filtro);
            $orden = str_replace("-"," ",$orden);

            //Número de registros a mostrar por página.
            $pp = 10;

            //Si llegara una variable con un texto a buscar será marcada como filtro.
            if($this -> post("busqueda")!=""){ $filtro = $this -> post("busqueda"); }
            
            //Configuración de los mensajes que llegan como filtos y le indicamos a la vista el mensaje a mostrar
            switch($filtro){
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "El Rol no pudo ser registrado correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Rol fue eliminado correctamente."; $filtro = "todos"; break;
	    	}

            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $usuario = Usuario::consultar(Session::get("usuario_id"));
            
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = SeguridadRol::total("app_id=".($usuario ? $usuario -> app_id : 1)); $this -> registros = SeguridadRol::reporte("app_id=".($usuario ? $usuario -> app_id : 1),$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = SeguridadRol::total("app_id=".($usuario ? $usuario -> app_id : 1)." AND nombre LIKE '%".$filtro."%'"); $this -> registros = SeguridadRol::reporte("app_id=".($usuario ? $usuario -> app_id : 1)." AND nombre LIKE '%".$filtro."%'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		function registroRol() {
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> mensaje = "";
			
			$this -> departamentos = Aplicacion::reporte();
	    }
	    
	    function registrarRol(){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$rol = Rol::registrar($this -> post("nombre"),$this -> post("aplicacion"));
	    	
	    	$rol -> app_id = $this -> post("aplicacion");
	    	
	    	$rol -> save();
	    	
	    	if($rol){
	    		$this -> redirect("seguridad/consultaRol/".$rol -> id."/registrado");
	    	}
	    	else{
	    		$this -> redirect("seguridad/roles/no_registrado");
	    	}
	    }
	    
	    function consultaRol($id,$tmp=""){
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> rol = Rol::consultar($id);
	    	
	    	$this -> mensaje = "";
	    	
	    	switch($tmp){
	    		case "modificado": $this -> mensaje = "El Rol fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Rol no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Rol fue registrado correctamente."; break;
	    	}
	    	
	    	$this -> departamentos = Aplicacion::reporte();
	    }
	    
	    function modificarRol(){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$rol = Rol::consultar($this -> post("id"));
	    	
	    	$rol -> app_id = $this -> post("aplicacion");
	    	$rol -> nombre = $this -> post("nombre");
	    	
	    	$rol -> save();
	    	
	    	if($rol){
	    		$this -> redirect("seguridad/consultaRol/".$rol -> id."/modificado");
	    	}
	    	else{
	    		$this -> redirect("seguridad/consultaRol/".$rol -> id."/no_modificado");
	    	}
	    }
	    
	    function eliminarRol($id){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$rol = Rol::consultar($id);
	    	
	    	$permisos = $rol -> permisos();
	    	
	    	if($permisos) foreach($permisos as $permiso){
	    		$permiso -> delete();
	    	}
	    	
	    	$rol -> delete();
	    	
	    	$this -> redirect("seguridad/roles/eliminado");
	    }
		
		/* *** ADMINISTRACION DE USUARIOS *** */
		
		public function buscarUsuario(){
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
            
            $this -> redirect("seguridad/usuarios/".$filtro);
        }
        
        public function usuarios($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("usuarios","default");
        	
 	        //Inicializacion, siempre irá igual en todos los controladores
            $this -> mensaje = "";
            $filtro = str_replace("-"," ",$filtro);
            $orden = str_replace("-"," ",$orden);

            //Número de registros a mostrar por página.
            $pp = 10;

            //Si llegara una variable con un texto a buscar será marcada como filtro.
            if($this -> post("busqueda")!=""){ $filtro = $this -> post("busqueda"); }
            
            //Configuración de los mensajes que llegan como filtos y le indicamos a la vista el mensaje a mostrar
            
            switch($filtro){
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "El Usuario no pudo ser registrado correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Usuario fue eliminado correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            $roles = Rol::reporte("nombre LIKE '%".$filtro."%'");
            if($roles) foreach($roles as $rol){ 
            	$sql .= " OR rol_id=".$rol -> id;
            }

            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = SeguridadUsuario::total(); $this -> registros = SeguridadUsuario::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = SeguridadUsuario::total("nombre LIKE '%".$filtro."%' OR usuario LIKE '%".$filtro."%'".$sql); $this -> registros = SeguridadUsuario::reporte("nombre LIKE '%".$filtro."%' OR usuario LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;			
		}
		
		function registroUsuario() {
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> usuario = false;
			
			$this -> mensaje = "";
			
			$this -> empleados = Empleado::reporte();
			$this -> departamentos = Aplicacion::reporte();
			$this -> roles = Rol::reporte("app_id=".Acl::aplicacion());
	    }
	    
	    function registrarUsuario(){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$usuario = Usuario::registrar($this -> post("usuario"),$this -> post("password"),$this -> post("rol"));
	    
	    	if(!$usuario){
	    		$this -> redirect("seguridad/usuarios/no_registrado");
	    	}
	    	
	    	$usuario -> app_id = $this -> post("aplicacion");
	    	$usuario -> nombre = $this -> post("nombre");
	    	$usuario -> estado = $this -> post("estado");
	    	
	    	$empleado = Empleado::buscar("nombre LIKE '".$this -> post("nombre")."'");
	    	
	    	$usuario -> empleado_id = $empleado -> id;
	    	
	    	$usuario -> save();
	    	
	    	
	    	
	    	$this -> redirect("seguridad/consultaUsuario/".$usuario -> id."/registrado");
	    }
	    
	    function consultaUsuario($id,$tmp=""){
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> usuario = Usuario::consultar($id);
	    	
	    	$this -> mensaje = "";
	    	
	    	switch($tmp){
	    		case "modificado": $this -> mensaje = "El Usuario fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Usuario no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Usuario fue registrado correctamente."; break;
	    	}
	    	
	    	$this -> empleados = Empleado::reporte();
			$this -> departamentos = Aplicacion::reporte();
			$this -> roles = Rol::reporte("app_id=".$this -> usuario -> app_id);
	    }
	    
	    function modificarUsuario(){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$usuario = Usuario::consultar($this -> post("id"));
	    	
	    	$usuario -> usuario = $this -> post("usuario");
	    	$usuario -> password = $this -> post("password") == "**********" ? $usuario -> password : sha1($this -> post("password"));
	    	
	    	$usuario -> modificarRol($this -> post("rol"));
	    	
	    	$usuario -> app_id = $this -> post("aplicacion");
	    	$usuario -> nombre = $this -> post("nombre");
	    	$usuario -> estado = $this -> post("estado");
	    	
	    	$usuario -> save();
	    	
	    	if($usuario){
	    		$this -> redirect("seguridad/consultaUsuario/".$usuario -> id."/modificado");
	    	}
	    	else{
	    		$this -> redirect("seguridad/consultaUsuario/".$usuario -> id."/no_modificado");
	    	}
	    	
	    }
	    
	    function eliminarUsuario($id){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$usuario = Usuario::consultar($id);
	    	
	    	$permisos = $usuario -> permisos();
	    	
	    	if($permisos) foreach($permisos as $permiso){
	    		$permiso -> delete();
	    	}
	    	
	    	$usuario -> delete();
	    	
	    	$this -> redirect("seguridad/usuarios/eliminado");
	    }
		
		/* *** ADMINISTRACION DE PERMISOS *** */
		
		function permisosUsuario($usuario=0, $tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("permisosUsuario",null);
            
            $this -> mensaje = "";
            
            switch($tmp){
	    		case "actualizados": $this -> mensaje = "Los permisos del Usuario fueron actualizados correctamente."; break;ak;
	    	}
			
			if($usuario == 0){
				$usuario = Session::get("usuario_id");
			}
			
			$this -> usuario = Usuario::consultar($usuario);
		}
		
		function registrarPermisosUsuario(){
			$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$usuario = Usuario::consultar($this -> post("id"));
			
			$aplicacion = Aplicacion::consultar($usuario -> app_id);
			$modulos = $aplicacion -> modulos();
			
			if($modulos) foreach($modulos as $modulo){
				$recursos = $modulo -> recursos();
				
				if($recursos) foreach($recursos as $recurso){
					if($this -> post("recurso".$recurso -> id)=="on"){
						SeguridadPermiso::registrar($usuario -> id,$recurso -> id);
					}
					else{
						$permiso = SeguridadPermiso::buscar("usuario_id=".$usuario -> id." AND recurso_id=".$recurso -> id);
						if($permiso){
							$permiso -> eliminar();
						}
					}
				}
			}
			
			$this -> redirect("seguridad/permisosUsuario/".$usuario -> id."/actualizados");
		}
		
		function permisosRol($rol=0, $tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("permisosRol",null);
			
			if($rol == 0){
				$rol = SeguridadUsuario::consultar(Session::get("usuario_id")) -> rol_id;
			}
			
			$this -> mensaje = "";
            
            switch($tmp){
	    		case "actualizados": $this -> mensaje = "Los permisos del Rol de Usuario fueron actualizados correctamente."; break;ak;
	    	}
			
			$this -> rol = Rol::consultar($rol);
		}
		
		function registrarPermisosRol(){
			$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$rol = Rol::consultar($this -> post("id"));
			
			$aplicacion = Aplicacion::consultar($rol -> app_id);
			$modulos = $aplicacion -> modulos();
			
			if($modulos) foreach($modulos as $modulo){
				$recursos = $modulo -> recursos();
				
				if($recursos) foreach($recursos as $recurso){
					if($this -> post("recurso".$recurso -> id)=="on"){
						SeguridadPermisos::registrar($rol -> id,$recurso -> id);
					}
					else{
						$permiso = SeguridadPermisos::buscar("rol_id=".$rol -> id." AND recurso_id=".$recurso -> id);
						if($permiso){
							$permiso -> eliminar();
						}
					}
				}
			}
			
			$this -> redirect("seguridad/permisosRol/".$rol -> id."/actualizados");
		}
		
		function nada(){
			$this -> render(null,null);
		}
	}
?>
