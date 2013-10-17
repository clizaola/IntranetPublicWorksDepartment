<?php
	/* Clase Html
	* Esta clase contiene diferentes metodos que regresan el código html para crear diferentes tags predefinidos de manera sencilla.	
	* 
	* Autor: Ramiro Vera(raalveco) y Carlos Lizaola(clizaola)
	* Company: Amecasoft S.A. de C.V. (México)
	* Fecha: 04/06/2011
	* 
	* NOTA: Esta clase esta diseñada para funcionar como libreria en el Kumbia PHP Spirit 1.0
	* 
	*/
	class Html {
		
		public static function link($accion, $texto) {
			$params = is_array($accion) ? $accion : Util::getParams(func_get_args());
			return  link_to($params);
		}
	
		public static function linkConfirmado($accion, $texto, $mensaje) {
			$params = is_array($accion) ? $accion : Util::getParams(func_get_args());
			$params["onclick"] = "return confirm('" . $mensaje . "');";
			return  link_to($params);
		}
	
		public static function linkAjax($accion, $text, $contenedor) {
			$params = is_array($accion) ? $accion : Util::getParams(func_get_args());
			$params["rel"] = "#" . $contenedor;
			$params["class"] = "jsRemote";
			return  link_to($params);
		}
	
		public static function imagen($imagen, $alt="", $w=0, $h=0) {
			$params = is_array($imagen) ? $imagen : Util::getParams(func_get_args());
			
			if($alt != "") {
				$params["alt"] = str_replace(":", "###", $alt);
				$params["title"] = str_replace(":", "###", $alt);
			}
			if($w != "") {
				$params["width"] = $w;
			}
			if($h != "") {
				$params["height"] = $h;
			}
			$params["border"] = "0";
			
			return  str_replace("###", ":", img_tag($params));
		}
		
		public static function mapaGoogle($x, $y, $zoom = 15, $alt="", $w=400, $h=400) {
			$params["src"] = 'http://maps.google.com/maps/api/staticmap?center='.$x.','.$y.'&zoom='.$zoom.'&size='.$w.'x'.$h.'&sensor=false&markers=color:red|'.$x.','.$y.'';
			
			if($alt != "") {
				$params["alt"] = str_replace(":", "###", $alt);
				$params["title"] = str_replace(":", "###", $alt);
			}
			if($w != "") {
				$params["width"] = $w;
			}
			if($h != "") {
				$params["height"] = $h;
			}
			$params["border"] = "0";
			
			return  str_replace("###", ":", img_tag($params));
		}
		
		public static function linkAjaxConfirmado($accion, $text, $contenedor){
            $params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            //$params["onclick"] = "return confirm('".$mensaje."');";
            $params["rel"] = "#".$contenedor;
            $params["class"] = "jsRemoteEliminar";
            
            $controlador = substr($accion,0,strpos($accion,"/"));
            $acciontmp = substr($accion,strpos($accion,"/")+1);
            
            if(strpos($acciontmp,"/")!==false){
            	$acciontmp = substr($acciontmp,0,strpos($acciontmp,"/"));
            }
            
            if(Acl::tienePermiso(Session::get("usuario_id"),$controlador,$acciontmp)){
            	return link_to($params);
            }
            else{
            	return "";
            }
        }
        
        public static function linkFBAzulAjax($accion, $texto, $contenedor){
            $params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            $params["rel"] = "#".$contenedor;
            $params["class"] = "jsRemote";
            $params["style"] = 'background-color: #3B5999;border: 1px solid #000000;padding: 5px 5px 5px 5px;color: #FFF;font-weight: bold;font-size: 14px; height: 50px; font-family: "Trebuchet MS", "Lucida Grande", Tahoma, Arial, Verdana, sans-serif;text-decoration: none;';
            
            return link_to($params);
        }
        
        public static function linkFBGrisAjax($accion, $texto, $contenedor){
            $params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            $params["rel"] = "#".$contenedor;
            $params["class"] = "jsRemote";
            $params["style"] = 'background-color: #CCCCCC;border: 1px solid #000000;padding: 5px 5px 5px 5px;color: #000;font-weight: bold;font-size: 14px; height: 50px; font-family: "Trebuchet MS", "Lucida Grande", Tahoma, Arial, Verdana, sans-serif; text-decoration: none;';
            
            $params[0] = $params[0];
            $params[1] = $params[1];
            
            return link_to($params);
        }
	
		public static function youtube($codigo, $w=662, $h=408) {
			$html = '<object width="' . $w . '" height="' . $h . '">
						<param name="movie" value="http://www.youtube.com/v/' . $codigo . '?fs=1&amp;hl=es_ES"></param>
						<param name="allowFullScreen" value="true"></param>
						<param name="allowscriptaccess" value="always"></param>
						<param name="wmode" value="transparent" />
						<embed wmode="transparent" src="http://www.youtube.com/v/' . $codigo . '?fs=1&amp;hl=es_ES" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $w . '" height="' . $h . '"></embed>
					</object>';
			return $html;
		}
	
		public static function botonJS($texto, $javascript="alert('Hola Mundo');") {
			return '<input type="button" value="' . $texto . '" onclick="' . $javascript . '" />';
		}
		
		public static function ajaxizar($texto){
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
                return utf8_decode($texto);    
            }
            return $texto;
        }
        
        public static function barra($valor, $style="width: 200px; height: 25px;"){
            $x = rand(0,9999999);
        	
        	$code = '<script>
						$(function() {
							$( "#progressbar'.$x.'" ).progressbar({
								value: '.$valor.'
							});
						});
					</script>';
                      
            return $code.'<div id="progressbar'.$x.'" style="'.$style.'"></div>';
        }
        
        public static function jsDialogo($div="dialogo",$boton="dialogar",$cerrar="cerrar"){
        	$code = '<script>
						$.fx.speeds._default = 1000;
						
						$(function() {
							$( "#'.$div.'" ).dialog({
								autoOpen: false,
								modal: true,	
								show: "blind",
								hide: "blind"
							});
							
							$( "#'.$cerrar.'" ).click(function() {
								$( "#'.$div.'" ).dialog( "close" );
							});
 
							$( "#'.$boton.'" ).click(function() {
								$( "#'.$div.'" ).dialog( "open" );
								return false;
							});
						});
					</script>';
        	
        	return $code;
        }
        
        public static function jsDialogoRequisicion($requisicion){
        	$code = '<script>
						$.fx.speeds._default = 1000;
						
						$(function() {
							$( "#requisicion'.$requisicion.'" ).dialog({
								autoOpen: false,
								modal: true,	
								show: "blind",
								hide: "blind"
							});
							
							$( "#eliminar'.$requisicion.'" ).click(function() {
								$( "#requisicion'.$requisicion.'" ).dialog( "close" );
							});
							
							$( "#guardar'.$requisicion.'" ).click(function() {
								$( "#requisicion'.$requisicion.'" ).dialog( "close" );
							});
 
							$( "#boton'.$requisicion.'" ).click(function() {
								$( "#requisicion'.$requisicion.'" ).dialog( "open" );
								return false;
							});
						});
					</script>';
        	
        	return $code;
        }
	}
?>