<?php
	class Acl{
		public static function validarUsuario($usuario, $password){
			return SeguridadUsuario::validar($usuario, $password);
		}
		
		public static function obtenerUsuario($usuario){
			return SeguridadUsuario::consultar($usuario);
		}
		
		public static function validarAcceso($controlador, $accion){
			if(Acl::tienePermiso(Session::get("usuario_id"),$controlador, $accion)){
				return true;
			}
			
			return false;
		}
		
		public static function permisoRecurso($usuario,$recurso){
			$recurso = SeguridadRecurso::consultar($recurso);
			
			if($recurso){
				if(SeguridadPermiso::existe("usuario_id=".$usuario." AND recurso_id=".$recurso -> id)){
					return true;
				}
				
				return false;
			}
			
			return false;
		}
		
		public static function permisoRecursoRol($rol,$recurso){
			$recurso = SeguridadRecurso::consultar($recurso);
			
			if($recurso){
				if(SeguridadPermisos::existe("rol_id=".$rol." AND recurso_id=".$recurso -> id)){
					return true;
				}
				
				return false;
			}
			
			return false;
		}
		
		public static function tienePermiso($usuario, $controlador, $accion){
			if(!$usuario){
				return false;
			}
			
			$accion = SeguridadAccion::buscar("controlador='".$controlador."' AND accion='".$accion."'");
			
			if(!$accion){
				return true;
			}
			
			$recurso = $accion -> recurso();
			
			return Acl::permisoRecurso($usuario, $recurso -> id);
		}
		
		public static function menu($accion, $texto, $submenu=1){
            $params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            $controlador = substr($accion,0,strpos($accion,"/"));
            $acciontmp = substr($accion,strpos($accion,"/")+1);
            
            if(strpos($acciontmp,"/")!==false){
            	$acciontmp = substr($acciontmp,0,strpos($acciontmp,"/"));
            }
            
            if(!Session::get("usuario_id")){
            	return "";
            }
            
            if(Acl::tienePermiso(Session::get("usuario_id"),$controlador,$acciontmp)){
            	return "<li>".link_to($params).($submenu ? "" : "</li>");
            }
            else{
            	return "";
            }
        }
        
        public static function link($accion, $texto){
            $params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            $controlador = substr($accion,0,strpos($accion,"/"));
            $acciontmp = substr($accion,strpos($accion,"/")+1);
            
            if(strpos($acciontmp,"/")!==false){
            	$acciontmp = substr($acciontmp,0,strpos($acciontmp,"/"));
            }
            
            if(!Session::get("usuario_id")){
            	return "";
            }
            
            if(Acl::tienePermiso(Session::get("usuario_id"),$controlador,$acciontmp)){
            	return link_to($params);
            }
            else{
            	return "";
            }
        }
        
        public static function linkConfirmado($accion, $texto, $mensaje){
        	$params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            $params["onclick"] = "return confirm('".$mensaje."');";
            
            $controlador = substr($accion,0,strpos($accion,"/"));
            $acciontmp = substr($accion,strpos($accion,"/")+1);
            
            if(strpos($acciontmp,"/")!==false){
            	$acciontmp = substr($acciontmp,0,strpos($acciontmp,"/"));
            }
            
            if(!Session::get("usuario_id")){
            	return "";
            }
            
            if(Acl::tienePermiso(Session::get("usuario_id"),$controlador,$acciontmp)){
            	return link_to($params);
            }
            else{
            	return "";
            }
        }
        
        public static function linkAjax($accion, $text, $contenedor){
            $params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            $params["rel"] = "#".$contenedor;
            $params["class"] = "jsRemote";
            
            $controlador = substr($accion,0,strpos($accion,"/"));
            $acciontmp = substr($accion,strpos($accion,"/")+1);
            
            if(strpos($acciontmp,"/")!==false){
            	$acciontmp = substr($acciontmp,0,strpos($acciontmp,"/"));
            }
            
            if(!Session::get("usuario_id")){
            	return "";
            }
            
            if(Acl::tienePermiso(Session::get("usuario_id"),$controlador,$acciontmp)){
            	return link_to($params);
            }
            else{
            	return "";
            }
        }
        
        public static function linkAjaxConfirmado($accion, $text, $contenedor, $mensaje){
            $params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            //$params["onclick"] = "alert(confirm('".$mensaje."JAJAJA')); this.href=null; return false;";
            $params["rel"] = "#".$contenedor;
            $params["class"] = "jsRemoteEliminar";
            
            $controlador = substr($accion,0,strpos($accion,"/"));
            $acciontmp = substr($accion,strpos($accion,"/")+1);
            
            if(strpos($acciontmp,"/")!==false){
            	$acciontmp = substr($acciontmp,0,strpos($acciontmp,"/"));
            }
            
            if(!Session::get("usuario_id")){
            	return "";
            }
            
            if(Acl::tienePermiso(Session::get("usuario_id"),$controlador,$acciontmp)){
            	return link_to($params);
            }
            else{
            	return "";
            }
        }
		
		public static function aplicacion(){
			return 1;
		}
	}