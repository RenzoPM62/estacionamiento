<?php
include('../app/config.php');
include('../layout/admin/datos_usuario_sesion.php');

// Incluir el controlador que tiene toda la lógica y los datos
include('controller_dashboard.php');

// Redirigir si el usuario no tiene permisos
if ($rol_sesion != "ADMINISTRADOR" && $rol_sesion != "CONTADOR") {
    header('Location: ' . $URL . '/');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include('../layout/admin/head.php'); ?>
    <script src="<?php echo $URL; ?>/app/templeates/AdminLTE-3.0.5/plugins/chart.js/Chart.min.js"></script>
    <title>Dashboard - Estacionamiento</title>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <?php include('../layout/admin/menu.php'); ?>

        <div class="content-wrapper">
            <br>
            <div class="container">

                <div class="row">
                    <div class="col-md-9">
                        <h2>Dashboard - Reportes del Negocio</h2>
                        <p>Resumen de la actividad del estacionamiento.</p>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="generar_reporte.php" target="_blank" class="btn btn-primary">
                            <i class="fas fa-file-pdf"></i> Generar Reporte del Día
                        </a>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>S/ <?php echo number_format($ingresos_hoy, 2); ?></h3>
                                <p>Ingresos de Hoy</p>
                            </div>
                            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $tickets_hoy; ?></h3>
                                <p>Vehículos Ingresados Hoy</p>
                            </div>
                            <div class="icon"><i class="fas fa-ticket-alt"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?php echo number_format($tasa_ocupacion, 1); ?> <sup style="font-size: 20px">%</sup></h3>
                                <p>Ocupación Actual (<?php echo $total_ocupados . "/" . $total_espacios; ?>)</p>
                            </div>
                            <div class="icon"><i class="fas fa-car"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3>S/ <?php echo number_format($ingreso_promedio_vehiculo, 2); ?></h3>
                                <p>Ingreso Promedio / Vehículo</p>
                            </div>
                            <div class="icon"><i class="fas fa-chart-pie"></i></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Generar Reportes Históricos (PDF)</h3>
                            </div>
                            <div class="card-body">
                                <form action="generar_reporte_historico.php" method="GET" target="_blank">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="tipo_reporte">Tipo de Reporte</label>
                                                <select class="form-control" name="tipo_reporte" id="tipo_reporte" required>
                                                    <option value="mensual">Mensual</option>
                                                    <option value="anual">Anual</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="mes">Mes</label>
                                                <select class="form-control" name="mes" id="mes">
                                                    <?php foreach ($meses_formulario as $num => $nombre) {
                                                        // Selecciona el mes actual por defecto
                                                        $selected = ($num == date('m')) ? 'selected' : '';
                                                        echo "<option value=\"$num\" $selected>$nombre</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="anio">Año</label>
                                                <select class="form-control" name="anio" id="anio" required>
                                                    <?php
                                                    $anio_actual = date('Y');
                                                    $anios_en_lista = [];
                                                    foreach ($anios_disponibles as $anio_data) {
                                                        $anio_item = $anio_data['anio'];
                                                        $anios_en_lista[] = $anio_item;
                                                        // Selecciona el año actual por defecto
                                                        $selected = ($anio_item == $anio_actual) ? 'selected' : '';
                                                        echo "<option value=\"$anio_item\" $selected>$anio_item</option>";
                                                    }
                                                    // Agregar año actual si no está en la lista de reportes (por si es un año nuevo sin datos)
                                                    if (!in_array($anio_actual, $anios_en_lista)) {
                                                        echo "<option value=\"$anio_actual\" selected>$anio_actual</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-chart-bar"></i> Generar Reporte</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <small class="text-muted">
                                    * Para reportes <b>Mensuales</b>, seleccione el mes y el año.<br>
                                    * Para reportes <b>Anuales</b>, solo seleccione el año (el mes será ignorado).
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Actividad de los Últimos 30 Días</h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="graficoHistorico" style="min-height: 250px; height: 300px; max-height: 350px; width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Ingresos del Año Actual (S/)</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="graficoIngresos" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Top 5 Clientes Frecuentes (por Placa)</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="graficoTopClientes" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Afluencia por Hora (Total Histórico)</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="graficoAfluencia" style="min-height: 565px; height: 565px; max-height: 565px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php include('../layout/admin/footer.php'); ?>

    </div>
    <?php include('../layout/admin/footer_link.php'); ?>

    <script>
        $(function() {
            /* --- Gráfico 1: Ingresos (Líneas) --- */
            var ctxIngresos = document.getElementById('graficoIngresos').getContext('2d');
            new Chart(ctxIngresos, {
                type: 'line',
                data: {
                    labels: <?php echo $json_meses; ?>,
                    datasets: [{
                        label: 'Ingresos (S/)',
                        backgroundColor: 'rgba(60,141,188,0.2)',
                        borderColor: 'rgba(60,141,188,1)',
                        data: <?php echo $json_ingresos_mes; ?>,
                        fill: true,
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            /* --- Gráfico 2: Afluencia (Barras) --- */
            var ctxAfluencia = document.getElementById('graficoAfluencia').getContext('2d');
            new Chart(ctxAfluencia, {
                type: 'bar',
                data: {
                    labels: <?php echo $json_labels_horas; ?>,
                    datasets: [{
                        label: 'Nro. de Tickets',
                        backgroundColor: 'rgba(0,166,90,0.9)',
                        borderColor: 'rgba(0,166,90,0.8)',
                        data: <?php echo $json_afluencia_horas; ?>,
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            /* --- Gráfico 3: Top Clientes (Barras Horizontales) (¡Nuevo!) --- */
            var ctxTopClientes = document.getElementById('graficoTopClientes').getContext('2d');
            new Chart(ctxTopClientes, {
                type: 'horizontalBar', // Gráfico de barras horizontales
                data: {
                    labels: <?php echo $json_labels_top_clientes; ?>,
                    datasets: [{
                        label: 'Nro. de Visitas',
                        backgroundColor: 'rgba(243, 156, 18, 0.9)', // Naranja
                        borderColor: 'rgba(243, 156, 18, 0.8)',
                        data: <?php echo $json_data_top_clientes; ?>,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1 // Asegura que solo muestre enteros
                            }
                        }]
                    }
                }
            });

            /* --- Gráfico 4: Histórico (Líneas Múltiples) (¡Nuevo!) --- */
            var ctxHistorico = document.getElementById('graficoHistorico').getContext('2d');
            new Chart(ctxHistorico, {
                type: 'line',
                data: {
                    labels: <?php echo $json_labels_historico; ?>,
                    datasets: [
                        {
                            label: 'Ingresos (S/)',
                            backgroundColor: 'rgba(60,141,188,0.2)',
                            borderColor: 'rgba(60,141,188,1)',
                            data: <?php echo $json_data_historico_ingresos; ?>,
                            fill: true,
                            yAxisID: 'y-axis-ingresos' // Asignar al eje Y izquierdo
                        },
                        {
                            label: 'Clientes Nuevos',
                            backgroundColor: 'rgba(0,166,90,0.2)',
                            borderColor: 'rgba(0,166,90,1)',
                            data: <?php echo $json_data_historico_clientes; ?>,
                            fill: true,
                            yAxisID: 'y-axis-clientes' // Asignar al eje Y derecho
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [
                            {
                                id: 'y-axis-ingresos',
                                type: 'linear',
                                position: 'left',
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) { return 'S/ ' + value; }
                                }
                            },
                            {
                                id: 'y-axis-clientes',
                                type: 'linear',
                                position: 'right',
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1 // Solo enteros
                                },
                                gridLines: {
                                    drawOnChartArea: false, // No mostrar cuadrícula para este eje
                                }
                            }
                        ]
                    }
                }
            });

            
            /* --- Lógica para el formulario de reportes (¡Nuevo!) --- */
            $('#tipo_reporte').on('change', function() {
                if ($(this).val() === 'anual') {
                    $('#mes').prop('disabled', true);
                } else {
                    $('#mes').prop('disabled', false);
                }
            });
            // Ejecutar al cargar para setear estado inicial
            if ($('#tipo_reporte').val() === 'anual') {
                 $('#mes').prop('disabled', true);
            }

        });
    </script>
</body>
</html>