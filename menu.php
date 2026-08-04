<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id_rol = $_SESSION['id_rol'] ?? null;
?>

<nav class="cyber-nav-hub glass-panel-neon">
    <div class="nav-scan-lines"></div>
    <ul class="nav-nodes-list">

        <?php if ($id_rol == 2): ?>
            <li class="nav-node-dropdown border-neon-blue">
                <a href="#" class="node-trigger text-neon-blue">
                    <i class="fa fa-shield"></i> [01] SYS_AUTH
                </a>
                <ul class="dropdown-terminal">
                    <li><a href="usuarios"><span class="t-cyan">//</span> GESTIÓN USUARIOS</a></li>
                    <li><a href="roles"><span class="t-cyan">//</span> CONTROL ROLES</a></li>
                    <li><a href="perfil"><span class="t-cyan">//</span> MI PERFIL</a></li>
                    <li><a href="configuracion"><span class="t-cyan">//</span> PARÁMETROS_CORE</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if ($id_rol == 1 || $id_rol == 2): ?>
            <li class="nav-node-dropdown border-neon-yellow">
                <a href="#" class="node-trigger text-neon-yellow">
                    <i class="fa fa-wrench"></i> [02] RMA_CORE
                </a>
                <ul class="dropdown-terminal">
                    <li><a href="nuevoCaso"><span class="t-yellow">//</span> REGISTRO NUEVO</a></li>
                    <li><a href="bandejaCasos"><span class="t-yellow">//</span> BANDEJA DE CASOS</a></li>
                    <li><a href="taller"><span class="t-yellow">//</span> DIAGNÓSTICO_LAB</a></li>
                    <li><a href="proveedoresRma"><span class="t-yellow">//</span> FLUJO EXTERNO</a></li>
                    <li><a href="historialEstado"><span class="t-yellow">//</span> HISTORIAL ESTADO</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if ($id_rol !== null): ?>
            <li class="nav-node-dropdown border-neon-green">
                <a href="#" class="node-trigger text-neon-green">
                    <i class="fa fa-comments"></i> [03] COMMS_NODE
                </a>
                <ul class="dropdown-terminal">
                    <li><a href="clientes"><span class="t-green">//</span> DIRECTORIO CLIENTES</a></li>
                    <li><a href="proveedores"><span class="t-green">//</span> DIRECTORIO PROVEEDORES</a></li>
                    <li><a href="notificaciones"><span class="t-green">//</span> ALERTAS WHATSAPP</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if ($id_rol == 2): ?>
            <li class="nav-node-dropdown border-neon-purple">
                <a href="#" class="node-trigger text-neon-purple">
                    <i class="fa fa-line-chart"></i> [04] BI_ANALYTICS
                </a>
                <ul class="dropdown-terminal">
                    <li><a href="dashboard"><span class="t-purple">//</span> EXECUTIVE DASHBOARD</a></li>
                    <li><a href="sla-procesos"><span class="t-purple">//</span> TIEMPOS DE CICLO (SLA)</a></li>
                    <li><a href="performance-tecnico"><span class="t-purple">//</span> PERFORMANCE TÉCNICO</a></li>
                    <li><a href="confiabilidad-producto"><span class="t-purple">//</span> ANALYTICS FALLAS</a></li>
                    <li><a href="auditoria-proveedores"><span class="t-purple">//</span> AUDIT PROVEEDORES</a></li>
                    <li><a href="recurrencia-clientes"><span class="t-purple">//</span> FIDELIZACIÓN CLIE</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if ($id_rol !== null): ?>
            <li class="nav-node-end m-left-auto">
                <a href="salir" class="node-trigger cerrar-sesion-cyber">
                    <i class="fa fa-power-off"></i> DISCONNECT
                </a>
            </li>
        <?php else: ?>
            <li class="nav-node-end m-left-auto">
                <a href="login" class="node-trigger text-neon-cyan">
                    <i class="fa fa-terminal"></i> INITIALIZE_LOGIN
                </a>
            </li>
        <?php endif; ?>

    </ul>
</nav>

<style>
    .cyber-nav-hub {
        position: relative;
        margin: 15px 0 25px 0;
        padding: 0 10px;
        border-radius: 6px;
        border: 1px solid rgba(0, 242, 255, 0.1);
        overflow: visible;
        /* Crucial para que desplieguen los submenús */
        background: rgba(10, 16, 32, 0.6);
        backdrop-filter: blur(10px);
        z-index: 1000;
    }

    .nav-scan-lines {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%),
            linear-gradient(90deg, rgba(255, 0, 0, 0.05), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.05));
        background-size: 100% 3px, 3px 100%;
        pointer-events: none;
        border-radius: 6px;
    }

    .nav-nodes-list {
        display: flex;
        align-items: center;
        list-style: none;
        margin: 0;
        padding: 0;
        font-family: 'Share Tech Mono', monospace;
    }

    .nav-node-dropdown {
        position: relative;
        padding: 6px 0;
    }

    /* Indicadores inferiores reactivos */
    .nav-node-dropdown.border-neon-blue {
        border-bottom: 2px solid transparent;
    }

    .nav-node-dropdown.border-neon-blue:hover {
        border-bottom-color: #00f2ff;
    }

    .nav-node-dropdown.border-neon-yellow {
        border-bottom: 2px solid transparent;
    }

    .nav-node-dropdown.border-neon-yellow:hover {
        border-bottom-color: #ffca28;
    }

    .nav-node-dropdown.border-neon-green {
        border-bottom: 2px solid transparent;
    }

    .nav-node-dropdown.border-neon-green:hover {
        border-bottom-color: #00ff66;
    }

    .nav-node-dropdown.border-neon-purple {
        border-bottom: 2px solid transparent;
    }

    .nav-node-dropdown.border-neon-purple:hover {
        border-bottom-color: #9d4edd;
    }

    .node-trigger {
        display: block;
        padding: 12px 20px;
        color: #a2b4cd;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
    }

    .node-trigger:hover {
        background: rgba(255, 255, 255, 0.01);
        text-shadow: 0 0 8px currentColor;
    }

    /* DROPDOWNS ESTILO CONSOLA */
    .dropdown-terminal {
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 220px;
        background-color: #060b19;
        border: 1px solid #101c38;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.65);
        list-style: none;
        padding: 6px 0;
        margin: 4px 0 0 0;
        display: none;
        border-radius: 4px;
        z-index: 2000;
    }

    .nav-node-dropdown:hover .dropdown-terminal {
        display: block;
    }

    .dropdown-terminal li a {
        display: block;
        padding: 9px 18px;
        color: #94a3b8;
        font-size: 0.8rem;
        text-decoration: none;
        letter-spacing: 0.5px;
        transition: all 0.15s ease;
    }

    .dropdown-terminal li a:hover {
        background-color: rgba(0, 242, 255, 0.04);
        color: #fff !important;
        padding-left: 24px;
        /* Efecto terminal displacement */
    }

    .m-left-auto {
        margin-left: auto;
    }

    /* Desconexión Crítica de Nodo */
    .cerrar-sesion-cyber {
        color: #ff3333 !important;
        border: 1px solid rgba(255, 51, 51, 0.2);
        border-radius: 4px;
        padding: 7px 14px !important;
        margin-right: 8px;
    }

    .cerrar-sesion-cyber:hover {
        background: rgba(255, 51, 51, 0.12) !important;
        box-shadow: 0 0 10px rgba(255, 51, 51, 0.35);
        text-shadow: 0 0 5px #ff3333;
    }
</style>