<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">📜</span>
            <h2>[05] KARDEX_LOG // AUDITORÍA DE MOVIMIENTOS DE STOCK</h2>
            <span class="system-badge-live-purple">KARDEX_AUDIT_ACTIVE</span>
        </div>
    </header>

    <section class="cyber-content mt-4">

        <!-- PANEL DE KARDEX DE INVENTARIO -->
        <div class="cyber-panel-card glass-panel-neon border-neon-purple">

            <div class="panel-cyber-header flex-header-toolbar">
                <div class="cyber-search-box">
                    <i class="fa fa-search search-icon-hud"></i>
                    <input type="text" id="buscarMovimiento" class="cyber-input-search"
                        placeholder="🔍 Filtrar por SKU, Insumo, Usuario o RMA...">
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <select id="filtroTipoMovimiento" class="cyber-input" style="width: auto;">
                        <option value="">[ TODOS LOS MOVIMIENTOS ]</option>
                        <option value="ENTRADA">🟢 ENTRADA (Compra/Reabastecimiento)</option>
                        <option value="SALIDA_RMA">🔴 SALIDA_RMA (Consumo Taller)</option>
                        <option value="AJUSTE_INVENTARIO">🔵 AJUSTE_INVENTARIO (Manual)</option>
                        <option value="DEVOLUCION">🟡 DEVOLUCION (Reingreso)</option>
                    </select>

                    <button type="button" class="btn-cyber-add-purple" id="btnNuevoAjuste">
                        <i class="fa fa-sliders"></i> [+ REGISTRAR AJUSTE MANUAL]
                    </button>
                </div>
            </div>

            <!-- TABLA DE KARDEX DE MOVIMIENTOS -->
            <div class="panel-cyber-body-table mt-3 font-mono">
                <table class="cyber-mini-table" id="tablaMovimientos">
                    <thead>
                        <tr>
                            <th>ID Log</th>
                            <th>Fecha</th>
                            <th>Tipo Movimiento</th>
                            <th>Insumo / Repuesto</th>
                            <th>SKU</th>
                            <th>Cant. Movida</th>
                            <th>Stock Anterior</th>
                            <th>Stock Nuevo</th>
                            <th>Caso RMA</th>
                            <th>Operador / Usuario</th>
                            <th>Motivo / Observación</th>
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
     MODAL EMERGENTE: REGISTRAR AJUSTE MANUAL (NATIVO)
========================================== -->
<div class="custom-cyber-modal-overlay" id="modalAjusteOverlay" style="display: none;">
    <div class="custom-cyber-modal-container border-neon-purple" style="max-width: 550px;">

        <div class="cyber-modal-header">
            <button type="button" class="text-cyber-close" id="btnCerrarAjusteX">&times;</button>
            <h4 class="modal-title font-mono">
                <span class="t-purple">//</span> [AJUSTE_MANUAL_INVENTARIO]
            </h4>
        </div>

        <form id="formAjuste" autocomplete="off" method="POST" onsubmit="return false;">
            <div class="modal-body font-mono">

                <div class="cyber-form-group">
                    <label class="node-label">INSUMO A AJUSTAR:</label>
                    <select name="id_stock" id="ajuste_id_stock" class="cyber-input" required>
                        <option value="">[SELECCIONE INSUMO]</option>
                    </select>
                </div>

                <div class="cyber-form-group-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="cyber-form-group">
                        <label class="node-label">TIPO DE AJUSTE:</label>
                        <select name="tipo_movimiento" id="ajuste_tipo_movimiento" class="cyber-input" required>
                            <option value="AJUSTE_INVENTARIO">🔵 AJUSTE INVENTARIO (Físico)</option>
                            <option value="DEVOLUCION">🟡 DEVOLUCION (Reingreso a taller)</option>
                            <option value="ENTRADA">🟢 ENTRADA (Compra)</option>
                        </select>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">CANTIDAD (UNIDADES):</label>
                        <input type="number" name="cantidad" id="ajuste_cantidad" class="cyber-input" min="1"
                            placeholder="Ej: 5" required>
                    </div>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">MOTIVO / JUSTIFICACIÓN TÉCNICA:</label>
                    <textarea name="motivo" id="ajuste_motivo" class="cyber-input cyber-textarea" rows="3"
                        placeholder="Ej: Corrección por conteo físico de inventario o merma técnica..."
                        required></textarea>
                </div>

            </div>

            <div class="modal-footer cyber-modal-footer">
                <button type="button" class="btn-cyber-cancel" id="btnCancelarAjuste">CANCELAR</button>
                <button type="submit" id="btnGuardarAjuste"
                    class="node-submit-btn-purple">[EJECUTAR_AJUSTE_KARDEX]</button>
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
        --neon-purple-dark: #7e22ce;
        --neon-orange-dark: #f97316;
        --neon-yellow-dark: #d97706;
    }

    .dashboard-cyber-wrapper {
        background-color: var(--bg-cyber-light);
        background-image:
            radial-gradient(circle at 50% 10%, rgba(126, 34, 206, 0.05) 0%, transparent 60%),
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
        border-bottom: 2px solid var(--neon-purple-dark);
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

    .system-badge-live-purple {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        border: 1px solid var(--neon-purple-dark);
        color: var(--neon-purple-dark);
        background: rgba(126, 34, 206, 0.08);
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

    .btn-cyber-add-purple {
        background: rgba(126, 34, 206, 0.1);
        border: 1px solid var(--neon-purple-dark);
        color: var(--neon-purple-dark);
        padding: 8px 16px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        font-size: 0.85rem;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cyber-add-purple:hover {
        background: var(--neon-purple-dark);
        color: #ffffff;
        box-shadow: 0 0 12px rgba(126, 34, 206, 0.3);
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

    .t-purple {
        color: var(--neon-purple-dark);
        font-weight: bold;
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
        border-color: var(--neon-purple-dark);
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

    .node-submit-btn-purple {
        background: rgba(126, 34, 206, 0.1);
        border: 1px solid var(--neon-purple-dark);
        color: var(--neon-purple-dark);
        padding: 8px 16px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
        border-radius: 4px;
    }

    .node-submit-btn-purple:hover {
        background: var(--neon-purple-dark);
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

    .mt-3 {
        margin-top: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }
</style>

<script src="vistas/js/inventario/stockMovimientos.js"></script>