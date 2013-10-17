<?php
    define("MAXMEM", 32*1024*1024);
    function enoughmem ($x, $y, $rgb=3) {
        return ( $x * $y * $rgb * 1.7 < MAXMEM - memory_get_usage() );
    }

    class Imagenes{
        public $tam_max = array(800,600);
        public $marca_agua = "public/img/galeriamk/marcadeagua.png";
        
        public static function dimensionar($imagen,$x=0,$y=0){
            $tmp = new Imagenes();
            if($x == 0) $x = $tmp -> tam_max[0];
            if($y == 0) $y = $x * 0.75;
            
            if($y>$x){$tmp = $x; $x = $y; $y = $tmp;}
            
            $ruta_original = $imagen;
            
            $a = strtolower(substr($imagen,0,strpos($imagen, ".")));
            $e = strtolower(substr($imagen,strpos($imagen, ".")));
            
            $ruta_destino = $a."tmp".$e;
            
            $dim = GetImageSize($imagen);
            
            if($dim[0]>$dim[1]){
                //HORIZONTAL
                if($dim[0]>$x){
                    //SI HABRA CAMBIO
                    switch(strtolower(substr($e,1))){
                        case "jpg": 
                        case "jpeg": $original = ImageCreateFromJPEG($ruta_original); break;
                        case "gif": $original = ImageCreateFromGIF($ruta_original); break;
                        case "png": $original = ImageCreateFromPNG($ruta_original); break; 
                    }
                        
                        $copia = imagecreatetruecolor($x,$y);
                        
                        imagecopyresampled($copia,$original,0,0,0,0,$x, $y,ImageSX($original),ImageSY($original));
                            
                        switch(strtolower(substr($e,1))){
                            case "jpg": 
                            case "jpeg": ImageJPEG($copia,$ruta_destino,100); break;
                            case "gif": ImageGIF($copia,$ruta_destino,100); break;
                            case "png": ImagePNG($copia,$ruta_destino,100); break; 
                        }
                            
                        unlink($ruta_original);
                        rename($ruta_destino,$ruta_original);
                        
                        ImageDestroy($original);
                        ImageDestroy($copia);
                    
                }
            }
            else{
                //VERTICAL
                if($dim[1]>$x){
                    //SI HABRA CAMBIO
                    
                    switch(strtolower(substr($e,1))){
                        case "jpg": 
                        case "jpeg": $original = ImageCreateFromJPEG($ruta_original); break;
                        case "gif": $original = ImageCreateFromGIF($ruta_original); break;
                        case "png": $original = ImageCreateFromPNG($ruta_original); break; 
                    }
                    
                        $copia = imagecreatetruecolor($y,$x);
                        
                        imagecopyresampled($copia,$original,0,0,0,0,$y, $x,ImageSX($original),ImageSY($original));
                        
                        switch(strtolower(substr($e,1))){
                            case "jpg": 
                            case "jpeg": ImageJPEG($copia,$ruta_destino,100); break;
                            case "gif": ImageGIF($copia,$ruta_destino,100); break;
                            case "png": ImagePNG($copia,$ruta_destino,100); break; 
                        }
                        
                        unlink($ruta_original);
                        rename($ruta_destino,$ruta_original);
                        
                    
                    ImageDestroy($original);
                    ImageDestroy($copia);
                }
            }
        }
        
        public static function crearMiniatura($imagen,$ruta,$x=160,$y=120){
            $dim = GetImageSize($imagen);
            
            if($y>$x){$tmp = $x; $x = $y; $y = $tmp;}
            
            $ruta_original = $imagen;
            $a = strtolower(substr($imagen,0,strpos($imagen, ".")));
            $r = substr($a,0,strrpos($a,"/")+1);
            $a = substr($a,strrpos($a,"/")+1);
            $e = strtolower(substr($imagen,strpos($imagen, ".")));
            $ruta_destino = $ruta.$a.$e;
            
            
            
            if($dim[0]>$dim[1]){
                //HORIZONTAL
                //if($dim[0]>$x){
                    //SI HABRA CAMBIO
                    
                    if (enoughmem($x,$y)){
                    
                        switch(strtolower(substr($e,1))){
                            case "jpg": 
                            case "jpeg": $original = ImageCreateFromJPEG($ruta_original); break;
                            case "gif": $original = ImageCreateFromGIF($ruta_original); break;
                            case "png": $original = ImageCreateFromPNG($ruta_original); break; 
                        }
                        
                        $copia = imagecreatetruecolor($x,$y);
                        
                        imagecopyresampled($copia,$original,0,0,0,0,$x, $y,ImageSX($original),ImageSY($original));
                        
                        switch(strtolower(substr($e,1))){
                            case "jpg": 
                            case "jpeg": ImageJPEG($copia,$ruta_destino,75); break;
                            case "gif": ImageGIF($copia,$ruta_destino,75); break;
                            case "png": ImagePNG($copia,$ruta_destino,75); break; 
                        }
                        
                        ImageDestroy($original);
                        ImageDestroy($copia);
                    }
                //}
            }
            else{
                //VERTICAL
                //if($dim[1]>$x){
                    //SI HABRA CAMBIO
                    
                    switch(strtolower(substr($e,1))){
                        case "jpg": 
                        case "jpeg": $original = ImageCreateFromJPEG($ruta_original); break;
                        case "gif": $original = ImageCreateFromGIF($ruta_original); break;
                        case "png": $original = ImageCreateFromPNG($ruta_original); break; 
                    }
                    
                    if (enoughmem($x,$y)){
                        $copia = imagecreatetruecolor($y,$x);
                        
                        imagecopyresampled($copia,$original,0,0,0,0,$y, $x,ImageSX($original),ImageSY($original));
                        
                        switch(strtolower(substr($e,1))){
                            case "jpg": 
                            case "jpeg": ImageJPEG($copia,$ruta_destino,75); break;
                            case "gif": ImageGIF($copia,$ruta_destino,75); break;
                            case "png": ImagePNG($copia,$ruta_destino,75); break; 
                        }
                        
                        ImageDestroy($original);
                        ImageDestroy($copia);
                    }
            }
        }
        
        public static function crearThumb($imagen,$tamano = "C"){
            $dim = GetImageSize($imagen);
            
            switch($tamano){ 
                case "C": $x=100; $y=78; $n="c"; break;
                case "M": $x=400; $y=300; $n="m"; break;
                case "G": $x=640; $y=480; $n="g"; break;
                case "X": $x=800; $y=600; $n="x"; break;
            }
			
			$medidas = Imagenes::ratio($x,$y,$imagen);
			
			$x = $medidas["x"];
			$y = $medidas["y"];
			
            $ruta_original = $imagen;
            $a = strtolower(substr($imagen,0,strpos($imagen, ".")));
            $r = substr($a,0,strrpos($a,"/")+1);
            $a = substr($a,strrpos($a,"/")+1);
            $e = strtolower(substr($imagen,strpos($imagen, ".")));
            $ruta_destino = $r.$n.$a.$e;
            
            if($dim[0]>$dim[1]){
                //HORIZONTAL
                //if($dim[0]>$x){
                    //SI HABRA CAMBIO
                    
                    switch(strtolower(substr($e,1))){
                        case "jpg": 
                        case "jpeg": $original = ImageCreateFromJPEG($ruta_original); break;
                        case "gif": $original = ImageCreateFromGIF($ruta_original); break;
                        case "png": $original = ImageCreateFromPNG($ruta_original); break; 
                    }
                    
                    if (enoughmem($x,$y)){
                        $copia = imagecreatetruecolor($x,$y);
                        
                        imagecopyresampled($copia,$original,0,0,0,0,$x, $y,ImageSX($original),ImageSY($original));
                        
                        switch(strtolower(substr($e,1))){
                            case "jpg": 
                            case "jpeg": ImageJPEG($copia,$ruta_destino,75); break;
                            case "gif": ImageGIF($copia,$ruta_destino,75); break;
                            case "png": ImagePNG($copia,$ruta_destino,75); break; 
                        }
                    }
                    
                    ImageDestroy($original);
                    ImageDestroy($copia);
                //}
            }
            else{
                //VERTICAL
                //if($dim[1]>$x){
                    //SI HABRA CAMBIO
                    
                    switch(strtolower(substr($e,1))){
                        case "jpg": 
                        case "jpeg": $original = ImageCreateFromJPEG($ruta_original); break;
                        case "gif": $original = ImageCreateFromGIF($ruta_original); break;
                        case "png": $original = ImageCreateFromPNG($ruta_original); break; 
                    }
                    
                    if (enoughmem($x,$y)){
                        $copia = imagecreatetruecolor($y,$x);
                        
                        imagecopyresampled($copia,$original,0,0,0,0,$y, $x,ImageSX($original),ImageSY($original));
                        
                        switch(strtolower(substr($e,1))){
                            case "jpg": 
                            case "jpeg": ImageJPEG($copia,$ruta_destino,75); break;
                            case "gif": ImageGIF($copia,$ruta_destino,75); break;
                            case "png": ImagePNG($copia,$ruta_destino,75); break; 
                        }
                    }
                    
                    ImageDestroy($original);
                    ImageDestroy($copia);
                //}
            }
        }
        
        public static function crearThumbs($imagen){
            $dim = GetImageSize($imagen);
            
            if($dim[0]>1280 || $dim[1]>1280)
                Imagenes::dimensionar($imagen);  
                
            Imagenes::crearThumb($imagen,"C");
            Imagenes::crearThumb($imagen,"M");
            Imagenes::crearThumb($imagen,"G");
            Imagenes::crearThumb($imagen,"X");    
        }
        
        public static function marcaAgua($imagen,$marca_agua){
            // obtener datos de la fotografia original
			$dim = getimagesize($imagen);
			$x = $dim[0];
			$y = $dim[1];
            
            // obtener datos de la "marca de agua"
			$dimm = getimagesize($marca_agua);
			$xm = $dimm[0];
			$ym = $dimm[1];
            
            // crear nueva imagen desde la marca de agua
			$marcadeagua = ImageCreateFromPNG($marca_agua);
            
            //Obtener ruta y extensión del archivo de la imagen a marcar
            $arc = strtolower(substr($imagen,0,strpos($imagen, ".")));
            $ext = strtolower(substr($imagen,strpos($imagen, ".")));
            
            // crear imagen desde el original
			switch(strtolower(substr($ext,1))){
                case "jpg": 
                case "jpeg": $original = ImageCreateFromJPEG($imagen); break;
                case "gif": $original = ImageCreateFromGIF($imagen); break;
                case "png": $original = ImageCreateFromPNG($imagen); break; 
            }
            
            // calcular la posición donde debe copiarse la "marca de agua" en la fotografia
			$hm = imagesx($original) - imagesx($marcadeagua) - 10;
			$vm = imagesy($original) - imagesy($marcadeagua) - 10;
            
            //ImageAlphaBlending($original, true);
            
            // copiar la "marca de agua" en la fotografia
			ImageCopy($original, $marcadeagua, $hm, $vm, 0, 0, $xm, $ym);
            
            //$copia = imagecreatetruecolor($x,$y);
            //imagecopyresampled($copia,$original,0,0,0,0,$y, $x,ImageSX($original),ImageSY($original));
            
            // guardar la nueva imagen
			switch(strtolower(substr($ext,1))){
                case "jpg": 
                case "jpeg": ImageJPEG($original,$imagen,75); break;
                case "gif": ImageGIF($original,$imagen,75); break;
                case "png": ImagePNG($original,$imagen,75); break; 
            }
            
            // cerrar las imágenes
			ImageDestroy($original);
			ImageDestroy($marcadeagua);     
        }
		
		/*
		 ejemplo de como usarlo
		 
		 $ruta = APP_PATH."public/img/galeriamk/categorias/".$categoria -> imagen;
         $medidas = $this -> ratio(GaleriamkConfiguracion::xg(),GaleriamkConfiguracion::yg(),$ruta);
         Imagenes::dimensionar($ruta,$medidas['x'],$medidas['y']);
		 
		 * */
		public static function ratio($x='800',$y='600',$ruta=''){
			list($ancho, $alto) = getimagesize($ruta);
			$ratio = $alto/$ancho;
			if($ancho>=$alto){
				$ancho = $x;
				$alto = $ratio * $ancho;	
			}else{
				$alto = $y;
				$ancho = $alto/$ratio;
			}
			
			$medidas['x'] = $ancho;
			$medidas['y'] = $alto;
			return $medidas;
		}
		
		public static function generarMapaGoogle($x,$y,$ruta,$zoom = 15, $w = 400, $h = 400){
			$mi_curl = curl_init ('http://maps.google.com/maps/api/staticmap?center='.$x.','.$y.'&zoom='.$zoom.'&size='.$w.'x'.$h.'&sensor=false&markers=color:red|'.$x.','.$y.'');
			
			$fs = fopen ($ruta, "w");  
			curl_setopt ($mi_curl, CURLOPT_FILE, $fs);  
			curl_setopt ($mi_curl, CURLOPT_HEADER, 0);  
			curl_exec ($mi_curl);  
			curl_close ($mi_curl);  
			fclose ($fs);
		}
    }
?>