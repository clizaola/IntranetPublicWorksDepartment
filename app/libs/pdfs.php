<?php

	class OPDF extends FPDF{
        var $obra; //Obra a mostrar
        
        function setObra($obra){
            $this -> obra = $obra;
        }
        
        function Header()
        { 
            $this->SetFont('Arial','B',15);
            $this -> SetFillColor(200,240,170);
            
            $obra = IobrasObras::consultar($this -> obra);
            
            $this->Image("http://www.ameca.gob.mx/intranet/img/ameca.jpg",15,8,21,19.2);
            $this->Image("http://www.ameca.gob.mx/intranet/img/titulo.jpg",150,8,46.3,12.8);
            
            //Move to the right
            $this->Cell(80);
            //Framed title
            $this->Cell(30,30,strtoupper($this -> title),0,0,'C');
            //Line break
            $this->Ln(20);
            
            $this -> SetFont('Arial','B',10);
            $this -> Cell(0,10,"DATOS DE LA OBRA",0,1,'L');
            
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Id de la Obra:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(50,6,formatoNumero($obra -> id,4),1,0,'L');
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Programa Asignado:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(80,6,$obra -> programa() ? $obra -> programa() -> nombre : "SIN ASIGNAR",1,0,'L');
            
            $this -> Ln();
            
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Clave de Obra:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(50,6,$obra -> codigo,1,0,'L');
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Tipo de Obra:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(80,6,$obra -> tipoobra() -> nombre,1,0,'L');
            
            $this -> Ln();
            $this -> SetFont('Arial','B',8);
            $this -> Cell(0,6,"Nombre de la Obra:",1,0,'C',1);
            
            $this -> Ln();
            
            $this -> SetFont('Arial','',8);
            $this -> Cell(0,6,$obra -> nombre,1,0,'C');
            
            $this -> Ln();
            $this -> SetFont('Arial','B',8);
            $this -> Cell(0,6,"Descripción de la Obra:",1,0,'C',1);
            
            $this -> Ln();
            $this -> SetFont('Arial','',8);
            $this -> MultiCell(0,6,str_replace("&nbsp;"," ",$obra -> descripcion),1,"J",false);
            
           
            $this -> Ln();
            
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Ubicación:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(50,6,$obra -> localidades_id > 0 ? $obra -> localidad() -> nombre : "Sin Localidad",1,0,'L');
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Supervisor:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(80,6,$obra -> porcentaje()."%",1,0,'L');
            
            $this -> Ln();
            
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Monto Asignado:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(50,6,"$".number_format($obra -> presupuestado,2,".",","),1,0,'L');
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Avance Realizado:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(80,6,$obra -> porcentaje()."%",1,0,'L');

            $this -> Ln();
            
            $this -> SetFont('Arial','B',8);
            $this -> Cell(80,6,"Acta de Autorización:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(110,6,$obra -> acta_cabildo,1,0,'L');
            
            $this -> Ln();
            $this -> Ln();
            $this -> Ln();
        }
        
        function Footer()
        {
            //Go to 1.5 cm from bottom
            $this->SetY(-15);
            //Select Arial italic 8
            $this->SetFont('Arial','I',6);
            //Print centered page number
            $this->Cell(0,10,'Impreso el '.fechaExtendida(date("Y-m-d")).' a las '.date("H:i"),0,0,'L');
            $this->Cell(0,10,'Página: '.$this -> PageNo(),0,0,'R');
        }
    }

    class NPDF extends FPDF{
        var $nomina; //Obra a mostrar
        
        function setNomina($nomina){
            $this -> nomina = $nomina;
        }
        
        function Header()
        { 
            $this->SetFont('Arial','B',15);
            $this -> SetFillColor(200,240,170);
            
            $nomina = Nomina::consultar($this -> nomina);
            $obra = $nomina -> obra();
            
            $this->Image("http://www.ameca.gob.mx/intranet/img/ameca.jpg",15,8,21,19.2);
            $this->Image("http://www.ameca.gob.mx/intranet/img/titulo.jpg",150,8,46.3,12.8);
            
            //Move to the right
            $this->Cell(80);
            //Framed title
            $this->Cell(30,30,strtoupper($this -> title),0,0,'C');
            //Line break
            $this->Ln(20);
            
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Nombre de la Obra:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(80,6,$obra ? (strlen($obra -> nombre)>55 ? substr($obra -> nombre,0,55)." ..." : $obra -> nombre) : "",1,0,'L');
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Fecha:",1,0,'L',1);
            $this -> SetFont('Arial','',7);
            $this -> Cell(50,6,fechaExtendida(date("Y-m-d")),1,0,'C');
            
            $this -> Ln();
            
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Lugar:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(80,6,$obra ? $obra -> localidad() -> nombre : "",1,0,'L');
            $this -> SetFont('Arial','B',8);
            $this -> Cell(30,6,"Semana:",1,0,'L',1);
            $this -> SetFont('Arial','',8);
            $this -> Cell(50,6,formatoFecha($nomina -> fecha_inicio)." - ".formatoFecha($nomina -> fecha_fin),1,0,'C');            
            
            $this -> Ln();
            $this -> Ln();
        }
        
        function Footer()
        {
            //Go to 1.5 cm from bottom
            $this->SetY(-15);
            //Select Arial italic 8
            $this->SetFont('Arial','I',6);
            //Print centered page number
            $this->Cell(0,10,'Impreso el '.fechaExtendida(date("Y-m-d")).' a las '.date("H:i"),0,0,'L');
            $this->Cell(0,10,'Página: '.$this -> PageNo(),0,0,'R');
        }
    }
    
