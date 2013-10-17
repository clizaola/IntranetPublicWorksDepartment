<?php
    class Dependencia extends IobrasDependencias{
        
    }

	class IobrasDependencias extends ActiveRecord{
		public static function registrar($nombre,$gobierno="ESTATAL",$nombre_encargado,$apellidos_encargado,$email_encargado,$cargo_encargado){
    		if($nombre == "" || $gobierno == ""){
  		        return null;
    		}
            
            if(Dependencia::total("organizacion='".$nombre."'")>0){
                return null;
            }
            
            $objeto = new IobrasDependencias();
    		
            $objeto -> organizacion = $nombre;
            $objeto -> gobierno = $gobierno;
            
            $objeto -> nombre_encargado = $nombre_encargado;
            $objeto -> apellidos_encargado = $apellidos_encargado;
            $objeto -> email_encargado = $email_encargado;
            $objeto -> cargo_encargado = $cargo_encargado;
            
            $objeto -> save();

			return $objeto;
    	}
        
        public function modificar($nombre,$gobierno="ESTATAL",$nombre_encargado,$apellidos_encargado,$email_encargado,$cargo_encargado){
    		$this -> organizacion = $nombre;
            $this -> gobierno = $gobierno;
            
            $this -> nombre_encargado = $nombre_encargado;
            $this -> apellidos_encargado = $apellidos_encargado;
            $this -> email_encargado = $email_encargado;
            $this -> cargo_encargado = $cargo_encargado;
            
            $this -> save();
    	}
        
        public function modificarDireccion($direccion, $ciudad, $estado){
            $this -> direccion = $direccion;
            $this -> ciudad = $ciudad;
            $this -> estado = $estado;
            
            $this -> save(); 
        }
        
        public function modificarTelefonos($trabajo, $movil){
            $this -> telefono_oficina = $trabajo;
            $this -> telefono_movil = $movil;
            
            $this -> save(); 
        }
        
        public function modificarWeb($web){
            $this -> pagina_web = $web;
            $this -> save(); 
        }
        
        public function modificarNotas($notas){
            $this -> notas = $notas;
            $this -> save(); 
        }

    	public static function consultar($id){
    		$objeto = new IobrasDependencias();
    		$objeto = $objeto -> find($id);

    		return $objeto;
    	}

    	public static function reporte($sql="id>0", $orden="id ASC", $inicio=0, $cantidad=0){
            $objeto = new IobrasDependencias();
            
            $sql .= " ORDER BY ".$orden;
            
            if($cantidad>0){
                $sql .= " LIMIT ".$inicio.",".$cantidad;
            }
            
            return $objeto -> find($sql);
        }
        
        public static function total($sql=""){
    		$objeto = new IobrasDependencias();

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
        
	}
?>