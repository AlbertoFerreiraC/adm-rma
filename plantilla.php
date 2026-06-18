<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =========================
   RUTA ACTUAL
========================= */
$ruta = $_GET["ruta"] ?? $_GET["page"] ?? 'inicio';

/* =========================
   RUTAS PUBLICAS
========================= */
$rutasPublicas = [
    "login",
    "forgot-password",
    "reset-password",
    "register"
];

/* =========================
   CONTROL DE ACCESO
========================= */
$usuarioLogueado = isset($_SESSION["id"]);
$esPublica = in_array($ruta, $rutasPublicas);
$idRol = $_SESSION["id_rol"] ?? null;

/* Forzar login */
if (!$usuarioLogueado && !$esPublica) {
    $ruta = "login";
}

/* Evitar volver a login si ya tiene sesión iniciada */
if ($usuarioLogueado && $ruta === "login") {
    if ((int) $idRol === 1) {
        $ruta = "perfil_tecnico";
    } else {
        $ruta = "inicio";
    }
}

/* ========================================================
   RE-DIRECCIONAMIENTO AUTOMÁTICO EN LA RAÍZ O DEFENSA DE URL
======================================================== */
if ($usuarioLogueado) {
    // Si intenta ir a 'inicio' pero es Técnico, forzar a su plantilla dedicada
    if ((int) $idRol === 1 && $ruta === "inicio") {
        $ruta = "perfil_tecnico";
    }
    // Si es Administrador e intenta ir a la vista del técnico, forzar a la pantalla global
    if ((int) $idRol === 2 && $ruta === "perfil_tecnico") {
        $ruta = "inicio";
    }
}

$esLogin = ($ruta === "login");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>.::MICROEXPRESS::.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="vistas/img/plantilla/icono.png">

    <link rel="stylesheet" href="vistas/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vistas/bower_components/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="vistas/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="vistas/dist/css/skins/skin-blue.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<?php
$bodyClass = $esLogin
    ? 'login-page'
    : 'skin-blue sidebar-mini hold-transition';
?>

<body class="<?= $bodyClass ?>">

    <?php if (!$esLogin): ?>
        <div class="wrapper">
        <?php endif; ?>


        <?php
        /* =========================
           ROUTER
        ========================= */
        switch ($ruta) {

            case "dashboard":
                if ($usuarioLogueado && ($_SESSION["tipo_usuario"] ?? '') === "comercial") {
                    include __DIR__ . "/modulos/dashboard_comerciante.php";
                } else {
                    include __DIR__ . "/modulos/dashboard.php";
                }
                break;

            case "login":
                include __DIR__ . "/vistas/modulos/inicio/login.php";
                break;

            case "forgot-password":
                include __DIR__ . "/modulos/forgot-password.php";
                break;

            case "reset-password":
                include __DIR__ . "/modulos/reset-password.php";
                break;

            case "register":
                include __DIR__ . "/modulos/register.php";
                break;

            case "inicio":
                include __DIR__ . "/vistas/modulos/inicio/inicio.php";
                break;

            case "perfil_tecnico":
                include __DIR__ . "/vistas/modulos/tecnico/perfil_tecnico.php";
                break;

            default:
                include __DIR__ . "/modulos/404.php";
                break;
        }
        ?>

        <?php if (!$esLogin): ?>
        </div>
    <?php endif; ?>


    <!-- JS -->
    <script src="vistas/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="vistas/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="vistas/dist/js/adminlte.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="vistas/js/login.js"></script>
    <script src="vistas/js/plantilla.js"></script>

</body>

</html>