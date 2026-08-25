<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">⚠️</span>
            <h2>[05] STOCK_ALERT // MONITOR DE STOCK CRÍTICO & REABASTECIMIENTO</h2>
            <span class="system-badge-live-alert">CRITICAL_MONITOR_ACTIVE</span>
        </div>
    </header>

    <section class="cyber-content mt-4">

        <!-- TARJETAS DE KPIS DE ALERTAS -->
        <div class="tec-metrics-row mb-4">
            <div class="cyber-kpi-card glass-panel-neon border-neon-red">
                <div class="kpi-header-inline font-mono">
                    <span class="text-neon-red">STOCK // AGOTADO</span>
                    <span class="blink-anim">🚨 ALERTA</span>
                </div>
                <div class="kpi-body-compact font-mono">
                    <h3 class="text-neon-red" id="kpiAgotados">0</h3>
                    <p class="kpi-title-text text-neon-red">Insumos sin unidades</p>
                </div>
                <div class="kpi-footer-meta font-mono">Requieren reposición inmediata</div>
            </div>

            <div class="cyber-kpi-card glass-panel-neon border-neon-yellow">
                <div class="kpi-header-inline font-mono">
                    <span class="text-neon-yellow">STOCK // UMBLAR_MÍNIMO</span>
                    <span class="pulse-dot-yellow"></span>
                </div>
                <div class="kpi-body-compact font-mono">
                    <h3 class="text-neon-yellow" id="kpiCriticos">0</h3>
                    <p class="kpi-title-text text-neon-yellow">Insumos por debajo del mínimo</p>
                </div>
                <div class="kpi-footer-meta font-mono">Próximos a agotarse</div>
            </div>

            <div class="cyber-kpi-card glass-panel-neon border-neon-blue">
                <div class="kpi-header-inline font-mono">
                    <span class="t-cyan">PROVEEDORES // IMPACTADOS</span>
                    <span>LOGÍSTICA</span>
                </div>
                <div class="kpi-body-compact font-mono">
                    <h3 class="t-cyan" id="kpiProveedoresImpactados">0</h3>
                    <p class="kpi-title-text">Proveedores involucrados</p>
                </div>
                <div class="kpi-footer-meta font-mono">Para emisión de órdenes de compra</div>
            </div>

            <div class="cyber-kpi-card glass-panel-neon border-neon-orange">
                <div class="kpi-header-inline font-mono">
                    <span class="t-orange">PRESUPUESTO // REPOSICIÓN</span>
                    <span>ESTIMADO</span>
                </div>
                <div class="kpi-body-compact font-mono">
                    <h3 class="t-orange" id="kpiCostoEstimado">₲ 0</h3>
                    <p class="kpi-title-text">Costo de reposición base</p>
                </div>
                <div class="kpi-footer-meta font-mono">Basado en costo unitario registrado</div>
            </div>
        </div>

        <!-- PANEL DE LA TABLA DE ALERTAS -->
        <div class="cyber-panel-card glass-panel-neon border-neon-red">

            <div class="panel-cyber-header flex-header-toolbar">
                <div class="cyber-search-box">
                    <i class="fa fa-search search-icon-hud"></i>
                    <input type="text" id="buscarAlerta" class="cyber-input-search"
                        placeholder="🔍 Filtrar por SKU, insumo o proveedor...">
                </div>

                <div style="display: flex; gap: 10px;">
                    <select id="filtroTipoAlerta" class="cyber-input" style="width: auto;">
                        <option value="todos">[ TODAS LAS ALERTAS ]</option>
                        <option value="agotados">🚨 solo AGOTADOS (0 unidades)</option>
                        <option value="criticos">⚠️ solo CRÍTICOS (&le; mínimo)</option>
                    </select>

                    <button type="button" class="btn-cyber-refresh" id="btnRecargarAlertas" title="Actualizar alertas">
                        <i class="fa fa-refresh"></i> [RECARRAGAR]
                    </button>
                </div>
            </div>

            <div class="panel-cyber-body-table mt-3 font-mono">
                <table class="cyber-mini-table" id="tablaAlertas">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Insumo / Repuesto</th>
                            <th>Categoría</th>
                            <th>Proveedor Habitual</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                            <th>Deficit</th>
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

<!-- ==========================================
     MODAL EMERGENTE: REABASTECER STOCK (NATIVO)
========================================== -->
<div class="custom-cyber-modal-overlay" id="modalRestockOverlay" style="display: none;">
    <div class="custom-cyber-modal-container border-neon-orange" style="max-width: 500px;">

        <div class="cyber-modal-header">
            <button type="button" class="text-cyber-close" id="btnCerrarRestockX">&times;</button>
            <h4 class="modal-title font-mono">
                <span class="t-orange">//</span> [REABASTECER_INSUMO]
            </h4>
        </div>

        <form id="formRestock" autocomplete="off" method="POST" onsubmit="return false;">
            <div class="modal-body font-mono">
                <input type="hidden" name="id" id="restock_id">

                <div class="cyber-form-group">
                    <label class="node-label">INSUMO A REABASTECER:</label>
                    <input type="text" id="restock_nombre_display" class="cyber-input" readonly
                        style="background:#e2e8f0; font-weight:bold;">
                </div>

                <div class="cyber-form-group-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="cyber-form-group">
                        <label class="node-label">STOCK ACTUAL:</label>
                        <input type="text" id="restock_stock_actual_display" class="cyber-input" readonly
                            style="background:#e2e8f0;">
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">CANTIDAD A INGRESAR:</label>
                        <input type="number" name="cantidad_ingreso" id="restock_cantidad" class="cyber-input" min="1"
                            placeholder="Ej: 10" required>
                    </div>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">MOTIVO / NOTA DE RECEPCIÓN:</label>
                    <textarea name="motivo" id="restock_motivo" class="cyber-input cyber-textarea" rows="2"
                        placeholder="Ej: Reabastecimiento por compra de insumos de taller"></textarea>
                </div>
            </div>

            <div class="modal-footer cyber-modal-footer">
                <button type="button" class="btn-cyber-cancel" id="btnCancelarRestock">CANCELAR</button>
                <button type="submit" id="btnConfirmarRestock"
                    class="node-submit-btn-orange">[INGRESAR_AL_STOCK]</button>
            </div>
        </form>

    </div>
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
        --neon-green-dark: #15803d;
        --neon-red-dark: #dc2626;
        --neon-orange-dark: #f97316;
        --neon-yellow-dark: #d97706;
    }

    .dashboard-cyber-wrapper {
        background-color: var(--bg-cyber-light);
        background-image:
            radial-gradient(circle at 50% 10%, rgba(220, 38, 38, 0.04) 0%, transparent 60%),
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
        border-bottom: 2px solid var(--neon-red-dark);
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
    }

    .system-badge-live-alert {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        border: 1px solid var(--neon-red-dark);
        color: var(--neon-red-dark);
        background: rgba(220, 38, 38, 0.08);
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: bold;
    }

    .tec-metrics-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        width: 100%;
    }

    @media(max-width: 1100px) {
        .tec-metrics-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 600px) {
        .tec-metrics-row {
            grid-template-columns: 1fr;
        }
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

    .border-neon-red {
        border-left: 4px solid var(--neon-red-dark);
    }

    .border-neon-yellow {
        border-left: 4px solid var(--neon-yellow-dark);
    }

    .border-neon-blue {
        border-left: 4px solid var(--neon-cyan-dark);
    }

    .border-neon-orange {
        border-left: 4px solid var(--neon-orange-dark);
    }

    .kpi-header-inline {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.7rem;
        color: var(--text-cyber-muted);
    }

    .kpi-body-compact h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 4px 0 0 0;
        line-height: 1;
    }

    .kpi-title-text {
        font-size: 0.75rem;
        color: var(--text-cyber-muted);
        margin: 4px 0 0 0;
        font-weight: bold;
        text-transform: uppercase;
    }

    .kpi-footer-meta {
        font-size: 0.7rem;
        color: var(--text-cyber-muted);
        margin-top: 4px;
    }

    .blink-anim {
        animation: blink 1.2s infinite;
    }

    @keyframes blink {
        to {
            opacity: 0.3;
        }
    }

    .pulse-dot-yellow {
        width: 8px;
        height: 8px;
        background: var(--neon-yellow-dark);
        border-radius: 50%;
        animation: blink 1s infinite;
    }

    .cyber-panel-card {
        background: var(--card-cyber-light);
        border: 1px solid var(--border-cyber-subtle);
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
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
    }

    .btn-cyber-refresh {
        background: rgba(2, 132, 199, 0.08);
        border: 1px solid var(--neon-cyan-dark);
        color: var(--neon-cyan-dark);
        padding: 8px 14px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cyber-refresh:hover {
        background: var(--neon-cyan-dark);
        color: #ffffff;
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

    .t-orange {
        color: var(--neon-orange-dark);
        font-weight: bold;
    }

    .t-cyan {
        color: var(--neon-cyan-dark);
        font-weight: bold;
    }

    .text-neon-red {
        color: var(--neon-red-dark);
        font-weight: bold;
    }

    .text-neon-yellow {
        color: var(--neon-yellow-dark);
        font-weight: bold;
    }

    .btn-terminal-restock {
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid var(--neon-orange-dark);
        color: var(--neon-orange-dark);
        padding: 4px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.75rem;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        transition: all 0.2s;
    }

    .btn-terminal-restock:hover {
        background: var(--neon-orange-dark);
        color: #ffffff;
    }

    /* MODAL NATIVO ESTÁNDAR */
    .custom-cyber-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-cyber-modal-container {
        background: #ffffff;
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid var(--border-cyber-subtle);
        overflow: hidden;
        animation: fadeInModal 0.2s ease-out;
    }

    @keyframes fadeInModal {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .cyber-modal-header {
        border-bottom: 1px solid var(--border-cyber-subtle);
        padding: 15px 20px;
        background: #f8fafc;
    }

    .text-cyber-close {
        color: var(--text-cyber-muted);
        font-size: 1.5rem;
        float: right;
        background: transparent;
        border: none;
        cursor: pointer;
    }

    .text-cyber-close:hover {
        color: var(--neon-red-dark);
    }

    .modal-body {
        padding: 20px;
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
        border-color: var(--neon-orange-dark);
        background: #ffffff;
        outline: none;
    }

    .cyber-textarea {
        resize: vertical;
        font-family: 'Share Tech Mono', monospace;
    }

    .cyber-modal-footer {
        border-top: 1px solid var(--border-cyber-subtle);
        padding: 12px 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #f8fafc;
    }

    .node-submit-btn-orange {
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid var(--neon-orange-dark);
        color: var(--neon-orange-dark);
        padding: 8px 16px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
        border-radius: 4px;
    }

    .node-submit-btn-orange:hover {
        background: var(--neon-orange-dark);
        color: #ffffff;
    }

    .btn-cyber-cancel {
        background: transparent;
        border: 1px solid var(--border-cyber-subtle);
        color: var(--text-cyber-muted);
        padding: 8px 16px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-cyber-cancel:hover {
        border-color: var(--neon-red-dark);
        color: var(--neon-red-dark);
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

<script src="vistas/js/inventario/stockAlertas.js"></script>