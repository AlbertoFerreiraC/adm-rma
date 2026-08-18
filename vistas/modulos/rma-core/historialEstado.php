<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">⏳</span>
            <h2>[04] RMA_CORE // AUDITORÍA CRONOLÓGICA DE ESTADOS</h2>
            <span class="system-badge-live">TIMELINE_READY</span>
        </div>
    </header>

    <section class="cyber-content mt-4">

        <!-- PANEL DE BÚSQUEDA Y RASTREO VECTORIAL -->
        <div class="cyber-panel-card glass-panel-neon border-neon-yellow mb-4">
            <div class="panel-cyber-header">
                <h4><span class="t-yellow">//</span> RASTREADOR VECTORIAL DE CASO</h4>
            </div>
            <div class="panel-cyber-body mt-3 font-mono">
                <div class="flex-header-toolbar">
                    <div class="cyber-search-box" style="max-width: none;">
                        <i class="fa fa-search search-icon-hud"></i>
                        <input type="text" id="buscadorHistorial" class="cyber-input-search text-neon-cyan"
                            placeholder="[ ESCANEE EL COMPONENTE ] o digite el N° de Caso (Ej: RMA-2026-0004)..."
                            autocomplete="off">
                        <span class="barcode-status-tag">[TIMELINE_SCAN]</span>
                    </div>

                    <button type="button" id="btnResetHistorial" class="btn-cyber-add">
                        [CLEAR]
                    </button>
                </div>
            </div>
        </div>

        <!-- HUD RESUMEN DEL CASO -->
        <div id="hudResumenCaso" class="cyber-panel-card glass-panel-neon border-neon-blue mb-4" style="display: none;">
            <div class="font-mono hud-resumen-grid">
                <div><span class="text-muted-label">NODO:</span> <strong id="hudNumero" class="t-cyan"></strong></div>
                <div><span class="text-muted-label">EQUIPO:</span> <strong id="hudEquipo"
                        class="text-uppercase"></strong></div>
                <div><span class="text-muted-label">SERIE S/N:</span> <strong id="hudSerie"
                        class="text-yellow"></strong></div>
                <div><span class="text-muted-label">CLIENTE:</span> <strong id="hudCliente"
                        class="text-uppercase"></strong></div>
            </div>
        </div>

        <!-- PANEL LÍNEA DE TIEMPO -->
        <div class="cyber-panel-card glass-panel-neon border-neon-purple">
            <div class="panel-cyber-header">
                <h4><span class="purple-accent">//</span> LÍNEA DE TIEMPO GENERADA POR EL NÚCLEO SYSTEM</h4>
            </div>
            <div class="panel-cyber-body mt-4 font-mono">
                <div id="timelineContainer" class="cyber-timeline-wrapper">
                    <div class="timeline-empty-message">
                        [ INGRESE O ESCANEE UN NÚMERO DE CASO VÁLIDO PARA DESPLEGAR LOS REGISTROS TEMPORALES ]
                    </div>
                </div>
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

    .border-neon-purple {
        border-left: 4px solid var(--neon-purple-dark);
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

    .font-mono {
        font-family: 'Share Tech Mono', monospace;
    }

    .t-yellow {
        color: var(--neon-yellow-dark);
        font-weight: bold;
        margin-right: 5px;
    }

    .t-cyan {
        color: var(--neon-cyan-dark);
        font-weight: bold;
        margin-right: 5px;
    }

    .purple-accent {
        color: var(--neon-purple-dark);
        font-weight: bold;
        margin-right: 5px;
    }

    .text-neon-cyan {
        color: var(--neon-cyan-dark) !important;
    }

    .text-yellow {
        color: var(--neon-yellow-dark) !important;
    }

    .text-muted-label {
        color: var(--text-cyber-muted);
        font-size: 0.8rem;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    /* HUD RESUMEN */
    .hud-resumen-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        font-size: 0.9rem;
    }

    /* ESTILOS LÍNEA DE TIEMPO (TIMELINE) */
    .cyber-timeline-wrapper {
        position: relative;
        padding-left: 30px;
        margin: 10px 0;
    }

    .cyber-timeline-wrapper::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: var(--border-cyber-subtle);
    }

    .timeline-empty-message {
        text-align: center;
        color: var(--text-cyber-muted);
        padding: 40px 0;
    }

    .timeline-node-item {
        position: relative;
        margin-bottom: 20px;
        transition: all 0.2s;
    }

    .timeline-node-item::before {
        content: '';
        position: absolute;
        left: -27px;
        top: 15px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid var(--text-cyber-muted);
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        z-index: 2;
        transition: all 0.2s;
    }

    .node-status-1::before {
        border-color: var(--neon-cyan-dark);
        background: rgba(2, 132, 199, 0.2);
    }

    .node-status-final::before {
        border-color: var(--neon-green-dark);
        background: rgba(21, 128, 61, 0.2);
    }

    .timeline-node-block {
        background: #f8fafc;
        border: 1px solid var(--border-cyber-subtle);
        padding: 15px;
        border-radius: 6px;
    }

    .timeline-node-item:hover .timeline-node-block {
        border-color: var(--neon-purple-dark);
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(126, 34, 206, 0.08);
    }

    .node-meta-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: var(--text-cyber-muted);
        margin-bottom: 6px;
        border-bottom: 1px solid var(--border-cyber-subtle);
        padding-bottom: 4px;
    }

    .node-state-badge {
        font-weight: bold;
        font-size: 0.85rem;
        color: var(--neon-cyan-dark);
        text-transform: uppercase;
    }

    .node-observation {
        font-size: 0.9rem;
        color: var(--text-cyber-dark);
        line-height: 1.4;
        word-break: break-word;
    }

    .node-operator {
        color: var(--neon-yellow-dark);
        font-weight: bold;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }
</style>

<script src="vistas/js/rma-core/historialEstado.js"></script>