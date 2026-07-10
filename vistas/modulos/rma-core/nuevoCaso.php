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
        <form class="font-mono" method="POST" id="formNuevoCaso" enctype="multipart/form-data">
            <input type="hidden" name="id_tecnico" value="<?php echo $id_tecnico_sesion; ?>">

            <div class="cyber-grid-two-columns">

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
                            <select name="id_tipo_case" id="selectTipoCaso" class="cyber-input" required>
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
                            <button type="submit" class="node-submit-btn text-neon-yellow">
                                [INITIALIZE_RMA_DEPLOYMENT]
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </form>
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

    .cyber-grid-three-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .table-span-2 {
        grid-column: span 2;
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

    .border-neon-purple {
        border-left: 3px solid #9d4edd;
    }

    .border-neon-blue {
        border-left: 3px solid #00f2ff;
    }

    .border-neon-green {
        border-left: 3px solid #00ff66;
    }

    .purple-accent {
        color: #9d4edd;
        margin-right: 5px;
    }

    .cyan-accent {
        color: #00f2ff;
        margin-right: 5px;
    }

    .green-accent {
        color: #00ff66;
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

    .text-yellow {
        color: #ffca28;
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

    .cyber-textarea {
        resize: vertical;
        font-family: 'Share Tech Mono', monospace;
    }

    select.cyber-input option {
        background: #060913;
        color: #fff;
    }

    .locked-node {
        color: #ffca28;
        background: rgba(255, 202, 40, 0.03);
        border-color: rgba(255, 202, 40, 0.2);
    }

    .cyber-input-file {
        display: none;
    }

    .cyber-file-trigger {
        display: block;
        width: 100%;
        padding: 10px;
        background: rgba(0, 242, 255, 0.05);
        border: 1px dashed #00f2ff;
        color: #00f2ff;
        text-align: center;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .cyber-file-trigger:hover {
        background: rgba(0, 242, 255, 0.15);
        box-shadow: 0 0 8px rgba(0, 242, 255, 0.3);
    }

    .node-submit-btn {
        background: rgba(255, 202, 40, 0.1);
        border: 1px solid #ffca28;
        color: #ffca28;
        width: 100%;
        padding: 12px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
    }

    .node-submit-btn:hover {
        background: rgba(255, 202, 40, 0.2);
        box-shadow: 0 0 10px rgba(255, 202, 40, 0.4);
    }

    .mt-1 {
        margin-top: 0.25rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .ticket-etiqueta {
        background: #fff;
        padding: 15px;
        border-radius: 4px;
        max-width: 220px;
        margin: 15px auto;
        color: #000;
        border: 2px solid #ffca28;
    }

    .ticket-title {
        font-size: 1.1rem;
        margin: 0 0 8px 0;
        font-weight: bold;
        color: #000;
        letter-spacing: 1px;
    }

    .ticket-qr {
        width: 140px;
        height: 140px;
        margin-bottom: 5px;
    }

    .ticket-barcode {
        width: 180px;
        height: 50px;
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