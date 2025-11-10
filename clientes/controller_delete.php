<?php
include ('../app/config.php');
include ('../layout/admin/datos_usuario_sesion.php');

// Comprobar si se recibió el ID del cliente por POST
if (isset($_POST['id_cliente'])) {
    
    $id_cliente = $_POST['id_cliente'];
    $fyh_eliminacion = date('Y-m-d H:i:s'); // Fecha y hora de eliminación

    // --- Sentencia SQL para "eliminar" (actualizar estado) ---
    // Es mejor práctica cambiar el estado a '0' (inactivo) que borrarlo
    $sentencia = $pdo->prepare("UPDATE tb_clientes SET
        estado = '0',
        fyh_eliminacion = :fyh_eliminacion
        WHERE id_cliente = :id_cliente");

    $sentencia->bindParam(':id_cliente', $id_cliente);
    $sentencia->bindParam(':fyh_eliminacion', $fyh_eliminacion);

    try {
        if ($sentencia->execute()) {
            // Si la actualización fue exitosa
            session_start();
            $_SESSION['mensaje'] = "Se eliminó al cliente de la base de datos correctamente";
            $_SESSION['icono'] = "success";
            header('Location: ' . $URL . '/clientes/');
            exit;
        } else {
            // Si hubo un error en la ejecución
            session_start();
            $_SESSION['mensaje'] = "Error al eliminar al cliente de la base de datos";
            $_SESSION['icono'] = "error";
            header('Location: ' . $URL . '/clientes/delete.php?id=' . $id_cliente);
            exit;
        }
    } catch (Exception $e) {
        // Si hubo una excepción
        session_start();
        $_SESSION['mensaje'] = "Error al eliminar al cliente: " . $e->getMessage();
        $_SESSION['icono'] = "error";
        header('Location: ' . $URL . '/clientes/delete.php?id=' . $id_cliente);
        exit;
    }

} else {
    // Si no se recibió un ID por POST
    session_start();
    $_SESSION['mensaje'] = "No se recibió el ID del cliente a eliminar";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/clientes/');
    exit;
}

?>