<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id_usuario_logistica = $_SESSION['id'] ?? '';
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">🚚</span>
            <h2>[03] RMA_CORE // FLUJO LOGÍSTICO EXTERNO (GARANTÍAS)</h2>
            <span class="system-badge-live">EXTERNAL_STREAM_ACTIVE</span>
        </div>
    </header>

    <section class="cyber-content mt-4">

        <!-- PANEL PRINCIPAL DE LA TABLA -->
        <div class="cyber-panel-card glass-panel-neon border-neon-cyan">

            <!-- BARRA SUPERIOR: Buscador y Filtros -->
            <div class="panel-cyber-header flex-header-toolbar">
                <div class="cyber-search-box">
                    <i class="fa fa-search search-icon-hud"></i>
                    <input type="text" id="buscadorLogistica" class="cyber-input-search"
                        placeholder="🔍 Buscar por N° caso, cliente, serie o service...">
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <select id="filtroLogisticaAlcance" class="cyber-input" style="width: auto;">
                        <option value="todos">[ MOSTRAR MATRIZ COMPLETA ]</option>
                        <option value="en_taller">[ EN TALLER / RECIÉN INGRESADOS ]</option>
                        <option value="en_proveedor">[ EN SERVICE OFICIAL / ENVIADOS ]</option>
                    </select>
                </div>
            </div>

            <!-- TABLA PRINCIPAL DE MONITOREO DE CASOS -->
            <div class="panel-cyber-body-table mt-3">
                <table class="cyber-mini-table" id="tablaLogistica">
                    <thead>
                        <tr>
                            <th>N° CASO</th>
                            <th>DISPOSITIVO / MARCA</th>
                            <th>N° SERIE</th>
                            <th>SERVICE ASIGNADO</th>
                            <th>ESTADO</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyLogistica">
                        <tr>
                            <td colspan="6" class="text-center font-mono">[STREAMING_EXTERNAL_FLOW_DATA...]</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</div>

<!-- ==========================================
     MODAL EMERGENTE: CONSOLA DE DESPACHO Y ASIGNACIÓN (NATIVO)
========================================== -->
<div class="custom-cyber-modal-overlay" id="modalLogisticaOverlay" style="display: none;">
    <div class="custom-cyber-modal-container border-neon-blue" style="max-width: 650px;">

        <div class="cyber-modal-header">
            <button type="button" class="text-cyber-close" id="btnCerrarModalX">&times;</button>
            <h4 class="modal-title font-mono" id="modalLogisticaLabel">
                <span class="cyan-accent">//</span> <span id="lblTituloModal">[DESPACHO_LOGÍSTICO: RMA-0000]</span>
            </h4>
        </div>

        <form id="formFlujoExterno" autocomplete="off" method="POST" onsubmit="return false;">
            <div class="modal-body font-mono">
                <input type="hidden" name="id_caso" id="logIdCaso">
                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario_logistica; ?>">

                <div class="cyber-form-group-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="cyber-form-group">
                        <label class="node-label">NODO SELECCIONADO:</label>
                        <input type="text" id="logNumeroCaso" class="cyber-input locked-node" readonly>
                    </div>
                    <div class="cyber-form-group">
                        <label class="node-label">NÚMERO DE SERIE (S/N):</label>
                        <input type="text" id="logSerie" class="cyber-input locked-node" readonly>
                    </div>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">EQUIPO / DISPOSITIVO:</label>
                    <input type="text" id="logHardware" class="cyber-input locked-node" readonly>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">SÍNTOMA O FALLA DETECTADA:</label>
                    <textarea id="logFallaReportada" class="cyber-input locked-node" rows="2" style="resize:none;"
                        readonly></textarea>
                </div>

                <hr class="cyber-hr">

                <!-- FORMULARIO LOGÍSTICO MUTABLE -->
                <div class="cyber-form-group">
                    <label class="node-label text-yellow">// PROVEEDOR / SERVICE OFICIAL DESTINO:</label>
                    <select name="id_proveedor" id="selectProveedorLog" class="cyber-input" required>
                        <option value="">[SELECCIONE SERVICE AUTORIZADO]</option>
                    </select>
                </div>

                <div class="cyber-form-group-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="cyber-form-group">
                        <label class="node-label text-yellow">// FECHA DE ENVÍO LOGÍSTICO:</label>
                        <input type="date" name="fecha_envio_proveedor" id="logFechaEnvio" class="cyber-input" required>
                    </div>
                    <div class="cyber-form-group">
                        <label class="node-label text-yellow">// REFERENCIA / N° TICKET PROVEEDOR:</label>
                        <input type="text" name="referencia_proveedor" id="logReferencia" class="cyber-input"
                            placeholder="Ej: REMITO-8902 / TK-4451" required>
                    </div>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label text-yellow">// NUEVO ESTADO DEL PROCESO EXTERNO:</label>
                    <select name="id_estado_actual" id="selectEstadoLog" class="cyber-input" required>
                        <option value="">[SELECCIONE TRANSICIÓN DE ESTADO]</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer cyber-modal-footer">
                <button type="button" class="btn-cyber-cancel" id="btnCancelarModal">[CANCEL_DISPATCH]</button>
                <button type="submit" id="btnGuardarLogistica"
                    class="node-submit-btn text-neon-blue">[COMMIT_LOGISTIC_DISPATCH_DATA]</button>
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
        --neon-yellow-dark: #d97706;
    }

    .hidden {
        display: none !important;
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
        border: 1px solid var(--neon-yellow-dark);
        color: var(--neon-yellow-dark);
        background: rgba(217, 119, 6, 0.08);
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

    .border-neon-cyan {
        border-left: 4px solid var(--neon-cyan-dark);
    }

    .border-neon-blue {
        border-left: 4px solid var(--neon-cyan-glow);
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
        border-color: var(--neon-cyan-dark);
        background: #ffffff;
        outline: none;
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

    .cyan-accent {
        color: var(--neon-cyan-dark);
        font-weight: bold;
    }

    .t-cyan {
        color: var(--neon-cyan-dark);
        font-weight: bold;
    }

    .text-yellow {
        color: var(--neon-yellow-dark);
        font-weight: bold;
    }

    .btn-terminal-edit {
        background: transparent;
        border: 1px solid var(--border-cyber-subtle);
        color: var(--text-cyber-muted);
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.75rem;
        font-family: 'Share Tech Mono', monospace;
        transition: all 0.2s;
    }

    .btn-terminal-edit:hover {
        color: var(--neon-cyan-dark);
        border-color: var(--neon-cyan-dark);
        background: rgba(2, 132, 199, 0.08);
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
        border-color: var(--neon-cyan-dark);
        background: #ffffff;
        outline: none;
    }

    .locked-node {
        color: var(--text-cyber-muted);
        background: #e2e8f0;
        cursor: not-allowed;
    }

    .cyber-hr {
        border: 0;
        border-top: 1px solid var(--border-cyber-subtle);
        margin: 15px 0;
    }

    .cyber-modal-footer {
        border-top: 1px solid var(--border-cyber-subtle);
        padding: 12px 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background: #f8fafc;
    }

    .node-submit-btn {
        background: rgba(2, 132, 199, 0.1);
        border: 1px solid var(--neon-cyan-dark);
        color: var(--neon-cyan-dark);
        padding: 8px 16px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .node-submit-btn:hover {
        background: var(--neon-cyan-dark);
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

    .text-center {
        text-align: center;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }
</style>

<script src="vistas/js/rma-core/proveedoresRma.js"></script>