<?php

require('conexion.php');

$sql='UPDATE public.facturar_ventas
   SET  estado=2
 WHERE n_factura='.$_GET['lp'].';';
 
$rs=pg_query($conn,$sql);

header('Location: facturar_ventas.php');


?>