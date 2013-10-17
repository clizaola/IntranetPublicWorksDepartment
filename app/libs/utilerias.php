<?php

	class Utilerias{
		public static function passwordAleatorio($x){
	        $caracteres = "0Aa1Bb2Cc3Dd4Ee5Ff6Gg7Hh8Ii9Jj0Kk9Ll8Mm7Nn6Oo5Pp4Qq3Rr2Ss1Tt2Uu3Vv4Ww5Xx6Yy7Zz8";
	            
	        $password = "";
	            
	        for($i=0;$i<$x;$i++){
	            $n = rand(0,strlen($caracteres)-1);
	                
	            $password .= substr($caracteres,$n,1);
	        }
	            
	        return $password;
	    }
		
		public static function comparaFechas($fecha1, $fecha2){
			Load::lib("formato");
			
			$f1 = Formato::tiempo($fecha1);
			$f2 = Formato::tiempo($fecha2);
			
			if($f1 > $f2){
				return 1;
			}
			else{
				if($f1 == $f2){
					return 0;
				}
				else{
					return -1;
				}
			}
		}
		
		public static function diferenciaFechas($fecha1, $fecha2){
			Load::lib("formato");
			
			$f1 = Formato::tiempo($fecha1);
			$f2 = Formato::tiempo($fecha2);
			
			return $f1 - $f2;
		}
	}

    function ajaxizar($texto){
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
            return utf8_decode($texto);    
        }
        return $texto;
    }
        
   function passwordAleatorio($x){
        $caracteres = "0Aa1Bb2Cc3Dd4Ee5Ff6Gg7Hh8Ii9Jj0Kk9Ll8Mm7Nn6Oo5Pp4Qq3Rr2Ss1Tt2Uu3Vv4Ww5Xx6Yy7Zz8";
            
        $password = "";
            
        for($i=0;$i<$x;$i++){
            $n = rand(0,strlen($caracteres)-1);
                
            $password .= substr($caracteres,$n,1);
        }
            
        return $password;
    }
    
    function formatoFechaDB($fecha){
        if(!$fecha) return "0000-00-00";
        
        if(strpos($fecha,"/")>0){
            return substr($fecha,6,4)."-".substr($fecha,3,2)."-".substr($fecha,0,2);
        }
        return $fecha;
    }
    
    function formatoFecha($fecha){
        if(!$fecha) return "00/00/0000";
        
        if(strpos($fecha,"-")>0){
            return substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4);
        }
        return $fecha;
    } 
    
    function formatoHora($x){
        $m = floor($x % 60);
        $h = floor($x / 60);
        
        return formatoNumero($h,2).":".formatoNumero($m,2);
    }
    
    function mesLetra($m){
		switch($m){
			case 1: return "Enero";
		    case 2: return "Febrero";
		    case 3: return "Marzo";
		    case 4: return "Abril";
		    case 5: return "Mayo";
		    case 6: return "Junio";
		    case 7: return "Julio";
		    case 8: return "Agosto";
		    case 9: return "Septiembre";
		    case 10: return "Octubre";
		    case 11: return "Noviembre";
		    case 12: return "Diciembre";
		}
	}
	
	

 	 function fechaExtendida($fecha){
	 	$tmp = mktime(0,0,0,substr($fecha,5,2),substr($fecha,8,2),substr($fecha,0,4));

		$n = date("N",$tmp);

		switch($n){
			case 1: $fecha = "Lunes, "; break;
			case 2: $fecha = "Martes, "; break;
			case 3: $fecha = "Miercoles, "; break;
			case 4: $fecha = "Jueves, "; break;
			case 5: $fecha = "Viernes, "; break;
			case 6: $fecha = "Sabado, "; break;
			case 7: $fecha = "Domingo, "; break;
		}

		$d = date("d",$tmp);
		$fecha .= $d." de ";

		$m = date("m",$tmp);

		switch($m){
			case 1: $fecha .= "Enero"; break;
		    case 2: $fecha .= "Febrero"; break;
		    case 3: $fecha .= "Marzo"; break;
		    case 4: $fecha .= "Abril"; break;
		    case 5: $fecha .= "Mayo"; break;
		    case 6: $fecha .= "Junio"; break;
		    case 7: $fecha .= "Julio"; break;
		    case 8: $fecha .= "Agosto"; break;
		    case 9: $fecha .= "Septiembre"; break;
		    case 10: $fecha .= "Octubre"; break;
		    case 11: $fecha .= "Noviembre"; break;
		    case 12: $fecha .= "Diciembre"; break;
		}

		$y = date("Y",$tmp);

	    $fecha .= " de ".$y;


	 	return $fecha;
	 }
     
     function fechaReducida($fecha){
	 	$tmp = mktime(0,0,0,substr($fecha,5,2),substr($fecha,8,2),substr($fecha,0,4));

		$d = date("d",$tmp);
		$fecha = $d." ";

		$m = date("m",$tmp);

		switch($m){
			case 1: $fecha .= "ENE"; break;
		    case 2: $fecha .= "FEB"; break;
		    case 3: $fecha .= "MAR"; break;
		    case 4: $fecha .= "ABR"; break;
		    case 5: $fecha .= "MAY"; break;
		    case 6: $fecha .= "JUN"; break;
		    case 7: $fecha .= "JUL"; break;
		    case 8: $fecha .= "AGO"; break;
		    case 9: $fecha .= "SEP"; break;
		    case 10: $fecha .= "OCT"; break;
		    case 11: $fecha .= "NOV"; break;
		    case 12: $fecha .= "DIC"; break;
		}

		$y = date("Y",$tmp);

	    $fecha .= " ".$y;


	 	return $fecha;
	 }

     function fechaMediana($fecha){
	 	$tmp = mktime(0,0,0,substr($fecha,5,2),substr($fecha,8,2),substr($fecha,0,4));

		$d = date("d",$tmp);
		$fecha = $d." de ";

		$m = date("m",$tmp);

		switch($m){
			case 1: $fecha .= "Enero"; break;
		    case 2: $fecha .= "Febrero"; break;
		    case 3: $fecha .= "Marzo"; break;
		    case 4: $fecha .= "Abril"; break;
		    case 5: $fecha .= "Mayo"; break;
		    case 6: $fecha .= "Junio"; break;
		    case 7: $fecha .= "Julio"; break;
		    case 8: $fecha .= "Agosto"; break;
		    case 9: $fecha .= "Septiembre"; break;
		    case 10: $fecha .= "Octubre"; break;
		    case 11: $fecha .= "Noviembre"; break;
		    case 12: $fecha .= "Diciembre"; break;
		}

		$y = date("Y",$tmp);

	    $fecha .= " de ".$y;


	 	return $fecha;
	 }

	function iconoArchivo($archivo){
		if(!$archivo || $archivo == "-") return "";
		
		$ext = "";
	        	
	    for($i=0;$i<strlen($archivo);$i++){
	    	if($archivo[$i]=="."){
	    		$ext = "";
	        }
	        else{
	        	$ext .= $archivo[$i];
	        }
	    }

		$x="txt.png";

		switch($ext){
			case "doc": case "docx": $x = "doc.png"; break;
			case "xls": case "xlsx": $x = "xls.png"; break;
			case "ppt": case "pptx": $x = "ppt.png"; break;
			case "jpg": case "jpeg": $x = "jpg.png"; break;
			case "gif": $x = "gif.png"; break;
			case "png": $x = "png.png"; break;
			case "bmp": $x = "bmp.png"; break;
			case "rar": $x = "rar.png"; break;
			case "zip": $x = "zip.png"; break;
			case "pdf": $x = "pdf.png"; break;
			case "txt": $x = "txt.png"; break;
			case "xml": $x = "xml.png"; break;
			case "html": $x = "html.png"; break;

			default: $x = "attach.png"; break;
		}

		$img = "icons/archivos/".$x;

		return $img;
	}
	
	function tipoArchivo($archivo){
		if(!$archivo || $archivo == "-") return "";

		$ext = "";
	        	
	    for($i=0;$i<strlen($archivo);$i++){
	    	if($archivo[$i]=="."){
	    		$ext = "";
	        }
	        else{
	        	$ext .= $archivo[$i];
	        }
	    }

		$x="txt.png";

		switch($ext){
			case "doc": case "docx": $x = "Documento de Texto"; break;
			case "xls": case "xlsx": $x = "Hoja de Calculo"; break;
			case "ppt": case "pptx": $x = "Presentación"; break;
			case "jpg": case "jpeg": $x = "Imagen JPG"; break;
			case "gif": $x = "Imagen GIF"; break;
			case "png": $x = "Imagen PNG"; break;
			case "bmp": $x = "Imagen BMP"; break;
			case "rar": $x = "Archivo Comprimido"; break;
			case "zip": $x = "Archivo Comprimido"; break;
			case "pdf": $x = "Documento en PDF"; break;
			case "txt": $x = "Archivo de Texto"; break;
			case "xml": $x = "Archivo XML"; break;
			case "html": $x = "Archivo HTML"; break;

			default: $x = "Archivo Adjunto"; break;
		}
		return $x;
	}

	function formatoNumero($numero,$x=2){
		$n = $x - strlen($numero);

		for($i=0;$i<$n;$i++){
			$numero = "0".$numero;
		}

		return $numero;
	}
	
	function quitarFormatoDinero($dinero){
		$dinero = str_replace(",","",$dinero);
		$dinero = str_replace("$","",$dinero);
		$dinero = trim($dinero);
		
		return $dinero;
	}
	
	function disponibilidad($insumo){
        $insumo = InsumoObra::consultar($insumo);
        
        $disponibilidad = 0;
        
        $requisiciones = IobrasRequisiciones::reporte("obras_id=".$insumo -> obras_id);
        
        if($requisiciones){ 
            foreach($requisiciones as $requisicion){
                $insumos = ConceptoRequisicion::reporte("productos_id=".$insumo -> productos_id." AND requisicion_id=".$requisicion -> id);
        
                if($insumos) foreach($insumos as $insumo){
                    $disponibilidad += $insumo -> asignado - $insumo -> utilizado;
                }    
            }
        }
        
        return $disponibilidad;
        
    }
    
    function disminuir_disponibilidad($insumo,$x){
        $insumo = InsumoObra::consultar($insumo);
        
        $disponibilidad = 0;
        
        $requisiciones = IobrasRequisiciones::reporte("obras_id=".$insumo -> obras_id);
        
        if($requisiciones){ 
            foreach($requisiciones as $requisicion){
                $insumos = ConceptoRequisicion::reporte("productos_id=".$insumo -> productos_id." AND requisicion_id=".$requisicion -> id);
        
                if($insumos) foreach($insumos as $insumo){
                    $resto = $insumo -> asignado - $insumo -> utilizado;
                    
                    if($resto > 0){
                        if($resto <= $x){
                            $insumo -> utilizado = $insumo -> asignado;
                            $insumo -> save();
                            
                            $x -= $resto;
                        }
                        else{
                            $insumo -> utilizado += $x;
                            $insumo -> save();
                            
                            $x=0;
                        }
                    }
                    
                    if($x==0) break;
                }    
            }
        }
        
    }
    
    
    function aumentar_disponibilidad($insumo,$x){
        $insumo = InsumoObra::consultar($insumo);
        
        $disponibilidad = 0;
        
        $requisiciones = IobrasRequisiciones::reporte("obras_id=".$insumo -> obras_id);
        
        if($requisiciones){ 
            foreach($requisiciones as $requisicion){
                $insumos = ConceptoRequisicion::reporte("productos_id=".$insumo -> productos_id." AND requisicion_id=".$requisicion -> id);
        
                if($insumos) foreach($insumos as $insumo){
                    
                    if($x >= $insumo -> utilizado){
                        $x -= $insumo -> utilizado;
                        
                        $insumo -> utilizado = 0;
                        $insumo -> save();
                    }
                    else{
                        $insumo -> utilizado -= $x;
                        $insumo -> save();    
                        
                        $x=0;
                    }
                    
                    if($x==0) break;
                }    
            }
        }
        
    }

        function estadosMexico(){
                    $estados = array(	"JALISCO" => "JALISCO",

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

						"ESTADO DE MEXICO" => "ESTADO DE MEXICO",

						"GUANAJUATO" => "GUANAJUATO",

						"GUERRERO" => "GUERRERO",

						"HIDALGO" => "HIDALGO",

						"JALISCO" => "JALISCO",

						"MICHOACAN" => "MICHOACAN",

						"MORELOS" => "MORELOS",

						"NAYARIT" => "NAYARIT",

						"NUEVO LEON" => "NUEVO LEON",

						"OAXACA" => "OAXACA",

						"PUEBLA" => "PUEBLA",

						"QUERETARO" => "QUERETARO",

						"QUINTANA ROO" => "QUINTANA ROO",

						"SAN LUIS POTOSI" => "SAN LUIS POTOSI",

						"SINALOA" => "SINALOA",

						"SONORA" => "SONORA",

						"TABASCO" => "TABASCO",

						"TAMAULIPAS" => "TAMAULIPAS",

						"TLAXCALA" => "TLAXCALA",

						"VERACRUZ" => "VERACRUZ",

						"YUCATAN" => "YUCATAN",

						"ZACATECAS" => "ZACATECAS"

					);

                    return $estados;
        }

        class zipfile
    {

        var $datasec = array(); // array to store compressed data
        var $ctrl_dir = array(); // central directory
        var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00"; //end of Central directory record
        var $old_offset = 0;

        function add_dir($name)

        // adds "directory" to archive - do this before putting any files in directory!
        // $name - name of directory... like this: "path/"
        // ...then you can add files using add_file with names like "path/file.txt"
        {
            $name = str_replace("\\", "/", $name);

            $fr = "\x50\x4b\x03\x04";
            $fr .= "\x0a\x00";    // ver needed to extract
            $fr .= "\x00\x00";    // gen purpose bit flag
            $fr .= "\x00\x00";    // compression method
            $fr .= "\x00\x00\x00\x00"; // last mod time and date

            $fr .= pack("V",0); // crc32
            $fr .= pack("V",0); //compressed filesize
            $fr .= pack("V",0); //uncompressed filesize
            $fr .= pack("v", strlen($name) ); //length of pathname
            $fr .= pack("v", 0 ); //extra field length
            $fr .= $name;
            // end of "local file header" segment

            // no "file data" segment for path

            // "data descriptor" segment (optional but necessary if archive is not served as file)
            $fr .= pack("V",$crc); //crc32
            $fr .= pack("V",$c_len); //compressed filesize
            $fr .= pack("V",$unc_len); //uncompressed filesize

            // add this entry to array
            $this -> datasec[] = $fr;

            $new_offset = strlen(implode("", $this->datasec));

            // ext. file attributes mirrors MS-DOS directory attr byte, detailed
            // at http://support.microsoft.com/support/kb/articles/Q125/0/19.asp

            // now add to central record
            $cdrec = "\x50\x4b\x01\x02";
            $cdrec .="\x00\x00";    // version made by
            $cdrec .="\x0a\x00";    // version needed to extract
            $cdrec .="\x00\x00";    // gen purpose bit flag
            $cdrec .="\x00\x00";    // compression method
            $cdrec .="\x00\x00\x00\x00"; // last mod time & date
            $cdrec .= pack("V",0); // crc32
            $cdrec .= pack("V",0); //compressed filesize
            $cdrec .= pack("V",0); //uncompressed filesize
            $cdrec .= pack("v", strlen($name) ); //length of filename
            $cdrec .= pack("v", 0 ); //extra field length
            $cdrec .= pack("v", 0 ); //file comment length
            $cdrec .= pack("v", 0 ); //disk number start
            $cdrec .= pack("v", 0 ); //internal file attributes
            $ext = "\x00\x00\x10\x00";
            $ext = "\xff\xff\xff\xff";
            $cdrec .= pack("V", 16 ); //external file attributes  - 'directory' bit set

            $cdrec .= pack("V", $this -> old_offset ); //relative offset of local header
            $this -> old_offset = $new_offset;

            $cdrec .= $name;
            // optional extra field, file comment goes here
            // save to array
            $this -> ctrl_dir[] = $cdrec;


        }


        function add_file($data, $name)

        // adds "file" to archive
        // $data - file contents
        // $name - name of file in archive. Add path if your want

        {
            $name = str_replace("\\", "/", $name);
            //$name = str_replace("\\", "\\\\", $name);

            $fr = "\x50\x4b\x03\x04";
            $fr .= "\x14\x00";    // ver needed to extract
            $fr .= "\x00\x00";    // gen purpose bit flag
            $fr .= "\x08\x00";    // compression method
            $fr .= "\x00\x00\x00\x00"; // last mod time and date

            $unc_len = strlen($data);
            $crc = crc32($data);
            $zdata = gzcompress($data);
            $zdata = substr( substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
            $c_len = strlen($zdata);
            $fr .= pack("V",$crc); // crc32
            $fr .= pack("V",$c_len); //compressed filesize
            $fr .= pack("V",$unc_len); //uncompressed filesize
            $fr .= pack("v", strlen($name) ); //length of filename
            $fr .= pack("v", 0 ); //extra field length
            $fr .= $name;
            // end of "local file header" segment

            // "file data" segment
            $fr .= $zdata;

            // "data descriptor" segment (optional but necessary if archive is not served as file)
            $fr .= pack("V",$crc); //crc32
            $fr .= pack("V",$c_len); //compressed filesize
            $fr .= pack("V",$unc_len); //uncompressed filesize

            // add this entry to array
            $this -> datasec[] = $fr;

            $new_offset = strlen(implode("", $this->datasec));

            // now add to central directory record
            $cdrec = "\x50\x4b\x01\x02";
            $cdrec .="\x00\x00";    // version made by
            $cdrec .="\x14\x00";    // version needed to extract
            $cdrec .="\x00\x00";    // gen purpose bit flag
            $cdrec .="\x08\x00";    // compression method
            $cdrec .="\x00\x00\x00\x00"; // last mod time & date
            $cdrec .= pack("V",$crc); // crc32
            $cdrec .= pack("V",$c_len); //compressed filesize
            $cdrec .= pack("V",$unc_len); //uncompressed filesize
            $cdrec .= pack("v", strlen($name) ); //length of filename
            $cdrec .= pack("v", 0 ); //extra field length
            $cdrec .= pack("v", 0 ); //file comment length
            $cdrec .= pack("v", 0 ); //disk number start
            $cdrec .= pack("v", 0 ); //internal file attributes
            $cdrec .= pack("V", 32 ); //external file attributes - 'archive' bit set

            $cdrec .= pack("V", $this -> old_offset ); //relative offset of local header
    //        echo "old offset is ".$this->old_offset.", new offset is $new_offset<br>";
            $this -> old_offset = $new_offset;

            $cdrec .= $name;
            // optional extra field, file comment goes here
            // save to central directory
            $this -> ctrl_dir[] = $cdrec;
        }

        function file() { // dump out file
            $data = implode("", $this -> datasec);
            $ctrldir = implode("", $this -> ctrl_dir);

            return
                $data.
                $ctrldir.
                $this -> eof_ctrl_dir.
                pack("v", sizeof($this -> ctrl_dir)).     // total # of entries "on this disk"
                pack("v", sizeof($this -> ctrl_dir)).     // total # of entries overall
                pack("V", strlen($ctrldir)).             // size of central dir
                pack("V", strlen($data)).                 // offset to start of central dir
                "\x00\x00";                             // .zip file comment length
        }
    }