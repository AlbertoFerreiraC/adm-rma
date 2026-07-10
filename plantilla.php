<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$ruta = $_GET["ruta"] ?? $_GET["page"] ?? 'inicio';

$rutasPublicas = [
    "login",
    "forgot-password",
    "reset-password",
    "register"
];

$usuarioLogueado = isset($_SESSION["id"]);
$esPublica = in_array($ruta, $rutasPublicas);
$idRol = isset($_SESSION["id_rol"]) ? (int) $_SESSION["id_rol"] : null;

if (!$usuarioLogueado && !$esPublica) {
    $ruta = "login";
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

$esLogin = ($ruta === "login");
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
</head>

<?php
$bodyClass = $esLogin ? 'login-page' : 'hold-transition';
?>

<body class="<?= $bodyClass ?>" style="background-color: #060913;">

    <?php if (!$esLogin): ?>
        <div class="wrapper" style="background-color: #060913;">

            <?php
            include "menu.php";
            ?>

        <?php endif; ?>

        <?php
        switch ($ruta) {

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
            case "proveedores":
            case "notificaciones":
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

        <?php if (!$esLogin): ?>
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