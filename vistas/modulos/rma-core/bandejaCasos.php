<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">📊</span>
            <h2>[02] RMA_CORE // BANDEJA DE CONTROL VECTORIAL</h2>
            <span class="system-badge-live">LIVE_RMA_STREAM</span>
        </div>
    </header>

    <section class="cyber-content mt-4">

        <!-- PANEL DE FILTRADO Y ESCANEO -->
        <div class="cyber-panel-card glass-panel-neon border-neon-yellow mb-4">
            <div class="panel-cyber-header">
                <h4><span class="t-yellow">//</span> FILTRADO ASÍNCRONO Y ESCANEO DE HARDWARE</h4>
            </div>

            <div class="panel-cyber-body mt-3 font-mono">
                <div class="flex-header-toolbar">
                    <div class="cyber-search-box" style="max-width: none;">
                        <i class="fa fa-search search-icon-hud"></i>
                        <input type="text" id="cyberBuscador" class="cyber-input-search text-neon-cyan"
                            placeholder="[ ESCANEE CÓDIGO DE BARRAS / QR ] o ingrese Cliente, Serie o Dispositivo...">
                        <span class="barcode-status-tag">[BARCODE_READY]</span>
                    </div>

                    <button type="button" id="btnLimpiarFiltro" class="btn-cyber-add">
                        [RESET]
                    </button>
                </div>
            </div>
        </div>

        <!-- PANEL TABLA DE CASOS -->
        <div class="cyber-panel-card glass-panel-neon border-neon-blue">
            <div class="panel-cyber-header flex-header-toolbar">
                <h4><span class="t-cyan">//</span> NODOS DE CASOS DETECTADOS EN LA MATRIZ</h4>
                <span id="contadorCasos" class="system-badge-live">NODES: 0</span>
            </div>

            <div class="panel-cyber-body-table mt-3 font-mono">
                <table class="cyber-mini-table" id="tablaCasos">
                    <thead>
                        <tr>
                            <th>N° CASO</th>
                            <th>FECHA INGRESO</th>
                            <th>CLIENTE</th>
                            <th>DISPOSITIVO / MARCA</th>
                            <th>N° SERIE</th>
                            <th>ESTADO</th>
                            <th style="text-align: center;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyCasos">
                        <tr>
                            <td colspan="7" class="text-center">[INITIALIZING_DATA_STREAM...]</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<div id="cyber-print-buffer" style="display: none;"></div>

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
        --neon-yellow-dark: #d97706;
    }

    .dashboard-cyber-wrapper {
        background-color: var(--bg-cyber-light);
        background-image:
            radial-gradient(circle at 50% 10%, rgba(2, 132, 199, 0.05) 0%, transparent 60%),
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
        border-bottom: 2px solid var(--neon-cyan-dark);
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 6px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
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
        border: 1px solid var(--neon-cyan-dark);
        color: var(--neon-cyan-dark);
        background: rgba(2, 132, 199, 0.08);
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: bold;
    }

    .cyber-panel-card {
        background: var(--card-cyber-light);
        border: 1px solid var(--border-cyber-subtle);
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    }

    .border-neon-yellow {
        border-left: 4px solid var(--neon-yellow-dark);
    }

    .border-neon-blue {
        border-left: 4px solid var(--neon-cyan-dark);
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
        min-width: 250px;
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
        padding: 10px 120px 10px 35px;
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .cyber-input-search:focus {
        border-color: var(--neon-cyan-dark);
        background: #ffffff;
        box-shadow: 0 0 10px rgba(2, 132, 199, 0.15);
        outline: none;
    }

    .barcode-status-tag {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-cyber-muted);
        font-size: 0.72rem;
        pointer-events: none;
    }

    .btn-cyber-add {
        background: rgba(2, 132, 199, 0.1);
        border: 1px solid var(--neon-cyan-dark);
        color: var(--neon-cyan-dark);
        padding: 9px 16px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        font-size: 0.85rem;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cyber-add:hover {
        background: var(--neon-cyan-dark);
        color: #ffffff;
        box-shadow: 0 0 12px rgba(2, 132, 199, 0.3);
    }

    .panel-cyber-body-table {
        overflow-x: auto;
    }

    .cyber-mini-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .cyber-mini-table th {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.75rem;
        color: var(--text-cyber-muted);
        padding: 12px 8px;
        border-bottom: 2px solid var(--border-cyber-subtle);
        text-transform: uppercase;
        text-align: center;
        background: #f8fafc;
    }

    .cyber-mini-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text-cyber-dark);
        text-align: center;
    }

    .font-mono {
        font-family: 'Share Tech Mono', monospace;
    }

    .t-yellow {
        color: var(--neon-yellow-dark);
        font-weight: bold;
        margin-right: 5px;
    }

    .t-cyan,
    .cyan-accent {
        color: var(--neon-cyan-dark);
        font-weight: bold;
        margin-right: 5px;
    }

    .text-neon-cyan {
        color: var(--neon-cyan-dark) !important;
    }

    .badge-status-cyber {
        font-size: 0.72rem;
        font-weight: bold;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-block;
        border: 1px solid;
    }

    .status-1 {
        background: rgba(2, 132, 199, 0.08);
        color: var(--neon-cyan-dark);
        border-color: rgba(2, 132, 199, 0.3);
    }

    .status-default {
        background: rgba(217, 119, 6, 0.08);
        color: var(--neon-yellow-dark);
        border-color: rgba(217, 119, 6, 0.3);
    }

    .btn-terminal-edit,
    .btn-terminal-reprint {
        background: transparent;
        border: 1px solid var(--border-cyber-subtle);
        color: var(--text-cyber-muted);
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.75rem;
        font-family: 'Share Tech Mono', monospace;
        transition: all 0.2s;
        margin: 0 2px;
    }

    .btn-terminal-edit:hover {
        color: var(--neon-cyan-dark);
        border-color: var(--neon-cyan-dark);
        background: rgba(2, 132, 199, 0.08);
    }

    .btn-terminal-reprint:hover {
        color: var(--neon-yellow-dark);
        border-color: var(--neon-yellow-dark);
        background: rgba(217, 119, 6, 0.08);
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .text-center {
        text-align: center;
    }
</style>

<script src="vistas/js/rma-core/bandejaCasos.js"></script>