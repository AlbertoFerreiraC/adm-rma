<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">💬</span>
            <h2>[03] COMMS_NODE // ALERTAS WHATSAPP Y NOTIFICACIONES DE ESTADO</h2>
            <span class="system-badge-live">WHATSAPP_DISPATCHER</span>
        </div>
    </header>

    <section class="cyber-content mt-4">
        <div class="cyber-grid-three-columns">

            <!-- PANEL IZQUIERDO: Buscador y Selección de Caso -->
            <div class="cyber-panel-card glass-panel-neon border-neon-green">
                <div class="panel-cyber-header">
                    <h4><span class="green-accent">//</span> SELECCIONAR CASO PARA DISPARAR ALERTA</h4>
                </div>
                <div class="panel-cyber-body-stats font-mono mt-3">
                    <div class="cyber-form-group">
                        <label class="node-label">FILTRAR CASO (N° CASO / CLIENTE / CEDULA):</label>
                        <input type="text" id="inputFiltroNotif" class="cyber-input"
                            placeholder="ej: RMA-0004 o Wilson">
                    </div>

                    <div class="cyber-form-group mt-3">
                        <label class="node-label">CASO SELECCIONADO:</label>
                        <select id="selectCasoNotif" class="cyber-input" size="8"
                            style="height: 220px; overflow-y: auto;">
                            <!-- Opciones cargadas dinámicamente -->
                        </select>
                    </div>
                </div>
            </div>

            <!-- PANEL DERECHO: Previsualización del Mensaje y Enlace wa.me -->
            <div class="cyber-panel-card glass-panel-neon border-neon-cyan table-span-2">
                <div class="panel-cyber-header flex-header">
                    <h4><span class="cyan-accent">//</span> TERMINAL DE SALIDA DE MENSAJES WHATSAPP</h4>
                    <span id="badgeCasoActual" class="system-badge-live" style="display:none;">SELECCIONADO</span>
                </div>

                <div id="contenedorMensajeWA" class="panel-cyber-body-stats mt-3" style="display: none;">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <span class="node-label">CLIENTE / DESTINATARIO:</span>
                            <div id="lblClienteWA" class="text-white font-mono font-weight-bold"
                                style="font-size: 1.1rem;">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <span class="node-label">NÚMERO CELULAR (PARAGUAY):</span>
                            <div id="lblCelularWA" class="t-cyan font-mono font-weight-bold" style="font-size: 1.1rem;">
                                -</div>
                        </div>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">ESTADO ACTUAL DEL EQUIPO:</label>
                        <select id="selectEstadoWA" class="cyber-input">
                            <!-- Estados cargados dinámicamente -->
                        </select>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">PREVISUALIZACIÓN DEL TEXTO MODELO:</label>
                        <textarea id="txtAreaMensajeWA" class="cyber-input" rows="4" readonly
                            style="color: #00ff66; border-color: rgba(0, 255, 102, 0.3); background: #02050b;"></textarea>
                    </div>

                    <div class="mt-4">
                        <a id="btnLanzarWA" href="#" target="_blank" class="node-submit-btn-wa">
                            <i class="fa fa-whatsapp" style="font-size: 1.3rem; margin-right: 8px;"></i>
                            [DISPARAR_NOTIFICACIÓN_WHATSAPP]
                        </a>
                    </div>
                </div>

                <!-- Estado Inicial VACÍO -->
                <div id="contenedorVacioWA" class="text-center font-mono py-5">
                    <i class="fa fa-comments-o mb-3" style="font-size: 3rem; color: #101c38;"></i>
                    <p style="color: #506690;">SELECCIONE UN CASO DEL LISTADO IZQUIERDO PARA CONSTRUIR EL MENSAJE</p>
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

    .header-brand-glitch {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-brand-glitch h2 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
        letter-spacing: 0.5px;
    }

    .system-badge-live {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        border: 1px solid #00ff66;
        color: #00ff66;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .cyber-grid-three-columns {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 20px;
    }

    @media(max-width: 992px) {
        .cyber-grid-three-columns {
            grid-template-columns: 1fr;
        }

        .table-span-2 {
            grid-column: span 1 !important;
        }
    }

    .cyber-panel-card {
        background: rgba(10, 16, 32, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 20px;
    }

    .border-neon-green {
        border-left: 3px solid #00ff66;
    }

    .border-neon-cyan {
        border-left: 3px solid #00b4d8;
    }

    .panel-cyber-header h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }

    .flex-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .green-accent {
        color: #00ff66;
        margin-right: 5px;
    }

    .cyan-accent {
        color: #00f2ff;
        margin-right: 5px;
    }

    .font-mono {
        font-family: 'Share Tech Mono', monospace;
    }

    .cyber-form-group {
        margin-bottom: 15px;
    }

    .node-label {
        color: #506690;
        font-size: 0.75rem;
        display: block;
        margin-bottom: 5px;
    }

    .cyber-input {
        width: 100%;
        background: #03050c;
        border: 1px solid #101c38;
        color: #fff;
        padding: 8px 12px;
        font-family: monospace;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .cyber-input:focus {
        border-color: #00ff66;
        outline: none;
    }

    .node-submit-btn-wa {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(37, 211, 102, 0.15);
        border: 1px solid #25D366;
        color: #25D366;
        width: 100%;
        padding: 12px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        font-size: 1rem;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .node-submit-btn-wa:hover {
        background: rgba(37, 211, 102, 0.3);
        box-shadow: 0 0 15px rgba(37, 211, 102, 0.4);
        color: #fff;
        text-decoration: none;
    }

    .t-cyan {
        color: #00b4d8;
    }

    .text-white {
        color: #fff;
    }
</style>

<script src="vistas/js/clientes/notificaciones.js"></script>