<?php
include ('../app/config.php');
include ('../layout/admin/datos_usuario_sesion.php');

// Obtener el ID del cliente desde la URL
$id_cliente_get = $_GET['id'];

// --- Consultar la BD para mostrar los datos del cliente ---
$query_cliente = $pdo->prepare("SELECT * FROM tb_clientes WHERE id_cliente = :id_cliente AND estado = '1'");
$query_cliente->bindParam(':id_cliente', $id_cliente_get);
$query_cliente->execute();
$cliente_datos = $query_cliente->fetch(PDO::FETCH_ASSOC);

if ($cliente_datos) {
    $nit_ci_cliente = $cliente_datos['nit_ci_cliente'];
    $nombre_cliente = $cliente_datos['nombre_cliente'];
    $placa_auto = $cliente_datos['placa_auto'];
} else {
    // Si no se encuentra el cliente, redirigir
    header('Location: '.$URL.'/clientes/');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include ('../layout/admin/head.php'); ?>
    <title>Eliminar Cliente</title>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include ('../layout/admin/menu.php'); ?>
    <div class="content-wrapper">
        <br>
        <div class="container">

            <h2>Eliminar Cliente</h2>
            <br>
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">¿Está seguro de eliminar a este cliente?</h3>
                        </div>
                        <div class="card-body">
                            <form action="controller_delete.php" method="post">
                                <input type="hidden" name="id_cliente" value="<?php echo $id_cliente_get; ?>">

                                <div class="form-group">
                                    <label for="">Nombre del Cliente</label>
                                    <input type="text" class="form-control" value="<?php echo $nombre_cliente; ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">NIT/CI del Cliente</label>
                                    <input type="text" class="form-control" value="<?php echo $nit_ci_cliente; ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">Placa del Auto</label>
                                    <input type="text" class="form-control" value="<?php echo $placa_auto; ?>" disabled>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
                                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <?php include ('../layout/admin/footer.php'); ?>
</div>
<?php include ('../layout/admin/footer_link.php'); ?>
</body>
</html>