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

            <h2>Asignación de roles a usuarios</h2>

            <br>
            <div class="row">
                <div class="col-md-12">

                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Listado de usuarios</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body" style="display: block;">
                            <table class="table table-bordered table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th><center>Nro</center></th>
                                        <th>Nombre de Usuarios</th>
                                        <th>Email</th>
                                        <th><center>Rol Actual</center></th>
                                        <th><center>Acción</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $contador = 0;
                                    // Consulta de usuarios activos
                                    $query_usuario = $pdo->prepare("SELECT * FROM tb_usuarios WHERE estado = '1' ");
                                    $query_usuario->execute();
                                    $usuarios = $query_usuario->fetchAll(PDO::FETCH_ASSOC);

                                    // Consulta de roles disponibles (Se hace fuera del bucle para optimizar)
                                    $query_roles = $pdo->prepare("SELECT * FROM tb_roles WHERE estado = '1' ");
                                    $query_roles->execute();
                                    $roles = $query_roles->fetchAll(PDO::FETCH_ASSOC);

                                    foreach($usuarios as $usuario){
                                        $id = $usuario['id'];
                                        $nombres = $usuario['nombres'];
                                        $email = $usuario['email'];
                                        $rol = $usuario['rol'];
                                        $contador = $contador + 1;
                                        ?>
                                        <tr>
                                            <td><center><?php echo $contador;?></center></td>
                                            <td><?php echo $nombres;?></td>
                                            <td><?php echo $email;?></td>
                                            <td><center><?php echo $rol;?></center></td> <!-- Muestra el rol actual -->
                                            <td>
                                                <center>
                                                    <?php if($rol == ""){ ?>
                                                        <!-- CASO 1: EL USUARIO NO TIENE ROL (Botón Asignar) -->
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAsignar<?php echo $id;?>">
                                                            <i class="fas fa-plus"></i> Asignar
                                                        </button>

                                                        <!-- Modal Asignar -->
                                                        <div class="modal fade" id="modalAsignar<?php echo $id;?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header" style="background-color: #28a745; color: white">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Asignar nuevo rol</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="controller_asignar.php" method="post">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label>Nombre del usuario</label>
                                                                                        <input type="text" name="nombre" class="form-control" value="<?php echo $nombres;?>" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label>Email</label>
                                                                                        <input type="text" name="email" class="form-control" value="<?php echo $email;?>" readonly>
                                                                                        <input type="text" name="id_user" value="<?php echo $id;?>" hidden>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label>Seleccione Rol</label>
                                                                                        <select name="rol" class="form-control">
                                                                                            <?php foreach($roles as $role_item){ ?>
                                                                                                <option value="<?php echo $role_item['nombre'];?>"><?php echo $role_item['nombre'];?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                                <button type="submit" class="btn btn-success">Guardar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php } else { ?>
                                                        <!-- CASO 2: EL USUARIO YA TIENE ROL (Botón Editar) -->
                                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditar<?php echo $id;?>">
                                                            <i class="fas fa-pencil-alt"></i> Editar
                                                        </button>

                                                        <!-- Modal Editar -->
                                                        <div class="modal fade" id="modalEditar<?php echo $id;?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header" style="background-color: #ffc107; color: black">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Editar rol asignado</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body" style="text-align: left;">
                                                                        <form action="controller_asignar.php" method="post">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label>Nombre del usuario</label>
                                                                                        <input type="text" name="nombre" class="form-control" value="<?php echo $nombres;?>" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label>Email</label>
                                                                                        <input type="text" name="email" class="form-control" value="<?php echo $email;?>" readonly>
                                                                                        <input type="text" name="id_user" value="<?php echo $id;?>" hidden>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label>Cambiar Rol</label>
                                                                                        <select name="rol" class="form-control">
                                                                                            <?php foreach($roles as $role_item){ 
                                                                                                $nombre_rol = $role_item['nombre']; ?>
                                                                                                <option value="<?php echo $nombre_rol;?>" <?php if($nombre_rol == $rol){ echo "selected"; } ?>>
                                                                                                    <?php echo $nombre_rol;?>
                                                                                                </option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                                <button type="submit" class="btn btn-warning">Actualizar Rol</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </center>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- /.content-wrapper -->
    <?php include('../layout/admin/footer.php'); ?>
</div>
<?php include('../layout/admin/footer_link.php'); ?>
</body>
</html>