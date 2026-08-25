<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id_rol = $_SESSION['id_rol'] ?? null;
?>

<nav class="cyber-nav-hub glass-panel-neon">
    <div class="nav-scan-lines"></div>
    <ul class="nav-nodes-list">

        <!-- [01] SEGURIDAD Y CONFIGURACIÓN (ROL 2 - ADMIN) -->
        <?php if ($id_rol == 2): ?>
            <li class="nav-node-dropdown border-neon-blue">
                <a href="#" class="node-trigger text-neon-blue">
                    <i class="fa fa-shield"></i> [01] Configuración <i class="fa fa-caret-down node-arrow"></i>
                </a>
                <ul class="dropdown-terminal">
                    <li><a href="usuarios"><span class="t-cyan">//</span> GESTIÓN DE USUARIOS</a></li>
                    <li><a href="roles"><span class="t-cyan">//</span> CONTROL DE ROLES</a></li>
                    <li><a href="perfil"><span class="t-cyan">//</span> MI PERFIL</a></li>
                    <li><a href="configuracion"><span class="t-cyan">//</span> PARÁMETROS DEL SISTEMA</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- [02] GESTIÓN OPERATIVA RMA (ROLES 1 Y 2 - TÉCNICOS / ADMIN) -->
        <?php if ($id_rol == 1 || $id_rol == 2): ?>
            <li class="nav-node-dropdown border-neon-yellow">
                <a href="#" class="node-trigger text-neon-yellow">
                    <i class="fa fa-wrench"></i> [02] Gestión RMA <i class="fa fa-caret-down node-arrow"></i>
                </a>
                <ul class="dropdown-terminal">
                    <li><a href="nuevoCaso"><span class="t-yellow">//</span> REGISTRO NUEVO RMA</a></li>
                    <li><a href="bandejaCasos"><span class="t-yellow">//</span> BANDEJA DE CASOS</a></li>
                    <li><a href="taller"><span class="t-yellow">//</span> DIAGNÓSTICO DE TALLER</a></li>
                    <li><a href="proveedoresRma"><span class="t-yellow">//</span> FLUJO EXTERNO GARANTÍAS</a></li>
                    <li><a href="historialEstado"><span class="t-yellow">//</span> HISTORIAL DE ESTADOS</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- [03] CONTACTOS Y COMUNICACIONES (TODOS LOS USUARIOS LOGUEADOS) -->
        <?php if ($id_rol !== null): ?>
            <li class="nav-node-dropdown border-neon-green">
                <a href="#" class="node-trigger text-neon-green">
                    <i class="fa fa-comments"></i> [03] Comunicaciones <i class="fa fa-caret-down node-arrow"></i>
                </a>
                <ul class="dropdown-terminal">
                    <li><a href="clientes"><span class="t-green">//</span> DIRECTORIO DE CLIENTES</a></li>
                    <li><a href="proveedores"><span class="t-green">//</span> DIRECTORIO DE PROVEEDORES</a></li>
                    <li><a href="notificaciones"><span class="t-green">//</span> ALERTAS WHATSAPP</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- [04] GESTIÓN DE INVENTARIO Y STOCK (ROL 2 - ADMIN / PROPIO COLOR NARANJA) -->
        <?php if ($id_rol == 2): ?>
            <li class="nav-node-dropdown border-neon-orange">
                <a href="#" class="node-trigger text-neon-orange">
                    <i class="fa fa-cubes"></i> [04] Inventario <i class="fa fa-caret-down node-arrow"></i>
                </a>
                <ul class="dropdown-terminal">
                    <li><a href="stockCatalogo"><span class="t-orange">//</span> CATÁLOGO Y CONTROL DE STOCK</a></li>
                    <li><a href="stockAlertas"><span class="t-orange">//</span> ALERTAS DE STOCK CRÍTICO</a></li>
                    <li><a href="stockAsignaciones"><span class="t-orange">//</span> ASIGNACIONES EN TALLER</a></li>
                    <li><a href="stockMovimientos"><span class="t-orange">//</span> KARDEX DE MOVIMIENTOS</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- [05] ANALÍTICA E INTELIGENCIA BI (ROL 2 - ADMIN) -->
        <?php if ($id_rol == 2): ?>
            <li class="nav-node-dropdown border-neon-purple">
                <a href="#" class="node-trigger text-neon-purple">
                    <i class="fa fa-line-chart"></i> [05] Analítica BI <i class="fa fa-caret-down node-arrow"></i>
                </a>
                <ul class="dropdown-terminal">
                    <li><a href="dashboard"><span class="t-purple">//</span> DASHBOARD EJECUTIVO</a></li>
                    <li><a href="slaProcesos"><span class="t-purple">//</span> TIEMPOS DE CICLO (SLA)</a></li>
                    <li><a href="performanceTecnico"><span class="t-purple">//</span> RENDIMIENTO TÉCNICO</a></li>
                    <li><a href="confiabilidadProducto"><span class="t-purple">//</span> ANÁLISIS DE FALLAS</a></li>
                    <li><a href="auditoriaProveedores"><span class="t-purple">//</span> AUDITORÍA PROVEEDORES</a></li>
                    <li><a href="recurrenciaClientes"><span class="t-purple">//</span> FIDELIZACIÓN CLIENTES</a></li>
                </ul>
            </li>
        <?php endif; ?>


        <!-- ACCIONES DE SESIÓN -->
        <?php if ($id_rol !== null): ?>
            <li class="nav-node-end m-left-auto">
                <a href="salir" class="node-trigger cerrar-sesion-cyber">
                    <i class="fa fa-power-off"></i> CERRAR SESIÓN
                </a>
            </li>
        <?php else: ?>
            <li class="nav-node-end m-left-auto">
                <a href="login" class="node-trigger text-neon-cyan">
                    <i class="fa fa-terminal"></i> INICIAR SESIÓN
                </a>
            </li>
        <?php endif; ?>

    </ul>
</nav>

<style>
    /* BARRA DE NAVEGACIÓN - CONTRASTE MIDNIGHT COMMAND */
    .cyber-nav-hub {
        position: relative;
        margin: 15px 20px 25px 20px;
        padding: 4px 15px;
        border-radius: 8px;
        border: 1px solid #1e293b;
        background: #0f172a;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25), 0 0 15px rgba(2, 132, 199, 0.1);
        overflow: visible;
        z-index: 1000;
    }

    .nav-scan-lines {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.15) 50%);
        background-size: 100% 3px;
        pointer-events: none;
        border-radius: 8px;
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
        padding: 4px 0;
    }

    /* Colores Neón e Indicadores Inferiores */
    .text-neon-blue {
        color: #38bdf8 !important;
    }

    .text-neon-yellow {
        color: #fbbf24 !important;
    }

    .text-neon-green {
        color: #4ade80 !important;
    }

    .text-neon-purple {
        color: #c084fc !important;
    }

    .text-neon-orange {
        color: #f97316 !important;
    }

    .text-neon-cyan {
        color: #38bdf8 !important;
    }

    .t-cyan {
        color: #38bdf8;
        font-weight: bold;
    }

    .t-yellow {
        color: #fbbf24;
        font-weight: bold;
    }

    .t-green {
        color: #4ade80;
        font-weight: bold;
    }

    .t-purple {
        color: #c084fc;
        font-weight: bold;
    }

    .t-orange {
        color: #f97316;
        font-weight: bold;
    }

    /* Bordes de hover por módulo */
    .nav-node-dropdown.border-neon-blue {
        border-bottom: 2px solid transparent;
    }

    .nav-node-dropdown.border-neon-blue:hover {
        border-bottom-color: #38bdf8;
    }

    .nav-node-dropdown.border-neon-yellow {
        border-bottom: 2px solid transparent;
    }

    .nav-node-dropdown.border-neon-yellow:hover {
        border-bottom-color: #fbbf24;
    }

    .nav-node-dropdown.border-neon-green {
        border-bottom: 2px solid transparent;
    }

    .nav-node-dropdown.border-neon-green:hover {
        border-bottom-color: #4ade80;
    }

    .nav-node-dropdown.border-neon-purple {
        border-bottom: 2px solid transparent;
    }

    .nav-node-dropdown.border-neon-purple:hover {
        border-bottom-color: #c084fc;
    }

    .nav-node-dropdown.border-neon-orange {
        border-bottom: 2px solid transparent;
    }

    .nav-node-dropdown.border-neon-orange:hover {
        border-bottom-color: #f97316;
    }

    .node-trigger {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        color: #94a3b8;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
        border-radius: 4px;
    }

    .node-trigger:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #ffffff !important;
        text-shadow: 0 0 8px currentColor;
    }

    /* Ícono Flecha */
    .node-arrow {
        font-size: 0.75rem;
        margin-left: 4px;
        transition: transform 0.25s ease;
        opacity: 0.6;
    }

    .nav-node-dropdown:hover .node-arrow {
        transform: rotate(180deg);
        opacity: 1;
    }

    /* DROPDOWNS TERMINAL DE ALTO CONTRASTE */
    .dropdown-terminal {
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 250px;
        background-color: #0f172a;
        border: 1px solid #334155;
        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.35);
        list-style: none;
        padding: 8px 0;
        margin: 6px 0 0 0;
        display: none;
        border-radius: 6px;
        z-index: 2000;
    }

    .nav-node-dropdown:hover .dropdown-terminal {
        display: block;
        animation: dropFade 0.2s ease-out;
    }

    @keyframes dropFade {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-terminal li a {
        display: block;
        padding: 9px 18px;
        color: #cbd5e1;
        font-size: 0.8rem;
        text-decoration: none;
        letter-spacing: 0.5px;
        transition: all 0.15s ease;
    }

    .dropdown-terminal li a:hover {
        background-color: rgba(249, 115, 22, 0.1);
        color: #ffffff !important;
        padding-left: 22px;
    }

    .m-left-auto {
        margin-left: auto;
    }

    /* Botón de Desconexión */
    .cerrar-sesion-cyber {
        color: #f87171 !important;
        border: 1px solid rgba(248, 113, 113, 0.3);
        border-radius: 4px;
        padding: 6px 14px !important;
        margin-right: 4px;
    }

    .cerrar-sesion-cyber:hover {
        background: rgba(239, 68, 68, 0.15) !important;
        border-color: #ef4444;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
        color: #ffffff !important;
    }
</style>