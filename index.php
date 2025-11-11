<?php include('app/config.php'); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estacionamiento Mercado Huascar</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body style="background-image: url('public/imagenes/arena.jpg');
    background-repeat: no-repeat;
    z-index: -3;
    background-size: 100vw 100vh;
    background-attachment: fixed;">


<nav class="navbar navbar-expand-lg" style="background-color: #FFE066;">
    <a class="navbar-brand" href="#">
        <img src="<?php echo $URL; ?>/public/imagenes/mercado_huascar.png" width="30" height="40" alt="" loading="lazy">
        ESTACIONAMIENTO HUASCAR
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#" id="btn-inicio">INICIO <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="btn-sobre-nosotros">SOBRE NOSOTROS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="btn-otros-servicios">OTROS SERVICIOS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="btn-contactanos">CONTACTANOS</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        </form>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Ingresar
        </button>
    </div>
</nav>
    
    <br>

    <div class="container">
    
    <div class="row" id="mapa-estacionamiento">
        <?php
        $query_mapeos = $pdo->prepare("SELECT * FROM tb_mapeos WHERE estado = '1' ");
        $query_mapeos->execute();
        $mapeos = $query_mapeos->fetchAll(PDO::FETCH_ASSOC);
        foreach ($mapeos as $mapeo) {
            $id_map = $mapeo['id_map'];
            $nro_espacio = $mapeo['nro_espacio'];
            $estado_espacio = $mapeo['estado_espacio'];
            if ($estado_espacio == 'LIBRE') { ?>
                <div class="col mb-3">
                    <center>
                        <h5><?php echo $nro_espacio; ?></h5>
                        <button class="btn btn-success" style="width: 100%; height: 114px">
                            <p><?php echo $estado_espacio; ?></p>
                        </button>
                    </center>
                </div>
            <?php
            }
            if ($estado_espacio == 'OCUPADO') { ?>
                <div class="col mb-3">
                    <center>
                        <h5><?php echo $nro_espacio; ?></h5>
                        <button class="btn btn-info" style="width: 100%; height: 114px">
                            <img src="<?php echo $URL; ?>/public/imagenes/auto1.png" width="60px" alt="">
                        </button>
                        <p><?php echo $estado_espacio; ?></p>
                    </center>
                </div>
            <?php
            }
            ?>

        <?php
        }
        ?>
    </div>
    
    <div id="info-sobre-nosotros" class="info-section" style="display: none;">
        <div class="card shadow-sm" style="background-color: #ffffffd0;">
            <div class="card-header">
                <h3>Nuestra Historia y Compromiso</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <h4 class="card-title">Bienvenidos al Mercado Huascar</h4>
                        <p class="card-text mt-3">
                            Desde <b>1966</b>, el Mercado Huascar ha sido el corazón y el principal centro de abastecimiento zonal de nuestra querida comunidad. Con <b>59 años de historia</b>, hemos crecido junto a las familias, adaptándonos a sus necesidades y manteniendo siempre la tradición.
                        </p>
                        <p class="card-text">
                            Somos un mercado minorista de construcción noble que alberga 110 puestos fijos, con más de 60 comerciantes activos que trabajan día a día para ofrecerte productos frescos y de la mejor calidad. Administrados por nuestra propia Junta Directiva de propietarios, nuestro compromiso es directo contigo.
                        </p>
                        <p class="card-text">
                            Nuestras instalaciones cuentan con todos los servicios esenciales (energía eléctrica, agua y alcantarillado) para garantizar una experiencia de compra y visita cómoda, segura e higiénica.
                        </p>
                    </div>
                    <div class="col-md-5">
                        <img src="<?php echo $URL; ?>/public/imagenes/fachada.jpeg" class="img-fluid rounded shadow-sm" alt="Fachada del Mercado Huascar">
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12">
                        <h4>Nuestras Instalaciones</h4>
                    </div>
                    <div class="col-md-4 mt-2">
                        <img src="<?php echo $URL; ?>/public/imagenes/pasillo_mercado.jpeg" class="img-fluid rounded shadow-sm" alt="Pasillos del Mercado">
                    </div>
                    <div class="col-md-4 mt-2">
                        <img src="<?php echo $URL; ?>/public/imagenes/area_estacionamiento.jpeg" class="img-fluid rounded shadow-sm" alt="Área de estacionamiento">
                    </div>
                    <div class="col-md-4 mt-2">
                        <img src="<?php echo $URL; ?>/public/imagenes/comerciantes_mercado.jpeg"class="img-fluid rounded shadow-sm" alt="Comerciantes del mercado">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="info-otros-servicios" class="info-section" style="display: none;">
        <div class="card shadow-sm" style="background-color: #ffffffd0;">
            <div class="card-header">
                <h3>Más que una Cochera</h3>
            </div>
            <div class="card-body">
                
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <img src="<?php echo $URL; ?>/public/imagenes/local_baile.jpg" class="img-fluid rounded shadow-sm" alt="Salón de Eventos Huascar">

                    </div>
                    <div class="col-md-7">
                        <h4>Alquiler de Salón Multieventos</h4>
                        <p class="card-text">
                            ¿Buscas el lugar perfecto para tu celebración? Contamos con un amplio y versátil salón ideal para todo tipo de eventos: <b>cumpleaños, aniversarios, bautizos, reuniones corporativas y más.</b>
                        </p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" style="background-color: transparent;">Espacio adaptable a tus necesidades.</li>
                            <li class="list-group-item" style="background-color: transparent;">Servicios higiénicos completos.</li>
                            <li class="list-group-item" style="background-color: transparent;">Ubicación céntrica y segura.</li>
                        </ul>
                        <p class="card-text mt-3">
                            <b>¡Pregunta por nuestras tarifas especiales!</b> Ponte en contacto con nosotros para consultar fechas y paquetes disponibles.
                        </p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4>¡Visita y Apoya el Corazón del Barrio!</h4>
                        <p class="card-text">
                            Somos más que solo un estacionamiento; somos el punto de encuentro de la comunidad. Mientras dejas tu vehículo seguro con nosotros, te invitamos a recorrer los pasillos de nuestro mercado.
                        </p>
                        <p class="card-text">
                            Apoya al comercio local y descubre la increíble variedad de productos frescos que nuestros más de 60 puestos tienen para ofrecerte:
                        </p>
                        <ul>
                            <li>Frutas y verduras frescas.</li>
                            <li>Carnes y abarrotes de primera calidad.</li>
                            <li>Puestos de comida y servicios varios.</li>
                        </ul>
                        <p><b>¡Todo lo que necesitas, en un solo lugar!</b></p>
                    </div>
                    <div class="col-md-5">
                        <img src="<?php echo $URL; ?>/public/imagenes/frutas_verduras.jpg" class="img-fluid rounded shadow-sm" alt="Productos del Mercado">
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="info-contactanos" class="info-section" style="display: none;">
        <div class="card shadow-sm" style="background-color: #ffffffd0;">
            <div class="card-header">
                <h3>Mantente en Contacto</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p>¿Tienes dudas o deseas reservar nuestro salón? ¡Estamos aquí para ayudarte!</p>
                        
                        <h5 class="mt-4"><i class="fas fa-map-marker-alt text-danger"></i> Nuestra Ubicación</h5>
                        <p>Av. La Unión 1199<br>Villa María del Triunfo 15837, Lima - Perú</p>
                        
                        <h5 class="mt-4"><i class="fas fa-phone text-success"></i> Teléfono / WhatsApp</h5>
                        <p>+51 987 566 151</p> 
                        <p>+51 922 448 331</p>

                        <h5 class="mt-4"><i class="fas fa-envelope text-primary"></i> Correo Electrónico</h5>
                        <p>informes@mercadohuascar.pe</p>

                        <h5 class="mt-4"><i class="fas fa-share-alt text-info"></i> Síguenos en Redes</h5>
                        <p>Entérate de nuestras novedades, ofertas y eventos especiales.</p>
                        <p>
                            <a href="https://www.facebook.com/" target="_blank" class="btn btn-outline-primary btn-lg m-1" style="width: 60px;"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/" target="_blank" class="btn btn-outline-danger btn-lg m-1" style="width: 60px;"><i class="fab fa-instagram"></i></a>
                            <a href="https://wa.me/51987566151" target="_blank" class="btn btn-outline-success btn-lg m-1" style="width: 60px;"><i class="fab fa-whatsapp"></i></a>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1950.0015294837783!2d-76.94920209380389!3d-12.180194599999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105b91988a1d735%3A0x5815e8b5f68b2ab4!2sMercado%20Cooperativa%20Huascar!5e0!3m2!1ses-419!2spe!4v1762884186736!5m2!1ses-419!2spe" 
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        <small class="form-text text-muted"> Mapa de ubicación insertado desde Google Maps.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

    <script src="public/js/jquery-3.5.1.min.js"></script>
    <script src="public/js/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="public/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
</body>

</html>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Inicio de Sesión</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Usuario/Email</label>
                            <input type="text" id="usuario" class="form-control" placeholder="Escribe tu email">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Contraseña</label>
                            <input type="password" id="password" class="form-control" placeholder="Escribe tu contraseña">
                        </div>
                    </div>
                </div>
                <div id="respuesta">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_ingresar">Ingresar</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        
        // --- LÓGICA DE LOGIN (Tu código original) ---
        $('#btn_ingresar').click(function() {
            login();
        });

        $('#password').keypress(function(e) {
            if (e.which == 13) {
                login();
            }
        });

        function login() {
            var usuario = $('#usuario').val();
            var password_user = $('#password').val();

            if (usuario == "") {
                alert('Debe Introducir su Usuario...');
                $('#usuario').focus();
            } else if (password_user == "") {
                alert('Debe Introducir su Contraseña...');
                $('#password').focus();
            } else {
                var url = 'login/controller_login.php'
                $.post(url, {
                    usuario: usuario,
                    password_user: password_user
                }, function(datos) {
                    $('#respuesta').html(datos);
                });
            }
        }
        
        // --- NUEVA LÓGICA PARA MOSTRAR/OCULTAR SECCIONES ---
        
        // Función para ocultar todo y resetear nav
        function ocultarTodo() {
            $('#mapa-estacionamiento').hide();
            $('.info-section').hide(); // Oculta todas las secciones de info
            $('.navbar-nav .nav-item').removeClass('active'); // Quita la clase activa de todos
        }

        // Botón INICIO
        $('#btn-inicio').click(function(e) {
            e.preventDefault(); // Evita que la página recargue
            ocultarTodo();
            $('#mapa-estacionamiento').fadeIn(); // Muestra el mapa
            $(this).parent().addClass('active'); // Activa este botón
        });

        // Botón SOBRE NOSOTROS
        $('#btn-sobre-nosotros').click(function(e) {
            e.preventDefault();
            ocultarTodo();
            $('#info-sobre-nosotros').fadeIn(); // Muestra la sección
            $(this).parent().addClass('active'); // Activa este botón
        });

        // Botón OTROS SERVICIOS
        $('#btn-otros-servicios').click(function(e) {
            e.preventDefault();
            ocultarTodo();
            $('#info-otros-servicios').fadeIn(); // Muestra la sección
            $(this).parent().addClass('active'); // Activa este botón
        });
        
        // Botón CONTACTANOS
        $('#btn-contactanos').click(function(e) {
            e.preventDefault();
            ocultarTodo();
            $('#info-contactanos').fadeIn(); // Muestra la sección
            $(this).parent().addClass('active'); // Activa este botón
        });
        
        // --- Lógica para el modal ---
        $('#exampleModal').on('shown.bs.modal', function () {
            $('#usuario').trigger('focus') // Pone el foco en el campo usuario al abrir el modal
        });

    });
</script>