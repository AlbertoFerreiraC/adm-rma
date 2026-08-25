<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">📦</span>
            <h2>[05] STOCK_CORE // CATÁLOGO Y CONTROL DE INVENTARIO</h2>
            <span class="system-badge-live">INVENTORY_ACTIVE</span>
        </div>
    </header>

    <section class="cyber-content mt-4">

        <!-- PANEL PRINCIPAL DE LA TABLA DE STOCK -->
        <div class="cyber-panel-card glass-panel-neon border-neon-orange">

            <!-- BARRA SUPERIOR: Buscador, Filtro Categoría y Botón Agregar -->
            <div class="panel-cyber-header flex-header-toolbar">
                <div class="cyber-search-box">
                    <i class="fa fa-search search-icon-hud"></i>
                    <input type="text" id="buscarStock" class="cyber-input-search"
                        placeholder="🔍 Buscar por código SKU, nombre o descripción...">
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <select id="filtroCategoriaStock" class="cyber-input" style="width: auto;">
                        <option value="">[ TODAS LAS CATEGORÍAS ]</option>
                    </select>

                    <button type="button" class="btn-cyber-add-orange" id="btnNuevoItemStock">
                        <i class="fa fa-plus"></i> [+ AGREGAR INSUMO / REPUESTO]
                    </button>
                </div>
            </div>

            <!-- TABLA DE INVENTARIO -->
            <div class="panel-cyber-body-table mt-3 font-mono">
                <table class="cyber-mini-table" id="tablaStock">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código SKU</th>
                            <th>Nombre Insumo / Repuesto</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Costo Unit.</th>
                            <th>Precio Venta</th>
                            <th>Estado</th>
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
     MODAL EMERGENTE: REGISTRAR / EDITAR INSUMO (NATIVO)
========================================== -->
<div class="custom-cyber-modal-overlay" id="modalStockOverlay" style="display: none;">
    <div class="custom-cyber-modal-container border-neon-orange" style="max-width: 600px;">

        <div class="cyber-modal-header">
            <button type="button" class="text-cyber-close" id="btnCerrarModalX">&times;</button>
            <h4 class="modal-title font-mono" id="modalStockLabel">
                <span class="t-orange">//</span> <span id="lblTituloModal">[INJECT_STOCK_ITEM]</span>
            </h4>
        </div>

        <form id="formStock" autocomplete="off" method="POST" onsubmit="return false;">
            <div class="modal-body font-mono">
                <input type="hidden" name="id" id="stock_id">

                <div class="cyber-form-group-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="cyber-form-group">
                        <label class="node-label">CÓDIGO SKU / BARRAS:</label>
                        <input type="text" name="codigo_sku" id="stock_codigo_sku" class="cyber-input"
                            placeholder="Ej: SKU-ART-MX4" required>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">CATEGORÍA:</label>
                        <select name="id_categoria" id="stock_id_categoria" class="cyber-input" required>
                            <option value="">[SELECCIONE CATEGORÍA]</option>
                        </select>
                    </div>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">NOMBRE DEL INSUMO / REPUESTO:</label>
                    <input type="text" name="nombre" id="stock_nombre" class="cyber-input"
                        placeholder="Ej: Pasta Térmica Arctic MX-4 4g" required>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">DESCRIPCIÓN TÉCNICA:</label>
                    <textarea name="descripcion" id="stock_descripcion" class="cyber-input cyber-textarea" rows="2"
                        placeholder="Especificaciones, compuesto, densidad..."></textarea>
                </div>

                <div class="cyber-form-group-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="cyber-form-group">
                        <label class="node-label">CANTIDAD EN DEPOSITÓ:</label>
                        <input type="number" name="cantidad" id="stock_cantidad" class="cyber-input" min="0"
                            placeholder="Ej: 10" required>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">STOCK MÍNIMO ALERTA:</label>
                        <input type="number" name="stock_minimo" id="stock_stock_minimo" class="cyber-input" min="1"
                            value="2" required>
                    </div>
                </div>

                <div class="cyber-form-group-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="cyber-form-group">
                        <label class="node-label">COSTO UNITARIO (₲):</label>
                        <input type="number" step="0.01" name="costo_unitario" id="stock_costo_unitario"
                            class="cyber-input" placeholder="Ej: 35000" required>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">PRECIO VENTA CLIENTE (₲):</label>
                        <input type="number" step="0.01" name="precio_venta" id="stock_precio_venta" class="cyber-input"
                            placeholder="Ej: 60000">
                    </div>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">PROVEEDOR HABITUAL:</label>
                    <select name="id_proveedor_habitual" id="stock_id_proveedor_habitual" class="cyber-input">
                        <option value="">[NINGUNO / VARIOS]</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer cyber-modal-footer">
                <button type="button" class="btn-cyber-cancel hidden" id="btnCancelarModal">[CANCEL_EDIT]</button>
                <button type="submit" id="btnGuardarStock" class="node-submit-btn-orange">[EXECUTE_DEPLOYMENT]</button>
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
        --neon-cyan-glow: #00b4d8;
        --neon-green-dark: #15803d;
        --neon-red-dark: #dc2626;
        --neon-purple-dark: #7e22ce;
        --neon-orange-dark: #f97316;
        --neon-yellow-dark: #d97706;
    }

    .hidden {
        display: none !important;
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
        transition: all 0.2s;
    }

    .cyber-input-search:focus {
        border-color: var(--neon-orange-dark);
        background: #ffffff;
        box-shadow: 0 0 10px rgba(249, 115, 22, 0.15);
        outline: none;
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

    .t-cyan {
        color: var(--neon-cyan-dark);
        font-weight: bold;
    }

    .btn-terminal-edit,
    .btn-terminal-delete {
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
        color: var(--neon-orange-dark);
        border-color: var(--neon-orange-dark);
        background: rgba(249, 115, 22, 0.08);
    }

    .btn-terminal-delete:hover {
        color: var(--neon-red-dark);
        border-color: var(--neon-red-dark);
        background: rgba(220, 38, 38, 0.08);
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
        line-height: 1;
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
        transition: all 0.2s;
    }

    .node-submit-btn-orange:hover {
        background: var(--neon-orange-dark);
        color: #ffffff;
        box-shadow: 0 0 10px rgba(249, 115, 22, 0.3);
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

<script src="vistas/js/inventario/stockCatalogo.js"></script>