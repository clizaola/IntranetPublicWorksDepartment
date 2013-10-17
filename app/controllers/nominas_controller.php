<?php
	class NominasController extends ApplicationController {
		
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
            
            $this -> redirect("nominas/reporte/".$filtro);
        }
		
        public function reporte($filtro="todos", $orden="id-DESC", $pagina=1){
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
	    		case "no_registrada": $this -> msg = "error"; $this -> mensaje = "La Nomina no pudo ser registrada correctamente."; $filtro = "todos"; break;
	    		case "eliminada": $this -> msg = "success"; $this -> mensaje = "La Nomina fue eliminada correctamente."; $filtro = "todos"; break;	    		 
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $obras = Obra::reporte("id='".$filtro."' OR codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%'");
            $sql_obras = "";
                
            if($obras){
            	$sql_obras = " AND (obra_id = 0";
            	foreach($obras as $obra){
            
            		$sql_obras .= " OR obra_id=".$obra -> id;
            	}
            	$sql_obras .= ")";
            }
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
            	case "todos": $this -> nregistros = Nomina::total("id>0"); $this -> registros = Nomina::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            	case "pendientes": $this -> nregistros = Nomina::total("estado = 'KO'"); $this -> registros = Nomina::reporte("estado = 'KO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            	case "aceptadas": $this -> nregistros = Nomina::total("estado = 'OK'"); $this -> registros = Nomina::reporte("estado = 'OK'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            	default: $this -> nregistros = Nomina::total("id>0".$sql_obras); $this -> registros = Nomina::reporte("id>0".$sql_obras,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		public function buscarCategoria(){
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
            
            $this -> redirect("nominas/categorias/".$filtro);
        }
        
        public function categorias($filtro="todos", $orden="id-ASC", $pagina=1){
 	        Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($this -> is_ajax()){
                $this -> render("categorias",null);
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
	    		case "no_registrada": $this -> msg = "error"; $this -> mensaje = "La Categoría no pudo ser registrada correctamente."; $filtro = "todos"; break;
	    		case "no_eliminada": $this -> msg = "error"; $this -> mensaje = "La Categoría no pudo ser eliminada."; $filtro = "todos"; break;
	    		case "eliminada": $this -> msg = "success"; $this -> mensaje = "La Categoría fue eliminada correctamente."; $filtro = "todos"; break;
	    	}

            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $usuario = Usuario::consultar(Session::get("usuario_id"));
            
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            //SIN FILTROS
            
            $this -> nregistros = NominaCategorias::total("id>0");
            $this -> registros = NominaCategorias::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp);
            

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
        public function registroCategoria(){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> mensaje = "";
        }
        
        public function registrarCategoria(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	Load::lib("utilerias");
	    	
	    	$categoria = NominaCategorias::registrar($this -> post("nombre"),quitarFormatoDinero($this -> post("salario")));
	    	
	    	$categoria -> save();
	    	
	    	if($categoria){
	    		$this -> redirect("nominas/consultaCategoria/".$categoria -> id."/registrada");
	    	}
	    	else{
	    		$this -> redirect("nominas/categorias/no_registrada");
	    	}
        }
        
        function consultaCategoria($id,$tmp=""){
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> categoria = NominaCategorias::consultar($id);
	    	
	    	$this -> mensaje = "";
	    	
	    	switch($tmp){
	    		case "modificada": $this -> mensaje = "La Categoría fue modificada correctamente."; break;
	    		case "no_modificada": $this -> mensaje = "La Categoría no pudo ser modificada."; break;
	    		case "registrada": $this -> mensaje = "La Categoría fue registrada correctamente."; break;
	    	}
	    }

	    function modificarCategoria(){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	Load::lib("utilerias");
	    	
	    	$categoria = NominaCategorias::consultar($this -> post("id"));
	    	
	    	$categoria -> nombre = $this -> post("nombre");
	    	$categoria -> salario = quitarFormatoDinero($this -> post("salario"));
	    	
	    	$categoria -> save();
	    	
	    	if($categoria){
	    		$this -> redirect("nominas/consultaCategoria/".$categoria -> id."/modificada");
	    	}
	    	else{
	    		$this -> redirect("nominas/consultaCategoria/".$categoria -> id."/no_modificada");
	    	}
	    }
	    
	    function eliminarCategoria($id){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$categoria = NominaCategorias::consultar($id);
	    	
	    	$x = NominaEmpleados::total("categoria_id=".$id);
	    	
	    	if($x>0){
	    		$this -> redirect("nominas/categorias/no_eliminada");
	    	}
	    	else{
	    		$categoria -> delete();
	    		$this -> redirect("nominas/categorias/eliminada");
	    	}
	    }
	    
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
            
            $this -> redirect("nominas/empleados/".$filtro);
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
	    		case "no_eliminado": $this -> msg = "error"; $this -> mensaje = "El Empleado no pudo ser eliminado."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Empleado fue eliminado correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            $categorias = NominaCategorias::reporte("nombre LIKE '%".$filtro."%'");
            if($categorias) foreach($categorias as $categoria){ 
            	$sql .= " OR categoria_id=".$categoria -> id;
            }

            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = NominaEmpleados::total(); $this -> registros = NominaEmpleados::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = NominaEmpleados::total("nombre LIKE '%".$filtro."%'".$sql); $this -> registros = NominaEmpleados::reporte("nombre LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
	    
		public function registroEmpleado(){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> usuario = false;
			
			$this -> mensaje = "";
			
			$this -> categorias = NominaCategorias::reporte();
		}
		
		function registrarEmpleado(){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$empleado = NominaEmpleados::registrar($this -> post("nombre"),$this -> post("categoria"),$this -> post("telefono"));
	    
	    	if(!$empleado){
	    		$this -> redirect("nominas/empleados/no_registrado");
	    	}
	    	
	    	$this -> redirect("nominas/consultaEmpleado/".$empleado -> id."/registrado");
	    }
	    
	    function consultaEmpleado($id,$tmp=""){
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> empleado = NominaEmpleados::consultar($id);
	    	
	    	$this -> mensaje = "";
	    	
	    	switch($tmp){
	    		case "modificado": $this -> mensaje = "El Empleado fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Empleado no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Empleado fue registrado correctamente."; break;
	    	}
	    	
			$this -> categorias = NominaCategorias::reporte();
	    }
	    
	    function modificarEmpleado(){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$empleado = NominaEmpleados::consultar($this -> post("id"));
	    	
	    	$empleado -> nombre = $this -> post("nombre");
	    	$empleado -> categoria_id = $this -> post("categoria");
	    	$empleado -> telefono = $this -> post("telefono");
	    	
	    	$empleado -> save();
	    	
	    	if($empleado){
	    		$this -> redirect("nominas/consultaEmpleado/".$empleado -> id."/modificado");
	    	}
	    	else{
	    		$this -> redirect("nominas/consultaEmpleado/".$empleado -> id."/no_modificado");
	    	}
	    	
	    }
	    
	    function eliminarEmpleado($id){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$empleado = NominaEmpleados::consultar($id);
	    	
	    	$x = NominaAsistencia::total("empleado_id=".$id);
	    	
	    	if($x>0){
	    		$this -> redirect("nominas/empleados/no_eliminado");
	    	}
	    	else{
	    		$empleado -> delete();
	    		$this -> redirect("nominas/empleados/eliminado");
	    	}
	    }
	    
	    function registro(){
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> usuario = false;
			
			$this -> mensaje = "";
			
			$this -> obras = Obra::reporte();
	    }
	    
	    function registrar(){
	    	$this -> render(null,null);
	    	
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$nomina = Nomina::registrar($this -> post("obra"),formatoFechaDB($this -> post("inicio")),formatoFechaDB($this -> post("fin")));
			
			$this -> redirect("nominas/consulta/".$nomina -> id);
	    }
	    
	    function consulta($id,$origen='nomina'){
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> usuario = false;
			$this -> origen = $origen;
			
			$this -> mensaje = "";
			
			$this -> obras = Obra::reporte();
			$this -> nomina = Nomina::consultar($id);
	    }
	    
	    function correccion($id){
	    	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$this -> usuario = false;
			
			$this -> mensaje = "";
			
			$this -> obras = Obra::reporte();
			$this -> nomina = Nomina::consultar($id);
	    }
	    
	    public function descartarTrabajador($id){
            $this -> render(null,null);
				
            $asistencia = NominaAsistencia::consultar($id);
            
            $nomina = Nomina::consultar($asistencia -> nomina_id);
            
            $asistencia -> delete();
            
            $nomina -> obra() -> actualizarMontos();
            
            $this -> redirect("nominas/consulta/".$nomina -> id);
        }
        
        public function agregarTrabajador($id){
        	$this -> render(null,null);
        	
        	$nomina = Nomina::consultar($id);
        	
        	$empleado = NominaEmpleados::buscar("nombre = '".$this -> post("empleado".$id)."'");
        	
        	$asistencia = NominaAsistencia::registrar($id,$empleado -> id,$this -> post("categoria".$id));
        	
        	$nomina -> obra() -> actualizarMontos();
        	
        	$this -> redirect("nominas/consulta/".$nomina -> id);
        }
        
        function actualizar(){
			$this -> render(null,null);
			
            $this -> nomina = Nomina::consultar($this -> post("nomina"));
            
            $asistencia = $this -> nomina -> asistencia();
            
            if($asistencia) foreach($asistencia as $a){
                $dias = 0;
                
                if($this -> post("l".$a -> id)=="on"){
                    $a -> lunes = "OK";
                    $dias++;
                }
                else{
                    $a -> lunes = "KO";
                }
                
                if($this -> post("m".$a -> id)=="on"){
                    $a -> martes = "OK";
                    $dias++;
                }
                else{
                    $a -> martes = "KO";
                }
                
                if($this -> post("i".$a -> id)=="on"){
                    $a -> miercoles = "OK";
                    $dias++;
                }
                else{
                    $a -> miercoles = "KO";
                }
                
                if($this -> post("j".$a -> id)=="on"){
                    $a -> jueves = "OK";
                    $dias++;
                }
                else{
                    $a -> jueves = "KO";
                }
                
                if($this -> post("v".$a -> id)=="on"){
                    $a -> viernes = "OK";
                    $dias++;
                }
                else{
                    $a -> viernes = "KO";
                }
                
                if($this -> post("s".$a -> id)=="on"){
                    $a -> sabado = "OK";
                    $dias++;
                }
                else{
                    $a -> sabado = "KO";
                }
                
                if($this -> post("d".$a -> id)=="on"){
                    $a -> domingo = "OK";
                    $dias++;
                }
                else{
                    $a -> domingo = "KO";
                }
                
                $a -> dias = $dias;
                
                //$a -> salario = $a -> categoria() -> salario;
                
                $a -> importe = $a -> dias * $a -> salario;
                
                $a -> save();
            }
            
            $this -> nomina -> obra() -> actualizarMontos();
            
            $this -> redirect("nominas/consulta/".$this -> nomina -> id);
        }
        
        public function terminar($nomina){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$nomina = Nomina::consultar($nomina);
			
			$nomina -> estado = "OK";
			$nomina -> save();
			
			$nomina -> obra() -> actualizarMontos();
			
			$this -> redirect("nominas/consulta/".$nomina -> id);
		}
		
		public function eliminar($nomina){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$nomina = Nomina::consultar($nomina);
			
			$this -> obra = Obra::consultar($nomina -> obra_id);
			
			$nomina -> delete();
			
			$nomina -> obra() -> actualizarMontos();
			
			$this -> redirect("nominas/reporte/eliminada");
		}
	}
?>