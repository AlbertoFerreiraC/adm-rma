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

            <!-- PANEL IZQUIERDO: Buscador y Lista de Casos -->
            <div class="cyber-panel-card glass-panel-neon border-neon-green">
                <div class="panel-cyber-header">
                    <h4><span class="green-accent">//</span> SELECCIONAR CASO PARA DISPARAR ALERTA</h4>
                </div>
                <div class="panel-cyber-body-stats font-mono mt-3">
                    <div class="cyber-form-group">
                        <label class="node-label">FILTRAR CASO (N° CASO / CLIENTE / CÉDULA):</label>

                        <div class="cyber-search-box" style="max-width: 100%;">
                            <i class="fa fa-search search-icon-hud"></i>
                            <input type="text" id="inputFiltroNotif" class="cyber-input-search"
                                placeholder="ej: RMA-0004 o Wilson">
                        </div>
                    </div>

                    <div class="cyber-form-group mt-3">
                        <label class="node-label">CASOS DISPONIBLES EN MATRIZ:</label>

                        <!-- Lista adaptable para evitar recortes de texto -->
                        <div class="cyber-select-container">
                            <select id="selectCasoNotif" class="cyber-select-list" size="8">
                                <!-- Opciones cargadas dinámicamente vía JS -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL DERECHO: Previsualización del Mensaje y Enlace wa.me -->
            <div class="cyber-panel-card glass-panel-neon border-neon-cyan table-span-2">
                <div class="panel-cyber-header flex-header-toolbar">
                    <h4><span class="cyan-accent">//</span> TERMINAL DE SALIDA DE MENSAJES WHATSAPP</h4>
                    <span id="badgeCasoActual" class="system-badge-live" style="display:none;">SELECCIONADO</span>
                </div>

                <div id="contenedorMensajeWA" class="panel-cyber-body-stats mt-3 font-mono" style="display: none;">

                    <div class="cyber-form-group-row mb-3"
                        style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="info-card-wa">
                            <span class="node-label">CLIENTE / DESTINATARIO:</span>
                            <div id="lblClienteWA" class="text-white font-mono font-weight-bold"
                                style="font-size: 1.05rem;">-</div>
                        </div>
                        <div class="info-card-wa">
                            <span class="node-label">NÚMERO CELULAR (PARAGUAY):</span>
                            <div id="lblCelularWA" class="t-cyan font-mono font-weight-bold"
                                style="font-size: 1.05rem;">
                                -</div>
                        </div>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label text-yellow">// ESTADO ACTUAL DEL EQUIPO:</label>
                        <select id="selectEstadoWA" class="cyber-input">
                            <!-- Estados cargados dinámicamente vía JS -->
                        </select>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label text-yellow">// PREVISUALIZACIÓN DEL TEXTO MODELO:</label>
                        <textarea id="txtAreaMensajeWA" class="cyber-input cyber-textarea-wa" rows="5"
                            readonly></textarea>
                    </div>

                    <div class="mt-4">
                        <a id="btnLanzarWA" href="#" target="_blank" class="node-submit-btn-wa">
                            <i class="fa fa-whatsapp" style="font-size: 1.3rem; margin-right: 8px;"></i>
                            [DISPARAR_NOTIFICACIÓN_WHATSAPP]
                        </a>
                    </div>
                </div>

                <!-- Estado Inicial VACÍO -->
                <div id="contenedorVacioWA" class="text-center font-mono" style="padding: 60px 0;">
                    <i class="fa fa-comments-o mb-3" style="font-size: 3.5rem; color: var(--border-cyber-subtle);"></i>
                    <p style="color: var(--text-cyber-muted); font-size: 0.9rem;">SELECCIONE UN CASO DEL LISTADO
                        IZQUIERDO PARA CONSTRUIR EL MENSAJE</p>
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
        border: 1px solid var(--neon-green-dark);
        color: var(--neon-green-dark);
        background: rgba(21, 128, 61, 0.08);
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: bold;
    }

    .cyber-grid-three-columns {
        display: grid;
        grid-template-columns: 1fr 2.2fr;
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
        background: var(--card-cyber-light);
        border: 1px solid var(--border-cyber-subtle);
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    }

    .border-neon-green {
        border-left: 4px solid var(--neon-green-dark);
    }

    .border-neon-cyan {
        border-left: 4px solid var(--neon-cyan-dark);
    }

    .flex-header-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .green-accent {
        color: var(--neon-green-dark);
        font-weight: bold;
        margin-right: 5px;
    }

    .cyan-accent {
        color: var(--neon-cyan-dark);
        font-weight: bold;
        margin-right: 5px;
    }

    .text-yellow {
        color: var(--neon-yellow-dark);
        font-weight: bold;
    }

    .font-mono {
        font-family: 'Share Tech Mono', monospace;
    }

    .cyber-form-group {
        margin-bottom: 15px;
    }

    .node-label {
        color: var(--text-cyber-muted);
        font-size: 0.75rem;
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .cyber-search-box {
        position: relative;
        flex: 1;
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
        box-sizing: border-box;
    }

    .cyber-input-search:focus {
        border-color: var(--neon-cyan-dark);
        background: #ffffff;
        outline: none;
    }

    .cyber-input {
        width: 100%;
        background: #f8fafc;
        border: 1px solid var(--border-cyber-subtle);
        color: var(--text-cyber-dark);
        padding: 8px 12px;
        font-family: monospace;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .cyber-input:focus {
        border-color: var(--neon-cyan-dark);
        background: #ffffff;
        outline: none;
    }

    /* FIX: LISTA ADAPTABLE SIN RECORTE DE TEXTO */
    .cyber-select-container {
        border: 1px solid var(--border-cyber-subtle);
        border-radius: 6px;
        background: #f8fafc;
        overflow: hidden;
    }

    select.cyber-select-list {
        width: 100%;
        height: 280px !important;
        border: none;
        background: transparent;
        padding: 6px;
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.82rem;
        color: var(--text-cyber-dark);
        outline: none;
    }

    select.cyber-select-list option {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        border-radius: 4px;
        margin-bottom: 2px;
        white-space: normal;
        word-wrap: break-word;
        cursor: pointer;
    }

    select.cyber-select-list option:hover,
    select.cyber-select-list option:focus,
    select.cyber-select-list option:checked {
        background: rgba(2, 132, 199, 0.1) !important;
        color: var(--neon-cyan-dark) !important;
        font-weight: bold;
    }

    .info-card-wa {
        background: #f8fafc;
        border: 1px solid var(--border-cyber-subtle);
        padding: 10px 14px;
        border-radius: 6px;
    }

    .cyber-textarea-wa {
        color: var(--neon-green-dark) !important;
        border-color: rgba(21, 128, 61, 0.3) !important;
        background: #f0fdf4 !important;
        font-weight: bold;
        resize: vertical;
        line-height: 1.4;
    }

    .node-submit-btn-wa {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(21, 128, 61, 0.1);
        border: 1px solid var(--neon-green-dark);
        color: var(--neon-green-dark);
        width: 100%;
        padding: 12px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        font-size: 0.95rem;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .node-submit-btn-wa:hover {
        background: var(--neon-green-dark);
        color: #ffffff;
        box-shadow: 0 0 15px rgba(21, 128, 61, 0.3);
        text-decoration: none;
    }

    .t-cyan {
        color: var(--neon-cyan-dark);
        font-weight: bold;
    }

    .text-white {
        color: var(--text-cyber-dark);
    }

    .text-center {
        text-align: center;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }
</style>

<script src="vistas/js/clientes/notificaciones.js"></script>