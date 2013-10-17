<?php
	class TareasController extends ApplicationController {
		
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
            
            $this -> redirect("tareas/reporte/".$filtro);
        }
		
        public function reporte($filtro="todos", $orden="id-ASC", $pagina=1){
        	//Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
        	
        	//$this -> render("reporte",null);
        	
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
	    		case "pendientex": $this -> msg = "success"; $this -> mensaje = "La Tarea ha sido terminada correctamente."; $filtro = "pendientes"; break;	    		 
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;

            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
            
            $usuario = Usuario::consultar(Session::get("usuario_id"));
            $empleado = $usuario -> empleado();
            
            if(!$empleado){
            	$this -> redirect("obras/reporte"); return;
            }
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> ntareasproyectos = PTarea::total("empleado_id=".$empleado -> id); $this -> tareasproyectos = PTarea::reporte("empleado_id=".$empleado -> id,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "terminadas": $this -> ntareasproyectos = PTarea::total("empleado_id=".$empleado -> id." AND estado='OK'"); $this -> tareasproyectos = PTarea::reporte("empleado_id=".$empleado -> id." AND estado='OK'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "pendientes": $this -> ntareasproyectos = PTarea::total("empleado_id=".$empleado -> id." AND estado='KO'"); $this -> tareasproyectos = PTarea::reporte("empleado_id=".$empleado -> id." AND estado='KO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> ntareasproyectos = PTarea::total("empleado_id=".$empleado -> id." AND (nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%'".$sql.")"); $this -> tareasproyectos = PTarea::reporte("empleado_id=".$empleado -> id." AND (nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%'".$sql.")",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }
            
            switch($filtro){
                case "todos": $this -> ntareasobras = OTarea::total("empleado_id=".$empleado -> id); $this -> tareasobras = OTarea::reporte("empleado_id=".$empleado -> id,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "terminadas": $this -> ntareasobras = OTarea::total("empleado_id=".$empleado -> id." AND estado='OK'"); $this -> tareasobras = OTarea::reporte("empleado_id=".$empleado -> id." AND estado='OK'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "pendientes": $this -> ntareasobras = OTarea::total("empleado_id=".$empleado -> id." AND estado='KO'"); $this -> tareasobras = OTarea::reporte("empleado_id=".$empleado -> id." AND estado='KO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> ntareasobras = OTarea::total("empleado_id=".$empleado -> id." AND (nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%'".$sql.")"); $this -> tareasobras = OTarea::reporte("empleado_id=".$empleado -> id." AND (nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%'".$sql.")",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }

            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> ntareasproyectos / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
            
            //Datos para la paginación en la vista
            $this -> npso = ceil($this -> ntareasobras / $pp);
            $this -> ppo = $pp;
            $this -> npo = $pagina;
		}
        
        public function consulta($id,$tmp=""){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
        	
        	$this -> render("consulta",null);
        	
        	$this -> tarea = PTarea::consultar($id);
                
			if(!$this -> tarea){
				$this -> redirect("tareas/reporte"); return;
			}
			
			$this -> mensaje = "";
            
            switch($tmp){
	    		case "modificada": $this -> mensaje = "La Tarea fue modificada correctamente."; break;
	    		case "no_modificada": $this -> mensaje = "La Tarea no pudo ser modificada."; break;
	    		case "registrada": $this -> mensaje = "La Tarea fue registrada correctamente."; break;
	    	}
            
            $this -> id = $id;
            $this -> tipo = "Proyecto";
        }
        
        public function consultao($id,$tmp=""){
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
        	
        	$this -> render("consulta",null);
        	
        	$this -> tarea = OTarea::consultar($id);
                
			if(!$this -> tarea){
				$this -> redirect("tareas/reporte"); return;
			}
			
			$this -> mensaje = "";
            
            switch($tmp){
	    		case "modificada": $this -> mensaje = "La Tarea fue modificada correctamente."; break;
	    		case "no_modificada": $this -> mensaje = "La Tarea no pudo ser modificada."; break;
	    		case "registrada": $this -> mensaje = "La Tarea fue registrada correctamente."; break;
	    	}
            
            $this -> id = $id;
            $this -> tipo = "Obra";
        }

        public function terminarTarea($id){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			Load::lib("utilerias");
			
			$this -> render(null,null);
			
			$tarea = PTarea::consultar($id);
			
			$this -> proyecto = Proyecto::consultar($tarea -> proyectos_id);
			
			$tarea -> estado = "OK";
			$tarea -> save();
			
			$this -> redirect("tareas/reporte/pendientex");
        }
        
        public function terminarTareaO($id){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect("seguridad/index"); return; }
			
			Load::lib("utilerias");
			
			$this -> render(null,null);
			
			$tarea = OTarea::consultar($id);
			
			$this -> proyecto = Proyecto::consultar($tarea -> proyectos_id);
			
			$tarea -> estado = "OK";
			$tarea -> save();
			
			$this -> redirect("tareas/reporte/pendientex");
        }
	}
?>