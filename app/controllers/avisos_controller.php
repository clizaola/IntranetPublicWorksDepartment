<?php
	class AvisosController extends ApplicationController {
		/* *** ADMINISTRACION DE AVISOS *** */
		
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
            
            $this -> redirect("avisos/reporte/".$filtro);
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
	    		case "no_registrado": $this -> msg = "error"; $this -> mensaje = "El Aviso no pudo ser registrado correctamente."; $filtro = "todos"; break;
	    		case "eliminado": $this -> msg = "success"; $this -> mensaje = "El Aviso fue eliminado correctamente."; $filtro = "todos"; break;
				case "error_eliminar": $this -> msg = "success"; $this -> mensaje = "El Aviso no se puede eliminar correctamente."; $filtro = "todos"; break;
	    	}
	    	
            //Variable para mantener el filtro.
            $this -> filtro = $filtro;
            
            //Variables para mantener el ordenamiento.
            $this -> campo = substr($orden,0,strpos($orden," "));
            $this -> orden = substr($orden,strpos($orden," ")+1)=="ASC" ? "DESC" : "ASC";
            
            $sql = "";
                    
            //Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todos": $this -> nregistros = IobrasAvisos::total(); $this -> registros = IobrasAvisos::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = IobrasAvisos::total("nombre LIKE '%".$filtro."%' OR puesto LIKE '%".$filtro."%' OR direccion LIKE '%".$filtro."%'".$sql); $this -> registros = IobrasAvisos::reporte("nombre LIKE '%".$filtro."%' OR puesto LIKE '%".$filtro."%' OR direccion LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }
            
            //Datos para la paginación en la vista
            $this -> nps = ceil($this -> nregistros / $pp);
            $this -> pp = $pp;
            $this -> np = $pagina;
		}
		
		function aviso($id=0,$tmp=""){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	if($id>0){
                $this -> aviso = IobrasAvisos::consultar($id);
            }
            else{
                $this -> aviso = false;
            }
            
            $this -> mensaje = "";
            
            switch($tmp){
	    		case "modificado": $this -> mensaje = "El Aviso fue modificado correctamente."; break;
	    		case "no_modificado": $this -> mensaje = "El Aviso no pudo ser modificado."; break;
	    		case "registrado": $this -> mensaje = "El Aviso fue registrado correctamente."; break;
				case "no_registrado": $this -> mensaje = "El Aviso no pudo ser modificado."; break;
	    	}
            
            $this -> id = $id;
        }
        
        function registrar(){
        	$this -> render(null,null);
        	Load::lib("formato");
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			if(Session::get('usuario_id')){
				if($this -> post("dirigido") == '')
					$dirigido = '0';
				else
					$dirigido = $this -> post('dirigido');
        		$aviso = IobrasAvisos::registrar($this -> post("aviso"),Formato::fechaDB($this -> post("fecha_limite")),Session::get('usuario_id'),$dirigido);				
				if($aviso)
					$this -> redirect("avisos/aviso/".$aviso -> id."/registrado");
				else
					$this -> redirect("avisos/aviso/0/no_registrado");
			}else{
				$this -> redirect();
			}
        }
        
        function modificar(){
        	$this -> render(null,null);
        	Load::lib("formato");
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$aviso = IobrasAvisos::consultar($this -> post("id"));
        	if($this -> post("dirigido") == '')
				$dirigido = '0';
			else
				$dirigido = $this -> post('dirigido');
        	$aviso -> aviso = $this -> post("aviso");
        	$aviso -> fecha_limite = Formato::fechaDB($this -> post("fecha_limite"));
        	$aviso -> dirigido_usuario_id = $dirigido;
        	
        	$aviso -> save();
        	
        	$this -> redirect("avisos/aviso/".$aviso -> id."/modificado");
        }
		
		function eliminar($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

        	$aviso = IobrasAvisos::consultar($id);
			if($aviso){
            	if($aviso -> eliminar()){
					$this -> redirect("avisos/reporte/eliminado");
				}else{
					$this -> redirect("avisos/reporte/error_eliminar");
				}
			}
        }
	}
?>