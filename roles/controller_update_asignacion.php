<?php
include('../app/config.php');

// Recibimos los datos del formulario
$id_usuario = $_POST['id_usuario'];
$rol_id = $_POST['rol_id'];

// Preparamos la sentencia SQL para actualizar
// OJO: Asumo que la tabla de usuarios tiene una columna 'rol_id'. 
// Si usas una tabla intermedia, avísame para corregirlo.
$sentencia = $pdo->prepare("UPDATE tb_usuarios SET 
    rol_id = :rol_id,
    fyh_actualizacion = :fyh_actualizacion 
    WHERE id_usuario = :id_usuario");

$sentencia->bindParam(':rol_id', $rol_id);
$sentencia->bindParam(':fyh_actualizacion', $fechaHora);
$sentencia->bindParam(':id_usuario', $id_usuario);

if($sentencia->execute()){
    session_start();
    $_SESSION['mensaje'] = "Se actualizó el rol del usuario correctamente";
    $_SESSION['icono'] = "success";
    header('Location: asignar.php');
}else{
    session_start();
    $_SESSION['mensaje'] = "Error al actualizar el rol";
    $_SESSION['icono'] = "error";
    header('Location: asignar.php');
}
?>