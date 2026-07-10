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
            <span class="system-badge-live" style="color: #00f2ff; border-color: #00f2ff;">TIMELINE_READY</span>
        </div>
    </header>

    <section class="cyber-content mt-4">
        <div class="cyber-panel-card glass-panel-neon border-neon-yellow mb-4">
            <div class="panel-cyber-header">
                <h4><span class="t-yellow">//</span> RASTREADOR VECTORIAL DE CASO</h4>
            </div>
            <div class="panel-cyber-body mt-3 font-mono">
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 15px;">
                    <div style="position: relative;">
                        <input type="text" id="buscadorHistorial" class="cyber-input text-neon-cyan"
                            placeholder="[ ESCANEE EL COMPONENTE ] o digite el N° de Caso (Ej: RMA-2026-0004)..."
                            autocomplete="off">
                        <span
                            style="position: absolute; right: 15px; top: 10px; color: #506690; font-size: 0.8rem;">[TIMELINE_SCAN]</span>
                    </div>
                    <button id="btnResetHistorial" class="btn-cyber-action" style="padding: 0 25px; height: 100%;">
                        [CLEAR]
                    </button>
                </div>
            </div>
        </div>

        <div id="hudResumenCaso" class="cyber-panel-card glass-panel-neon border-neon-blue mb-4" style="display: none;">
            <div class="font-mono"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 0.9rem;">
                <div><span style="color: #506690;">NODO:</span> <strong id="hudNumero" class="text-neon-cyan"></strong>
                </div>
                <div><span style="color: #506690;">EQUIPO:</span> <strong id="hudEquipo"
                        style="text-transform: uppercase;"></strong></div>
                <div><span style="color: #506690;">SERIE S/N:</span> <strong id="hudSerie"
                        style="color: #ffca28;"></strong></div>
                <div><span style="color: #506690;">CLIENTE:</span> <strong id="hudCliente"
                        style="text-transform: uppercase;"></strong></div>
            </div>
        </div>

        <div class="cyber-panel-card glass-panel-neon border-neon-purple">
            <div class="panel-cyber-header">
                <h4><span class="purple-accent">//</span> LÍNEA DE TIEMPO GENERADA POR EL NÚCLEO SYSTEM</h4>
            </div>
            <div class="panel-cyber-body mt-4 font-mono">
                <div id="timelineContainer" class="cyber-timeline-wrapper">
                    <div style="text-align: center; color: #506690; padding: 40px 0;">
                        [ INGRESE O ESCANEE UN NÚMERO DE CASO VÁLIDO PARA DESPLEGAR LOS REGISTROS TEMPORALES ]
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

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

    .border-neon-purple {
        border-left: 3px solid #9d4edd;
    }

    .t-yellow {
        color: #ffca28;
        margin-right: 5px;
    }

    .text-neon-cyan {
        color: #00f2ff !important;
        font-weight: bold;
    }

    .purple-accent {
        color: #9d4edd;
        margin-right: 5px;
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
        box-shadow: 0 0 8px rgba(0, 242, 255, 0.2);
    }

    .btn-cyber-action {
        background: rgba(0, 242, 255, 0.05);
        border: 1px solid #101c38;
        color: #a2b4cd;
        cursor: pointer;
        border-radius: 4px;
        font-size: 0.75rem;
        transition: all 0.2s;
    }

    .btn-cyber-action:hover {
        background: rgba(255, 51, 51, 0.1);
        color: #fff;
        border-color: #ff3333;
        box-shadow: 0 0 8px rgba(255, 51, 51, 0.3);
    }

    .cyber-timeline-wrapper {
        position: relative;
        padding-left: 30px;
        margin: 10px 0;
    }

    .cyber-timeline-wrapper::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: #101c38;
    }

    .timeline-node-item {
        position: relative;
        margin-bottom: 25px;
        transition: all 0.2s;
    }

    .timeline-node-item::before {
        content: '';
        position: absolute;
        left: -30px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #060913;
        border: 2px solid #506690;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        z-index: 2;
        transition: all 0.2s;
    }

    .node-status-1::before {
        border-color: #00f2ff;
        background: rgba(0, 242, 255, 0.2);
        box-shadow: 0 0 8px #00f2ff;
    }

    .node-status-final::before {
        border-color: #00ff66;
        background: rgba(0, 255, 102, 0.2);
        box-shadow: 0 0 8px #00ff66;
    }

    .timeline-node-block {
        background: rgba(13, 20, 36, 0.4);
        border: 1px solid #101c38;
        padding: 15px;
        border-radius: 4px;
    }

    .timeline-node-item:hover .timeline-node-block {
        border-color: #9d4edd;
        background: rgba(157, 78, 221, 0.02);
    }

    .node-meta-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: #506690;
        margin-bottom: 6px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        padding-bottom: 4px;
    }

    .node-state-badge {
        font-weight: bold;
        font-size: 0.85rem;
        color: #00f2ff;
        text-transform: uppercase;
    }

    .node-observation {
        font-size: 0.9rem;
        color: #cbd5e1;
        line-height: 1.4;
        word-break: break-word;
    }

    .node-operator {
        color: #ffca28;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }
</style>

<script src="vistas/js/rma-core/historialEstado.js"></script>