<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">🔧</span>
            <h2>[05] STOCK_ASSIGN // ASIGNACIÓN DE INSUMOS Y REPUESTOS EN TALLER</h2>
            <span class="system-badge-live-orange">RMA_CONSUMPTION_ACTIVE</span>
        </div>
    </header>

    <section class="cyber-content mt-4">

        <!-- PANEL DE ASIGNACIÓN Y REGISTRO DE CONSUMO -->
        <div class="cyber-panel-card glass-panel-neon border-neon-orange">

            <div class="panel-cyber-header flex-header-toolbar">
                <div class="cyber-search-box">
                    <i class="fa fa-search search-icon-hud"></i>
                    <input type="text" id="buscarAsignacion" class="cyber-input-search"
                        placeholder="🔍 Buscar por N° Caso, Insumo, SKU o Técnico...">
                </div>

                <button type="button" class="btn-cyber-add-orange" id="btnNuevaAsignacion">
                    <i class="fa fa-wrench"></i> [+ APLICAR INSUMO A CASO RMA]
                </button>
            </div>

            <!-- TABLA DE HISTORIAL DE ASIGNACIONES EN TALLER -->
            <div class="panel-cyber-body-table mt-3 font-mono">
                <table class="cyber-mini-table" id="tablaAsignaciones">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>N° Caso RMA</th>
                            <th>Insumo / Repuesto</th>
                            <th>SKU</th>
                            <th>Cantidad</th>
                            <th>Costo Aplicado</th>
                            <th>Costo Total</th>
                            <th>Técnico Responsable</th>
                            <th>Fecha Aplicación</th>
                            <th>Observación</th>
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
     MODAL EMERGENTE: REGISTRAR CONSUMO DE INSUMO EN RMA (NATIVO)
========================================== -->
<div class="custom-cyber-modal-overlay" id="modalAsignacionOverlay" style="display: none;">
    <div class="custom-cyber-modal-container border-neon-orange" style="max-width: 600px;">

        <div class="cyber-modal-header">
            <button type="button" class="text-cyber-close" id="btnCerrarAsignacionX">&times;</button>
            <h4 class="modal-title font-mono">
                <span class="t-orange">//</span> [APLICAR_INSUMO_A_REPARACION]
            </h4>
        </div>

        <form id="formAsignacion" autocomplete="off" method="POST" onsubmit="return false;">
            <div class="modal-body font-mono">

                <div class="cyber-form-group">
                    <label class="node-label">CASO DE RMA (EQUIPO EN TALLER):</label>
                    <select name="id_caso" id="asig_id_caso" class="cyber-input" required>
                        <option value="">[SELECCIONE CASO RMA]</option>
                    </select>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">INSUMO / REPUESTO DISPONIBLE:</label>
                    <select name="id_stock" id="asig_id_stock" class="cyber-input" required>
                        <option value="">[SELECCIONE INSUMO DEL STOCK]</option>
                    </select>
                </div>

                <div class="cyber-form-group-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="cyber-form-group">
                        <label class="node-label">CANTIDAD A APLICAR:</label>
                        <input type="number" name="cantidad" id="asig_cantidad" class="cyber-input" min="1" value="1"
                            required>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">COSTO UNITARIO APLICADO (₲):</label>
                        <input type="number" step="0.01" name="costo_aplicado" id="asig_costo_aplicado"
                            class="cyber-input" placeholder="Ej: 35000" required>
                    </div>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">TÉCNICO OPERADOR RESPONSABLE:</label>
                    <select name="id_tecnico" id="asig_id_tecnico" class="cyber-input" required>
                        <option value="">[SELECCIONE TÉCNICO]</option>
                    </select>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">OBSERVACIÓN / NOTA DE TRABAJO:</label>
                    <textarea name="observacion" id="asig_observacion" class="cyber-input cyber-textarea" rows="2"
                        placeholder="Ej: Reemplazo de pasta térmica y limpieza de disipador de calor"></textarea>
                </div>

            </div>

            <div class="modal-footer cyber-modal-footer">
                <button type="button" class="btn-cyber-cancel" id="btnCancelarAsignacion">CANCELAR</button>
                <button type="submit" id="btnGuardarAsignacion"
                    class="node-submit-btn-orange">[APLICAR_Y_DESCONTAR]</button>
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

    .system-badge-live-orange {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        border: 1px solid var(--neon-orange-dark);
        color: var(--neon-orange-dark);
        background: rgba(249, 115, 22, 0.08);
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

    .border-neon-orange {
        border-left: 4px solid var(--neon-orange-dark);
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

    .btn-cyber-add-orange {
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

    .btn-cyber-add-orange:hover {
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

    .mt-3 {
        margin-top: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }
</style>

<script src="vistas/js/inventario/stockAsignaciones.js"></script>