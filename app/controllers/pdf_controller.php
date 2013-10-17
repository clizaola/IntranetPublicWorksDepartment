<?php
	//Load::lib("pdfs");
	//Load::lib("utilerias");

	class PdfController extends ApplicationController {
		public function grafica(){
			$this -> render(null,null);
			$this -> set_response("view");
			
			Load::lib("pdf");
			
			$pdf = new Pdf();
			$pdf -> controlador("pdf/graficaPDF");
			$pdf -> renderizar();
		}
		
		public function graficaPDF(){
			$this -> render("graficaPDF",null);
		}
		
		public function solicitud($solicitud = 1){
			$this -> render(null,null);
			$this -> set_response("view");
			
			Load::lib("pdf");
			
			$pdf = new Pdf();
			$pdf -> controlador("pdf/solicitudPDF/".$solicitud);
			$pdf -> renderizar();
		}
		
		public function solicitudPDF($solicitud = 1){
			$this -> render("solicitudPDF",null);
			
			$this -> solicitud = Solicitud::consultar($solicitud);
		}	
		
		public function proyecto($proyecto = 1){
			$this -> render(null,null);
			$this -> set_response("view");
			
			Load::lib("pdf");
			
			$pdf = new Pdf();
			$pdf -> controlador("pdf/proyectoPDF/".$proyecto);
			$pdf -> renderizar();
		}
		
		function recibe_imagen ($url_origen,$archivo_destino){  
				$mi_curl = curl_init ($url_origen);  
				$fs_archivo = fopen ($archivo_destino, "w");  
				curl_setopt ($mi_curl, CURLOPT_FILE, $fs_archivo);  
				curl_setopt ($mi_curl, CURLOPT_HEADER, 0);  
				curl_exec ($mi_curl);  
				curl_close ($mi_curl);  
				fclose ($fs_archivo);  
		}
		
		public function proyectoPDF($proyecto = 1){
			$this -> render("proyectoPDF",null);
			//$this -> render(null,null);
			$this -> proyecto = Proyecto::consultar($proyecto);
			//$this -> recibe_imagen("http://maps.google.com/maps/api/staticmap?center=20.532475105879225,-104.04152512550354&zoom=14&size=400x400&sensor=false",APP_PATH."img/imagenes_proyecto/mapa/".$this -> proyecto.".jpg");			
		}
		
		public function obra($obra = 1){
			$this -> render(null,null);
			$this -> set_response("view");
			
			Load::lib("pdf");
			
			$pdf = new Pdf();			
			$pdf -> controlador("pdf/obraPDF/".$obra);			
			$pdf -> renderizar();
		}
		
		public function obraPDF($obra = 1){
			$this -> render("obraPDF",null);
			
			$this -> obra = Obra::consultar($obra);
		}
		
		public function obraDescriptiva($obra = 0){
			$this -> render(null,null);
			$this -> set_response("view");
			
			Load::lib("pdf");
			
			$pdf = new Pdf();
			if($obra == 0){
				if($this -> has_post('inicio') &&  $this -> has_post('fin')){
					for($i = $this -> post('inicio');$i <= $this -> post('fin');$i++){
						if($this -> has_post($i)){						
							$obra .= $this -> post($i).'-'; 	
						}
						$this -> post($i).'-';
					}
					
					$obra = substr($obra,0,-1);
				}
			}			
			$pdf -> controlador("pdf/obraDescriptivaPDF/".$obra);			
			$pdf -> renderizar();
		}
		
		public function obraDescriptivaPDF($obra){
				$this -> render("obraDescriptivaPDF",null);
				$pos = strpos($obra,'-');
				if($pos) 
					$this -> obras = explode('-',$obra);
				else
					$this -> obras[0] = Obra::consultar($obra) -> id;
		}
		
		public function avanceObra($obra= 0,$avance = 0){
			$this -> render(null,null);
			$this -> set_response("view");
			
			Load::lib("pdf");
			
			$pdf = new Pdf();			
			$pdf -> controlador("pdf/avanceObraPDF/".$obra."/".$avance);			
			$pdf -> renderizar();
		}
		
		public function avanceObraPDF($obra=0,$avance = 0){
			$this -> render("avanceObraPDF",null);
			if($avance == 0){
				$avance = 'id>0 and obras_id = '.$obra;
			}else{
				$avance = 'id = '.$avance;
			}
			$this -> avances = AvanceObra::reporte($avance);
			$this -> obra = Obra::consultar($obra);
			
		}
		
		public function requisicion($requisicion = 1){
			$this -> render(null,null);
			$this -> set_response("view");
			
			Load::lib("pdf");
			
			$pdf = new Pdf();			
			$pdf -> controlador("pdf/requisicionPDF/".$requisicion);			
			$pdf -> renderizar();
		}
		
		public function requisicionPDF($requisicion = 1){
			$this -> render("requisicionPDF",null);
			//$this -> render(null,null);
			$this -> requisicion = IobrasRequisiciones::consultar($requisicion);
			$this -> obra = Obra::consultar($this -> requisicion -> obras_id);
			
		}
		
		public function tareas($usuario_id = 0,$tipo = 't',$tarea = 0){
			$this -> render(null,null);
			$this -> set_response("view");
			
			Load::lib("pdf");
			
			$pdf = new Pdf();			
			if($usuario_id == 0){
				$usuario_id = Session::get("usuario_id");			
			}
			$pdf -> controlador("pdf/tareasPDF/".$usuario_id.'/'.$tipo.'/'.$tarea);			
			$pdf -> renderizar();
		}
		
		public function tareasPDF($usuario_id = 0,$tipo = 't',$tarea = 0){
			$this -> render("tareasPDF",null);									
			$this -> empleado = IobrasEmpleados::consultar(Usuario::consultar($usuario_id) -> empleado_id);
			$this -> tarea_sola = '';
			$sql = 'id > 0';
			if($tarea != 0)
				$sql = 'id = '.$tarea;
				
			echo $tipo;
			$this -> Otareas = $this -> Ptareas = NULL;
			if($tipo == 'p'){
				$this -> Ptareas = PTarea::reporte($sql." and empleado_id = ".$this -> empleado -> id);
				if($tarea != 0 ) $this -> tarea_sola = PTarea::buscar($sql." and empleado_id = ".$this -> empleado -> id) -> nombre;
			}elseif( $tipo == 'o'){
				$this -> Otareas = OTarea::reporte($sql." and estado = 'KO' and empleado_id = ".$this -> empleado -> id);
				if($tarea != 0 ) $this -> tarea_sola = OTarea::buscar($sql." and estado = 'KO' and empleado_id = ".$this -> empleado -> id) -> nombre;
			}else{
				$this -> tarea_sola = '';				
				$this -> Ptareas = PTarea::reporte("id > 0 and estado = 'KO' and empleado_id = ".$this -> empleado -> id,'limite ASC');
				$this -> Otareas = OTarea::reporte("id > 0 and estado = 'KO' and empleado_id = ".$this -> empleado -> id,'limite ASC');			
			}
		}
		
		public function nomina($nomina){
			$this -> render(null,null);
			$this -> set_response("view");
			
			Load::lib("pdf");
			
			$pdf = new Pdf();			
			$pdf -> controlador("pdf/nominaPDF/".$nomina);			
			$pdf -> renderizar();
		}
		
		public function nominaPDF($nomina){
			$this -> render("nominaPDF",null);
			
			$this -> nomina = Nomina::consultar($nomina);
			$this -> obra = $this -> nomina -> obra();
		}
	}
	
?>