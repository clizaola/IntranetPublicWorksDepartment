<?php
	class CatalogosController extends ApplicationController {
		
		/* *** ADMINISTRACION DE AVANCES *** */
		public function buscarAvance(){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
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
            
            $this -> redirect("catalogos/avances/".$filtro);
        }
		
		public function verificarAvance($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$avance = OAvance::consultar($id);
			
			if($avance -> verificado == "PENDIENTE"){
				$avance -> verificado = "VERIFICADO";
				echo '<img src=""/intranet/img/miniconos/accept.png">';
			}
			else{
				$avance -> verificado = "PENDIENTE";
				echo '<img src="/intranet/img/miniconos/delete.png">';
			}
			
			$avance -> save();
		}
		
		public function verificarObra(){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$avance = OAvance::consultar($this -> post("avance"));
			
			$avance -> obras_id = $this -> post("obra");
			
			$avance -> verificado = "VERIFICADO";
			
			echo $avance -> obra() -> nombre;
			
			$avance -> save();
		}
		
		public function avances($filtro="todos", $orden="id-ASC", $pagina=1){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("avances","default");
        	
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
	    		case "verificado": $this -> msg = "success"; $this -> mensaje = "El avance fue verificado correctamente."; $filtro = "todos"; break;
				case "reasignado": $this -> msg = "success"; $this -> mensaje = "El avance fue reasignado correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = OAvance::total(); $this -> registros = OAvance::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
				case "pendientes": $this -> nregistros = OAvance::total("id>0 AND verificado='PENDIENTE'"); $this -> registros = OAvance::reporte("id>0 AND verificado='PENDIENTE'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = OAvance::total("descripcion LIKE '%".$filtro."%'".$sql); $this -> registros = OAvance::reporte("descripcion LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		/* *** ADMINISTRACION DE DEPENDENCIAS *** */
		
		public function buscarDependencia(){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
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
            
            $this -> redirect("catalogos/dependencias/".$filtro);
        }
        
        public function dependencias($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        	
        	$this -> render("dependencias","default");
        	
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
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "La Dependencia no pudo ser registrada correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "La Dependencia fue eliminada correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = Dependencia::total(); $this -> registros = Dependencia::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = Dependencia::total("organizacion LIKE '%".$filtro."%'".$sql); $this -> registros = Dependencia::reporte("organizacion LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		function dependencia($id=0,$tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			if($id>0){
                $this -> dependencia = Dependencia::consultar($id);
            }
            else{
                $this -> dependencia = false;
            }
            
            $this -> mensaje = "";
            
            switch($tmp){
	    		case "modificado": $this -> mensaje = "La Dependencia fue modificada correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "La Dependencia no pudo ser modificada."; break;
	    		case "registrado": $this -> mensaje = "La Dependencia fue registrada correctamente."; break;
	    	}
            
            $this -> id = $id;
        }
        
        function registrarDependencia(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$dependencia = Dependencia::registrar($this -> post("organizacion"),$this -> post("gobierno"),$this -> post("nombres"),$this -> post("apellidos"),$this -> post("correo"),$this -> post("cargo"));
        	
        	$dependencia -> modificarDireccion($this -> post("direccion"),$this -> post("ciudad"),$this -> post("estado"));
            $dependencia -> modificarTelefonos($this -> post("telefono"),$this -> post("celular"));
            $dependencia -> modificarWeb($this -> post("web"));
            $dependencia -> modificarNotas($this -> post("notas"));
        	
        	$this -> redirect("catalogos/dependencia/".$dependencia -> id."/registrado");
        }
        
        function modificarDependencia(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$dependencia = Dependencia::consultar($this -> post("id"));
        	
        	$dependencia -> modificar($this -> post("organizacion"),$this -> post("gobierno"),$this -> post("nombres"),$this -> post("apellidos"),$this -> post("correo"),$this -> post("cargo"));
        	
        	$dependencia -> modificarDireccion($this -> post("direccion"),$this -> post("ciudad"),$this -> post("estado"));
            $dependencia -> modificarTelefonos($this -> post("telefono"),$this -> post("celular"));
            $dependencia -> modificarWeb($this -> post("web"));
            $dependencia -> modificarNotas($this -> post("notas"));
        	
        	$this -> redirect("catalogos/dependencia/".$dependencia -> id."/modificado");
        }
		
		function eliminarDependencia($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
            $dependencia = Dependencia::consultar($id);

            $dependencia -> eliminar();

            $this -> redirect("catalogos/dependencias/eliminado");
        }
        
		/* *** ADMINISTRACION DE PROGRAMAS *** */
		
		public function buscarPrograma(){
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
            
            $this -> redirect("catalogos/programas/".$filtro);
        }
        
        public function programas($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("programas","default");
        	
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
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "El Programa no pudo ser registrado correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Programa fue eliminado correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = Programa::total(); $this -> registros = Programa::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = Programa::total("nombre LIKE '%".$filtro."%'".$sql); $this -> registros = Programa::reporte("nombre LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		function programa($id=0,$tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($id>0){
                $this -> programa = Programa::consultar($id);
            }
            else{
                $this -> programa = false;
            }
            
            $this -> mensaje = "";
            
            switch($tmp){
	    		case "modificado": $this -> mensaje = "El Programa fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Programa no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Programa fue registrado correctamente."; break;
	    	}
            
            $this -> id = $id;
        }
        
        function registrarPrograma(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$programa = Programa::registrar($this -> post("nombre"),$this -> post("dependencia"));
        	
        	$this -> redirect("catalogos/programa/".$programa -> id."/registrado");
        }
        
        function modificarPrograma(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$programa = Programa::consultar($this -> post("id"));
        	
        	$programa -> nombre = $this -> post("nombre");
        	$programa -> dependencias_id = $this -> post("dependencia");
        	
        	$programa -> save();
        	
        	$this -> redirect("catalogos/programa/".$programa -> id."/modificado");
        }
		
		function eliminarPrograma($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$programa = Programa::consultar($id);

            $programa -> eliminar();

            $this -> redirect("catalogos/programas/eliminado");
        }
        
		/* *** ADMINISTRACION DE LOCALIDADES *** */
		
		public function buscarLocalidad(){
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
            
            $this -> redirect("catalogos/localidades/".$filtro);
        }
        
        public function localidades($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("localidades","default");
        	
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
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "La Localidad no pudo ser registrada correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "La Localidad fue eliminada correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = Localidad::total(); $this -> registros = Localidad::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = Localidad::total("nombre LIKE '%".$filtro."%'".$sql); $this -> registros = Localidad::reporte("nombre LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		function localidad($id=0,$tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($id>0){
                $this -> localidad = Localidad::consultar($id);
            }
            else{
                $this -> localidad = false;
            }
            
            $this -> mensaje = "";
            
            switch($tmp){
	    		case "modificado": $this -> mensaje = "La Localidad fue modificada correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "La Localidad no pudo ser modificada."; break;
	    		case "registrado": $this -> mensaje = "La Localidad fue registrada correctamente."; break;
	    	}
            
            $this -> id = $id;
        }
        
        function registrarLocalidad(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$localidad = Localidad::registrar($this -> post("nombre"));
        	
        	$this -> redirect("catalogos/localidad/".$localidad -> id."/registrado");
        }
        
        function modificarLocalidad(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        	
        	$localidad = Localidad::consultar($this -> post("id"));
        	
        	$localidad -> nombre = $this -> post("nombre");
        	
        	$localidad -> save();
        	
        	$this -> redirect("catalogos/localidad/".$localidad -> id."/modificado");
        }
		
		function eliminarLocalidad($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$localidad = Localidad::consultar($id);

            $localidad -> eliminar();

            $this -> redirect("catalogos/localidades/eliminado");
        }
        
		/* *** ADMINISTRACION DE TIPOS DE OBRA *** */
		
		public function buscarTipo(){
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
            
            $this -> redirect("catalogos/tipos/".$filtro);
        }
        
        public function tipos($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("tipos","default");
        	
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
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "El Tipo de Obra no pudo ser registrado correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Tipo de Obra fue eliminado correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = TipoObra::total(); $this -> registros = TipoObra::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = TipoObra::total("nombre LIKE '%".$filtro."%'".$sql); $this -> registros = TipoObra::reporte("nombre LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		function tipo($id=0,$tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($id>0){
                $this -> tipo = TipoObra::consultar($id);
            }
            else{
                $this -> tipo = false;
            }
            
            $this -> mensaje = "";
            
            switch($tmp){
	    		case "modificado": $this -> mensaje = "El Tipo de Obra fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Tipo de Obra no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Tipo de Obra fue registrado correctamente."; break;
	    	}
            
            $this -> id = $id;
        }
        
        function registrarTipo(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$tipo = TipoObra::registrar($this -> post("nombre"));
        	
        	$this -> redirect("catalogos/tipo/".$tipo -> id."/registrado");
        }
        
        function modificarTipo(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$tipo = TipoObra::consultar($this -> post("id"));
        	
        	$tipo -> nombre = $this -> post("nombre");
        	
        	$tipo -> save();
        	
        	$this -> redirect("catalogos/tipo/".$tipo -> id."/modificado");
        }
		
		function eliminarTipo($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$tipo = TipoObra::consultar($id);

            $tipo -> eliminar();

            $this -> redirect("catalogos/tipos/eliminado");
        }
        
		/* *** ADMINISTRACION DE TIPOS DE OBRA *** */
		
		public function buscarConcepto(){
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
            
            $this -> redirect("catalogos/conceptos/".$filtro);
        }
        
        public function conceptos($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("conceptos","default");
        	
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
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "El Concepto no pudo ser registrado correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Concepto fue eliminado correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = Concepto::total(); $this -> registros = Concepto::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = Concepto::total("nombre LIKE '%".$filtro."%'".$sql); $this -> registros = Concepto::reporte("nombre LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		function concepto($id=0,$tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($id>0){
                $this -> concepto = Concepto::consultar($id);
            }
            else{
                $this -> concepto = false;
            }
            
            $this -> mensaje = "";
            
            switch($tmp){
	    		case "modificado": $this -> mensaje = "El Concepto fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Concepto no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Concepto fue registrado correctamente."; break;
	    	}
            
            $this -> id = $id;
        }
        
        function registrarConcepto(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$concepto = Concepto::registrar($this -> post("nombre"));
        	
        	$this -> redirect("catalogos/concepto/".$concepto -> id."/registrado");
        }
        
        function modificarConcepto(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$concepto = Concepto::consultar($this -> post("id"));
        	
        	$concepto -> nombre = $this -> post("nombre");
        	
        	$concepto -> save();
        	
        	$this -> redirect("catalogos/concepto/".$concepto -> id."/modificado");
        }
		
		function eliminarConcepto($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$concepto = Concepto::consultar($id);

            $concepto -> eliminar();

            $this -> redirect("catalogos/conceptos/eliminado");
        }
        
		/* *** ADMINISTRACION DE INSUMOS *** */
		
		public function buscarInsumo(){
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
            
            $this -> redirect("catalogos/insumos/".$filtro);
        }
        
        public function insumos($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("insumos","default");
        	
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
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "El Insumo no pudo ser registrado correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Insumo fue eliminado correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = Insumo::total(); $this -> registros = Insumo::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = Insumo::total("nombre LIKE '%".$filtro."%'".$sql); $this -> registros = Insumo::reporte("nombre LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		function insumo($id=0,$tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($id>0){
                $this -> insumo = Insumo::consultar($id);
            }
            else{
                $this -> insumo = false;
            }
            
            $this -> mensaje = "";
            
            switch($tmp){
	    		case "modificado": $this -> mensaje = "El Insumo fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Insumo no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Insumo fue registrado correctamente."; break;
				case "error": $this -> mensaje = "El Insumo No se pudo registrar correctamente."; break;
	    	}
            
            $this -> id = $id;
        }
        
        function registrarInsumo(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$insumo = Insumo::registrar($this -> post("nombre"),$this -> post("medida"));
        	if($insumo)
        		$this -> redirect("catalogos/insumo/".$insumo -> id."/registrado");
			else
				$this -> redirect("catalogos/insumo/0/error");
        }
        
        function modificarInsumo(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$insumo = Insumo::consultar($this -> post("id"));
        	
        	$insumo -> nombre = $this -> post("nombre");
        	$insumo -> medida = $this -> post("medida");
        	
        	$insumo -> save();
        	
        	$this -> redirect("catalogos/insumo/".$insumo -> id."/modificado");
        }
		
		function eliminarInsumo($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$programa = Insumo::consultar($id);

            $programa -> eliminar();

            $this -> redirect("catalogos/insumos/eliminado");
        }
		
		/* *** ADMINISTRACION DE EMPLEADOS *** */
		
		public function buscarEmpleado(){
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
            
            $this -> redirect("catalogos/empleados/".$filtro);
        }
        
        public function empleados($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("empleados","default");
        	
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
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "El Empleado no pudo ser registrado correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Empleado fue eliminado correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;
            
            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = Empleado::total(); $this -> registros = Empleado::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = Empleado::total("nombre LIKE '%".$filtro."%' OR puesto LIKE '%".$filtro."%' OR direccion LIKE '%".$filtro."%'".$sql); $this -> registros = Empleado::reporte("nombre LIKE '%".$filtro."%' OR puesto LIKE '%".$filtro."%' OR direccion LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }
            
            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		function empleado($id=0,$tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($id>0){
                $this -> empleado = Empleado::consultar($id);
            }
            else{
                $this -> empleado = false;
            }
            
            $this -> mensaje = "";
            
            switch($tmp){
	    		case "modificado": $this -> mensaje = "El Empleado fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Empleado no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Empleado fue registrado correctamente."; break;
	    	}
            
            $this -> id = $id;
        }
        
        function registrarEmpleado(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$empleado = Empleado::registrar($this -> post("nombre"),$this -> post("puesto"),$this -> post("direccion"));
        	
        	$empleado -> telefono = $this -> post("telefono");
        	$empleado -> celular = $this -> post("celular");
        	$empleado -> correo = $this -> post("correo");
        	
        	$empleado -> save();
        	
        	$this -> redirect("catalogos/empleado/".$empleado -> id."/registrado");
        }
        
        function modificarEmpleado(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$empleado = Empleado::consultar($this -> post("id"));
        	
        	$empleado -> nombre = $this -> post("nombre");
        	$empleado -> puesto = $this -> post("puesto");
        	$empleado -> direccion = $this -> post("direccion");
        	
        	$empleado -> telefono = $this -> post("telefono");
        	$empleado -> celular = $this -> post("celular");
        	$empleado -> correo = $this -> post("correo");
        	
        	$empleado -> save();
        	
        	$this -> redirect("catalogos/empleado/".$empleado -> id."/modificado");
        }
		
		function eliminarEmpleado($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$empleado = Empleado::consultar($id);

            $empleado -> eliminar();

            $this -> redirect("catalogos/empleados/eliminado");
        }
        
		/* *** ADMINISTRACION DE CONTRATISTAS *** */
		
		public function buscarContratista(){
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
            
            $this -> redirect("catalogos/contratistas/".$filtro);
        }
        
        public function contratistas($filtro="todos", $orden="id-ASC", $pagina=1){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> render("contratistas","default");
        	
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
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "El Contratista no pudo ser registrado correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Contratista fue eliminado correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;
            
            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = Contratista::total(); $this -> registros = Contratista::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = Contratista::total("nombre LIKE '%".$filtro."%'".$sql); $this -> registros = Contratista::reporte("nombre LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }
            
            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		function contratista($id=0,$tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($id>0){
                $this -> contratista = Contratista::consultar($id);
            }
            else{
                $this -> contratista = false;
            }
            
            $this -> mensaje = "";
            
            switch($tmp){
	    		case "modificado": $this -> mensaje = "El Contratista fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Contratista no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Contratista fue registrado correctamente."; break;
	    	}
            
            $this -> id = $id;
        }
        
        function registrarContratista(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$contratista = Contratista::registrar($this -> post("nombre"),$this -> post("telefono"),$this -> post("celular"));
        	
        	$this -> redirect("catalogos/contratista/".$contratista -> id."/registrado");
        }
        
        function modificarContratista(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$contratista = Contratista::consultar($this -> post("id"));
        	
        	$contratista -> nombre = $this -> post("nombre");
        	$contratista -> telefono = $this -> post("telefono");
        	$contratista -> celular = $this -> post("celular");
        	
        	$contratista -> save();
        	
        	$this -> redirect("catalogos/contratista/".$contratista -> id."/modificado");
        }
		
		function eliminarContratista($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$contratista = Contratista::consultar($id);

            $contratista -> eliminar();

            $this -> redirect("catalogos/contratistas/eliminado");
        }
        
		/* *** IMPORTADOR DE CATALOGOS *** */
		
		public function importarCatalogo(){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
		}
        
        public function exportarCatalogo(){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        }
        
        function exportar($tabla){
            Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> set_response("view");
            
            unset($this -> titulos);
            unset($this -> registros);
            
            if($tabla == "dependencias"){
                $this -> titulos = array("organizacion","gobierno","nombre_encargado","apellidos_encargado","email_encargado","cargo_encargado","telefono_oficina","telefono_movil","direccion","ciudad");
                
                $dependencias = Dependencia::reporte("id>0");
                
                $i=0;
                if($dependencias) foreach($dependencias as $dependencia){
                    $this -> registros[$i][0] = $dependencia -> organizacion;
                    $this -> registros[$i][1] = $dependencia -> gobierno;
                    $this -> registros[$i][2] = $dependencia -> nombre_encargado;
                    $this -> registros[$i][3] = $dependencia -> apellidos_encargado;
                    $this -> registros[$i][4] = $dependencia -> email_encargado;
                    $this -> registros[$i][5] = $dependencia -> cargo_encargado;
                    $this -> registros[$i][6] = $dependencia -> telefono_oficina;
                    $this -> registros[$i][7] = $dependencia -> telefono_movil;
                    $this -> registros[$i][8] = $dependencia -> direccion;
                    $this -> registros[$i][9] = $dependencia -> ciudad;
                    
                    $i++;
                }
            }
            
            if($tabla == "programas"){
                $this -> titulos = array("nombre","dependencia_id");
                
                $programas = Programa::reporte("id>0");
                
                $i=0;
                if($programas) foreach($programas as $programa){
                    $this -> registros[$i][0] = $programa -> nombre;
                    $this -> registros[$i][1] = $programa -> dependencias_id;
                    
                    $i++;
                }
            }
            
            if($tabla == "localidades"){
                $this -> titulos = array("nombre");
                
                $programas = Localidad::reporte("id>0");
                
                $i=0;
                if($programas) foreach($programas as $programa){
                    $this -> registros[$i][0] = $programa -> nombre;
                    
                    $i++;
                }
            }
            
            if($tabla == "tipos"){
                $this -> titulos = array("nombre");
                
                $programas = TipoObra::reporte("id>0");
                
                $i=0;
                if($programas) foreach($programas as $programa){
                    $this -> registros[$i][0] = $programa -> nombre;
                    
                    $i++;
                }
            }
            
            if($tabla == "insumos"){
                $this -> titulos = array("nombre","medida");
                
                $programas = Insumo::reporte("id>0");
                
                $i=0;
                if($programas) foreach($programas as $programa){
                    $this -> registros[$i][0] = $programa -> nombre;
                    $this -> registros[$i][1] = $programa -> medida;
                    
                    $i++;
                }
            }
            
            if($tabla == "conceptos"){
                $this -> titulos = array("nombre");
                
                $programas = Concepto::reporte("id>0");
                
                $i=0;
                if($programas) foreach($programas as $programa){
                    $this -> registros[$i][0] = $programa -> nombre;
                    
                    $i++;
                }
            }
            
            if($tabla == "empleados"){
                $this -> titulos = array("nombre","puesto","direccion","telefono","celular");
                
                $programas = Empleado::reporte("id>0");
                
                $i=0;
                if($programas) foreach($programas as $programa){
                    $this -> registros[$i][0] = $programa -> nombre;
                    $this -> registros[$i][1] = $programa -> puesto;
                    $this -> registros[$i][2] = $programa -> direccion;
                    $this -> registros[$i][3] = $programa -> telefono;
                    $this -> registros[$i][4] = $programa -> celular;
                    
                    $i++;
                }
            }
            
            if($tabla == "contratistas"){
                $this -> titulos = array("nombre","telefono","celular");
                
                $programas = Contratista::reporte("id>0");
                
                $i=0;
                if($programas) foreach($programas as $programa){
                    $this -> registros[$i][0] = $programa -> nombre;
                    $this -> registros[$i][1] = $programa -> telefono;
                    $this -> registros[$i][2] = $programa -> celular;
                    
                    $i++;
                }
            }
        }
        
        function importar(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($this -> post("boton")=="Exportar"){
                $this -> redirect("catalogos/exportar/".$_POST["catalogo"]);
                return;
            }
        
            if($_FILES["csv"]["name"]!=""){
    			$archivo = strtolower(APP_PATH."public/files/csvs/".$_POST["catalogo"].".csv");
    			move_uploaded_file($_FILES['csv']['tmp_name'], $archivo);
    		}
            
            $registros = file(APP_PATH."public/files/csvs/".$_POST["catalogo"].".csv");
            
            if($_POST["catalogo"]=="dependencias"){
                $x=true; if($registros) foreach($registros as $registro){
                    
                    if($x){
                        $x=false;
                        
                        if(strpos(" ".$registro,"organizacion,gobierno,nombre_encargado,apellidos_encargado,email_encargado,cargo_encargado,telefono_oficina,telefono_movil,direccion,ciudad")<=0){
                            throw new KumbiaException("El archivo no tiene el encabezado correcto: organizacion,gobierno,nombre_encargado,apellidos_encargado,email_encargado,cargo_encargado,telefono_oficina,telefono_movil,direccion,ciudad");    
                        }
                        
                        $titulos = explode(",",$registro);
                        
                        continue;
                    }
                    
    				unset($temporal);
    
    				$campos = explode(",",$registro);
    
    				$i=0;
    				foreach($campos as $campo) {
    					$temporal[$i] = $campo;
    
    					$i++;
    				}
    
    				if(!isset($temporal[0])) continue;
    
    				$nombre = trim($temporal[0]);
    				$gobierno = trim($temporal[1]);
    				$n_encargado = trim($temporal[2]);
                    $a_encargado = trim($temporal[3]);
                    $e_encargado = trim($temporal[4]);
                    $c_encargado = trim($temporal[5]);
    				$t_encargado = trim($temporal[6]);
                    $tm_encargado = trim($temporal[7]);
                    $direccion = trim($temporal[8]);
                    $ciudad = trim($temporal[9]);
    				
    				$dependencia = Dependencia::registrar($nombre,$gobierno,$n_encargado,$a_encargado,$e_encargado,$c_encargado);
                    
                    if($dependencia){
                        if($t_encargado!="")
                            $dependencia -> telefono_oficina = $t_encargado;
                            
                        if($tm_encargado!="")
                            $dependencia -> telefono_movil = $tm_encargado;
                            
                        if($direccion!="")
                            $dependencia -> direccion = $direccion;
                            
                        if($ciudad!="")
                            $dependencia -> ciudad = $ciudad;
                        
                        $dependencia -> save();
                    }
                }
                
                $this -> redirect("catalogos/dependencias");
                return;
            }
            
            if($_POST["catalogo"]=="programas"){
                $x=true; if($registros) foreach($registros as $registro){
                    
                    if($x){
                        $x=false;
                        
                        if(strpos(" ".$registro,"nombre,dependencia_id")<=0){
                            throw new KumbiaException("El archivo no tiene el encabezado correcto: nombre,dependencia_id");    
                        }
                        
                        $titulos = explode(",",$registro);
                        
                        continue;
                    }
                    
    				unset($temporal);
    
    				$campos = explode(",",$registro);
    
    				$i=0;
    				foreach($campos as $campo) {
    					$temporal[$i] = $campo;
    
    					$i++;
    				}
    
    				if(!isset($temporal[0])) continue;
    
    				$nombre = trim($temporal[0]);
    				$dependencia = trim($temporal[1]); if($dependencia=="") $dependencia="0";
                    
    				$programa = Programa::registrar($nombre,$dependencia);
                }
                
                $this -> redirect("catalogos/programas");
                return;
            }
            
            if($_POST["catalogo"]=="localidades"){
                $x=true; if($registros) foreach($registros as $registro){
                    
                    if($x){
                        $x=false;
                        
                        if(strpos(" ".$registro,"nombre")<=0){
                            throw new KumbiaException("El archivo no tiene el encabezado correcto: nombre");    
                        }
                        
                        $titulos = explode(",",$registro);
                        
                        continue;
                    }
    				unset($temporal);
    
    				$campos = explode(",",$registro);
    
    				$i=0;
    				foreach($campos as $campo) {
    					$temporal[$i] = $campo;
    
    					$i++;
    				}
    
    				if(!isset($temporal[0])) continue;
    
    				$nombre = trim($temporal[0]);
                    
    				$localidad = Localidad::registrar($nombre);
                }
                
                $this -> redirect("catalogos/localidades");
                return;
            }
            
            if($_POST["catalogo"]=="tipos"){
                $x=true; if($registros) foreach($registros as $registro){
                    
                    if($x){
                        $x=false;
                        
                        if(strpos(" ".$registro,"nombre")<=0){
                            throw new KumbiaException("El archivo no tiene el encabezado correcto: nombre");    
                        }
                        
                        $titulos = explode(",",$registro);
                        
                        continue;
                    }
    				unset($temporal);
    
    				$campos = explode(",",$registro);
    
    				$i=0;
    				foreach($campos as $campo) {
    					$temporal[$i] = $campo;
    
    					$i++;
    				}
    
    				if(!isset($temporal[0])) continue;
    
    				$nombre = trim($temporal[0]);
                    
    				$tipo = TipoObra::registrar($nombre);
                }
                
                $this -> redirect("catalogos/tipos");
                return;
            }
            
            if($_POST["catalogo"]=="insumos"){
                $x=true; if($registros) foreach($registros as $registro){
                    
                    if($x){
                        $x=false;
                        
                        if(strpos(" ".$registro,"nombre,medida")<=0){
                            throw new KumbiaException("El archivo no tiene el encabezado correcto: nombre,medida");    
                        }
                        
                        $titulos = explode(",",$registro);
                        
                        continue;
                    }
    				unset($temporal);
    
    				$campos = explode(",",$registro);
    
    				$i=0;
    				foreach($campos as $campo) {
    					$temporal[$i] = $campo;
    
    					$i++;
    				}
    
    				if(!isset($temporal[0])) continue;
    
    				$nombre = trim($temporal[0]);
                    $medida = trim($temporal[1]);
                    
    				$insumo = Insumo::registrar($nombre,$medida);
                }
                
                $this -> redirect("catalogos/insumos");
                return;
            }
            
            if($_POST["catalogo"]=="conceptos"){
                $x=true; if($registros) foreach($registros as $registro){
                    
                    if($x){
                        $x=false;
                        
                        if(strpos(" ".$registro,"nombre")<=0){
                            throw new KumbiaException("El archivo no tiene el encabezado correcto: nombre");    
                        }
                        
                        $titulos = explode(",",$registro);
                        
                        continue;
                    }
    				unset($temporal);
    
    				$campos = explode(",",$registro);
    
    				$i=0;
    				foreach($campos as $campo) {
    					$temporal[$i] = $campo;
    
    					$i++;
    				}
    
    				if(!isset($temporal[0])) continue;
    
    				$nombre = trim($temporal[0]);
                    
    				$concepto = Concepto::registrar($nombre);
                }
                
                $this -> redirect("catalogos/conceptos");
                return;
            }
            
            if($_POST["catalogo"]=="empleados"){
                $x=true; if($registros) foreach($registros as $registro){
                    
                    if($x){
                        $x=false;
                        
                        if(strpos(" ".$registro,"nombre,puesto,direccion,telefono,celular")<=0){
                            throw new KumbiaException("El archivo no tiene el encabezado correcto: nombre,puesto,direccion,telefono,celular");    
                        }
                        
                        $titulos = explode(",",$registro);
                        
                        continue;
                    }
    				unset($temporal);
                    
    				$campos = explode(",",$registro);
    
    				$i=0;
    				foreach($campos as $campo) {
    					$temporal[$i] = $campo;
    
    					$i++;
    				}
    
    				if(!isset($temporal[0])) continue;
    
    				$nombre = trim($temporal[0]);
                    $puesto = trim($temporal[1]);
                    $direccion = trim($temporal[2]);
                    $telefono = trim($temporal[3]);
                    $celular = trim($temporal[4]);
                    
    				$empleado = Empleado::registrar($nombre,$puesto,$direccion,$telefono,$celular);
                }
                
                $this -> redirect("catalogos/empleados");
                return;
            }
            
            if($_POST["catalogo"]=="contratistas"){
                $x=true; if($registros) foreach($registros as $registro){
                    
                    if($x){
                        $x=false;
                        
                        if(strpos(" ".$registro,"nombre,telefono,celular")<=0){
                            throw new KumbiaException("El archivo no tiene el encabezado correcto: nombre,telefono,celular");    
                        }
                        
                        $titulos = explode(",",$registro);
                        
                        continue;
                    }
    				unset($temporal);
    
    				$campos = explode(",",$registro);
    
    				$i=0;
    				foreach($campos as $campo) {
    					$temporal[$i] = $campo;
    
    					$i++;
    				}
    
    				if(!isset($temporal[0])) continue;
    
    				$nombre = trim($temporal[0]);
                    $telefono = trim($temporal[1]);
                    $celular = trim($temporal[2]);
                    
    				$contratista = Contratista::registrar($nombre,$telefono,$celular);
                }
                
                $this -> redirect("catalogos/contratistas");
                return;
            }
        }
         
	}
?>