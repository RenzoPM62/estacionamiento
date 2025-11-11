CREATE TABLE tb_reportes_diarios (
    id_reporte          INT (11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_reporte       DATE NOT NULL UNIQUE,
    ingresos_dia        DECIMAL(10, 2) NULL,
    tickets_dia         INT (11) NULL,
    clientes_nuevos_dia INT (11) NULL,
    fyh_creacion        DATETIME NULL,
    fyh_actualizacion   DATETIME NULL,
    estado              VARCHAR (10)
);