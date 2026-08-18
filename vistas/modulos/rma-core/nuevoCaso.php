<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id_tecnico_sesion = $_SESSION['id'] ?? '';
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">⚡</span>
            <h2>[02] RMA_CORE // INYECTAR NUEVO REGISTRO OPERACIONAL</h2>
            <span class="system-badge-live">SEC_CORE_v1.0</span>
        </div>
    </header>

    <section class="cyber-content mt-4">
        <form class="font-mono" method="POST" id="formNuevoCaso" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="id_tecnico" value="<?php echo $id_tecnico_sesion; ?>">

            <div class="cyber-grid-two-equal">

                <!-- VECTOR 01: VÍNCULO DE ENTIDAD Y MATRIZ -->
                <div class="cyber-panel-card glass-panel-neon border-neon-purple">
                    <div class="panel-cyber-header">
                        <h4><span class="purple-accent">//</span> VECTOR 01: VÍNCULO DE ENTIDAD Y MATRIZ</h4>
                    </div>
                    <div class="panel-cyber-body mt-3">

                        <div class="cyber-form-group">
                            <label class="node-label">ASOCIAR CLIENTE (BUSCADOR):</label>
                            <select name="id_cliente" id="selectCliente" class="cyber-input" required>
                                <option value="">[SELECCIONE UN NODO CLIENTE]</option>
                            </select>
                        </div>

                        <div class="cyber-form-group">
                            <label class="node-label">TIPO DE CASO / PROTOCOLO:</label>
                            <select name="id_tipo_caso" id="selectTipoCaso" class="cyber-input" required>
                                <option value="">[SELECCIONE CLASIFICACIÓN]</option>
                            </select>
                        </div>

                        <div class="cyber-form-group">
                            <label class="node-label text-yellow">// NÚMERO DE CASO PRE-ASIGNADO (AUTO):</label>
                            <input type="text" name="numero_caso" id="numeroCasoAuto" class="cyber-input locked-node"
                                placeholder="EVALUANDO CÓDIGO SECUENCIAL..." readonly>
                        </div>

                    </div>
                </div>

                <!-- VECTOR 02: ESPECIFICACIONES DE COMPONENTE -->
                <div class="cyber-panel-card glass-panel-neon border-neon-blue">
                    <div class="panel-cyber-header">
                        <h4><span class="cyan-accent">//</span> VECTOR 02: ESPECIFICACIONES DE COMPONENTE</h4>
                    </div>
                    <div class="panel-cyber-body mt-3">

                        <div class="cyber-form-group">
                            <label class="node-label">EQUIPO / DISPOSITIVO:</label>
                            <input type="text" name="equipo" class="cyber-input"
                                placeholder="EJ: NOTEBOOK, MOTHERBOARD, GPU" required>
                        </div>

                        <div class="cyber-form-group-row"
                            style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="cyber-form-group">
                                <label class="node-label">MARCA:</label>
                                <input type="text" name="marca" class="cyber-input" placeholder="EJ: ASUS, GIGABYTE"
                                    required>
                            </div>
                            <div class="cyber-form-group">
                                <label class="node-label">MODELO:</label>
                                <input type="text" name="modelo" class="cyber-input" placeholder="EJ: ROG STRIX Z790"
                                    required>
                            </div>
                        </div>

                        <div class="cyber-form-group">
                            <label class="node-label">NÚMERO DE SERIE (S/N):</label>
                            <input type="text" name="numero_serie" class="cyber-input" placeholder="EJ: SN98234710293"
                                required>
                        </div>

                    </div>
                </div>

                <!-- VECTOR 03: TELEMETRÍA DE FALLA Y LOGS PRIMARIOS -->
                <div class="cyber-panel-card glass-panel-neon border-neon-green table-span-2">
                    <div class="panel-cyber-header">
                        <h4><span class="green-accent">//</span> VECTOR 03: TELEMETRÍA DE FALLA Y LOGS PRIMARIOS</h4>
                    </div>
                    <div class="panel-cyber-body mt-3">

                        <div class="cyber-form-group">
                            <label class="node-label">DESCRIPCIÓN DETALLADA DEL PROBLEMA:</label>
                            <textarea name="descripcion_problema" class="cyber-input cyber-textarea" rows="4"
                                placeholder="LOGS SINTOMÁTICOS: El dispositivo no genera video / Bloqueos intermitentes en test de carga..."
                                required></textarea>
                        </div>

                        <div class="cyber-form-group">
                            <label class="node-label text-yellow">// ADJUNTAR EVIDENCIA VISUAL PRIMARIA
                                (OPCIONAL):</label>
                            <input type="file" name="foto_archivo" id="fotoArchivo" class="cyber-input-file">
                            <label for="fotoArchivo" class="cyber-file-trigger">[UPLOAD_EVIDENCE_STREAM]</label>
                            <span id="fileNameDisplay" class="t-cyan font-mono mt-1"
                                style="display:block; font-size:0.8rem;"></span>
                        </div>

                        <div class="mt-4">
                            <button type="submit" id="btnGuardarCaso" class="node-submit-btn text-neon-yellow">
                                [INITIALIZE_RMA_DEPLOYMENT]
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </section>
</div>

<!-- ==========================================
     MODAL EMERGENTE NATIVO: CONFIRMACIÓN Y TICKET DE CASO DESPLEGADO
========================================== -->
<div class="custom-cyber-modal-overlay" id="modalCasoOverlay" style="display: none;">
    <div class="custom-cyber-modal-container border-neon-blue">

        <div class="cyber-modal-header">
            <button type="button" class="text-cyber-close" id="btnCerrarModalX">&times;</button>
            <h4 class="modal-title font-mono">
                <span class="cyan-accent">//</span> [NODO_DESPLEGADO: <span id="lblNumeroCaso">RMA-0000</span>]
            </h4>
        </div>

        <div class="modal-body font-mono text-center">
            <p class="cyan-accent mb-2">// TRANSMISIÓN Y PERSISTENCIA EXITOSA //</p>

            <!-- TICKET ETIQUETA CONTRASTADA -->
            <div id="ticket-impresion" class="ticket-etiqueta">
                <h3 class="ticket-title" id="ticketNumeroCaso">RMA-0000</h3>
                <img id="ticketQrImg" src="" class="ticket-qr" alt="QR" />
                <br>
                <img id="ticketBarcodeImg" src="" class="ticket-barcode" alt="BARCODE" />
            </div>

            <small class="node-label mt-2">Adhiera la etiqueta física al hardware o abra el comprobante digital.</small>
        </div>

        <div class="modal-footer cyber-modal-footer">
            <button type="button" class="btn-cyber-add" id="btnImprimirEtiqueta">
                <i class="fa fa-print"></i> [PRINT_LABEL]
            </button>
            <button type="button" class="node-submit-btn text-neon-blue" id="btnVerComprobantePop">
                <i class="fa fa-file-pdf-o"></i> [VIEW_RECEIPT]
            </button>
        </div>

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

    .cyber-grid-two-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .table-span-2 {
        grid-column: span 2;
    }

    @media(max-width: 992px) {
        .cyber-grid-two-equal {
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

    .border-neon-purple {
        border-left: 4px solid var(--neon-purple-dark);
    }

    .border-neon-blue {
        border-left: 4px solid var(--neon-cyan-dark);
    }

    .border-neon-green {
        border-left: 4px solid var(--neon-green-dark);
    }

    .purple-accent {
        color: var(--neon-purple-dark);
        font-weight: bold;
        margin-right: 5px;
    }

    .cyan-accent {
        color: var(--neon-cyan-dark);
        font-weight: bold;
        margin-right: 5px;
    }

    .green-accent {
        color: var(--neon-green-dark);
        font-weight: bold;
        margin-right: 5px;
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

    .text-yellow {
        color: var(--neon-yellow-dark);
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

    .cyber-textarea {
        resize: vertical;
        font-family: 'Share Tech Mono', monospace;
    }

    select.cyber-input option {
        background: #ffffff;
        color: var(--text-cyber-dark);
    }

    .locked-node {
        color: var(--neon-yellow-dark);
        background: #fef3c7;
        border-color: var(--border-cyber-subtle);
        font-weight: bold;
    }

    .cyber-input-file {
        display: none;
    }

    .cyber-file-trigger {
        display: block;
        width: 100%;
        padding: 10px;
        background: rgba(2, 132, 199, 0.05);
        border: 1px dashed var(--neon-cyan-dark);
        color: var(--neon-cyan-dark);
        text-align: center;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        box-sizing: border-box;
        font-weight: bold;
    }

    .cyber-file-trigger:hover {
        background: rgba(2, 132, 199, 0.15);
        box-shadow: 0 0 8px rgba(2, 132, 199, 0.3);
    }

    .node-submit-btn {
        background: rgba(217, 119, 6, 0.1);
        border: 1px solid var(--neon-yellow-dark);
        color: var(--neon-yellow-dark);
        width: 100%;
        padding: 12px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 4px;
    }

    .node-submit-btn:hover {
        background: var(--neon-yellow-dark);
        color: #ffffff;
        box-shadow: 0 0 10px rgba(217, 119, 6, 0.3);
    }

    .btn-cyber-add {
        background: rgba(2, 132, 199, 0.1);
        border: 1px solid var(--neon-cyan-dark);
        color: var(--neon-cyan-dark);
        padding: 8px 16px;
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
    }

    .t-cyan {
        color: var(--neon-cyan-dark);
        font-weight: bold;
    }

    .mt-1 {
        margin-top: 0.25rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
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
        max-width: 480px;
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

    .cyber-modal-footer {
        border-top: 1px solid var(--border-cyber-subtle);
        padding: 12px 20px;
        display: flex;
        justify-content: center;
        gap: 10px;
        background: #f8fafc;
    }

    /* ETIQUETA IMPRESIÓN */
    .ticket-etiqueta {
        background: #f8fafc;
        padding: 15px;
        border-radius: 6px;
        max-width: 220px;
        margin: 10px auto;
        color: #0f172a;
        border: 2px solid var(--neon-cyan-dark);
    }

    .ticket-title {
        font-size: 1.1rem;
        margin: 0 0 8px 0;
        font-weight: bold;
        color: #0f172a;
        letter-spacing: 1px;
    }

    .ticket-qr {
        width: 140px;
        height: 140px;
        margin-bottom: 5px;
    }

    .ticket-barcode {
        width: 180px;
        height: 45px;
        object-fit: contain;
    }
</style>

<script>
    document.getElementById('fotoArchivo')?.addEventListener('change', function (e) {
        const display = document.getElementById('fileNameDisplay');
        if (display) display.textContent = e.target.files[0] ? `[READY]: ${e.target.files[0].name}` : '';
    });
</script>
<script src="vistas/js/rma-core/nuevoCaso.js"></script>