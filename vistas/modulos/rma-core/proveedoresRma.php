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
            <span class="system-badge-live" style="color: #ff7b00; border-color: #ff7b00;">EXTERNAL_STREAM_ACTIVE</span>
        </div>
    </header>

    <section class="cyber-content mt-4">
        <div class="cyber-grid-two-columns">

            <div class="cyber-panel-card glass-panel-neon border-neon-orange"
                style="display: flex; flex-direction: column; gap: 15px;">
                <div class="panel-cyber-header"
                    style="display:flex; justify-content:space-between; align-items:center;">
                    <h4><span class="t-orange">//</span> MONITOREO DE CASOS EN PROCESAMIENTO</h4>
                    <span id="contadorLogistica" class="system-badge-live"
                        style="color:#ff7b00; border-color:#ff7b00;">TOTAL: 0</span>
                </div>

                <div class="cyber-filter-subpanel font-mono">
                    <div class="filter-row-input">
                        <input type="text" id="buscadorLogistica" class="cyber-input input-mini"
                            placeholder="[ ESCANEE O BUSQUE CASO, CLIENTE O SERIE... ]">
                    </div>
                    <div class="filter-row-controls">
                        <select id="filtroLogisticaAlcance" class="cyber-input input-mini">
                            <option value="todos">[ MOSTRAR MATRIZ COMPLETA ]</option>
                            <option value="en_taller">[ EN TALLER / RECIÉN INGRESADOS ]</option>
                            <option value="en_proveedor">[ EN SERVICE OFICIAL / ENVIADOS ]</option>
                        </select>
                    </div>
                </div>

                <div class="panel-cyber-body font-mono" style="max-height: 420px; overflow-y: auto;">
                    <table class="cyber-mini-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>N° CASO</th>
                                <th>DISPOSITIVO / MARCA</th>
                                <th>SERVICE ASIGNADO</th>
                                <th style="text-align: center;">ACCION</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyLogistica">
                            <tr>
                                <td colspan="4" style="text-align: center; color: #506690;">
                                    [STREAMING_EXTERNAL_FLOW_DATA...]
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="cyber-panel-card glass-panel-neon border-neon-purple">
                <div class="panel-cyber-header">
                    <h4><span class="purple-accent">//</span> CONSOLA DE DESPACHO Y ASIGNACIÓN DE GARANTÍA</h4>
                </div>
                <div class="panel-cyber-body mt-3 font-mono">

                    <form id="formFlujoExterno" method="POST" class="cyber-disabled-form">
                        <input type="hidden" name="id_caso" id="logIdCaso">
                        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario_logistica; ?>">

                        <div class="cyber-form-group">
                            <label class="node-label">NODO CORRELATIVO SELECCIONADO:</label>
                            <input type="text" id="logNumeroCaso" class="cyber-input locked-node" readonly>
                        </div>

                        <div class="cyber-form-group-row"
                            style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="cyber-form-group">
                                <label class="node-label">EQUIPO / DISPOSITIVO:</label>
                                <input type="text" id="logHardware" class="cyber-input locked-node" readonly>
                            </div>
                            <div class="cyber-form-group">
                                <label class="node-label">NÚMERO DE SERIE (S/N):</label>
                                <input type="text" id="logSerie" class="cyber-input locked-node" readonly>
                            </div>
                        </div>

                        <div class="cyber-form-group">
                            <label class="node-label">SÍNTOMA O FALLA DETECTADA:</label>
                            <textarea id="logFallaReportada" class="cyber-input locked-node" rows="2"
                                style="resize:none;" readonly></textarea>
                        </div>

                        <hr class="cyber-hr">

                        <!-- FORMULARIO LOGÍSTICO MUTABLE -->
                        <div class="cyber-form-group">
                            <label class="node-label text-orange">// PROVEEDOR / SERVICE OFICIAL DESTINO:</label>
                            <select name="id_proveedor" id="selectProveedorLog" class="cyber-input" required>
                                <option value="">[SELECCIONE SERVICE AUTORIZADO]</option>
                            </select>
                        </div>

                        <div class="cyber-form-group-row"
                            style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="cyber-form-group">
                                <label class="node-label text-orange">// FECHA DE ENVÍO LOGÍSTICO:</label>
                                <input type="date" name="fecha_envio_proveedor" id="logFechaEnvio" class="cyber-input"
                                    required>
                            </div>
                            <div class="cyber-form-group">
                                <label class="node-label text-orange">// REFERENCIA / N° TICKET PROVEEDOR:</label>
                                <input type="text" name="referencia_proveedor" id="logReferencia" class="cyber-input"
                                    placeholder="Ej: REMITO-8902 / TK-4451" required>
                            </div>
                        </div>

                        <div class="cyber-form-group">
                            <label class="node-label text-orange">// NUEVO ESTADO DEL PROCESO EXTERNO:</label>
                            <select name="id_estado_actual" id="selectEstadoLog" class="cyber-input" required>
                                <option value="">[SELECCIONE TRANSICIÓN DE ESTADO]</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <button type="submit" id="btnGuardarLogistica" class="node-submit-btn btn-orange-action"
                                disabled>
                                [COMMIT_LOGISTIC_DISPATCH_DATA]
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </section>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght=500;600;700&display=swap');

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

    .cyber-grid-two-columns {
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        gap: 20px;
    }

    @media(max-width: 992px) {
        .cyber-grid-two-columns {
            grid-template-columns: 1fr;
        }
    }

    .cyber-panel-card {
        background: rgba(10, 16, 32, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 20px;
    }

    .border-neon-orange {
        border-left: 3px solid #ff7b00;
    }

    .border-neon-purple {
        border-left: 3px solid #9d4edd;
    }

    .t-orange {
        color: #ff7b00;
        margin-right: 5px;
    }

    .purple-accent {
        color: #9d4edd;
        margin-right: 5px;
    }

    .text-orange {
        color: #ff7b00;
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
        padding: 10px 12px;
        border-radius: 4px;
        box-sizing: border-box;
        outline: none;
    }

    .cyber-input:focus {
        border-color: #ff7b00;
    }

    select.cyber-input option {
        background: #060913;
        color: #fff;
    }

    .cyber-filter-subpanel {
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: rgba(13, 20, 36, 0.5);
        padding: 12px;
        border: 1px solid #101c38;
        border-radius: 4px;
    }

    .filter-row-controls {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .input-mini {
        padding: 6px 10px !important;
        font-size: 0.8rem;
        border-color: #162447;
    }

    .locked-node {
        color: #506690;
        background: rgba(255, 255, 255, 0.01);
        border-color: #0d162d;
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
        background: rgba(255, 123, 0, 0.02);
        cursor: pointer;
    }

    .cyber-hr {
        border: 0;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        margin: 20px 0;
    }

    .cyber-disabled-form {
        opacity: 0.4;
        pointer-events: none;
        transition: opacity 0.3s;
    }

    .cyber-active-form {
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    .node-submit-btn {
        background: rgba(255, 123, 0, 0.05);
        border: 1px solid #ff7b00;
        color: #ff7b00;
        width: 100%;
        padding: 12px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
    }

    .node-submit-btn:hover:not([disabled]) {
        background: rgba(255, 123, 0, 0.15);
        box-shadow: 0 0 10px rgba(255, 123, 0, 0.4);
        color: #fff;
    }

    .node-submit-btn:disabled {
        border-color: #506690;
        color: #506690;
        background: transparent;
        cursor: not-allowed;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }
</style>

<script src="vistas/js/rma-core/proveedoresRma.js"></script>