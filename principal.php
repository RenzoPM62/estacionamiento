<?php

include('app/config.php');
include('layout/admin/datos_usuario_sesion.php');

//recuperar el nro de la factura (esto lo dejamos igual, funcionaba bien)
$contador_del_nro_de_factura = 0;
$query_facturaciones = $pdo->prepare("SELECT * FROM tb_facturaciones WHERE estado = '-'");
$query_facturaciones->execute();
$facturaciones = $query_facturaciones->fetchAll(PDO::FETCH_ASSOC);
foreach ($facturaciones as $facturacion) {
    $contador_del_nro_de_factura = $contador_del_nro_de_factura + 1;
}
$contador_del_nro_de_factura = $contador_del_nro_de_factura + 1;

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php include('layout/admin/head.php'); ?>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <?php include('layout/admin/menu.php'); ?>

    <div class="content-wrapper">
      <br>
      <div class="container">

        <h2>Bienvenido al ESTACIONAMIENTO - MERCADO HUASCAR</h2>

        <br>
        <div class="row">
          <div class="col-md-12">

            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Mapeo actual del parqueo</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>

              <div class="card-body" style="display: block;">
                <div class="row">
                  <?php
                  // 1. Obtenemos todos los espacios del mapa
                  $query_mapeos = $pdo->prepare("SELECT * FROM tb_mapeos WHERE estado = '1' ");
                  $query_mapeos->execute();
                  $mapeos = $query_mapeos->fetchAll(PDO::FETCH_ASSOC);
                  
                  foreach ($mapeos as $mapeo) {
                    $id_map = $mapeo['id_map'];
                    $nro_espacio = $mapeo['nro_espacio'];
                    $estado_espacio = $mapeo['estado_espacio']; // "LIBRE" o "OCUPADO"

                    // ---------------------------------------------------------
                    // LOGICA PARA ESPACIOS LIBRES (VERDES)
                    // ---------------------------------------------------------
                    if ($estado_espacio == 'LIBRE') { ?>
                      <div class="col">
                        <center>
                          <h2><?php echo $nro_espacio; ?></h2>
                          <button class="btn btn-success" style="width: 100%; height: 114px"
                            data-toggle="modal" data-target="#modal<?php echo $id_map; ?>">
                            <p><?php echo $estado_espacio; ?></p>
                          </button>

                          <!-- Modal Registro Entrada -->
                          <div class="modal fade" id="modal<?php echo $id_map; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">INGRESO DEL VEHÍCULO</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Placa: <span><b style="color: red">*</b></span></label>
                                    <div class="col-sm-6">
                                      <input type="text" style="text-transform: uppercase" class="form-control" id="placa_buscar<?php echo $id_map; ?>">
                                    </div>
                                    <div class="col-sm-3">
                                      <button class="btn btn-primary" id="btn_buscar_cliente<?php echo $id_map; ?>">
                                        <i class="fas fa-search"></i> Buscar
                                      </button>
                                      <script>
                                          // Script de búsqueda (tu lógica original estaba bien aquí)
                                          $('#btn_buscar_cliente<?php echo $id_map; ?>').click(function() {
                                            var placa = $('#placa_buscar<?php echo $id_map; ?>').val();
                                            var id_map = "<?php echo $id_map; ?>";
                                            if (placa == "") {
                                              alert('Debe de llenar el campo placa');
                                              $('#placa_buscar<?php echo $id_map; ?>').focus();
                                            } else {
                                              var url = 'clientes/controller_buscar_cliente.php';
                                              $.get(url, { placa: placa, id_map: id_map }, function(datos) {
                                                $('#respuesta_buscar_cliente<?php echo $id_map; ?>').html(datos);
                                              });
                                            }
                                          });
                                      </script>
                                    </div>
                                  </div>

                                  <div id="respuesta_buscar_cliente<?php echo $id_map; ?>"></div>

                                  <!-- Fechas automáticas -->
                                  <?php
                                    date_default_timezone_set("America/Lima");
                                    $fecha_actual = date("Y-m-d");
                                    $hora_actual = date("H:i");
                                  ?>

                                  <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Fecha de ingreso:</label>
                                    <div class="col-sm-8">
                                      <input type="date" class="form-control" id="fecha_ingreso<?php echo $id_map; ?>" value="<?php echo $fecha_actual; ?>">
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Hora de ingreso:</label>
                                    <div class="col-sm-8">
                                      <input type="time" class="form-control" id="hora_ingreso<?php echo $id_map; ?>" value="<?php echo $hora_actual; ?>">
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Cúbiculo:</label>
                                    <div class="col-sm-8">
                                      <input type="text" class="form-control" id="cuviculo<?php echo $id_map; ?>" value="<?php echo $nro_espacio; ?>" readonly>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                  <button type="button" class="btn btn-primary" id="btn_registrar_ticket<?php echo $id_map; ?>">Imprimir ticket</button>
                                  <script>
                                    $('#btn_registrar_ticket<?php echo $id_map; ?>').click(function() {
                                      // Recolección de variables
                                      var placa = $('#placa_buscar<?php echo $id_map; ?>').val();
                                      var nombre_cliente = $('#nombre_cliente<?php echo $id_map; ?>').val();
                                      var nit_ci = $('#nit_ci<?php echo $id_map; ?>').val();
                                      var fecha_ingreso = $('#fecha_ingreso<?php echo $id_map; ?>').val();
                                      var hora_ingreso = $('#hora_ingreso<?php echo $id_map; ?>').val();
                                      var cuviculo = $('#cuviculo<?php echo $id_map; ?>').val();
                                      var user_session = "<?php echo $usuario_sesion; ?>";

                                      // Validaciones
                                      if (placa == "") {
                                        alert('Debe de llenar este campo Placa');
                                        $('#placa_buscar<?php echo $id_map; ?>').focus();
                                      } else if (nombre_cliente == "") {
                                        alert('Debe de llenar el campo nombre del cliente');
                                        $('#nombre_cliente<?php echo $id_map; ?>').focus();
                                      } else if (nit_ci == "") {
                                        alert('Debe de llenar el campo NIT/CI')
                                        $('#nit_ci<?php echo $id_map; ?>').focus();
                                      } else {
                                        // 1. Cambiar estado a Ocupado
                                        $.get('parqueo/controller_cambiar_estado_ocupado.php', { cuviculo: cuviculo }, function(datos) { $('#respuesta_ticket').html(datos); });
                                        
                                        // 2. Registrar Cliente (si no existe)
                                        $.get('clientes/controller_registrar_clientes.php', { nombre_cliente: nombre_cliente, nit_ci: nit_ci, placa: placa }, function(datos) { $('#respuesta_ticket').html(datos); });

                                        // 3. Registrar Ticket
                                        $.get('tickets/controller_registrar_ticket.php', {
                                          placa: placa,
                                          nombre_cliente: nombre_cliente,
                                          nit_ci: nit_ci,
                                          fecha_ingreso: fecha_ingreso,
                                          hora_ingreso: hora_ingreso,
                                          cuviculo: cuviculo,
                                          user_session: user_session
                                        }, function(datos) {
                                          $('#respuesta_ticket').html(datos);
                                          // Opcional: Recargar página tras éxito para ver el cambio a ROJO
                                          // location.reload(); 
                                        });
                                      }
                                    });
                                  </script>
                                </div>
                                <div id="respuesta_ticket"></div>
                              </div>
                            </div>
                          </div>
                        </center>
                      </div>

                    <?php
                    // ---------------------------------------------------------
                    // LOGICA PARA ESPACIOS OCUPADOS (ROJOS) - AQUÍ ESTABA EL ERROR
                    // ---------------------------------------------------------
                    } elseif ($estado_espacio == 'OCUPADO') { 
                        // CORRECCION IMPORTANTE:
                        // Buscamos el ticket activo (estado = '1') asociado a este espacio específico.
                        // Usamos prepare con parámetros para evitar errores de sintaxis SQL
                        $query_datos_cliente = $pdo->prepare("SELECT * FROM tb_tickets WHERE cuviculo = :cuviculo AND estado = '1' LIMIT 1");
                        $query_datos_cliente->bindParam(':cuviculo', $nro_espacio);
                        $query_datos_cliente->execute();
                        $datos_cliente = $query_datos_cliente->fetch(PDO::FETCH_ASSOC);

                        // Inicializamos variables vacías para evitar error de "Undefined variable" si no hay datos
                        $placa_auto = "";
                        $nombre_cliente = "";
                        $nit_ci = "";
                        $cuviculo_db = "";
                        $fecha_ingreso = "";
                        $hora_ingreso = "";
                        $user_sesion_db = "";
                        $id_ticket = "";

                        if($datos_cliente){
                            $id_ticket = $datos_cliente['id_ticket'];
                            $placa_auto = $datos_cliente['placa_auto'];
                            $nombre_cliente = $datos_cliente['nombre_cliente'];
                            $nit_ci = $datos_cliente['nit_ci'];
                            $cuviculo_db = $datos_cliente['cuviculo'];
                            $fecha_ingreso = $datos_cliente['fecha_ingreso'];
                            $hora_ingreso = $datos_cliente['hora_ingreso'];
                            $user_sesion_db = $datos_cliente['user_sesion'];
                            
                            // Recuperamos el ID del cliente para la facturación
                            $query_cliente = $pdo->prepare("SELECT id_cliente FROM tb_clientes WHERE placa_auto = :placa AND estado = '1' LIMIT 1");
                            $query_cliente->bindParam(':placa', $placa_auto);
                            $query_cliente->execute();
                            $data_cliente = $query_cliente->fetch(PDO::FETCH_ASSOC);
                            $id_cliente_facturacion = $data_cliente ? $data_cliente['id_cliente'] : '';
                        }
                    ?>
                      <div class="col">
                        <center>
                          <h2><?php echo $nro_espacio; ?></h2>
                          <button class="btn btn-info" id="btn_ocupado<?php echo $id_map; ?>" data-toggle="modal"
                            data-target="#exampleModal<?php echo $id_map; ?>">
                            <!-- Usamos la imagen del auto rojo -->
                            <img src="<?php echo $URL; ?>/public/imagenes/auto1.png" width="60px" alt="Ocupado">
                          </button>

                          <!-- Modal Datos Cliente (Salida/Facturación) -->
                          <div class="modal fade" id="exampleModal<?php echo $id_map; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Datos del cliente (Salida)</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body text-left">
                                  <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Placa:</label>
                                    <div class="col-sm-8">
                                      <input type="text" class="form-control" value="<?php echo $placa_auto; ?>" disabled>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nombre:</label>
                                    <div class="col-sm-8">
                                      <input type="text" class="form-control" value="<?php echo $nombre_cliente; ?>" disabled>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">DNI/RUC:</label>
                                    <div class="col-sm-8">
                                      <input type="text" class="form-control" value="<?php echo $nit_ci; ?>" disabled>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Fecha Ingreso:</label>
                                    <div class="col-sm-8">
                                      <input type="text" class="form-control" value="<?php echo $fecha_ingreso; ?>" disabled>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Hora Ingreso:</label>
                                    <div class="col-sm-8">
                                      <input type="text" class="form-control" value="<?php echo $hora_ingreso; ?>" disabled>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Cúbiculo:</label>
                                    <div class="col-sm-8">
                                      <input type="text" class="form-control" value="<?php echo $nro_espacio; ?>" disabled>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                                  
                                  <!-- Botón Cancelar Ticket (Libera el espacio sin cobrar) -->
                                  <a href="tickets/controller_cancelar_ticket.php?id=<?php echo $id_ticket; ?>&&cuviculo=<?php echo $nro_espacio; ?>" class="btn btn-danger">Cancelar ticket</a>
                                  
                                  <!-- Botón Reimprimir Ticket Entrada -->
                                  <a href="tickets/reimprimir_ticket.php?id=<?php echo $id_ticket; ?>" class="btn btn-primary" target="_blank"><i class="fas fa-print"></i></a>
                                  
                                  <!-- Botón Facturar (Cobrar y Salida) -->
                                  <button type="button" class="btn btn-success" id="btn_facturar<?php echo $id_map;?>">Facturar</button>
                                  
                                  <script>
                                    $('#btn_facturar<?php echo $id_map;?>').click(function() {
                                      var nro_factura = "<?php echo $contador_del_nro_de_factura; ?>";
                                      var id_cliente = "<?php echo $id_cliente_facturacion; ?>";
                                      var fecha_ingreso = "<?php echo $fecha_ingreso; ?>";
                                      var hora_ingreso = "<?php echo $hora_ingreso; ?>";
                                      var cuviculo = "<?php echo $nro_espacio; ?>";
                                      var user_sesion = "<?php echo $usuario_sesion; ?>"; // Cuidado con la variable PHP si cambia de nombre

                                      // Validamos que existan datos antes de enviar (por si acaso)
                                      if(id_cliente === "") {
                                          alert("Error: No se pudo identificar al cliente. Revise el registro.");
                                          return;
                                      }

                                      var url_4 = 'facturacion/controller_registrar_factura.php';
                                      $.get(url_4, {
                                          nro_factura: nro_factura,
                                          id_cliente: id_cliente,
                                          fecha_ingreso: fecha_ingreso,
                                          hora_ingreso: hora_ingreso,
                                          cuviculo: cuviculo,
                                          user_sesion: user_sesion
                                      }, function(datos) {
                                          $('#respuesta_factura<?php echo $id_map;?>').html(datos);
                                      });
                                    });
                                  </script>
                                </div>
                                <div id="respuesta_factura<?php echo $id_map;?>"></div>
                              </div>
                            </div>
                          </div>
                          <p><?php echo $estado_espacio; ?></p>
                        </center>
                      </div>
                    <?php
                    }
                  }
                  ?>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>

    </div>
    <!-- /.content-wrapper -->

    <?php include('layout/admin/footer.php'); ?>

  </div>
  <?php include('layout/admin/footer_link.php'); ?>
</body>

</html>