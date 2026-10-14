<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id_rol = $_SESSION['id_rol'] ?? null;

// Restringir acceso solo a Administrador (2) e Inventario (5)
if ($id_rol != 2 && $id_rol != 5) {
    echo '<script>window.location = "dashboard";</script>';
    exit;
}
?>

<input type="hidden" name="rol" id="rol" value="<?php echo $_SESSION['id_rol'] ?? ''; ?>">

<div class="content-wrapper dashboard-cyber-wrapper style-relative">

    <!-- OVERLAY LOADER CYBERPUNK EN VIVO -->
    <div id="cyberLoaderDashboard" class="cyber-loader-overlay">
        <div class="loader-content-hud">
            <div class="cyber-spinner-ring border-neon-orange"></div>
            <span class="hud-loading-text font-mono">[04] SYS_INV // INICIALIZANDO_MATRIZ_STOCK...</span>
            <div class="hud-progress-bar">
                <div class="hud-progress-fill"></div>
            </div>
        </div>
    </div>

    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">📦</span>
            <h2 class="glitch-text">Microexpress — Control Logístico y Kardex</h2>
            <span class="system-badge-live">DB_STOCK_SECURE</span>
        </div>

        <div class="cyber-meta-nodes font-mono">
            <div class="meta-node">
                <span class="node-label">OPERADOR:</span>
                <span class="node-val text-neon-orange">👤
                    <?php echo $_SESSION['nombre'] ?? 'Encargado de Inventario'; ?></span>
            </div>
            <div class="meta-node">
                <span class="node-label">PERÍODO:</span>
                <span class="node-val text-neon-purple">📅 <?php echo date('M Y'); ?></span>
            </div>
            <div class="meta-node system-status-pulse">
                <span class="pulse-dot-orange"></span> <span class="node-val">KARDEX ACTIVO</span>
            </div>
        </div>
    </header>

    <section class="cyber-content mt-4">

        <!-- FILA SUPERIOR: TARJETAS KPI DENSAS -->
        <div class="cyber-grid-super-dense">

            <div class="cyber-kpi-card glass-panel-neon border-neon-cyan">
                <div class="kpi-header-inline">
                    <span class="kpi-tag-label">SKU_TOTALES</span>
                    <span class="trend-indicator t-cyan">ACTIVO</span>
                </div>
                <div class="kpi-main-data-compact font-mono">
                    <h3 id="kpiTotalProductos" class="t-cyan">0</h3>
                    <p class="kpi-desc-title">Productos distintos</p>
                </div>
            </div>

            <div class="cyber-kpi-card glass-panel-neon border-neon-orange">
                <div class="kpi-header-inline">
                    <span class="kpi-tag-label">UNIDADES</span>
                    <span class="pulse-dot-orange"></span>
                </div>
                <div class="kpi-main-data-compact font-mono">
                    <h3 id="kpiUnidadesStock" class="neon-text-orange">0</h3>
                    <p class="kpi-desc-title">Stock total bodega</p>
                </div>
            </div>

            <div class="cyber-kpi-card glass-panel-neon border-neon-yellow">
                <div class="kpi-header-inline">
                    <span class="kpi-tag-label">GARANTÍA</span>
                    <span class="trend-indicator text-yellow">TALLER</span>
                </div>
                <div class="kpi-main-data-compact font-mono">
                    <h3 id="kpiStockRma" class="neon-text-yellow">0</h3>
                    <p class="kpi-desc-title">En proceso RMA</p>
                </div>
            </div>

            <div class="cyber-kpi-card glass-panel-neon border-neon-red">
                <div class="kpi-header-inline">
                    <span class="kpi-tag-label">CRÍTICOS</span>
                    <span class="trend-indicator text-neon-red">ALERTA</span>
                </div>
                <div class="kpi-main-data-compact font-mono">
                    <h3 id="kpiAlertasStock" class="text-neon-red">0</h3>
                    <p class="kpi-desc-title">Sin stock / Bajo</p>
                </div>
            </div>

        </div>

        <!-- PANEL PRINCIPAL DE LA TABLA Y ACCIONES -->
        <div class="cyber-panel-card glass-panel-neon border-neon-orange mt-4">

            <div class="panel-cyber-header flex-header-toolbar">
                <div class="cyber-search-box">
                    <i class="fa fa-search search-icon-hud"></i>
                    <input type="text" id="buscarInventario" class="cyber-input-search"
                        placeholder="🔍 Buscar por código, producto o marca...">
                </div>

                <div class="toolbar-actions">
                    <button type="button" class="btn-cyber-add" id="btnNuevaRemision">
                        <i class="fa fa-file-text-o"></i> [+ NUEVA REMISIÓN]
                    </button>
                    <button type="button" class="btn-cyber-add ml-2" id="btnRegistrarMovimiento">
                        <i class="fa fa-exchange"></i> [MOVIMIENTO / REPOSICIÓN]
                    </button>
                </div>
            </div>

            <!-- TABLA DE INVENTARIO -->
            <div class="panel-cyber-body-table mt-3">
                <table class="cyber-mini-table font-mono" id="tablaStockInventario">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre del Producto</th>
                            <th>Categoría</th>
                            <th>Marca</th>
                            <th>Stock Actual</th>
                            <th>Stock RMA</th>
                            <th>Stock Disponible</th>
                            <th>Estado Alerta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Carga dinámica vía JS -->
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@500;600;700&display=swap');

    :root {
        --bg-cyber-light: #f0f4f8;
        --card-cyber-light: #ffffff;
        --border-cyber-subtle: #cbd5e1;
        --text-cyber-dark: #0f172a;
        --text-cyber-muted: #475569;

        --neon-cyan-dark: #0284c7;
        --neon-cyan-glow: #00b4d8;
        --neon-green-dark: #15803d;
        --neon-red-dark: #dc2626;
        --neon-purple-dark: #7e22ce;
        --neon-yellow-dark: #d97706;
        --neon-orange-dark: #f97316;
    }

    .style-relative {
        position: relative;
    }

    /* OVERLAY LOADER CYBERPUNK HUD */
    .cyber-loader-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(240, 244, 248, 0.92);
        backdrop-filter: blur(6px);
        z-index: 2000;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .loader-content-hud {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .cyber-spinner-ring {
        width: 50px;
        height: 50px;
        border: 4px solid #cbd5e1;
        border-top: 4px solid var(--neon-orange-dark);
        border-radius: 50%;
        animation: spinHUD 0.8s linear infinite;
    }

    @keyframes spinHUD {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .hud-loading-text {
        font-size: 0.85rem;
        color: var(--neon-orange-dark);
        font-weight: bold;
        letter-spacing: 1px;
    }

    .hud-progress-bar {
        width: 220px;
        height: 4px;
        background: #cbd5e1;
        border-radius: 2px;
        overflow: hidden;
    }

    .hud-progress-fill {
        width: 100%;
        height: 100%;
        background: var(--neon-orange-dark);
        animation: loadingFill 1.2s ease-in-out infinite;
    }

    @keyframes loadingFill {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    .dashboard-cyber-wrapper {
        background-color: var(--bg-cyber-light);
        background-image:
            radial-gradient(circle at 50% 10%, rgba(249, 115, 22, 0.05) 0%, transparent 60%),
            linear-gradient(rgba(203, 213, 225, 0.2) 1px, transparent 1px),
            linear-gradient(90deg, rgba(203, 213, 225, 0.2) 1px, transparent 1px);
        background-size: 100% 100%, 20px 20px, 20px 20px;
        min-height: 100vh;
        padding: 20px;
        color: var(--text-cyber-dark);
        font-family: 'Rajdhani', sans-serif;
    }

    .cyber-header {
        background-color: #ffffff;
        border-bottom: 2px solid var(--neon-orange-dark);
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 6px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        flex-wrap: wrap;
        gap: 15px;
    }

    .header-brand-glitch {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-brand-glitch h2 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-cyber-dark);
        margin: 0;
        letter-spacing: 0.5px;
    }

    .system-badge-live {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        border: 1px solid var(--neon-orange-dark);
        color: var(--neon-orange-dark);
        background: rgba(249, 115, 22, 0.08);
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: bold;
    }

    .cyber-meta-nodes {
        display: flex;
        gap: 20px;
        font-size: 0.8rem;
        flex-wrap: wrap;
    }

    .node-label {
        color: var(--text-cyber-muted);
        font-weight: bold;
    }

    .system-status-pulse {
        color: var(--neon-orange-dark) !important;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: bold;
    }

    .pulse-dot-orange {
        width: 8px;
        height: 8px;
        background-color: var(--neon-orange-dark);
        border-radius: 50%;
        animation: blink-animation 1s infinite;
    }

    @keyframes blink-animation {
        to {
            opacity: 0.3;
        }
    }

    .cyber-grid-super-dense {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        width: 100%;
        margin-top: 20px;
    }

    @media(max-width: 992px) {
        .cyber-grid-super-dense {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 576px) {
        .cyber-grid-super-dense {
            grid-template-columns: 1fr;
        }
    }

    .cyber-panel-card {
        background: var(--card-cyber-light);
        border: 1px solid var(--border-cyber-subtle);
        border-radius: 8px;
        padding: 18px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    }

    .cyber-kpi-card {
        position: relative;
        border-radius: 8px;
        padding: 14px;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: var(--card-cyber-light);
        border: 1px solid var(--border-cyber-subtle);
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
    }

    .border-neon-cyan {
        border-left: 4px solid var(--neon-cyan-glow);
    }

    .border-neon-orange {
        border-left: 4px solid var(--neon-orange-dark);
    }

    .border-neon-yellow {
        border-left: 4px solid var(--neon-yellow-dark);
    }

    .border-neon-red {
        border-left: 4px solid var(--neon-red-dark);
    }

    .kpi-header-inline {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.7rem;
        color: var(--text-cyber-muted);
    }

    .kpi-main-data-compact h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 4px 0 0 0;
        line-height: 1;
    }

    .kpi-desc-title {
        font-size: 0.75rem;
        color: var(--text-cyber-muted);
        margin: 4px 0 0 0;
        font-weight: bold;
        text-transform: uppercase;
    }

    .flex-header-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .cyber-search-box {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-icon-hud {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-cyber-muted);
    }

    .cyber-input-search {
        width: 100%;
        background: #f8fafc;
        border: 1px solid var(--border-cyber-subtle);
        color: var(--text-cyber-dark);
        padding: 8px 12px 8px 35px;
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .cyber-input-search:focus {
        border-color: var(--neon-orange-dark);
        background: #ffffff;
        box-shadow: 0 0 10px rgba(249, 115, 22, 0.15);
        outline: none;
    }

    .btn-cyber-add {
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid var(--neon-orange-dark);
        color: var(--neon-orange-dark);
        padding: 8px 16px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        font-size: 0.85rem;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cyber-add:hover {
        background: var(--neon-orange-dark);
        color: #ffffff;
        box-shadow: 0 0 12px rgba(249, 115, 22, 0.3);
    }

    .panel-cyber-body-table {
        overflow-x: auto;
    }

    .cyber-mini-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .cyber-mini-table th {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.72rem;
        color: var(--text-cyber-muted);
        padding: 10px 8px;
        border-bottom: 2px solid var(--border-cyber-subtle);
        text-transform: uppercase;
        text-align: center;
        background: #f8fafc;
    }

    .cyber-mini-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text-cyber-dark);
        text-align: center;
    }

    .t-cyan {
        color: var(--neon-cyan-dark);
        font-weight: bold;
    }

    .neon-text-orange {
        color: var(--neon-orange-dark);
        font-weight: bold;
    }

    .neon-text-yellow {
        color: var(--neon-yellow-dark);
        font-weight: bold;
    }

    .text-neon-red {
        color: var(--neon-red-dark);
        font-weight: bold;
    }

    .font-mono {
        font-family: 'Share Tech Mono', monospace;
    }
</style>

<script src="vistas/js/inventario/dashboardInventario.js"></script>