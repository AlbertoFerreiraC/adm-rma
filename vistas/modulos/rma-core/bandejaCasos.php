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
        <div class="cyber-panel-card glass-panel-neon border-neon-yellow mb-4">
            <div class="panel-cyber-header">
                <h4><span class="t-yellow">//</span> FILTRADO ASÍNCRONO Y ESCANEO DE HARDWARE</h4>
            </div>
            <div class="panel-cyber-body mt-3 font-mono">
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 15px;">
                    <div style="position: relative;">
                        <input type="text" id="cyberBuscador" class="cyber-input text-neon-cyan"
                            placeholder="[ ESCANEE CÓDIGO DE BARRAS / QR ] o ingrese Cliente, Serie o Dispositivo...">
                        <span
                            style="position: absolute; right: 15px; top: 10px; color: #506690; font-size: 0.8rem;">[BARCODE_READY]</span>
                    </div>
                    <button id="btnLimpiarFiltro" class="btn-cyber-action" style="padding: 0 25px; height: 100%;">
                        [RESET]
                    </button>
                </div>
            </div>
        </div>

        <div class="cyber-panel-card glass-panel-neon border-neon-blue">
            <div class="panel-cyber-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h4><span class="t-cyan">//</span> NODOS DE CASOS DETECTADOS EN LA MATRIZ</h4>
                <span id="contadorCasos" class="system-badge-live" style="color: #00f2ff; border-color: #00f2ff;">NODES:
                    0</span>
            </div>

            <div class="panel-cyber-body mt-3 font-mono" style="overflow-x: auto;">
                <table class="cyber-mini-table" style="width: 100%;">
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
                            <td colspan="7" style="text-align: center; color: #506690;">
                                [INITIALIZING_DATA_STREAM...]
                            </td>
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

    .dashboard-cyber-wrapper {
        background-color: #060913;
        min-height: 100vh;
        padding: 20px;
        color: #e2e8f0;
        font-family: 'Rajdhani', sans-serif;
    }

    .cyber-header {
        background-color: rgba(6, 11, 25, 0.9);
        border-bottom: 2px solid #101c38;
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 4px;
    }

    .header-brand-glitch h2 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }

    .system-badge-live {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        border: 1px solid #ffca28;
        color: #ffca28;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .cyber-panel-card {
        background: rgba(10, 16, 32, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 20px;
    }

    .border-neon-yellow {
        border-left: 3px solid #ffca28;
    }

    .border-neon-blue {
        border-left: 3px solid #00f2ff;
    }

    .t-yellow {
        color: #ffca28;
        margin-right: 5px;
    }

    .t-cyan {
        color: #00f2ff;
        margin-right: 5px;
    }

    .text-neon-cyan {
        color: #00f2ff !important;
        font-weight: bold;
    }

    .font-mono {
        font-family: 'Share Tech Mono', monospace;
    }

    .cyber-input {
        width: 100%;
        background: #03050c;
        border: 1px solid #101c38;
        color: #fff;
        padding: 12px;
        border-radius: 4px;
        box-sizing: border-box;
        outline: none;
    }

    .cyber-input:focus {
        border-color: #00f2ff;
    }

    .cyber-mini-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        background: rgba(4, 7, 16, 0.6);
    }

    .cyber-mini-table th {
        background: #0d1424;
        color: #506690;
        border: 1px solid #101c38;
        padding: 10px;
        text-align: left;
    }

    .cyber-mini-table td {
        border: 1px solid #101c38;
        padding: 10px;
        color: #cbd5e1;
    }

    .cyber-mini-table tr:hover {
        background: rgba(0, 242, 255, 0.02);
    }

    .badge-status-cyber {
        font-size: 0.7rem;
        font-weight: bold;
        padding: 3px 8px;
        border-radius: 3px;
        display: inline-block;
        border: 1px solid;
    }

    .status-1 {
        background: rgba(0, 242, 255, 0.1);
        color: #00f2ff;
        border-color: #00f2ff;
    }

    .status-default {
        background: rgba(255, 202, 40, 0.1);
        color: #ffca28;
        border-color: #ffca28;
    }

    .btn-cyber-action {
        background: rgba(0, 242, 255, 0.05);
        border: 1px solid #101c38;
        color: #a2b4cd;
        cursor: pointer;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 0.75rem;
        transition: all 0.2s;
    }

    .btn-cyber-action:hover {
        background: rgba(0, 242, 255, 0.15);
        color: #fff;
        border-color: #00f2ff;
        box-shadow: 0 0 8px rgba(0, 242, 255, 0.3);
    }

    .btn-cyber-reprint {
        background: rgba(255, 202, 40, 0.05);
        color: #ffca28;
    }

    .btn-cyber-reprint:hover {
        background: rgba(255, 202, 40, 0.15);
        border-color: #ffca28;
        box-shadow: 0 0 8px rgba(255, 202, 40, 0.3);
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }
</style>

<script src="vistas/js/rma-core/bandejaCasos.js"></script>