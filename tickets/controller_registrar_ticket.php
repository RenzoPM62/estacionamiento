<?php

include('../app/config.php');

$placa = $_GET['placa'];
$placa = strtoupper($placa);//convierte todo a mayuscula
$nombre_cliente = $_GET['nombre_cliente'];
$nit_ci = $_GET['nit_ci'];
$cuviculo = $_GET['cuviculo'];
$fecha_ingreso = $_GET['fecha_ingreso'];
$hora_ingreso = $_GET['hora_ingreso'];
$user_sesion = $_GET['user_session'];
$estado_ticket = "OCUPADO";
// Se inicializa la variable de estado que faltaba
$estado_del_registro = "1"; 

date_default_timezone_set("America/Lima");
$fechaHora = date("Y-m-d h:i:s");

$sentencia = $pdo->prepare('INSERT INTO tb_tickets
(placa_auto, nombre_cliente, nit_ci, cuviculo, fecha_ingreso, hora_ingreso, estado_ticket, user_sesion, fyh_creacion, estado) 
VALUES ( :placa_auto, :nombre_cliente, :nit_ci, :cuviculo, :fecha_ingreso, :hora_ingreso, :estado_ticket, :user_sesion, :fyh_creacion, :estado)');

$sentencia->bindParam(':placa_auto',$placa);
$sentencia->bindParam(':nombre_cliente',$nombre_cliente);
$sentencia->bindParam(':nit_ci',$nit_ci);
$sentencia->bindParam(':cuviculo',$cuviculo);
$sentencia->bindParam(':fecha_ingreso',$fecha_ingreso);
$sentencia->bindParam(':hora_ingreso',$hora_ingreso);
$sentencia->bindParam(':estado_ticket',$estado_ticket);
$sentencia->bindParam(':user_sesion',$user_sesion);
$sentencia->bindParam(':fyh_creacion',$fechaHora);
$sentencia->bindParam(':estado',$estado_del_registro);

if($sentencia->execute()) {
    // Obtener el ID del último registro para enviarlo a la página de impresión
    $id_ticket = $pdo->lastInsertId();

    echo 'Ticket registrado';
    ?>
        <script>
            // 1. Abre la página de generación del ticket en una NUEVA PESTAÑA (_blank)
            window.open('tickets/generar_ticket.php?id_ticket=<?php echo $id_ticket; ?>', '_blank');

            // 2. Redirige la PESTAÑA ACTUAL al mapeo de vehículos
            location.href = "principal.php";
        </script>
    <?php
}else{
    echo 'Error al registrar a la base de datos';
}
// Se eliminó la llave de cierre adicional que causaba un error de sintaxis en PHP.