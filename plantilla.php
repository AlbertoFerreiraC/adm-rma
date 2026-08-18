<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

/* =========================================================
   1. RUTA POR DEFECTO: Apunta a 'consulta'
========================================================= */
$ruta = $_GET["ruta"] ?? $_GET["page"] ?? 'consulta';

/* =========================
   RUTAS PUBLICAS
========================= */
$rutasPublicas = [
    "consulta",
    "login",
    "forgot-password",
    "reset-password",
    "register"
];

$usuarioLogueado = isset($_SESSION["id"]);
$esPublica = in_array($ruta, $rutasPublicas);
$idRol = isset($_SESSION["id_rol"]) ? (int) $_SESSION["id_rol"] : null;

/* =========================================================
   2. CONTROL DE ACCESO
========================================================= */
if (!$usuarioLogueado && !$esPublica) {
    $ruta = "consulta";
}

if ($usuarioLogueado && $ruta === "login") {
    if ($idRol === 1) {
        $ruta = "perfil_tecnico";
    } else {
        $ruta = "inicio";
    }
}

if ($usuarioLogueado) {
    if ($idRol === 1 && $ruta === "inicio") {
        $ruta = "perfil_tecnico";
    }
    if ($idRol === 2 && $ruta === "perfil_tecnico") {
        $ruta = "inicio";
    }
}

$sinMenuPrivado = $esPublica;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>.::MICROEXPRESS :: RMA CONTROL::.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="vistas/img/plantilla/icono.png">

    <link rel="stylesheet" href="vistas/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vistas/bower_components/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" href="vistas/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="vistas/dist/css/skins/skin-blue.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- CSS Unificado Cyberpunk Light -->
    <link rel="stylesheet" href="vistas/css/estilos.css">

    <!-- Estilos de Ajuste Global para la Nueva Paleta -->
    <style>
        body,
        .wrapper,
        .content-wrapper {
            background-color: #f0f4f8 !important;
        }

        .content-wrapper,
        .main-footer,
        .main-header {
            margin-left: 0 !important;
        }

        .wrapper {
            background-image:
                radial-gradient(circle at 50% 10%, rgba(2, 132, 199, 0.05) 0%, transparent 60%),
                linear-gradient(rgba(203, 213, 225, 0.2) 1px, transparent 1px),
                linear-gradient(90deg, rgba(203, 213, 225, 0.2) 1px, transparent 1px) !important;
            background-size: 100% 100%, 20px 20px, 20px 20px !important;
            min-height: 100vh;
            overflow-x: hidden;
        }
    </style>
</head>

<?php
$bodyClass = $sinMenuPrivado ? 'login-page' : 'hold-transition skin-blue layout-top-nav';
?>

<body class="<?= $bodyClass ?>" style="background-color: #f0f4f8;">

    <?php if (!$sinMenuPrivado): ?>
        <div class="wrapper">
            <?php
            include "menu.php";
            ?>

        <?php endif; ?>

        <?php
        /* =========================
           ROUTER CENTRALIZADO
        ========================= */
        switch ($ruta) {

            case "consulta":
                include __DIR__ . "/vistas/modulos/publico/consulta.php";
                break;

            case "login":
                include __DIR__ . "/vistas/modulos/inicio/login.php";
                break;

            case "salir":
                include __DIR__ . "/salir.php";
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
            case "dashboard":
                include __DIR__ . "/vistas/modulos/inicio/inicio.php";
                break;

            case "perfil_tecnico":
                include __DIR__ . "/vistas/modulos/tecnico/perfil_tecnico.php";
                break;

            case "usuarios":
                include __DIR__ . "/vistas/modulos/clientes/usuarios.php";
                break;

            case "roles":
                include __DIR__ . "/vistas/modulos/clientes/roles.php";
                break;

            case "perfil":
                include __DIR__ . "/vistas/modulos/clientes/perfil.php";
                break;

            case "configuracion":
                include __DIR__ . "/vistas/modulos/clientes/configuracion.php";
                break;

            case "nuevoCaso":
                include __DIR__ . "/vistas/modulos/rma-core/nuevoCaso.php";
                break;

            case "bandejaCasos":
                include __DIR__ . "/vistas/modulos/rma-core/bandejaCasos.php";
                break;

            case "taller":
                include __DIR__ . "/vistas/modulos/rma-core/taller.php";
                break;

            case "proveedoresRma":
                include __DIR__ . "/vistas/modulos/rma-core/proveedoresRma.php";
                break;

            case "historialEstado":
                include __DIR__ . "/vistas/modulos/rma-core/historialEstado.php";
                break;

            case "clientes":
                include __DIR__ . "/vistas/modulos/clientes/clientes.php";
                break;

            case "proveedores":
                include __DIR__ . "/vistas/modulos/clientes/proveedores.php";
                break;

            case "notificaciones":
                include __DIR__ . "/vistas/modulos/clientes/notificaciones.php";
                break;

            case "sla-procesos":
            case "performance-tecnico":
            case "confiabilidad-producto":
            case "auditoria-proveedores":
            case "recurrencia-clientes":
                include __DIR__ . "/vistas/modulos/inicio/inicio.php";
                break;

            default:
                include __DIR__ . "/modulos/404.php";
                break;
        }
        ?>

        <?php if (!$sinMenuPrivado): ?>
        </div>
    <?php endif; ?>

    <script src="vistas/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="vistas/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="vistas/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="vistas/js/login.js"></script>
    <script src="vistas/js/plantilla.js"></script>

</body>

</html>