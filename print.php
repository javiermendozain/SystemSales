<?php
	require('fpdf/fpdf.php');
	include('conexion.php');
	
			
$n_factura=$_GET['lp'];

$sql="
	SELECT 
   concat(ciudad.nombre_ciudad,' ',departamento.nombre ) as ciudad,
  clientes.nombre_completo, 
  clientes.direccion, 
 concat( clientes.tel_movil,' - ',clientes.tel_fijo) as tel, 
  clientes.nit_cc, 
  to_char(facturar_ventas.valor_total_pagar, 'LFM 9,999,999') as valor_total_pagar, 
  facturar_ventas.tipo_de_venta, 
  facturar_ventas.fecha_hora, 
  to_char(facturar_ventas.abono,'LFM 9,999,999') as abono, 
  to_char(facturar_ventas.iva_total,'LFM 9,999,999') as iva_tota,
  to_char(facturar_ventas.descuento_total,'LFM 9,999,999')as descuento_total,
  to_char(facturar_ventas.saldo,'LFM 9,999,999') as saldo,
  to_char(facturar_ventas.sub_total,'LFM 9,999,999') as sub_total,
  facturar_ventas.estado
  
FROM 
  public.facturar_ventas, 
  public.clientes, 
  public.ciudad, 
  public.departamento
WHERE 
  clientes.nit_cc = facturar_ventas.clientes_nit_cc AND
  ciudad.idciudad = clientes.idciudad AND
  departamento.iddepartamento = ciudad.iddepartamento AND
  facturar_ventas.n_factura =".$n_factura.";";
	
	$rs=pg_query($conn,$sql);
	
while($row=pg_fetch_row($rs)) { 
 $ciudad=$row[0];
 $nombre=$row[1];
 $direccion=$row[2];
 $tel=$row[3];
 $cc_nit=$row[4];
 $vr_pagar=$row[5];
 $tipo_venta = ($row[6] == 1) ? 'DEBITO' : 'CRÉDITO';
 $fecha=$row[7];
 $abono=$row[8];
 $iva_tota=$row[9];
 $descuento_total=$row[10];
 $saldo=$row[11];
 $sub_total=$row[12];
  $estado=$row[13];
  }
	
	if($estado==2){
	
	class PDF extends FPDF
	{

	
		
		
		
			// Tabla simple
		/*function BasicTable($header, $data)
		{
			// Cabecera
				foreach($header as $col)
				$this->Cell(40,7,$col,1);
				$this->Ln();
			// Datos
				foreach($data as $row)
				{
				foreach($row as $col)
				$this->Cell(40,6,$col,1);
				$this->Ln();
				}
		}**/
		
		
		// Cabecera de página
		/**function Header()
		{
			// Logo
			$this->Image('img/logo1.png',10,8,50);
			//$this->Image('../appipuc/imagn.png',0,0,180);
			
			// Arial bold 15
			$this->SetFont('Arial','B',15);
			// Movernos a la derecha
			$this->Cell(150);
			// Título
			$this->Cell(30,10,'FACTURA VENTA',0,1,'C');
			$this->SetY(15);
			$this->SetX(170);
			$this->Cell(25,10,utf8_decode('N°'.str_pad($n_factura, 7, '0', STR_PAD_LEFT).''),0,1,'');
			
			$this->SetY(8);
	$this->SetX(80);
	$this->SetFont('Arial','B',12);
	$this->Cell(30,10,utf8_decode(' INTER.J@MENCAST '),0,1,'');
	
	$this->SetY(12.5);
	$this->SetX(85);
	$this->SetFont('Arial','',11);
	$this->Cell(30,10,utf8_decode(' Regimen Común. '),0,1,'');
	
	$this->SetY(19);
	$this->SetX(8);
	$this->Cell(30,10,utf8_decode(' NIT: 1.064.117.392-1'),0,1,'');
	
	$this->SetY(24);
	$this->SetX(8);
	$this->Cell(30,10,utf8_decode(' Transv. 1G #17-15 Barrio: 17 de Febrero'),0,1,'');
	
	$this->SetY(29);
	$this->SetX(8);
	$this->Cell(30,10,utf8_decode(' Tels: (+57) 5769176 / 300 502 0032 / 322 534 2849 '),0,1,'');
	
	$this->SetX(10);
	$this->Cell(190,2,'---------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
	
	$this->SetY(40);
	$this->SetX(10);
	
	$this->SetFont('Arial','B',10);
	$this->Cell(120,6,'Cliente: '.$nombre.'',0,0,'l');
	$this->Cell(50,6,utf8_decode('CC/NIT: '.$cc_nit.''),0,1,'l');
	
	$this->SetY(46);
	$this->SetX(10);
	$this->Cell(120,6,utf8_decode('Dirección: '.$direccion.''),0,0,'l');
	$this->Cell(50,6,utf8_decode('Tel: '.$tel.''),0,1,'l');
	
	$this->SetY(52);
	$this->SetX(10);
	$this->Cell(120,6,utf8_decode('Ciudad: '.$ciudad.''),0,0,'l');
	$this->Ln(9);
			
			/**
			ALINEACION
			C:CENTRO
			R:RIGTH
			L:LEF
			
			
			// Salto de línea
			
		}**/
		
		// Pie de página
		function Footer()
		{
			// Posición: a 1,5 cm del final
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Número de página
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');
		}
		
		function AcceptPageBreak()
		{
	$this->AddPage();
	$this->SetFillColor(232,232,232);
	$this->SetFont('Arial','B',12);
    $this->Cell(10,7,'Item',1,0,'',1);
	$this->Cell(83,7,utf8_decode('Descripción'),1,0,'',1);
	$this->Cell(10,7,utf8_decode('Ref.'),1,0,'',1);
	$this->Cell(11,7,utf8_decode('Unid'),1,0,'',1);
	$this->Cell(11,7,utf8_decode('Des'),1,0,'C',1);
    $this->Cell(16,7,utf8_decode('Cant.'),1,0,'C',1);
    $this->Cell(25,7,utf8_decode('Vr. Unitario'),1,0,'',1);
	$this->Cell(25,7,utf8_decode('Vr. Total'),1,0,'',1);
    $this->Ln();
	$this->SetFont('Times','',12);
		}
	}
	
	// Creación del objeto de la clase heredada
	$pdf = new PDF();
	//Alias contabiliza el numero de paginas
	$pdf->AliasNbPages();
	
	$pdf->AddPage();
	
	
	
	//INICIALIZA
	// Logo
			$pdf->Image('img/logo1.png',10,8,50);
			//$this->Image('../appipuc/imagn.png',0,0,180);
			
			// Arial bold 15
			$pdf->SetFont('Arial','B',15);
			// Movernos a la derecha
			$pdf->Cell(150);
			// Título
			$pdf->Cell(30,10,'FACTURA VENTA',0,1,'C');
			$pdf->SetY(15);
			$pdf->SetX(170);
			$pdf->Cell(25,10,utf8_decode('N°'.str_pad($n_factura, 7, '0', STR_PAD_LEFT).''),0,1,'');
			
			$pdf->SetY(8);
	$pdf->SetX(80);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(30,10,utf8_decode(' INTER.J@MENCAST '),0,1,'');
	
	$pdf->SetY(12.5);
	$pdf->SetX(85);
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(30,10,utf8_decode(' Regimen Simplificado. '),0,1,'');
	
	$pdf->SetY(19);
	$pdf->SetX(8);
	$pdf->Cell(30,10,utf8_decode(' NIT: 1.064.117.392-1'),0,1,'');
	
	$pdf->SetY(24);
	$pdf->SetX(8);
	$pdf->Cell(30,10,utf8_decode(' Transv. 1G #17-15 Barrio: 17 de Febrero'),0,1,'');
	
	$pdf->SetY(29);
	$pdf->SetX(8);
	$pdf->Cell(30,10,utf8_decode(' Tels: (+57) 5769176 / 300 502 0032 / 322 534 2849 '),0,1,'');
	
	$pdf->SetY(26.5);
	$pdf->SetX(153);
	$pdf->Cell(30,10,utf8_decode('Tipo de Venta: '.$tipo_venta.''),0,1,'');
	
	
	$pdf->SetY(33);
	$pdf->SetX(148);
	$date = date_create("".$fecha."");
    $pdf->Cell(40,8,utf8_decode('Fecha y Hora: '.date_format($date, 'Y-m-d h:i a').''),0,0,'C');
	
	
	$pdf->SetY(38.6);
	$pdf->SetX(10);
	$pdf->Cell(190,2,'---------------------------------------------------------------------------------------------------------------------------------------------------',0,1,'C');
	
	$pdf->SetY(40);
	$pdf->SetX(10);
	
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(120,6,'Cliente: '.$nombre.'',0,0,'l');
	$pdf->Cell(50,6,utf8_decode('CC/NIT: '.$cc_nit.''),0,1,'l');
	
	$pdf->SetY(46);
	$pdf->SetX(10);
	$pdf->Cell(120,6,utf8_decode('Dirección: '.$direccion.''),0,0,'l');
	$pdf->Cell(50,6,utf8_decode('Tel: '.$tel.''),0,1,'l');
	
	$pdf->SetY(52);
	$pdf->SetX(10);
	$pdf->Cell(120,6,utf8_decode('Ciudad: '.$ciudad.''),0,0,'l');
	$pdf->Ln(9);
			
	//FINALIZA
	
	
	//$pdf->ln(2);
	
	
	/**$pdf->SetY(50);
	$pdf->SetFillColor(232,232,232);
    $pdf->SetFont('Times','B',14);
	$pdf->SetX(10);
	$pdf->Cell(70,6,'ESTADO',1,0,'C',1);
	$pdf->SetX(80);
	$pdf->Cell(30,6,'ID',1,0,'C',1);
	$pdf->SetX(110);
	$pdf->Cell(70,6,'MUNICIPIO',1,0,'C',1);
	$pdf->Ln();
	
	$i=0;
	$j=150;
	while($i<$j){
		$i++;
    $pdf->SetFont('Times','',12);
	$pdf->SetX(10);
	$pdf->Cell(70,6,utf8_decode('ESTADÓ').$i,0,0,'C',0);
	$pdf->SetX(80);
	$pdf->Cell(30,6,utf8_decode('ID').$i,0,0,'C',0);
	$pdf->SetX(110);
	$pdf->Cell(70,6,utf8_decode('MUNICIPÍÓ').$i,0,1,'C',0);
   //$pdf->Ln(); mejor es poniendo el 1 de salto de linea
	}
	**/
	
	
	
	//ENCABEZADO DETALLE FACTURA
	
	//$pdf->Ln(9);
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',12);
    $pdf->Cell(10,7,'Item',1,0,'',1);
	$pdf->Cell(83,7,utf8_decode('Descripción'),1,0,'',1);
	$pdf->Cell(10,7,utf8_decode('Ref.'),1,0,'',1);
	$pdf->Cell(11,7,utf8_decode('Unid'),1,0,'',1);
	
	$pdf->Cell(11,7,utf8_decode('Des'),1,0,'C',1);
    $pdf->Cell(16,7,utf8_decode('Cant.'),1,0,'C',1);
    $pdf->Cell(25,7,utf8_decode('Vr. Unitario'),1,0,'',1);
	$pdf->Cell(25,7,utf8_decode('Vr. Total'),1,0,'',1);
    $pdf->Ln();
	
	
	$sql="SELECT 
  detalle_factura_venta.cantidad, 
   to_char(detalle_factura_venta.precio_venta_unitario,'LFM 9,999,999'),
   to_char(detalle_factura_venta.valor_parcial,'LFM 9,999,999'), 
  substr(productos.nombre,1,31), 
  substr(unidades.descripcion,1,3) as unidad,
  productos.idproducto, 
  detalle_factura_venta.descuento, 
  detalle_factura_venta.iva
FROM 
  public.detalle_factura_venta, 
  public.facturar_ventas, 
  public.productos, 
  public.unidades
WHERE 
  detalle_factura_venta.idventa = facturar_ventas.idventa AND
  productos.idproducto = detalle_factura_venta.idproducto AND
  unidades.idunidad = productos.idunidad AND
  facturar_ventas.n_factura = ".$n_factura."
  ORDER BY productos.nombre ASC;";
	
	$rs=pg_query($conn,$sql);
	
	$i=0;
	while($row=pg_fetch_row($rs)){
		$i++;
	
	
		//DETALLE FACTURA
	$pdf->SetFont('Times','',12);
    $pdf->Cell(10,6,utf8_decode(''.$i.''),1,0,'C');
	$pdf->Cell(83,6,utf8_decode(''.$row[3].''),1,0,'l');
		
    $pdf->Cell(10,6,utf8_decode(''.$row[5].''),1,0,'C');
	$pdf->Cell(11,6,utf8_decode(''.$row[4].''),1,0,'C');
		
   
	$pdf->Cell(11,6,utf8_decode(''.$row[6].''),1,0,'R');
	
	$pdf->Cell(16,6,utf8_decode(''.$row[0].''),1,0,'R');
		
    $pdf->Cell(25,6,utf8_decode(''.$row[1].''),1,0,'R');
	$pdf->Cell(25,6,utf8_decode(''.$row[2].''),1,1,'R');
	}
	
	//
	
	$pdf->ln(3.5);
	$pdf->SetX(135);
	$pdf->Cell(41,6,utf8_decode('Total Bruto: '),1,0,'R');
	$pdf->Cell(25,6,utf8_decode(''.$sub_total.''),1,1,'R');
	
	$pdf->SetX(135);
	$pdf->Cell(41,6,utf8_decode('Total Descuento: '),1,0,'R');
	$pdf->Cell(25,6,utf8_decode(''.$descuento_total.''),1,1,'R');
	
	$pdf->SetX(135);
	$pdf->Cell(41,6,utf8_decode('Total Iva: '),1,0,'R');
	$pdf->Cell(25,6,utf8_decode(''.$iva_tota.''),1,1,'R');
	
	$pdf->SetX(135);
	$pdf->Cell(41,6,utf8_decode('Neto a Pagar: '),1,0,'R');
	$pdf->Cell(25,6,utf8_decode(''.$vr_pagar.''),1,1,'R');
	
	if($tipo_venta=='CRÉDITO'){
  //  $pdf->ln();
	$pdf->SetX(135);
	$pdf->Cell(41,6,utf8_decode('Abono: '),1,0,'R');
	$pdf->Cell(25,6,utf8_decode(''.$abono.''),1,1,'R');
	
	$pdf->SetX(135);
	$pdf->Cell(41,6,utf8_decode('Saldo: '),1,0,'R');
	$pdf->Cell(25,6,utf8_decode(''.$saldo.''),1,1,'R');
		
	}
	
	//$pdf->Output();
	$pdf->Output('Factura N '.$n_factura.'.pdf','D');
	
	}else{
		
		header('Location: facturar_ventas.php');


	}
?>