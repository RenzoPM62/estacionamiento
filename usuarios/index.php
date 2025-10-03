<?php
include('../app/config.php');
include('../layout/admin/datos_usuario_sesion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include('../layout/admin/head.php'); ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include('../layout/admin/menu.php'); ?>
    <div class="content-wrapper">
        <br>
        <div class="container">

            <h2>Listado de Usuarios</h2>

            <br>
            <table border="1" class="table table-bordered table-striped">
                    <tr>
                        <th>Nro</th>
                        <th>Nombre de Usuarios</th>
                        <th>Email</th>
                        <th>Acción</th>
                    </tr>

                    <?php
                        $contador = 0;
                        $query_usuario = $pdo->prepare("SELECT * FROM tb_usuarios WHERE estado = '1'");
                        $query_usuario->execute();
                        $usuarios = $query_usuario->fetchAll(PDO::FETCH_ASSOC);

                        $nro = 1;
                        foreach($usuarios as $usuario){
                            $id = $usuario['id'];
                            $nombres = $usuario['nombres'];
                            $email = $usuario['email'];
                            $contador = $contador + 1;
                            ?>
                            <tr>
                                <td><?php echo $contador; ?></td>
                                <td><?php echo $nombres; ?></td>
                                <td><?php echo $email; ?></td>
                                <td>
                                    <a href="update.php?id=<?php echo $id; ?>" class="btn btn-success">Editar</a>
                                    <a href="delete.php?id=<?php echo $id; ?>" class="btn btn-danger">Borrar</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

            </table>

            <hr>
            <a href="generar-reporte.php" class="btn btn-primary">Generar reporte
                <i class="fa fa">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-bar-graph" viewBox="0 0 16 16">
                        <path d="M10 13.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-6a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v6zm-2.5.5a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1zm-3 0a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-1z"/>
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                    </svg>
                </i>
            </a>
        </div>


    </div>
    <!-- /.content-wrapper -->
    <?php include('../layout/admin/footer.php'); ?>
</div>
<?php include('../layout/admin/footer_link.php'); ?>
</body>
</html>
