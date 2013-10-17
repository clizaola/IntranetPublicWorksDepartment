<?php
    class Proyecto extends IobrasProyectos{
        
    }
    
	class IobrasProyectos extends ActiveRecord{
        public static function registrar($solicitud,$codigo,$nombre, $alcance, $localidad, $descripcion, $objetivo, $tipo, $supervisor, $costo){
    		$proyecto = new IobrasProyectos();

            if($solicitud=="") $solicitud = "0";
            $proyecto -> solicitudes_id = $solicitud;
            
            $proyecto -> nombre = $nombre;
    		$proyecto -> codigo = $codigo;
            $proyecto -> tipoobra_id = $tipo;
            
            if($alcance=="") $alcance = "LMR";
            $proyecto -> alcance = $alcance;
            
    		$proyecto -> localidades_id = $localidad;
    		$proyecto -> descripcion = $descripcion;
            
    		$proyecto -> objetivo = $objetivo;
            
    		$proyecto -> supervisores_id = $supervisor;

    		if($costo=="") $costo = "0.0";            
            
    		$proyecto -> costo = $costo;

    		$proyecto -> estado = "NUEVO";

    		$proyecto -> fecha = date("Y-m-d",time());

    		$proyecto -> save();
			
			$proyecto -> generarMapa();

    		return $proyecto;
    	}
        
        public function modificar($codigo,$nombre, $alcance, $localidad, $descripcion, $objetivo, $tipo, $supervisor, $costo){
            $this -> nombre = $nombre;
    		$this -> codigo = $codigo;
            $this -> tipoobra_id = $tipo;
            
            if($alcance=="") $alcance = ".";
            $this -> alcance = $alcance;
            
    		$this -> localidades_id = $localidad;
    		$this -> descripcion = $descripcion;
            
            if($objetivo=="") $objetivo = ".";
    		$this -> objetivo = $objetivo;
            
    		$this -> supervisores_id = $supervisor;

    		if($costo=="") $costo = "0.0";            
            
    		$this -> costo = $costo;
            
            if($this -> estado == "") $this -> estado = "NUEVO";
            if($this -> acta_cabildo == "") $this -> acta_cabildo = ".";
            if($this -> cabildo_id == 0) $this -> cabildo_id = -1;
			
			//SI EL PROYECTO ES NUEVO CAMBIARLO A EN PROCESO
			if($this -> estado == "NUEVO"){
				$this -> estado = "EN PROCESO";
			}

    		$this -> save();
			
			$this -> generarMapa();
    	}

    	public static function consultar($id){
    		$proyecto = new IobrasProyectos();

    		$proyecto = $proyecto -> find($id);

    		return $proyecto;
    	}

    	public static function consultarSolicitud($id){
    		$proyecto = new IobrasProyectos();

    		$proyecto = $proyecto -> find_first("solicitudes_id=".$id);

    		return $proyecto;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasProyectos();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasProyectos();

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
		
		public function solicitud(){
    		return IobrasSolicitudes::consultar($this -> solicitudes_id);
    	}
        
        public function localidad(){
    		return IobrasLocalidades::consultar($this -> localidades_id);
    	}

    	public function supervisor(){
    		return IobrasEmpleados::consultar($this -> supervisores_id);
    	}
        
        public function tipoobra(){
    		return IobrasTipoobras::consultar($this -> tipoobra_id);
    	}

    	public function programas(){
    		return IobrasProgramasproyectos::reporte("proyectos_id=".$this -> id);
    	}

    	public function archivos(){
    		return IobrasArchivosproyectos::reporte("proyectos_id=".$this -> id);
    	}

    	public function contratistas(){
    		return IobrasContratistasproyectos::reporte("proyectos_id=".$this -> id);
    	}
        
        public function tareas(){
    		return IobrasTareasproyectos::reporte("proyectos_id=".$this -> id);
    	}

    	public function avances(){
    		return IobrasAvancesproyectos::reporte("proyectos_id=".$this -> id." ORDER BY porcentaje ASC");
    	}
        
		public function porcentaje(){
            $avances = IobrasAvancesproyectos::reporte("proyectos_id=".$this -> id." ORDER BY porcentaje DESC");
            
            if($avances){
                foreach($avances as $avance){
                    return $avance -> porcentaje;
                }
            }
            
            return 0;
        }	
		
        public function iniciarObra(){
            $obra = Obra::registrar($this -> id, $this -> nombre, "O".date("Y")."-XXXX-".formatoNumero($this -> localidades_id,2), $this -> tipoobra_id, $this -> alcance, $this -> localidades_id, $this -> descripcion, $this -> objetivo, $this -> supervisor, 0, $this -> costo,0,0);
            
            $obra -> codigo = str_replace("XXXX",formatoNumero($obra -> id,4),$obra -> codigo);
            
            $obra -> pfederal = $this -> pfederal;
            $obra -> pestatal = $this -> pestatal;
            $obra -> pmunicipal = $this -> pmunicipal;
            $obra -> pbeneficiarios = $this -> pbeneficiarios;
            
            $obra -> asignado = $this -> pfederal+$this -> pestatal+$this -> pmunicipal+$this -> pbeneficiarios;

            $obra -> cabildo_id = $this -> cabildo_id;
            $obra -> acta_cabildo = $this -> acta_cabildo;
            
            $obra -> georeferencia = $this -> georeferencia;

            $obra -> save();
            
            copy("public/img/".$this -> imagen,"public/img/".str_replace("proyecto","obra",$this -> imagen));
            
            $obra -> imagen = str_replace("proyecto","obra",$this -> imagen);
            $obra -> save();
                        
            $this -> estado = "EN OBRA";
            $this -> save();
            
            return $obra;
        }
        
        public static function listado($codigo='',$alcance=0,$tipoObra=0,$supervisor=0, $programa=0, $estado="", $contratista=0){
            //validamos el campo de codigo
            if($codigo == ''){
                $codigo = '';
            }else{
                $codigo = " codigo like '".$codigo."' AND ";
            }
            //validamos el campo de alcance
            if($alcance == 'T'){
                $alcance = '';
            }else{
                switch($alcance){
                    case 'L':   $alcance = " alcance like 'LXX' AND "; 
                                break;
                    case 'M':   $alcance = " alcance like 'XMX' AND "; 
                                break;
                    case 'R':   $alcance = " alcance like 'XXR' AND "; 
                                break;
                }
                
            }
            //validamos el campo de tipo de obra
            if($tipoObra == 0){
                $tipoObra = '';
            }else{
                $tipoObra = " tipoObra_id = ".$tipoObra." AND ";
            }
            //validamos el campo de supervisores
            if($supervisor == 0){
                $supervisor = '';
            }else{
                $supervisor = " supervisores_id = ".$supervisor." AND ";
            }
            
            //validamos el campo de supervisores
            if($estado == ""){
                $estado = '';
            }else{
                $estado = " estado = '".$estado."' AND ";
            }
                        
            if($codigo == '' && $alcance == '' && $tipoObra == '' && $supervisor  == '' && $estado  == '' ){
                $sql ='';
            }else{
                $sql = $codigo.$alcance.$tipoObra.$supervisor.$estado;
            }
            $Proyectos = new IobrasProyectos();            
            if($proyectos = $Proyectos -> find($sql.' id >0')){
                if($programa!=0 || $contratista!=0){
                    $finales = array();
                    foreach($proyectos as $proyecto){
                        if($programa!=0){
                            if(IobrasProgramasproyectos::total("proyectos_id=".$proyecto -> id." AND programas_id=".$programa)==0){
                                continue;
                            }
                        }
                        
                        if($contratista!=0){
                            if(IobrasContratistasproyectos::total("proyectos_id=".$proyecto -> id." AND contratistas_id=".$contratista)==0){
                                continue;
                            }       
                        }
                        
                        $finales[$proyecto -> id] = $proyecto;
                    }
                    
                    return $finales;
                }
                else{
                    return $proyectos;    
                }   
            }else{
                return NULL;
            }            
        }

		public function generarMapa(){
			$posicion = explode(",",$this -> georeferencia);
			
			$x = trim($posicion[0]);
			$y = trim($posicion[1]);
			
			Load::lib("imagenes");
			
			$ruta = APP_PATH."public/img/mapas_proyectos/".$this -> id.".png";
			
			Imagenes::generarMapaGoogle($x,$y,$ruta,15);
		}
	}
?>