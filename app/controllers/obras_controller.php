<?php
	class ObrasController extends ApplicationController {
		
		public function imagen(){
			$this -> set_response("view");
		}
		
		public function pruebax(){
			$this -> render(null,null);
			
			echo $this -> post("mapax");
		}
		
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
            
            $this -> redirect("obras/reporte/".$filtro);
        }
		
        public function reporte($filtro="todas", $orden="id-ASC", $pagina=1){
        	$this -> render("reporte","default");
				
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        	
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
	    		case "no_registrada": $this -> msg = "error"; $this -> mensaje = "La Obra no pudo ser registrada correctamente."; $filtro = "todas"; break;
	    		case "eliminada": $this -> msg = "success"; $this -> mensaje = "La obra fue eliminada correctamente."; $filtro = "todas"; break;	    		 
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
                case "todas": $this -> nregistros = Obra::total(); $this -> registros = Obra::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "nuevas": $this -> nregistros = Obra::total("estado = 'NUEVA' OR estado = 'NUEVO'"); $this -> registros = Obra::reporte("estado = 'NUEVA' OR estado = 'NUEVO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "proceso": $this -> nregistros = Obra::total("estado = 'EN PROCESO'"); $this -> registros = Obra::reporte("estado = 'EN PROCESO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "terminadas": $this -> nregistros = Obra::total("estado = 'TERMINADA' OR estado = 'TERMINADO'"); $this -> registros = Obra::reporte("estado = 'TERMINADA' OR estado = 'TERMINADO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> nregistros = Obra::total("codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR objetivo LIKE '%".$filtro."%'".$sql); $this -> registros = Obra::reporte("codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR objetivo LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
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

		//Filtros para la busqueda a realizar, marcar con cases las busquedas predefinidas.
            switch($filtro){
                case "todas": $this -> registros = Obra::reporte("id>0",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "nuevas": $this -> registros = Obra::reporte("estado = 'NUEVA' OR estado = 'NUEVO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "proceso": $this -> registros = Obra::reporte("estado = 'EN PROCESO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                case "terminadas": $this -> registros = Obra::reporte("estado = 'TERMINADA' OR estado = 'TERMINADO'",$orden.", id ASC",$pp * ($pagina-1),$pp); break;
                default: $this -> registros = Obra::reporte("codigo LIKE '%".$filtro."%' OR nombre LIKE '%".$filtro."%' OR descripcion LIKE '%".$filtro."%' OR objetivo LIKE '%".$filtro."%'".$sql,$orden.", id ASC",$pp * ($pagina-1),$pp); break;
            }
		}
		
		public function registro($proyecto=0){
			$this -> render("obra");	
				
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			if($proyecto > 0){
				$this -> proyectando = Proyecto::consultar($proyecto);
			}
        	
			$this -> obra = false;
			
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
        	$presupuestado = quitarFormatoDinero($this -> post("presupuestado"));
            
            $total = $pfederal+$pestatal+$pmunicipal+$pbeneficiarios;
            
            $presupuesto = formatoNumero(round($pfederal/$total*100),3)."|";
            $presupuesto .= formatoNumero(round($pestatal/$total*100),3)."|";
            $presupuesto .= formatoNumero(round($pmunicipal/$total*100),3)."|";
            $presupuesto .= formatoNumero(round($pbeneficiarios/$total*100),3);
			
			$obra = Obra::registrar($this -> post("proyecto"),$this -> post("codigo"),$this -> post("nombre"),$alcance,$this -> post("localidad"),$this -> post("descripcion"),$this -> post("objetivo"),$this -> post("tipo"),$this -> post("supervisor"),$presupuestado);
			
			if(!$obra){
        		$this -> redirect("obras/reporte/no_registrada"); return;
        	}
        	
        	$obra -> georeferencia = substr($this -> post("mapa"),1,strlen($this -> post("mapa"))-2);
        	$obra -> presupuesto = $presupuesto == "" ? "000|000|000|000" : $presupuesto;
        	$obra -> pfederal = $pfederal;
        	$obra -> pestatal = $pestatal;
        	$obra -> pmunicipal = $pmunicipal;
        	$obra -> pbeneficiarios = $pbeneficiarios;
        	
        	$cabildo = Cabildo::consultar($this -> post("cabildo"));
        	
        	$obra -> cabildo_id = $cabildo ? $cabildo -> id : 0;
        	$obra -> acta_cabildo = $cabildo ? $cabildo -> nombre : "";
        	
        	$obra -> save();
			$obra -> generarMapa();
        	
        	if($this -> post("proyecto")){
                $proyecto = Proyecto::consultar($this -> post("proyecto"));
    			if($proyecto)
    			{
    				$proyecto -> estado = "EN OBRA";
    				$proyecto -> save();
    			}
            }
        	
        	$this -> redirect("obras/consulta/".$obra -> id."/registrada");
        }
        
        public function consulta($id,$tmp="",$tmp2=""){
        	$this -> render("obra");
				
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        	
        	$this -> obra = Obra::consultar($id);
                
			if(!$this -> obra){
				$this -> redirect("obras/reporte"); return;
			}
			
			$this -> mensaje = "";
            
            switch($tmp){
	    		case "modificada": $this -> mensaje = "La Obra fue modificada correctamente."; break;
	    		case "no_modificada": $this -> mensaje = "La Obra no pudo ser modificada."; break;
	    		case "registrada": $this -> mensaje = "La Obra fue registrada correctamente."; break;
	    	}
	    	
	    	switch($tmp2){
	    		case "programas": $this -> partial = "obras/programas"; break;
	    		case "contratistas": $this -> partial = "obras/contratistas"; break;
	    		case "archivos": $this -> partial = "obras/archivos"; break;
	    		
	    		case "pendientes": $this -> partial = "obras/tareas"; break;
	    		case "terminadas": $this -> partial = "obras/tareasTerminadas"; break;
	    		
	    		case "requisiciones": $this -> partial = "obras/requisiciones"; break;
	    		case "nominas": $this -> partial = "obras/nominas"; break;
	    		case "facturas": $this -> partial = "obras/facturas"; break;
	    		
	    		default: $this -> partial = "obras/programas";
	    	}
	    	
	    	if($tmp == "avances"){
	    		$this -> partial = "obras/avances";
	    	}
	    	
	    	$this -> seccion = $tmp;
	    	$this -> subseccion = $tmp2;
	    	
	    	$this -> id = $id;
        }
        
        public function modificar(){
        	$this -> render(null,null);
        	
        	Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        	
        	Load::lib("utilerias");
        	
        	$obra = Obra::consultar($this -> post("id"));
        	
        	//NO DEBE HABER MODIFICACIONES SI LA OBRA ESTA TERMINADA
        	if($obra -> estado != "TERMINADA"){
        	
	        	$alcance = "";
	        	
	        	if($this -> post("l")=="on") $alcance .= "L"; else $alcance .= "X";
				if($this -> post("mu")=="on") $alcance .= "M"; else $alcance .= "X";
				if($this -> post("r")=="on") $alcance .= "R"; else $alcance .= "X";
	        	
	        	$pfederal = quitarFormatoDinero($this -> post("f"));
	        	$pestatal = quitarFormatoDinero($this -> post("e"));
	        	$pmunicipal = quitarFormatoDinero($this -> post("m"));
	        	$pbeneficiarios = quitarFormatoDinero($this -> post("b"));
	        	$presupuestado = quitarFormatoDinero($this -> post("presupuestado"));
	            
	            $total = $pfederal+$pestatal+$pmunicipal+$pbeneficiarios;
	            
	            $presupuesto = formatoNumero(round($pfederal/$total*100),3)."|";
	            $presupuesto .= formatoNumero(round($pestatal/$total*100),3)."|";
	            $presupuesto .= formatoNumero(round($pmunicipal/$total*100),3)."|";
	            $presupuesto .= formatoNumero(round($pbeneficiarios/$total*100),3);
	        	
	        	$obra -> modificar($this -> post("codigo"),$this -> post("nombre"),$alcance,$this -> post("localidad"),$this -> post("descripcion"),$this -> post("objetivo"),$this -> post("tipo"),$this -> post("supervisor"),$presupuestado);
	        	
	        	$obra -> georeferencia = substr($this -> post("mapa"),1,strlen($this -> post("mapa"))-2);
	        	$obra -> presupuesto = $presupuesto == "" ? "000|000|000|000" : $presupuesto;
	        	$obra -> pfederal = $pfederal;
	        	$obra -> pestatal = $pestatal;
	        	$obra -> pmunicipal = $pmunicipal;
	        	$obra -> pbeneficiarios = $pbeneficiarios;
	        	
	        	$cabildo = Cabildo::consultar($this -> post("cabildo"));
	        	
	        	$obra -> cabildo_id = $cabildo ? $cabildo -> id : 0;
	        	$obra -> acta_cabildo = $cabildo ? $cabildo -> nombre : "";
	        	
	        	if($this -> post("proyecto")){
	        		if($this -> post("proyecto") != $obra -> proyectos_id){
		                $proyecto = Proyecto::consultar($this -> post("proyectp"));
		        		
		                if($proyecto)
		    			{
		    				$proyecto -> estado = "EN OBRA";
		    				$proyecto -> save();
		    			}
		    			
		    			$obra -> proyectos_id = $this -> post("proyecto");
	        		}
	            }
            }
			
			//SI LA OBRA ESTA TERMINADA LO UNICO QUE SE PUEDE MODIFICAR ES EL ESTADO
        	$obra -> estado = $this -> post("estado");
        	$obra -> save();
			
			$obra -> generarMapa();
        	        	
        	$this -> redirect("obras/consulta/".$obra -> id."/modificada");
        }
		
		public function eliminar($id){
			$this -> render(null,null);	
				
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$obra = Obra::consultar($id);
			
			$archivos = $obra -> archivos();
			
			if($archivos) foreach($archivos as $archivo){
				$file = strtolower(APP_PATH."public/files/archivos_obras/".$archivo -> archivo);
			
				@unlink($file);
				
				$archivo -> delete();
			}
			
			$programas = $obra -> programas();
			
			if($programas) foreach($programas as $programa){
				$programa -> delete();
			}
			
			$contratistas = $obra -> contratistas();
			
			if($contratistas) foreach($contratistas as $contratista){
				$contratista -> delete();
			}
			
			$avances = $obra -> avances();
			
			if($avances) foreach($avances as $avance){
				$avance -> delete();
			}
			
			$tareas = $obra -> tareas();
			
			if($tareas) foreach($tareas as $tarea){
				$tarea -> delete();
			}
			
			$obra -> delete();
			
			$this -> redirect("obras/reporte/eliminada");
		}
		
		public function estado($id){
			$this -> render("estado",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> registro = Obra::consultar($id);
		}
		
		public function cambiarEstado(){
			$this -> render("cambio",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$obra = Obra::consultar($this -> post("obra"));
			
			$obra -> estado = $this -> post("estado");
			$obra -> save();
			
			$this -> registro = $obra;
		}
		
		public function archivos($obra){
			$this -> render("archivos",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function adjuntar(){
			$this -> render("archivos",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> obra = Obra::consultar($this -> post("id"));
			
			$archivo_obra = OArchivo::registrar($this -> post("id"),$this -> post("nombre"));
       		
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
                
				$file = strtolower($this -> obra -> codigo . "-" .$archivo_obra -> id.".".$ext);
				
			 	if(!file_exists(APP_PATH."public/files/archivos_obras/".date("Y"))){
			 		mkdir(APP_PATH."public/files/archivos_obras/".date("Y"));
			 	}
			    
				$archivo = strtolower(APP_PATH."public/files/archivos_obras/".$file);

				$archivo_obra -> archivo = $file;

				move_uploaded_file($_FILES['archivo']['tmp_name'], $archivo);
			}

			$archivo_obra -> save();
		}
		
		public function desadjuntar($id){
			$this -> render("archivos",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$archivo_obra = OArchivo::consultar($id);
			$this -> obra = Obra::consultar($archivo_obra -> obras_id);
			
			$archivo = strtolower(APP_PATH."public/files/archivos_obras/".$archivo_obra -> archivo);
			
			@unlink($archivo);
			
			$archivo_obra -> delete();
		}

		public function programas($obra){
			$this -> render("programas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function programar(){
			$this -> render("programas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> obra = Obra::consultar($this -> post("id"));
			
			$obra = OPrograma::registrar($this -> post("id"),$this -> post("programa"));
		}
		
		public function desprogramar($id){
			$this -> render("programas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$obra = OPrograma::consultar($id);
			$this -> obra = Obra::consultar($obra -> obras_id);
			
			$obra -> delete();
		}
		
		public function insumos($obra){
			$this -> render("insumos",null);	
				
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function insumar(){
			$this -> render("insumos",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> obra = Obra::consultar($this -> post("id"));
			
			$obra = OInsumo::registrar($this -> post("id"),$this -> post("producto"),quitarFormatoDinero($this -> post("precio")));
		}
		
		public function desinsumar($id){
			$this -> render("insumos",null);
				
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$insumo = OInsumo::consultar($id);
			$this -> obra = Obra::consultar($insumo -> obras_id);
			
			$insumo -> delete();
		}
		
		public function contratistas($obra){
			$this -> render("contratistas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function contratar(){
			$this -> render("contratistas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> obra = Obra::consultar($this -> post("id"));
			
			$contratista = OContratista::registrar($this -> post("id"),$this -> post("contratista"));
		}
		
		public function descontratar($id){
			$this -> render("contratistas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$contratista = OContratista::consultar($id);
			$this -> obra = Obra::consultar($contratista -> obras_id);
			
			$contratista -> delete();
		}
		
		public function avances($obra){
			$this -> render("avances",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function avanzar(){
			$this -> render("avances",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> obra = Obra::consultar($this -> post("id"));
			
			$avance = OAvance::registrar($this -> post("id"),$this -> post("porcentaje"),$this -> post("descripcion"),formatoFechaDB($this -> post("fecha")));
			
			$this -> obra -> avance = $this -> obra -> porcentaje();
			$this -> obra -> save();
		}
		
		public function desavanzar($id){
			$this -> render("avances",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$avance = OAvance::consultar($id);
			$this -> obra = Obra::consultar($avance -> obras_id);
			
			$avance -> delete();
			
			$this -> obra -> avance = $this -> obra -> porcentaje();
			$this -> obra -> save();
		}
		
		public function tareas($obra){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("tareas",null);
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function tareasTerminadas($obra){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("tareasTerminadas",null);
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function asignarTarea(){
			$this -> render("tareas",null);
				
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> obra = Obra::consultar($this -> post("id"));
			
			$empleado = Empleado::buscar("nombre LIKE '".$this -> post("empleado")."'");
			
			$correo = $empleado -> correo ? $empleado -> correo : "opydu1012@gmail.com";
			
			$tarea = OTarea::registrar($this -> post("id"),$empleado -> id,$this -> post("nombre"),$this -> post("descripcion"),formatoFechaDB($this -> post("creacion")),formatoFechaDB($this -> post("limite")));
				
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
			$headers .= "From: Departamento de Obras Públicas <opydu1012@gmail.com>\r\n";
			
			mail($correo,"Tienes una nueva tarea por realizar.",$empleado -> nombre."<br /><br />Tienes una nueva tarea por realizar, a continuación se muestran los detalles de la misma:<br /><br /><b>Obra: </b>".$this -> obra -> nombre."<br /><b>Responsable de la Obra: </b>".$this -> obra -> supervisor() -> nombre."<br /><b>Nombre de la Tarea: </b>".$tarea -> nombre."<br /><b>Descripción: </b>".$tarea -> descripcion."<br /><b>Fecha de Asignación: </b>".formatoFecha($tarea -> creacion)."<br /><b>Fecha Límite: </b>".formatoFecha($tarea -> limite)."<br /><br />Este mensaje fue enviado de manera automática desde el sistema de obras públicas de Ameca Jalisco.",$headers);
		}
		
		public function consultarTarea($id){
			$this -> render("tarea",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> tarea = OTarea::consultar($id);
		}
		
		public function cancelarTarea($id){
			$this -> render("tareas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$tarea = OTarea::consultar($id);
			
			$this -> obra = Obra::consultar($tarea -> obras_id);
			
			$tarea -> delete();
		}
		
		public function terminarTarea($id){
			$this -> render("tareasTerminadas",null);
				
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$tarea = OTarea::consultar($id);
			
			$this -> obra = Obra::consultar($tarea -> obras_id);
			
			$tarea -> estado = "OK";
			$tarea -> save();
		}
		
		public function informacion($obra){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("informacion",null);
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function materiales($obra){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("materiales",null);
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function finanzas($obra){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("finanzas",null);
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function requisiciones($obra){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("requisiciones",null);
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function requisicion($requisicion){
			$this -> render("requisicion_desglozada",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> id = $requisicion; 
		}
		
		public function requisitar(){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> render("requisiciones",null);
			
			$this -> obra = Obra::consultar($this -> post("id"));
			
			$requisicion = IobrasRequisiciones::registrar($this -> post("id"),$this -> post("fecha"),$this -> post("concepto"),$this -> post("supervisor"));
			
			$requisicion -> elaboro_id = $this -> post("elaboro");
			$requisicion -> save();
		}
		
		public function autorizarRequisicion(){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }

			Load::lib("utilerias");
			
			$requisicion = IobrasRequisiciones::consultar($this -> post("requisicion"));
			
			$conceptos = $requisicion -> conceptos();
			
			if($conceptos) foreach($conceptos as $concepto){
				$concepto -> asignado = $this -> post("cantidad".$concepto -> id);
				$concepto -> save();
			}
			
			$this -> obra = Obra::consultar($requisicion -> obras_id);
			
			$requisicion -> estado = "AUTORIZADA";
			$requisicion -> save();
			
			$this -> redirect("obras/consulta/".$this -> obra -> id."/finanzas/requisiciones");
		}
		
		public function terminarRequisicion($requisicion){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$requisicion = IobrasRequisiciones::consultar($requisicion);
			
			$this -> obra = Obra::consultar($requisicion -> obras_id);
			
			$requisicion -> estado = "ENVIADA";
			$requisicion -> save();
			
			$this -> redirect("obras/consulta/".$this -> obra -> id."/finanzas/requisiciones");
		}
		
		public function cancelarRequisicion($requisicion){
			$this -> render("requisiciones",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$requisicion = IobrasRequisiciones::consultar($requisicion);
			
			$this -> obra = Obra::consultar($requisicion -> obras_id);
			
			$conceptos = $requisicion -> conceptos();
			
			if($conceptos) foreach($conceptos as $concepto){
				$concepto -> delete();
			}
			
			$requisicion -> delete();
		}
		
		public function agregarConceptoRequisicion(){
			$this -> render("requisicion_desglozada",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$id = $this -> post("id");
			
			$requisicion = IobrasRequisiciones::consultar($id);
			
			$nombre = trim(substr($this -> post("concepto".$id),0,strpos($this -> post("concepto".$id),"{")));
			
			$producto = Insumo::buscar("nombre LIKE '%".$nombre."%'");
			
			if(IobrasConceptorequisicion::total("requisicion_id=".$requisicion -> id." AND productos_id=".$producto -> id)>0){
				$concepto = IobrasConceptorequisicion::consultar("requisicion_id=".$requisicion -> id." AND productos_id=".$producto -> id);
				
				$concepto -> cantidad += $this -> post("cantidad".$id);
				$concepto -> solicitado = $concepto -> cantidad;
				
				$concepto -> save();
				
			}
			else{
				$concepto = IobrasConceptorequisicion::registrar($requisicion -> id, $producto -> id,$this -> post("cantidad".$this -> post("id")));
			}
			
			$this -> id = $requisicion -> id;
		}
		
		public function eliminarConceptoRequisicion($concepto){
			$this -> render("requisicion_desglozada",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$concepto = IobrasConceptorequisicion::consultar($concepto);
			
			$this -> id = $concepto -> requisicion_id;
			
			$concepto -> delete();
		}
		
		public function nominas($obra){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("nominas",null);
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function facturasRequisicion($requisicion){
			$this -> render("requisicion_facturas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> id = $requisicion; 
		}
		
		public function agregarFacturaRequisicion(){
			$this -> render("requisicion_facturas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$id = $this -> post("id");
			
			$requisicion = IobrasRequisiciones::consultar($id);
			
			$factura = IobrasFacturas::consultar("nombre_emisor='".$this -> post("factura".$id)."'");

			if($factura){
				$rfactura = IobrasFacturarequisicion::registrar($requisicion -> id, $factura -> id);
				
				if($rfactura){
					$this -> notificacion = "La factura ha sido agregada a la requisición.";
					$this -> tipo_notificacion = "success_message";
				}
				else{
					$this -> notificacion = "La factura ya habia sido agregada anteriormente.";
					$this -> tipo_notificacion = "warning_message";
				}
			}
			else{
				$this -> notificacion = "Debes incluir el nombre de la factura completo.";
				$this -> tipo_notificacion = "warning_message";
			}
			
			$this -> id = $requisicion -> id;
		}
		
		public function eliminarFacturaRequisicion($factura){
			$this -> render("requisicion_facturas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$factura = IobrasFacturarequisicion::consultar($factura);
			
			$this -> id = $factura -> requisicion_id;
			
			$factura -> delete();
			
			$this -> notificacion = "La factura ha sido descartada correctamente de la requisición.";
			$this -> tipo_notificacion = "success_message";
		}
		
		public function requisicionesFactura($factura){
			$this -> render("factura_requisiciones",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> id = $factura; 
		}
		
		public function agregarRequisicionFactura(){
			$this -> render("factura_requisiciones",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			 
			$id = $this -> post("id");
			
			$factura = IobrasFacturas::consultar($id);
			
			$requisicion = IobrasRequisiciones::consultar("concepto='".$this -> post("requisicion".$id)."'");

			if($requisicion){
				$frequisicion = IobrasFacturarequisicion::registrar($requisicion -> id, $factura -> id);
				
				if($frequisicion){
					$this -> notificacion = "La requisición ha sido agregada a la factura.";
					$this -> tipo_notificacion = "success_message";
				}
				else{
					$this -> notificacion = "La requisición ya habia sido agregada anteriormente.";
					$this -> tipo_notificacion = "warning_message";
				}
			}
			else{
				$this -> notificacion = "Debes incluir el nombre del concepto de la requisición completo.";
				$this -> tipo_notificacion = "warning_message";
			}
			
			$this -> id = $factura -> id;
		}

		public function eliminarRequisicionFactura($factura){
			$this -> render("factura_requisiciones",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$factura = IobrasFacturarequisicion::consultar($factura);
			
			$this -> id = $factura -> factura_id;
			
			$factura -> delete();
			
			$this -> notificacion = "La requisición ha sido descartada correctamente de la factura.";
			$this -> tipo_notificacion = "success_message";
		}
		
		public function nominar(){
			$this -> render("nominas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> obra = Obra::consultar($this -> post("id"));
			
			$nomina = Nomina::registrar($this -> post("id"),formatoFechaDB($this -> post("fecha_inicio")),formatoFechaDB($this -> post("fecha_fin")));
			
			$this -> obra -> actualizarMontos();
		}
		
		public function nomina($nomina){
			$this -> render("nomina_desglozada",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> nomina = $nomina; 
		}
		
		public function aprobarNomina($nomina){
			$this -> render("nominas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$nomina = Nomina::consultar($nomina);
			
			$this -> obra = Obra::consultar($nomina -> obra_id);
			
			$nomina -> estado = "OK";
			$nomina -> save();
			
			$this -> obra -> actualizarMontos();
		}
		
		public function descartarTrabajador($id){
            $this -> render("verNomina",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
            
            $asistencia = NominaAsistencia::consultar($id);
            
            $this -> nomina = Nomina::consultar($asistencia -> nomina_id);
            
            $asistencia -> delete();
            
            $this -> nomina -> obra() -> actualizarMontos();
        }
        
        public function agregarTrabajador($id){
        	$this -> render("verNomina",null);
        	
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
        	
        	$this -> nomina = Nomina::consultar($id);
        	
        	$empleado = NominaEmpleados::buscar("nombre = '".$this -> post("empleado".$id)."'");
        	
        	$asistencia = NominaAsistencia::registrar($id,$empleado -> id,$this -> post("categoria".$id));
        	
        	$this -> nomina -> obra() -> actualizarMontos();
        }
        
        public function terminarNomina($nomina){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$nomina = Nomina::consultar($nomina);
			
			$nomina -> estado = "OK";
			$nomina -> save();
			
			$nomina -> obra() -> actualizarMontos();
			
			$this -> redirect("obras/consulta/".$nomina -> obra_id."/finanzas/nominas");
		}
		
		public function eliminarNomina($nomina){
			$this -> render("nominas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$nomina = Nomina::consultar($nomina);
			
			$this -> obra = Obra::consultar($nomina -> obra_id);
			
			$nomina -> delete();
			
			$nomina -> obra() -> actualizarMontos();
		}
		
		public function eliminarNominax($nomina){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$nomina = Nomina::consultar($nomina);
			
			$this -> obra = Obra::consultar($nomina -> obra_id);
			
			$nomina -> delete();
			
			$nomina -> obra() -> actualizarMontos();
			
			$this -> redirect("nominas/reporte/eliminada");
		}
		
		function modificarNomina(){
			$this -> render("verNomina",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
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
        }
		
		public function facturas($obra){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("facturas",null);
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function facturar(){
			$this -> render("facturas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> obra = Obra::consultar($this -> post("id"));
			
			$factura = Factura::registrar($this -> post("id"));
			
			$factura -> numero_factura = $this -> post("numero");
			$factura -> rfc_emisor = $this -> post("rfc");
			
			$factura -> nombre_emisor = $this -> post("emisor");
			$factura -> fecha_emision = formatoFechaDB($this -> post("fecha_emision"));
			$factura -> importe = quitarFormatoDinero($this -> post("importe"));
       		
       		if($_FILES["factura"]["name"]!=""){
       			$tmp = $_FILES["factura"]["name"];
                
	            $ext = "";
	        	
	        	for($i=0;$i<strlen($tmp);$i++){
	        		if($tmp[$i]=="."){
	        			$ext = "";
	        		}
	        		else{
	        			$ext .= $tmp[$i];
	        		}
	        	}
                
                $file = strtolower("factura".$factura -> id.".".$ext);
                
				$archivo = strtolower(APP_PATH."public/files/facturas_obras/".$file);

				$factura -> imagen = $file;

				move_uploaded_file($_FILES['factura']['tmp_name'], $archivo);
			}

			$factura -> save();
			
			$this -> obra -> actualizarMontos();
		}
		
		public function desfacturar($factura){
			$this -> render("facturas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$factura = Factura::consultar($factura);
			
			$this -> obra = Obra::consultar($factura -> obras_id);
			
			@unlink(APP_PATH."/public/files/facturas_obras/".$factura -> imagen);
			
			$conceptos = $factura -> conceptos();
			
			if($conceptos) foreach($conceptos as $concepto){
				$concepto -> delete();
			}
			
			$factura -> delete();
			
			$this -> obra -> actualizarMontos();
		}
		
		public function pagar($factura){
			$this -> render("facturas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$factura = Factura::consultar($factura);
			
			$this -> obra = Obra::consultar($factura -> obras_id);
			
			$factura -> estado = "PAGADA";
			$factura -> save();
			
			$this -> obra -> actualizarMontos();
		}
		
		public function despagar($factura){
			$this -> render("facturas",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$factura = Factura::consultar($factura);
			
			$this -> obra = Obra::consultar($factura -> obras_id);
			
			$factura -> estado = "NUEVA";
			$factura -> save();
			
			$this -> obra -> actualizarMontos();
		}
		
		public function consultaAvance($id){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
		
			$this -> avance = OAvance::consultar($id);
			
			$this -> mensaje = "";
		}
		
		public function subirFoto(){
			$this -> render("fotos",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
		
			$avance = OAvance::consultar($this -> post("id"));

       		$foto_obra = new IobrasFotosobras();

       		$foto_obra -> avance_id = $this -> post("id");

       		$foto_obra -> save();
       		
       		Load::lib("imagenes");

			if($_FILES["foto"]["name"]!=""){
				$file = strtolower($foto_obra -> id.substr($_FILES["foto"]["name"],strrpos($_FILES["foto"]["name"], ".")));
				$archivo = strtolower(APP_PATH."public/img/fotos_obras/".$file);

				$foto_obra -> foto = $file;
				$foto_obra -> save();

				move_uploaded_file($_FILES['foto']['tmp_name'], $archivo);
                
				Imagenes::crearThumb($archivo,"C");
				Imagenes::crearThumb($archivo,"M");  
			}

			$foto_obra -> save();
			
			$this -> avance = $avance;
		}
		
		//ESTA FUNCION ES TEMPORAL Y ES UTILIZADA PARA DIMENSIONAR IMAGENES QUE YA HABIAN SIDO CARGADAS
		public function dimensionador(){
			$this -> render(null,null);
			
			Load::lib("imagenes");
				
			$directorio = opendir(APP_PATH."public/img/fotos_obras/");
			
			$x = 0;
			 
			while ($archivo = readdir($directorio) && $x<10){
				if($archivo==".." || $archivo == "." || strpos($archivo, "c") === 0 || strpos($archivo, "m") === 0){
					continue;
				}
				
				$ruta = APP_PATH."public/img/fotos_obras/".$archivo;
				
				Imagenes::crearThumb($ruta,"C");
				Imagenes::crearThumb($ruta,"M");
				
				echo "$ruta<br>";
				
				$x++;
			}
			
			echo "<br><br><br>";
			
			$directorio = opendir(APP_PATH."public/img/fotos_obras/");
			
			while ($archivo = readdir($directorio)){
				echo "$archivo<br>";
			}
			 
			closedir($directorio); 
		}
		
		public function eliminarAvance($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
		
			$avance = OAvance::consultar($id);
			
			$fotos = $avance -> fotos();
			
			if($fotos) foreach($fotos as $foto){
				@unlink(strtolower(APP_PATH."public/img/fotos_obras/".$foto -> foto));
				@unlink(strtolower(APP_PATH."public/img/fotos_obras/c".$foto -> foto));
				
				$foto -> delete();
			}
			
			$obra = $avance -> obras_id;
			
			$avance -> delete();
			
			$this -> redirect("obras/consulta/".$obra."/avances");
		}
		
		public function modificarAvance(){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
		
			Load::lib("utilerias");
			
			$avance = OAvance::consultar($this -> post("id"));
			
			$avance -> porcentaje = $this -> post("porcentaje");
			$avance -> descripcion = $this -> post("descripcion");
			$avance -> fecha = formatoFechaDB($this -> post("fecha"));
			
			$avance -> save();
			
			$this -> redirect("obras/consultaAvance/".$avance -> id);
		}
		
		public function eliminarFoto($id){
			$this -> render("fotos",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$foto_obra = IobrasFotosobras::consultar($id);
			$avance = OAvance::consultar($foto_obra -> avance_id);
			
			@unlink(strtolower(APP_PATH."public/img/fotos_obras/".$foto_obra -> foto));
			@unlink(strtolower(APP_PATH."public/img/fotos_obras/c".$foto_obra -> foto));
			
			$foto_obra -> delete();
			
			$this -> avance = $avance;
		}
		
		public function descargar($id){
			$this -> render(null,null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$obra = Obra::consultar($id);
			
			$archivos = $obra -> archivos();
			
			Load::lib("pclzip");
			
			$nombre = $obra -> codigo.".zip";
			
			$directorio = substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"/"));
			$url = $directorio."/".$nombre;
	
			if(file_exists($url)){
				unlink($url);
			}
			
			$zip = new PclZip($nombre);
			
			if($archivos) foreach($archivos as $archivo){
				$zip -> add($directorio."/files/archivos_obras/".$archivo -> archivo,PCLZIP_OPT_REMOVE_ALL_PATH);
			}
			
			header ("Content-Disposition: attachment; filename=".$nombre."\n\n"); 
			header ("Content-Type: application/octet-stream");
			header ("Content-Length: ".filesize($url));
			readfile($url);
			
			unlink($url);
		}
		
		public function desglozada($factura){
			$this -> render("factura_desglozada",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> id = $factura; 
		}
		
		public function agregarConceptoFactura(){
			$this -> render("factura_desglozada",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> id = $this -> post("id");
			
			IobrasFacturasConceptos::registrar($this -> post("id"),$this -> post("cantidad"),$this -> post("concepto"),$this -> post("precio"),$this -> post("importe"));
			
			$this -> notificacion = "El concepto ha sido agregado correctamente.";
			
			$factura = Factura::consultar($this -> post("id"));
			
			$conceptos = $factura -> conceptos();
			
			$subtotal = 0;
			
			if($conceptos) foreach($conceptos as $concepto){
				$subtotal += $concepto -> importe;
			}
			
			$factura -> subtotal = $subtotal;
			$factura -> iva = $subtotal * 0.16;
			$factura -> importe = $subtotal + $subtotal * 0.16;
			
			$factura -> save();
		}
		
		public function eliminarConceptoFactura($id){
			$this -> render("factura_desglozada",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$concepto = IobrasFacturasConceptos::consultar($id);
			
			$this -> id = $concepto -> factura_id;

			$concepto -> delete();
			
			$this -> notificacion = "El concepto ha sido eliminado correctamente.";
			
			$factura = Factura::consultar($this -> id);
			
			$conceptos = $factura -> conceptos();
			
			$subtotal = 0;
			
			if($conceptos) foreach($conceptos as $concepto){
				$subtotal += $concepto -> importe;
			}
			
			$factura -> subtotal = $subtotal;
			$factura -> iva = $subtotal * 0.16;
			$factura -> importe = $subtotal + $subtotal * 0.16;
			
			$factura -> save();
		}
		
		public function beneficiarios($obra){
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> render("beneficiarios",null);
			
			$this -> obra = Obra::consultar($obra);
			$this -> beneficiado = false;
		}
		
		public function beneficiar(){
			$this -> render("beneficiarios",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$obra = Obra::consultar($this -> post("obra_id"));
			
			if($this -> post("id")>0){
				$beneficiario = IobrasBeneficiarios::consultar($this -> post("id"));	
				
				$beneficiario -> nombre_representante = $this -> post("nombre_representante");
				$beneficiario -> domicilio_representante = $this -> post("domicilio_representante");
				$beneficiario -> telefono_representante = $this -> post("telefono_representante");
				$beneficiario -> total = $obra -> pbeneficiarios;
				
				$beneficiario ->  save();
			}
			else{
				$beneficiario = IobrasBeneficiarios::registrar($obra -> id,$this -> post("nombre_representante"),$this -> post("domicilio_representante"),$this -> post("telefono_representante"),$obra -> pbeneficiarios);
			}
			
			$this -> obra = $obra;
			$this -> beneficiado = true;
		}

		public function aportar(){
			$this -> render("beneficiarios",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			Load::lib("formato");
			
			$obra = Obra::consultar($this -> post("obra_id"));
			$beneficiario = IobrasBeneficiarios::consultar($this -> post("id"));
			
			echo Formato::fechaDB($this -> post("fecha_aportacion"));
			
			$beneficiario -> agregarAportacion(Formato::fechaDB($this -> post("fecha_aportacion")),$this -> post("concepto_aportacion"),$pfederal = quitarFormatoDinero($this -> post("monto_aportacion")));
			
			$this -> obra = $obra;
			$this -> beneficiado = false;
		}
		
		public function desaportar($id){
			$this -> render("beneficiarios",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$aportacion = IobrasAportaciones::consultar($id);
			$beneficiario = $aportacion -> beneficiarios();

			$beneficiario -> quitarAportacion($id);
			
			$this -> obra = $beneficiario -> obra();
			$this -> beneficiado = false;
		}
		
		public function fichas(){						
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			Load::lib("utilerias");
			
			$this -> Obras = Obra::reporte('id > 0','id ASC',0,1000);
			$this -> inicio = Obra::buscar('id > 0 order by id ASC') -> id;
			$this -> fin = Obra::buscar('id > 0 order by id DESC') -> id;
			
		}
		
		public function incidencias($obra){
			$this -> render("incidencias",null);
			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function registrarIncidencia(){			
			$this -> render("incidencias",null);
			Load::lib("formato");
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			
			$incidencia = IobrasIncidencias::registrar($this -> post('obra_id'),$this -> post('empleado_id'),$this -> post('descripcion'),Formato::fechaDB($this -> post('fecha')));
			$this -> obra = Obra::consultar($this -> post('obra_id'));
		}
		
		public function eliminarIncidencia($id){
			$this -> render("incidencias",null);			
			Load::lib("aclx"); if(!Acl::validarAcceso($this -> controller_name,$this -> action_name)){ $this -> redirect(""); return; }
			$incidencia = IobrasIncidencias::consultar($id);
			$this -> obra = Obra::consultar($incidencia -> obras_id);
			$incidencia -> eliminar();
		}
	}