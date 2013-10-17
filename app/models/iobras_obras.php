<?php
    class Obra extends IobrasObras{
        
    }
    
	class IobrasObras extends ActiveRecord{		
        public static function registrar($proyecto,$codigo,$nombre, $alcance, $localidad, $descripcion, $objetivo, $tipo, $supervisor, $presupuestado){
    		$obra = new IobrasObras();

            if($proyecto=="") $proyecto = "0";
            $obra -> proyectos_id = $proyecto;
            
            $obra -> nombre = $nombre;
    		$obra -> codigo = $codigo;
            $obra -> tipoobra_id = $tipo;
            
            if($alcance=="") $alcance = "LMR";
            $obra -> alcance = $alcance;
            
    		$obra -> localidades_id = $localidad;
    		$obra -> descripcion = $descripcion;
            
    		$obra -> objetivo = $objetivo;
            
    		$obra -> supervisores_id = $supervisor;

    		if($presupuestado=="") $presupuestado = "0.0";            
            
    		$obra -> presupuestado = $presupuestado;

    		$obra -> estado = "NUEVA";

    		$obra -> fecha = date("Y-m-d",time());
    		$obra -> fecha_at = date("Y-m-d",time());
    		$obra -> fecha_in = date("Y-m-d",time());

    		$obra -> save();
			
			$obra -> generarMapa();

    		return $obra;
    	}
        
        public function modificar($codigo,$nombre, $alcance, $localidad, $descripcion, $objetivo, $tipo, $supervisor, $presupuestado){
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

    		if($presupuestado=="") $presupuestado = "0.0";            
            
    		$this -> presupuestado = $presupuestado;
            
            if($this -> estado == "") $this -> estado = "NUEVO";
            if($this -> acta_cabildo == "") $this -> acta_cabildo = ".";
            if($this -> cabildo_id == 0) $this -> cabildo_id = -1;

    		//SI LA OBRA ES NUEVA CAMBIARLA A EN PROCESO
			if($this -> estado == "NUEVO"){
				$this -> estado = "EN PROCESO";
			}
			
			$this -> save();
			
			$this -> generarMapa();
    	}

    	public static function consultar($id){
    		$obra = new IobrasObras();

    		$obra = $obra -> find($id);

    		return $obra;
    	}
		
		public static function buscar($id){
			$objeto = new IobrasObras();							
			if($objeto -> count($id)==0){
				return false;
			}
			
			return $objeto -> find_first($id);
		}

    	public static function consultarProyecto($id){
    		$obra = new IobrasObras();

    		$obra = $obra -> find_first("proyectos_id=".$id);

    		return $obra;
    	}
		
		public function proyecto(){
    		return Proyecto::consultar($this -> proyectos_id);
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasObras();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasObras();

    		if($sql == ""){
    			$objeto = $objeto -> count();
    		}
            else{
	    		$objeto = $objeto -> count($sql);
    		}

    		return $objeto;
    	}
        
	public function supervisor(){
    		return IobrasEmpleados::consultar($this -> supervisores_id);
    	}

    	public function tipoobra(){
    		return IobrasTipoobras::consultar($this -> tipoobra_id);
    	}
        
        public function localidad(){
            return Localidad::consultar($this -> localidades_id);
        }
        
		public function contratista(){
    		$contratistas = IobrasContratistasobras::reporte("obras_id=".$this -> id);
            
            if($contratistas){
                foreach($contratistas as $contratista){
                    $regresa =  Contratista::consultar($contratista -> contratistas_id);
                }
				return $regresa;
            }
            
            return 'Sin Contratista';
    	}

    	public function archivos(){
    		return IobrasArchivosobras::reporte("obras_id=".$this -> id);
    	}

    	public function contratistas(){
    	   return IobrasContratistasobras::reporte("obras_id=".$this -> id);
    	}
        
        public function programas(){
    		return IobrasProgramasobras::reporte("obras_id=".$this -> id);
    	}
        
        public function programa(){
    		$programas = IobrasProgramasobras::reporte("obras_id=".$this -> id);
            
            if($programas){
                foreach($programas as $programa){
                    return Programa::consultar($programa -> programas_id);
                }
            }
            
            return $programas;
    	}
		
		public function programa2(){
    		$programas = IobrasProgramasobras::reporte("obras_id=".$this -> id);
            
            if($programas){
                foreach($programas as $programa){
                    $regresa =  Programa::consultar($programa -> programas_id) -> nombre.' ';
                }
				return $regresa;
            }
            
            return 'Sin Programa';
    	}
		
		public function contratista2(){
    		$contratistas = IobrasContratistasobras::reporte("obras_id=".$this -> id);
            
            if($contratistas){
                foreach($contratistas as $contratista){
                    $regresa =  Contratista::consultar($contratista -> contratistas_id) -> nombre.' ';
                }
				return $regresa;
            }
            
            return 'Sin Contratista';
    	}
        
        public function tareas(){
    		return IobrasTareasobras::reporte("obras_id=".$this -> id);
    	}

    	public function conceptos(){
    		return IobrasConceptosobra::reporte("obras_id=".$this -> id);
    	}
        
        public function insumos(){
    		return IobrasInsumosobra::reporte("obras_id=".$this -> id);
    	}
    	
    	public function productos(){
    		$insumos = IobrasInsumosobra::reporte("obras_id=".$this -> id);
    		
    		$productos = array();
    		
    		if($insumos) foreach($insumos as $insumo){
    			$productos[$insumo -> productos_id] = Insumo::consultar($insumo -> productos_id);
    		}
    		
    		return $productos;
    	}
        
        public function requisiciones(){
    		return IobrasRequisiciones::reporte("obras_id=".$this -> id,"fecha ASC");
    	}
    	
    	public function nominas(){
    		return IobrasNomina::reporte("obra_id=".$this -> id,"fecha_inicio ASC");
    	}
    	
    	public function nominasAprobadas(){
    		return IobrasNomina::reporte("obra_id=".$this -> id." AND estado='OK'","fecha_inicio ASC");
    	}
    	
    	public function facturas(){
    		return IobrasFacturas::reporte("obras_id=".$this -> id,"fecha_emision ASC");
    	}
    	
    	public function facturasPagadas(){
    		return IobrasFacturas::reporte("obras_id=".$this -> id." AND estado='PAGADA'");
    	}
    	
    	public function actualizarMontos(){
    		$facturas = $this -> facturas(); 
    		$pagadas = $this -> facturasPagadas();
    		
    		$facturado = 0;
    		$pagado = 0;
    		
    		if($facturas) foreach($facturas as $factura){
    			$facturado += $factura -> importe;
    		}
    		
    		if($pagadas) foreach($pagadas as $pagada){
    			$pagado += $pagada -> importe;
    		}
    		
    		//Sacar totales de las nominas...
    		
    		$nominas = $this -> nominas(); 
    		$aprobadas = $this -> nominasAprobadas();
    		
    		if($nominas) foreach($nominas as $nomina){
    			
    			$facturado += $nomina -> importe();
    		}
    		
    		if($aprobadas) foreach($aprobadas as $aprobada){
    			$pagado += $aprobada -> importe();
    		}
    		
    		$this -> asignado = $facturado;
    		$this -> ejecutado = $pagado;
    		
    		//SI LA OBRA ES NUEVA CAMBIARLA A EN PROCESO
			if($this -> estado == "NUEVO"){
				$this -> estado = "EN PROCESO";
			}
			
			$this -> save();
    	}
        
        public function requisicionesAutorizadas(){
    		return IobrasRequisiciones::reporte("obras_id=".$this -> id." AND estado!='NUEVA'");
    	}
        
        public function porcentaje(){
            $avances = IobrasAvancesobras::reporte("obras_id=".$this -> id,"porcentaje DESC");
            
            if($avances){
                foreach($avances as $avance){
                    return $avance -> porcentaje;
                }
            }
            
            return 0;
        }
        
        public function materialSolicitado($producto){
            $total = 0;
            
            $requisiciones = $this -> requisicionesAutorizadas();
            
            if($requisiciones) foreach($requisiciones as $requisicion){
                $conceptos = $requisicion -> conceptos();
                
                if($conceptos) foreach($conceptos as $concepto){
                    if($concepto -> productos_id == $producto){
                        $total += $concepto -> cantidad;
                    }
                }
            }
            
            return $total;
        }
        
        public function materialAsignado($producto){
            $total = 0;
            
            $requisiciones = $this -> requisicionesAutorizadas();
            
            if($requisiciones) foreach($requisiciones as $requisicion){
                $conceptos = $requisicion -> conceptos();
                
                if($conceptos) foreach($conceptos as $concepto){
                    if($concepto -> productos_id == $producto){
                        $total += $concepto -> asignado;
                    }
                }
            }
            
            return $total;
        }
        
        public function materialUtilizado($producto){
            $total = 0;
            
            $requisiciones = $this -> requisicionesAutorizadas();
            
            if($requisiciones) foreach($requisiciones as $requisicion){
                $conceptos = $requisicion -> conceptos();
                
                if($conceptos) foreach($conceptos as $concepto){
                    if($concepto -> productos_id == $producto){
                        $total += $concepto -> utilizado;
                    }
                }
            }
            
            return $total;
        }
        
        public function avances(){
    		return IobrasAvancesobras::reporte("obras_id=".$this -> id,"fecha ASC");
    	}
		
		public function incidencias(){
    		return IobrasIncidencias::reporte("obras_id=".$this -> id,"fecha ASC");
    	}
        
        public function tieneAvances(){
    		$x = IobrasAvancesobras::totalAvances($this -> id);
            if($x>0) return $x;
            return false;
    	}
        
        public function agregarContratista($contratista){
            IobrasContratistasobras::registrar($this -> id, $contratista);   
        }
        
        public function agregarPrograma($programa){
            IobrasProgramasobras::registrar($this -> id, $programa);    
        }
        
        public function actualizarImagen($imagen){
            $this -> imagen = $imagen;
            //SI LA OBRA ES NUEVA CAMBIARLA A EN PROCESO
			if($this -> estado == "NUEVO"){
				$this -> estado = "EN PROCESO";
			}
			
			$this -> save();
        }
        
		public function beneficiarios(){
			$beneficiario = IobrasBeneficiarios::consultar("obra_id=".$this -> id);
			
			if(!$beneficiario){
				$beneficiario = new IobrasBeneficiarios(); 
			}
			
			return $beneficiario;
		}
		
		public function aportaciones(){
			$beneficiario = IobrasBeneficiarios::consultar("obra_id=".$this -> id);
			
			if(!$beneficiario){
				return null;
			}
			
			return $beneficiario -> aportaciones();
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
                if($estado == "EN OBRA")
                    $estado = '';
                else{
                    if($estado != "PROCESO") $estado = str_replace("O","A",$estado);
                    $estado = " estado = '".$estado."' AND ";
                }
            }
                        
            if($codigo == '' && $alcance == '' && $tipoObra == '' && $supervisor  == '' && $estado  == ''){
                $sql ='';
            }else{
                $sql = $codigo.$alcance.$tipoObra.$supervisor.$estado;
            }
            
            $Obras = new IobrasObras();       
            if($obras = $Obras -> find($sql.' id >0')){
                if($programa!=0 || $contratista!=0){
                    $finales = array();
                    foreach($obras as $obra){
                        if($programa!=0){
                            if(IobrasProgramasobras::total("obras_id=".$obra -> id." AND programas_id=".$programa)>0){
                                continue;
                            }    
                        }
                        
                        if($contratista!=0){
                            if(IobrasContratistasobras::total("obras_id=".$obra -> id." AND contratistas_id=".$contratista)==0){
                                continue;
                            }       
                        }
                        
                        $finales[$obra -> id] = $obra;
                    }
                    
                    return $finales;
                }
                else{
                    return $obras;    
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
			
			$ruta = APP_PATH."public/img/mapas_obras/".$this -> id.".png";
			
			Imagenes::generarMapaGoogle($x,$y,$ruta,15);
		}
		
	}
?>