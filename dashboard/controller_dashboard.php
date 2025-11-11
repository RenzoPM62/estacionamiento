<?php
// Incluimos la configuración de la base de datos
include_once('../app/config.php');

// Establecer la zona horaria
date_default_timezone_set("America/Lima");
$fecha_hora_actual = date("Y-m-d H:i:s");

// =================================================================
// 1. KPIs PRINCIPALES (Tarjetas Superiores)
// =================================================================

// --- KPI 1: Ingresos de Hoy ---
$query_ingresos_hoy = $pdo->prepare("SELECT SUM(monto_total) AS ingresos_hoy FROM tb_facturaciones WHERE DATE(fyh_creacion) = CURDATE()");
$query_ingresos_hoy->execute();
$datos_ingresos_hoy = $query_ingresos_hoy->fetch(PDO::FETCH_ASSOC);
$ingresos_hoy = $datos_ingresos_hoy['ingresos_hoy'] ?? 0;

// --- KPI 2: Tickets de Hoy ---
$query_tickets_hoy = $pdo->prepare("SELECT COUNT(id_ticket) AS tickets_hoy FROM tb_tickets WHERE DATE(fyh_creacion) = CURDATE()");
$query_tickets_hoy->execute();
$datos_tickets_hoy = $query_tickets_hoy->fetch(PDO::FETCH_ASSOC);
$tickets_hoy = $datos_tickets_hoy['tickets_hoy'] ?? 0;

// --- KPI 3: Clientes Nuevos de Hoy ---
$query_clientes_hoy = $pdo->prepare("SELECT COUNT(id_cliente) AS clientes_hoy FROM tb_clientes WHERE DATE(fyh_creacion) = CURDATE() AND estado = '1'");
$query_clientes_hoy->execute();
$datos_clientes_hoy = $query_clientes_hoy->fetch(PDO::FETCH_ASSOC);
$clientes_hoy = $datos_clientes_hoy['clientes_hoy'] ?? 0;

// --- KPI 4: Tasa de Ocupación (¡Nuevo!) ---
$query_total_espacios = $pdo->prepare("SELECT COUNT(id_map) AS total FROM tb_mapeos WHERE estado = '1'");
$query_total_espacios->execute();
$total_espacios = $query_total_espacios->fetch(PDO::FETCH_ASSOC)['total'] ?? 1; // Evitar división por cero

$query_ocupados = $pdo->prepare("SELECT COUNT(id_map) AS ocupados FROM tb_mapeos WHERE estado_espacio = 'OCUPADO' AND estado = '1'");
$query_ocupados->execute();
$total_ocupados = $query_ocupados->fetch(PDO::FETCH_ASSOC)['ocupados'] ?? 0;

$tasa_ocupacion = ($total_ocupados / $total_espacios) * 100;
$total_libres = $total_espacios - $total_ocupados;

// --- KPI 5: Ingreso Promedio por Vehículo (¡Nuevo!) ---
$ingreso_promedio_vehiculo = ($tickets_hoy > 0) ? ($ingresos_hoy / $tickets_hoy) : 0;


// =================================================================
// 2. LÓGICA DE REGISTRO DIARIO (Guardar en BD)
// =================================================================

// Verificar si ya existe un reporte para el día de hoy
$query_verificar_reporte = $pdo->prepare("SELECT * FROM tb_reportes_diarios WHERE fecha_reporte = CURDATE()");
$query_verificar_reporte->execute();
$reporte_hoy = $query_verificar_reporte->fetch(PDO::FETCH_ASSOC);

if ($reporte_hoy) {
    // Si existe, ACTUALIZAR
    $sentencia = $pdo->prepare("UPDATE tb_reportes_diarios 
        SET ingresos_dia = :ingresos_dia, 
            tickets_dia = :tickets_dia, 
            clientes_nuevos_dia = :clientes_nuevos_dia, 
            fyh_actualizacion = :fyh_actualizacion 
        WHERE fecha_reporte = CURDATE()");
    
    $sentencia->bindParam(':ingresos_dia', $ingresos_hoy);
    $sentencia->bindParam(':tickets_dia', $tickets_hoy);
    $sentencia->bindParam(':clientes_nuevos_dia', $clientes_hoy);
    $sentencia->bindParam(':fyh_actualizacion', $fecha_hora_actual);
    $sentencia->execute();
} else {
    // Si no existe, INSERTAR
    $sentencia = $pdo->prepare("INSERT INTO tb_reportes_diarios 
        (fecha_reporte, ingresos_dia, tickets_dia, clientes_nuevos_dia, fyh_creacion, estado) 
        VALUES (:fecha_reporte, :ingresos_dia, :tickets_dia, :clientes_nuevos_dia, :fyh_creacion, :estado)");
    
    $fecha_hoy = date('Y-m-d');
    $estado = '1';
    $sentencia->bindParam(':fecha_reporte', $fecha_hoy);
    $sentencia->bindParam(':ingresos_dia', $ingresos_hoy);
    $sentencia->bindParam(':tickets_dia', $tickets_hoy);
    $sentencia->bindParam(':clientes_nuevos_dia', $clientes_hoy);
    $sentencia->bindParam(':fyh_creacion', $fecha_hora_actual);
    $sentencia->bindParam(':estado', $estado);
    $sentencia->execute();
}


// =================================================================
// 3. DATOS PARA GRÁFICOS
// =================================================================

// --- Gráfico 1: Ingresos por Mes (Año Actual) ---
$meses_espanol = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
$ingresos_por_mes = array_fill(0, 12, 0); 
$query_ingresos_mes = $pdo->prepare("
    SELECT MONTH(fyh_creacion) AS mes, SUM(monto_total) AS total_mes 
    FROM tb_facturaciones 
    WHERE YEAR(fyh_creacion) = YEAR(CURDATE()) 
    GROUP BY MONTH(fyh_creacion)");
$query_ingresos_mes->execute();
$datos_ingresos_mes = $query_ingresos_mes->fetchAll(PDO::FETCH_ASSOC);
foreach ($datos_ingresos_mes as $dato) {
    $ingresos_por_mes[$dato['mes'] - 1] = $dato['total_mes'];
}
$json_ingresos_mes = json_encode($ingresos_por_mes);
$json_meses = json_encode($meses_espanol);

// --- Gráfico 2: Afluencia por Hora (Histórico) ---
$afluencia_por_hora = array_fill(0, 24, 0); 
$labels_horas = [];
for ($i = 0; $i < 24; $i++) {
    $labels_horas[] = $i . ':00';
} 
$query_afluencia = $pdo->prepare("
    SELECT HOUR(hora_ingreso) AS hora, COUNT(id_ticket) AS cantidad 
    FROM tb_tickets 
    GROUP BY HOUR(hora_ingreso)
    ORDER BY hora ASC");
$query_afluencia->execute();
$datos_afluencia = $query_afluencia->fetchAll(PDO::FETCH_ASSOC);
foreach ($datos_afluencia as $dato) {
    $afluencia_por_hora[intval($dato['hora'])] = $dato['cantidad'];
}
$json_afluencia_horas = json_encode($afluencia_por_hora);
$json_labels_horas = json_encode($labels_horas);


// --- Gráfico 3: Top 5 Clientes por Placa (¡Nuevo!) ---
$labels_top_clientes = [];
$data_top_clientes = [];
$query_top_clientes = $pdo->prepare("
    SELECT placa_auto, COUNT(id_ticket) AS visitas 
    FROM tb_tickets 
    GROUP BY placa_auto 
    ORDER BY visitas DESC 
    LIMIT 5");
$query_top_clientes->execute();
$datos_top_clientes = $query_top_clientes->fetchAll(PDO::FETCH_ASSOC);
foreach ($datos_top_clientes as $cliente) {
    $labels_top_clientes[] = $cliente['placa_auto'];
    $data_top_clientes[] = $cliente['visitas'];
}
$json_labels_top_clientes = json_encode($labels_top_clientes);
$json_data_top_clientes = json_encode($data_top_clientes);


// --- Gráfico 4: Histórico de Ingresos y Clientes (Últimos 30 días) (¡Nuevo!) ---
$labels_historico = [];
$data_historico_ingresos = [];
$data_historico_clientes = [];
$query_historico = $pdo->prepare("
    SELECT fecha_reporte, ingresos_dia, clientes_nuevos_dia 
    FROM tb_reportes_diarios 
    WHERE fecha_reporte >= CURDATE() - INTERVAL 30 DAY 
    ORDER BY fecha_reporte ASC");
$query_historico->execute();
$datos_historico = $query_historico->fetchAll(PDO::FETCH_ASSOC);
foreach ($datos_historico as $dato) {
    // Formatear la fecha a "dd/mm"
    $labels_historico[] = date('d/m', strtotime($dato['fecha_reporte']));
    $data_historico_ingresos[] = $dato['ingresos_dia'];
    $data_historico_clientes[] = $dato['clientes_nuevos_dia'];
}
$json_labels_historico = json_encode($labels_historico);
$json_data_historico_ingresos = json_encode($data_historico_ingresos);
$json_data_historico_clientes = json_encode($data_historico_clientes);

// =================================================================
// 4. DATOS PARA FORMULARIO DE REPORTES
// =================================================================

// Consultar los años únicos que tienen reportes en la base de datos
$query_anios = $pdo->prepare("SELECT DISTINCT YEAR(fecha_reporte) AS anio FROM tb_reportes_diarios ORDER BY anio DESC");
$query_anios->execute();
$anios_disponibles = $query_anios->fetchAll(PDO::FETCH_ASSOC);

// Array de meses para el formulario
$meses_formulario = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
    7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
?>