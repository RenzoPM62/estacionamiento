# 🚗 Sistema de Estacionamiento MERCADO HUASCAR

Este es un sistema web para la gestión de un estacionamiento (parqueo) desarrollado en **PHP** de forma nativa. Permite administrar el ingreso y salida de vehículos, gestionar clientes, generar tickets, manejar la facturación y administrar usuarios y roles dentro del sistema.

## ✨ Características Principales

El sistema cuenta con varios módulos para una gestión completa:

* **Autenticación:** Sistema de Login y Logout para usuarios.
* **Dashboard Principal:** Una vista principal (`principal.php`) que muestra información relevante.
* **Gestión de Usuarios:** CRUD (Crear, Leer, Actualizar, Borrar) para los usuarios del sistema.
* **Gestión de Roles:** Permite crear roles (ej. Administrador, Operador) y asignarlos a los usuarios.
* **Gestión de Clientes:** CRUD para registrar y administrar los clientes del estacionamiento.
* **Gestión de Precios:** Interfaz para definir las tarifas y precios del servicio.
* **Mapeo de Parqueo:** Una interfaz (`parqueo/mapeo-de-vehiculos.php`) para visualizar los espacios del estacionamiento y cambiar su estado (disponible, ocupado).
* **Tickets:**
    * Generación de tickets de ingreso (`tickets/generar_ticket.php`).
    * Cancelación y reimpresión de tickets.
* **Facturación:**
    * Módulo para generar facturas basadas en los tickets (`facturacion/controller_registrar_factura.php`).
    * Generación de reportes en PDF.

## 🛠️ Tecnologías y Librerías

* **Backend:** **PHP** (Nativo, sin frameworks principales).
* **Frontend:** **AdminLTE 3** (basado en Bootstrap 4 y jQuery).
* **Base de Datos:** **MySQL** (scripts de tablas en la carpeta `bd_tables/`).
* **Generación de PDF:** **TCPDF** (para crear reportes, tickets y facturas).

## 🚀 Instalación y Puesta en Marcha

Sigue estos pasos para configurar el proyecto en tu entorno local:

1.  **Clonar el repositorio:**
    ```bash
    git clone [https://github.com/tu-usuario/estacionamiento.git](https://github.com/tu-usuario/estacionamiento.git)
    cd estacionamiento
    ```

2.  **Configurar la Base de Datos:**
    * Crea una base de datos en tu gestor de MySQL (por ejemplo, `estacionamiento_db`).
    * Importa todos los archivos `.sql` que se encuentran en la carpeta `/bd_tables` para crear la estructura de las tablas.

3.  **Configurar la Conexión:**
    * Edita el archivo `app/config.php`.
    * Actualiza las variables (`$servidor`, `$usuario`, `$password`, `$bd`) con tus credenciales de la base de datos que creaste en el paso anterior.

4.  **Iniciar el Servidor:**
    * Asegúrate de tener un servidor web (como XAMPP, WAMP o MAMP) corriendo Apache y MySQL.
    * Coloca la carpeta del proyecto en el directorio `htdocs` (o `www`) de tu servidor.
    * Accede al proyecto desde tu navegador (ej. `http://localhost/estacionamiento/`).

5.  **Acceder:**
    * Puedes usar las credenciales de usuario que hayas creado en tu base de datos (revisa `tb_usuarios`).

## 📁 Estructura del Proyecto
/estacionamiento

├── app

│   ├── config.php         # Configuración de la BD y del sistema

│   └── templeates         # Plantillas (AdminLTE, TCPDF)

├── bd_tables              # Scripts SQL para las tablas

├── clientes               # Módulo de Clientes

├── facturacion            # Módulo de Facturación

├── layout                 # Vistas reutilizables (header, footer, menu)

├── login                  # Lógica de autenticación y cierre de sesión

├── parqueo                # Módulo principal de estacionamiento y mapeo

├── precios                # Módulo de gestión de precios

├── public                 # Archivos públicos (CSS, JS, imágenes)

├── roles                  # Módulo de Roles

├── tickets                # Módulo de Tickets

├── usuarios               # Módulo de Usuarios

├── index.php              # Página de login

└── principal.php          # Dashboard principal (después de iniciar sesión)
