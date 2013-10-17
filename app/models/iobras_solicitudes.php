<?php
    class Solicitud extends IobrasSolicitudes{
        
    }

	class IobrasSolicitudes extends ActiveRecord{
    	public static function registrar($nombre,$codigo,$medio,$descripcion, $localizacion, $localidad, $tipo, $emisor, $receptor, $solicitante, $direccion, $telefono, $celular, $correo){
    		$objeto = new IobrasSolicitudes(); 

    		$objeto -> nombre = $nombre;
            $objeto -> codigo = $codigo;
            $objeto -> medio = $medio;
    		$objeto -> descripcion = $descripcion;
            
            $objeto -> localizacion = $localizacion;
            $objeto -> localidades_id = $localidad;
            $objeto -> tiposobra_id = $tipo;
            $objeto -> emisor = $emisor;
    		$objeto -> receptor = $receptor;
            
    		$objeto -> solicitante_nombre = $solicitante;
    		$objeto -> solicitante_direccion = $direccion;
    		$objeto -> solicitante_telefono = $telefono;
            $objeto -> solicitante_celular = $celular;
    		$objeto -> solicitante_correo = $correo;

    		$objeto -> fecha = date("Y-m-d",time());
    		$objeto -> agenda = date("Y-m-d",time()+60*60*24*7);
    		
    		$objeto -> fecha_in = date("Y-m-d",time());
    		$objeto -> fecha_at = date("Y-m-d",time());
    		
    		$objeto -> estado = "NUEVA";

    		$objeto -> save();

    		return $objeto;
    	}

    	public function asignarSolicitante($nombre, $direccion, $telefono, $correo){
    		$this -> solicitante_nombre = $nombre;
    		$this -> solicitante_direccion = $direccion;
    		$this -> solicitante_telefono = $telefono;
    		$this -> solicitante_correo = $correo;

    		$this -> save();
    	}
        
        public function asignarLocalidadTipo($localidad, $tipo){
    		$this -> localidades_id = $localidad;
    		$this -> tiposobra_id = $tipo;

    		$this -> save();
    	}
        
        public function modificar($nombre,$codigo,$medio,$descripcion, $localizacion, $localidad, $tipo, $emisor, $receptor, $solicitante, $direccion, $telefono, $celular, $correo){
    		$this -> nombre = $nombre;
            $this -> codigo = $codigo;
            $this -> medio = $medio;
    		$this -> descripcion = $descripcion;
            
            $this -> localizacion = $localizacion;
            $this -> localidades_id = $localidad;
    		$this -> tiposobra_id = $tipo;
            $this -> emisor = $emisor;
    		$this -> receptor = $receptor;
            
    		$this -> solicitante_nombre = $solicitante;
    		$this -> solicitante_direccion = $direccion;
    		$this -> solicitante_telefono = $telefono;
            $this -> solicitante_celular = $celular;
    		$this -> solicitante_correo = $correo;

    		$this -> save();
    	}

    	public static function consultar($id){
    		$objeto = new IobrasSolicitudes();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasSolicitudes();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasSolicitudes();

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
        
        public function archivos(){
    		return IobrasArchivossolicitud::reporte("solicitud_id=".$this -> id);
    	}
    	
    	public function localidad(){
    		return IobrasLocalidades::consultar($this -> localidades_id);
    	}
		
		public function tipoObra(){
    		return IobrasTipoobras::consultar($this -> tiposobra_id);
    	}
		
		public function responsable(){
    		return IobrasEmpleados::consultar($this -> responsable_id);
    	}
	}
?>