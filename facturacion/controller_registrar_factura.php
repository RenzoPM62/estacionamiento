<?php

include ('../app/config.php');

date_default_timezone_set("America/Lima");
$fechaHora = date("Y-m-d h:i:s");
$dia = date("d");
$mes = date('m');
if($mes=="1")$mes = "Enero";
if($mes=="2")$mes = "Febrero";
if($mes=="3")$mes = "Marzo";
if($mes=="4")$mes = "Abril";
if($mes=="5")$mes = "Mayo";
if($mes=="6")$mes = "Junio";
if($mes=="7")$mes = "Julio";
if($mes=="8")$mes = "Agosto";
if($mes=="9")$mes = "Septiembre";
if($mes=="10")$mes = "Octubre";
if($mes=="11")$mes = "Noviembre";
if($mes=="12")$mes = "Diciembre";
$ano = date('Y');

$nro_factura = $_GET['nro_factura'];
$id_cliente = $_GET['id_cliente'];


$fecha_factura = "Lima, ".$dia." de ".$mes." de ".$ano;

$fecha_ingreso = $_GET['fecha_ingreso'];
$hora_ingreso = $_GET['hora_ingreso'];
$fecha_salida = date('Y-m-d');
$hora_salida = date('H:i');

//calculando tiempo de parqueo
$c_hora_ingreso = strtotime($hora_ingreso);
$c_hora_salida = strtotime($hora_salida);
$diferencia_hora = ($c_hora_salida - $c_hora_ingreso)/3600;
$hora_calculado = ((int)$diferencia_hora);
$diferencia_minutos = ($c_hora_salida - $c_hora_ingreso)/60;
$calculando = $hora_calculado * 60;
$minutos_calculado = $diferencia_minutos - $calculando;
$tiempo = $hora_calculado." horas con".$minutos_calculado." minutos";

echo $tiempo;
/*
$cuviculo = $_GET['cuviculo'];
$detalle = $_GET['detalle'];
$precio = $_GET['precio'];
$cantidad = $_GET['cantidad'];
$total = $_GET['total'];
$monto_total = $_GET['monto_total'];
$monto_literal = $_GET['monto_literal'];
$user_sesion = $_GET['user_sesion'];
$qr = $_GET['qr'];

$sentencia = $pdo->prepare('INSERT INTO tb_facturaciones
(id_informacion,nro_factura,id_cliente,fecha_factura,fecha_ingreso,hora_ingreso,fecha_salida,hora_salida,tiempo,cuviculo,detalle,precio,cantidad,total,monto_total,monto_literal,user_sesion,qr, fyh_creacion, estado)
VALUES ( :id_informacion,:nro_factura,:id_cliente,:fecha_factura,:fecha_ingreso,:hora_ingreso,:fecha_salida,:hora_salida,:tiempo,:cuviculo,:detalle,:precio,:cantidad,:total,:monto_total,:monto_literal,:user_sesion,:qr,:fyh_creacion,:estado)');


$sentencia->bindParam(':nro_factura',$nro_factura);
$sentencia->bindParam(':id_cliente',$id_cliente);
$sentencia->bindParam(':fecha_factura',$fecha_factura);
$sentencia->bindParam(':fecha_ingreso',$fecha_ingreso);
$sentencia->bindParam(':hora_ingreso',$hora_ingreso);
$sentencia->bindParam(':fecha_salida',$fecha_salida);
$sentencia->bindParam(':hora_salida',$hora_salida);
$sentencia->bindParam(':tiempo',$tiempo);
$sentencia->bindParam(':cuviculo',$cuviculo);
$sentencia->bindParam(':detalle',$detalle);
$sentencia->bindParam(':precio',$precio_final);
$sentencia->bindParam(':cantidad',$cantidad);
$sentencia->bindParam(':total',$total);
$sentencia->bindParam(':monto_total',$monto_total);
$sentencia->bindParam(':monto_literal',$monto_literal);
$sentencia->bindParam(':user_sesion',$user_sesion);
$sentencia->bindParam(':qr',$qr);
$sentencia->bindParam('fyh_creacion',$fechaHora);
$sentencia->bindParam('estado',$estado_del_registro);
*/