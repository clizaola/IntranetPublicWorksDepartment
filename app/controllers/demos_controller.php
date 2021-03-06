<?php
	class DemosController extends ApplicationController 
	{
		
		public function pdf(){
			$this -> render(null,null);
			$this -> set_response("view");
			
			try
			{
			    $html2pdf = new HTML2PDF('P', 'A4', 'es');
			    $html2pdf->setDefaultFont('Arial');
			    $html2pdf->writeHTML(AUTOR_NOMBRE);
			    $html2pdf->Output('exemple00.pdf');
			}
			catch(HTML2PDF_exception $e) {
			    echo $e;
			    exit;
			}
		}	
		
		public function graficas(){
			$this -> render("graficas",null);
		}
		
		public function index(){
			
		}
		
		public function fancybox(){ 
				
		}

		public function stars(){
			
		}
		
		public function votar(){
			$this -> render(null,null);
			
			//CONTABILIZAR VOTO
		}
				
		public function jcrop(){
		}
		
		public function jcropear(){
			$this -> render(null,null);
			
			Load::lib("jcrop");
			
			$x = $this -> post("x1");
			$y = $this -> post("y1");
			$w = $this -> post("w");
			$h = $this -> post("h");
			$imagen = $this -> post("imagen");
			
			JCrop::cortar($imagen,"miniaturas/".$imagen,$x,$y,$w,$h);
		}
		
		public function formatos(){
			$this -> render(null,null);
			
			Load::lib("formato");
			
			header('Content-type: text/html; charset=iso-8859-1');
			
			echo "UTF8: ".Formato::utf8("M�xico")."<br />";
			echo "Normal: "."M�xico"."<br />";
			echo "ISO8859-1: ".Formato::iso88591("M�xico")."<br />";
			echo "<br />";
			echo "Numero: ".Formato::numero(738374)."<br />";
			echo "Dinero: ".Formato::dinero(738374)."<br />";
			echo "Ceros: ".Formato::ceros(73,5)."<br />";
			echo "<br />";
			echo "Numero con Letra: ".Formato::numeroLetra(738374)."<br />";
			echo "<br />";
			echo "Mayusculas: ".Formato::mayusculas("Lorem Ipsum is simply dummy text of the printing and typesetting industry")."<br />";
			echo "Minusculas: ".Formato::minusculas("Lorem Ipsum is simply dummy text of the printing and typesetting industry")."<br />";
			echo "Capital: ".Formato::capital("Lorem Ipsum is simply dummy text of the printing and typesetting industry")."<br />";
			echo "Texto: ".Formato::texto("clientes_distinguidos")."<br />";
			echo "Camello: ".Formato::camello("clientes_distinguidos")."<br />";
			echo "InversaCamello: ".Formato::inversaCamello("facturasEmitidasMes")."<br />";
			echo "<br />";
			echo "Fecha: ".Formato::fecha(date("Y-m-d"))."<br />";
			echo "Fecha DB: ".Formato::fechaDB(date("d/m/Y"))."<br />";
			echo "Hora: ".Formato::hora(452)."<br />";
			
		}
		
		public function html(){
			
		}
		
		public function formulario(){
			
		}
		
		public function envioFormulario(){
			$this -> render(null,null);
			
			echo "Hola: ".$this -> post("mensaje");
		}
		
		public function ajax(){
			
		}
		
		public function horaAjax(){
			$this -> render(null,null);
			echo date("H:i:s");
		}
		
		public function postAjax(){
			$this -> render(null,null);
			echo "El mensaje fue enviado correctamente: ".$this -> post("mensaje");
		}
		
		public function mensajeAjax($mensaje){
			if($this -> is_ajax()){
				$this -> set_response("view");
			}
			
			$this -> mensaje = "Hola Mundo";			
		}
	}
?>