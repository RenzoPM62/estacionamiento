<?php
include('../app/config.php');
include('../layout/admin/datos_usuario_sesion.php');

// Redirigir si el usuario no tiene permisos
if ($rol_sesion != "ADMINISTRADOR" && $rol_sesion != "CONTADOR") {
    header('Location: ' . $URL . '/');
    exit();
}


// =================================================================
// CONSULTAS A LA BASE DE DATOS PARA LOS KPIs
// =================================================================

// 1. Ingresos de Hoy (Suma de monto_total de facturas de hoy)
$query_ingresos_hoy = $pdo->prepare("SELECT SUM(monto_total) AS ingresos_hoy FROM tb_facturaciones WHERE DATE(fyh_creacion) = CURDATE()");
$query_ingresos_hoy->execute();
$datos_ingresos_hoy = $query_ingresos_hoy->fetch(PDO::FETCH_ASSOC);
$ingresos_hoy = $datos_ingresos_hoy['ingresos_hoy'] ?? 0;

// 2. Tickets de Hoy (Conteo de tickets creados hoy)
$query_tickets_hoy = $pdo->prepare("SELECT COUNT(id_ticket) AS tickets_hoy FROM tb_tickets WHERE DATE(fyh_creacion) = CURDATE()");
$query_tickets_hoy->execute();
$datos_tickets_hoy = $query_tickets_hoy->fetch(PDO::FETCH_ASSOC);
$tickets_hoy = $datos_tickets_hoy['tickets_hoy'] ?? 0;

// 3. Clientes Nuevos de Hoy (Conteo de clientes creados hoy)
$query_clientes_hoy = $pdo->prepare("SELECT COUNT(id_cliente) AS clientes_hoy FROM tb_clientes WHERE DATE(fyh_creacion) = CURDATE() AND estado = '1'");
$query_clientes_hoy->execute();
$datos_clientes_hoy = $query_clientes_hoy->fetch(PDO::FETCH_ASSOC);
$clientes_hoy = $datos_clientes_hoy['clientes_hoy'] ?? 0;

// 4. Espacios Libres (Conteo de espacios libres)
$query_espacios_libres = $pdo->prepare("SELECT COUNT(id_map) AS espacios_libres FROM tb_mapeos WHERE estado_espacio = 'LIBRE' AND estado = '1'");
$query_espacios_libres->execute();
$datos_espacios_libres = $query_espacios_libres->fetch(PDO::FETCH_ASSOC);
$espacios_libres = $datos_espacios_libres['espacios_libres'] ?? 0;


// =================================================================
// CONSULTAS PARA LOS GRÁFICOS
// =================================================================

// 1. Gráfico de Ingresos por Mes (Año Actual)
$meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
$ingresos_por_mes = array_fill(0, 12, 0); // Inicializar array con 12 ceros

$query_ingresos_mes = $pdo->prepare("
    SELECT MONTH(fyh_creacion) AS mes, SUM(monto_total) AS total_mes 
    FROM tb_facturaciones 
    WHERE YEAR(fyh_creacion) = YEAR(CURDATE()) 
    GROUP BY MONTH(fyh_creacion)");
$query_ingresos_mes->execute();
$datos_ingresos_mes = $query_ingresos_mes->fetchAll(PDO::FETCH_ASSOC);

foreach ($datos_ingresos_mes as $dato) {
    $ingresos_por_mes[$dato['mes'] - 1] = $dato['total_mes']; // -1 porque los meses del array empiezan en 0
}
$json_ingresos_mes = json_encode($ingresos_por_mes);
$json_meses = json_encode($meses);


// 2. Gráfico de Afluencia por Hora (Promedio Histórico)
$afluencia_por_hora = array_fill(0, 24, 0); // Array de 24 horas (0-23)
$labels_horas = [];
for ($i = 0; $i < 24; $i++) {
    $labels_horas[] = $i . ':00';
} // Labels para el eje X (0:00, 1:00, etc.)

// Usamos la columna hora_ingreso que es VARCHAR, por eso usamos HOUR()
$query_afluencia = $pdo->prepare("
    SELECT HOUR(hora_ingreso) AS hora, COUNT(id_ticket) AS cantidad 
    FROM tb_tickets 
    GROUP BY HOUR(hora_ingreso)
    ORDER BY hora ASC");
$query_afluencia->execute();
$datos_afluencia = $query_afluencia->fetchAll(PDO::FETCH_ASSOC);

foreach ($datos_afluencia as $dato) {
    $afluencia_por_hora[$dato['hora']] = $dato['cantidad'];
}
$json_afluencia_horas = json_encode($afluencia_por_hora);
$json_labels_horas = json_encode($labels_horas);

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

                <h2>Dashboard - Reportes del Negocio</h2>
                <p>Resumen de la actividad del estacionamiento.</p>

                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>S/ <?php echo number_format($ingresos_hoy, 2); ?></h3>
                                <p>Ingresos de Hoy</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $tickets_hoy; ?></h3>
                                <p>Vehículos Ingresados Hoy</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo $clientes_hoy; ?></h3>
                                <p>Clientes Nuevos Hoy</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?php echo $espacios_libres; ?></h3>
                                <p>Espacios Libres Ahora</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-car"></i>
                            </div>
                        </div>
                    </div>
                    </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Ingresos del Año Actual (S/)</h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="graficoIngresos" style="min-height: 250px; height: 300px; max-height: 350px; width: 100%;"></canvas>
                                </div>
                            </div>
                            </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Afluencia por Hora (Histórico)</h3>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="graficoAfluencia" style="min-height: 250px; height: 300px; max-height: 350px; width: 100%;"></canvas>
                                </div>
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
            /* --- Gráfico de Ingresos (Líneas) --- */
            var ctxIngresos = document.getElementById('graficoIngresos').getContext('2d');
            var dataIngresos = {
                labels: <?php echo $json_meses; ?>,
                datasets: [{
                    label: 'Ingresos (S/)',
                    backgroundColor: 'rgba(60,141,188,0.2)', // Azul
                    borderColor: 'rgba(60,141,188,1)',
                    pointRadius: 5,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: <?php echo $json_ingresos_mes; ?>,
                    fill: true, // Rellenar área bajo la línea
                }]
            };

            new Chart(ctxIngresos, {
                type: 'line',
                data: dataIngresos,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                // Formatear como moneda
                                callback: function(value, index, values) {
                                    return 'S/ ' + value;
                                }
                            }
                        }]
                    }
                }
            });

            /* --- Gráfico de Afluencia (Barras) --- */
            var ctxAfluencia = document.getElementById('graficoAfluencia').getContext('2d');
            var dataAfluencia = {
                labels: <?php echo $json_labels_horas; ?>,
                datasets: [{
                    label: 'Nro. de Tickets',
                    backgroundColor: 'rgba(0,166,90,0.9)', // Verde
                    borderColor: 'rgba(0,166,90,0.8)',
                    data: <?php echo $json_afluencia_horas; ?>,
                }]
            };

            new Chart(ctxAfluencia, {
                type: 'bar',
                data: dataAfluencia,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1 // Asegura que solo muestre enteros
                            }
                        }]
                    }
                }
            });
        });
    </script>
</body>

</html>