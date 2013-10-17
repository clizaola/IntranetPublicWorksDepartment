<?php
	Load::lib("utilerias");

    class Formulario{
		public static function inicio($accion){
			$params = is_array($accion) ? $accion : Util::getParams(func_get_args());
			
			$params["enctype"] = "multipart/form-data";
            
			$x = rand(0,9999999);
            
            $params["name"] = "f".$x;
            $params["id"] = "f".$x;
            
            $opciones = '';
            
            if(isset($params["success"])){
                $opciones .= ', success: function() { '.$params["success"].' }';
            }
            
            if(isset($params["before"])){
                $opciones .= ', beforeSubmit: function() { '.$params["before"].' }';
            }
            
            $code = '<script type="text/javascript"> $.metadata.setType("attr", "validate"); $(document).ready(function() { $("#'.$params["id"].'").validate({}); }); </script>';
            $code .= form_tag($params);
			
            return $code;
		}
		
		public static function inicioAjax($accion, $contenedor){
			$params = is_array($accion) ? $accion : Util::getParams(func_get_args());

            $params["enctype"] = "multipart/form-data";
            
            $x = rand(0,9999999);
            
            $params["name"] = "f".$x;
            $params["id"] = "f".$x;
            
            $opciones = 'target: "#'.$contenedor.'"';
            
            if(isset($params["success"])){
                $opciones .= ', success: function() { '.$params["success"].' }';
            }
            
            if(isset($params["before"])){
                $opciones .= ', beforeSubmit: function() { '.$params["before"].' }';
            }
            
            $code = '<script type="text/javascript"> $.metadata.setType("attr", "validate"); $(document).ready(function() { $("#'.$params["id"].'").validate({});  $("#'.$params["id"].'").ajaxForm({ '.$opciones.' }); }); </script>';
            $code .= form_tag($params);
            
            return $code;
		}
		
		public static function fin(){
            return end_form_tag();
        }
        
        public static function submit($texto){
            $params = is_array($texto) ? $texto : Util::getParams(func_get_args());

            $params["value"] = $params[0];

            return submit_tag($params);
        }
        
        public static function imagen($alt, $src){
            $params = is_array($alt) ? $alt : Util::getParams(func_get_args());
            
            $params["alt"] = $params[0];
            $params["title"] = $params[0];
            
            return submit_image_tag($params);
        }
        
        public static function reset($texto){
            $params = is_array($texto) ? $texto : Util::getParams(func_get_args());

            $params["value"] = $params[0];

            return xhtml_tag('input', $params, 'type: reset');
        }
        
        public static function cancelar($texto,$accion){
            $params = is_array($texto) ? $texto : Util::getParams(func_get_args());

            $params["value"] = $params[0];
            $params["onclick"] = "direccionar('".PUBLIC_PATH.$params[1]."');";

            return button_tag($params);
        }
        
		public static function boton($texto,$onclick = "alert('Has presionado el boton');"){
	        $params = is_array($texto) ? $texto : Util::getParams(func_get_args());

            $params["value"] = $params[0];

            return button_tag($params);
        }
        
        public static function texto($nombre, $valor=""){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["value"] = utf8_encode($valor);
            
            if(!isset($params['onblur'])) {
        		$params['onblur'] = "texto(this)";
        	} else {
        		$params['onblur'].=";texto(this)";
        	}
            
            return @text_field_tag($params);
        }
        
        public static function mayusculas($nombre, $valor=""){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["value"] = utf8_encode(strtoupper($valor));
            
            if(!isset($params['onblur'])) {
        		$params['onblur'] = "texto(this)";
        	} else {
        		$params['onblur'].=";texto(this)";
        	}
            
            return @textupper_field_tag($params);
        }
        
        public static function password($nombre, $valor=""){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["value"] = utf8_encode($valor);
            
            if(!isset($params['onblur'])) {
        		$params['onblur'] = "texto(this)";
        	} else {
        		$params['onblur'].=";texto(this)";
        	}
            
            return @password_field_tag($params);
        }
        
        public static function numerico($nombre, $valor=""){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["value"] = $valor;
            
            if(!isset($params['onblur'])) {
        		$params['onblur'] = "texto(this)";
        	} else {
        		$params['onblur'].=";texto(this)";
        	}
            
            return @numeric_field_tag($params);
        }
        
        public static function dinero($nombre, $valor=""){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["value"] = "$ ".number_format($valor,2);
            
            if(!isset($params['onblur'])) {
        		$params['onblur'] = "dinero(this)";
        	} else {
        		$params['onblur'].=";dinero(this)";
        	}
            
            return @numeric_field_tag($params);
        }
        
        public static function fecha($nombre,$valor="",$formato="dd/mm/yy",$min=false,$max=false){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["value"] = utf8_encode($valor);
            
            if(strpos($min,"-")>0 || strpos($min,"/")>0){
                if(strpos($min,"-")>0){
                    $fm = mktime(0,0,0,substr($min,5,2),substr($min,8,2),substr($min,0,4));
                }
                else{
                    $fm = mktime(0,0,0,substr($min,3,2),substr($min,0,2),substr($min,6,4));
                }
                
                $min = (mktime(0,0,0,date("m"),date("d"),date("Y")) - $fm) / 60 / 60 / 24 ;
                 
            }
            
            if(strpos($max,"-")>0 || strpos($max,"/")>0){
                if(strpos($max,"-")>0){
                    $fm = mktime(0,0,0,substr($max,5,2),substr($max,8,2),substr($max,0,4));
                }
                else{
                    $fm = mktime(0,0,0,substr($max,3,2),substr($max,0,2),substr($max,6,4));
                }
                
                $max = ($fm - mktime(0,0,0,date("m"),date("d"),date("Y"))) / 60 / 60 / 24 ;
                 
            }
            
            $code = "<script type='text/javascript'>
                        $(function() {
                            $( '#".$nombre."' ).datepicker({
                                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            			        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                                dayNamesMin: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                                ".($min!==false ? "minDate: -".$min."," : "")."
                                ".($max!==false ? "maxDate: ".$max."," : "")."
                                dateFormat: '".$formato."' 
                            });
                       	});
                      </script>";
                      
                      return $code.text_field_tag($params);
        }
        
        public static function hora($nombre,$inicio="00:00",$fin="23:59",$intervalo=15,$seleccion=""){
        	$hi = strpos($inicio,":") > 0 ? substr($inicio,0,strpos($inicio,":")) : $inicio;
        	$mi = strpos($inicio,":") > 0 ? substr($inicio,strpos($inicio,":")+1) : 0;
        	$inicio = $hi * 60 + $mi;
        	
        	$hf = strpos($fin,":") > 0 ? substr($fin,0,strpos($fin,":")) : $fin;
        	$mf = strpos($fin,":") > 0 ? substr($fin,strpos($fin,":")+1) : 0;
        	$fin = $hf * 60 + $mf;
        	
        	$opciones = array();
        	
            for($i = $inicio ; $i <= $fin ; $i += $intervalo){
            	$h = floor($i / 60); $h = $h < 10 ? "0".$h : $h;
            	$m = $i % 60; $m = $m < 10 ? "0".$m : $m;
            	$opciones[$h.":".$m] = $h.":".$m;
            }
            
            return Formulario::select($nombre,$opciones,$seleccion);
        }
        
        public static function archivo($nombre, $valor=""){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["value"] = utf8_encode($valor);
            
            return file_field_tag($params);
        }
        
        public static function checkbox($nombre, $activo = 0, $disabled=false){
        	if($disabled){
        		return '<input type="checkbox" name="'.$nombre.'" '.($activo ? "CHECKED" : "").' disabled="disabled">';
        	}
			else{
				return '<input type="checkbox" name="'.$nombre.'" '.($activo ? "CHECKED" : "").'>';
			}
        }
        
        public static function select($nombre, $opciones=array(), $seleccion=""){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["selected"] = $seleccion;
            
            return select_tag($params);
        }
        
        public static function selectModelo($nombre, $registros, $opcion="id", $valor="id", $seleccion=""){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["selected"] = $seleccion;
            
            $tmp = array();
            
            $bandera = true;
            
            $tmp[""] = "...";
            
            if($registros) foreach($registros as $registro){
                $tmp[$registro -> {$valor}] = $registro -> {$opcion};
                if($seleccion == $registro -> {$valor} && $seleccion != 0){
                    $bandera = false;
                }
            }
            
            if($bandera) $tmp[""] = "";
            
            $params[1] = $tmp;
            
            return select_tag($params);
        }
        
        public static function selectEstados($nombre, $valor=""){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["selected"] = $valor;
            
            $params[1] = array(
            	"" => "",
                "AGUASCALIENTES" => "AGUASCALIENTES",
                "BAJA CALIFORNIA" => "BAJA CALIFORNIA",
                "BAJA CALIFORNIA SUR" => "BAJA CALIFORNIA SUR",
                "CAMPECHE" => "CAMPECHE",
                "CHIAPAS" => "CHIAPAS",
                "CHIHUAHUA" => "CHIHUAHUA",
                "COAHUILA" => "COAHUILA",
                "COLIMA" => "COLIMA",
                "DISTRITO FEDERAL" => "DISTRITO FEDERAL",
                "DURANGO" => "DURANGO",
                "ESTADO DE M�XICO" => "ESTADO DE M�XICO",
                "GUANAJUATO" => "GUANAJUATO",
                "GUERRERO" => "GUERRERO",
                "HIDALGO" => "HIDALGO",
                "JALISCO" => "JALISCO",
                "MICHOAC�N" => "MICHOAC�N",
                "MORELOS" => "MORELOS",
                "NAYARIT" => "NAYARIT",
                "NUEVO LE�N" => "NUEVO LE�N",
                "OAXACA" => "OAXACA",
                "PUEBLA" => "PUEBLA",
                "QUER�TARO" => "QUER�TARO",
                "QUINTANA ROO" => "QUINTANA ROO",
                "SAN LUIS POTOS�" => "SAN LUIS POTOS�",
                "SINALOA" => "SINALOA",
                "SONORA" => "SONORA",
                "TABASCO" => "TABASCO",
                "TAMAULIPAS" => "TAMAULIPAS",
                "TLAXCALA" => "TLAXCALA",
                "VERACRUZ" => "VERACRUZ",
                "YUCAT�N" => "YUCAT�N",
                "ZACATECAS" => "ZACATECAS"
            );
            
            return select_tag($params);
        }
        
        public static function selectMes($nombre, $valor=""){
            $params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["selected"] = $valor;
            
            $params[1] = array(
                "01" => "ENERO",
                "02" => "FEBRERO",
                "03" => "MARZO",
                "04" => "ABRIL",
                "05" => "MAYO",
                "06" => "JUNIO",
                "07" => "JULIO",
                "08" => "AGOSTO",
                "09" => "SEPTIEMBRE",
                "10" => "OCTUBRE",
                "11" => "NOVIEMBRE",
                "12" => "DICIEMBRE"
            );
            
            return select_tag($params);
        }
        
        public static function autocomplete($nombre, $opciones, $valor=""){
        	$params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["value"] = utf8_encode($valor);
            
            if(!isset($params['onblur'])) {
        		$params['onblur'] = "texto(this)";
        	} else {
        		$params['onblur'].=";texto(this)";
        	}
        	
        	if(!isset($params["case"])){
        		$params["case"] = "minusculas";
        	}
        	
        	$x = count($opciones);
        	$tmp = '';
        	if($opciones) foreach($opciones as $opcion){
        		$x--; 
        		$tmp .= '"'.$opcion.' "'.($x != 0 ? ',' : '');
        	} 
        	
        	$code = '<script>
						$(function() {

			        		var available'.$nombre.' = [
			
			                    '.$tmp.'
			
			        		];
			
			        		$( "#'.$nombre.'" ).autocomplete({
			
			        			source: available'.$nombre.'
			
			        		});
			
			        	});
					</script><div class="ui-widget">'.($params["case"]=="mayusculas" ? textupper_field_tag($params) : text_field_tag($params)).'</div>';
        	
        	return $code;
        }
        
        public static function autocompleteModelo($nombre, $opciones, $opcion="id", $valor=""){
        	$params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
            
            $params["value"] = utf8_encode($valor);
            
            if(!isset($params['onblur'])) {
        		$params['onblur'] = "texto(this)";
        	} else {
        		$params['onblur'].=";texto(this)";
        	}
        	
        	if(!isset($params["case"])){
        		$params["case"] = "minusculas";
        	}
        	
        	$x = count($opciones);
        	$tmp = '';
        	if($opciones) foreach($opciones as $opc){
        		$x--; 
        		$tmp .= '"'.$opc -> {$opcion}.' "'.($x != 0 ? ',' : '');
        	} 
        	
        	$code = '<script>
						$(function() {

			        		var available'.$nombre.' = [
			
			                    '.$tmp.'
			
			        		];
			
			        		$( "#'.$nombre.'" ).autocomplete({
			
			        			source: available'.$nombre.'
			
			        		});
			
			        	});
					</script><div class="ui-widget">'.($params["case"]=="mayusculas" ? textupper_field_tag($params) : text_field_tag($params)).'</div>';
        	
        	return $code;
        }
        
        public static function textarea($nombre,$valor=""){
        	$params = is_array($nombre) ? $nombre : Util::getParams(func_get_args());
        	
        	$params["name"] = $nombre;
        	$params["id"] = $nombre;
        	
        	return textarea_tag($params);
        }
        
        public static function oculto($nombre, $valor){
            return '<input type="hidden" name="'.$nombre.'" value="'.$valor.'" />';
        }
        
        public static function titulo($texto){
            if($texto=="") $texto = "&nbsp;";
            return '<div style="padding: 0px 0px 2px 2px; text-align: left">'.$texto.'</div>';
        }
        
        public static function subtitulo($texto){
            if($texto=="") $texto = "&nbsp;";
            return '<div style="padding: 2px 3px 0px 0px; text-align: right">'.$texto.'</div>';
        }
        
        public static function asterisco(){
            return '<span style="color: red;">*</span>';
        }
	}
    
    class Html{
        public static function imagen($imagen,$alt="",$w=0,$h=0){
            $params = is_array($imagen) ? $imagen : Util::getParams(func_get_args());
            
            if($alt!=""){
                $params["alt"] = $alt;
                $params["title"] = $alt;    
            }
            
            if($w!=""){
                $params["width"] = $w;    
            }
            
            if($h!=""){
                $params["height"] = $h;    
            }
            
            $params["border"] = "0";
            
            return img_tag($params);
        } 
        
        public static function link($accion, $texto){
            $params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            return link_to($params);
        }
        
        public static function linkConfirmado($accion, $texto, $mensaje){
            $params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            $params["onclick"] = "return confirm('".$mensaje."');";
            
            return link_to($params);
        }
        
        public static function linkAjax($accion, $text, $contenedor){
            $params = is_array($accion) ? $accion : Util::getParams(func_get_args());
            
            $params["rel"] = "#".$contenedor;
            $params["class"] = "jsRemote";
            
            return link_to($params);
        }
        
        public static function linkAjaxConfirmado($accion, $text, $contenedor, $mensaje){
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

