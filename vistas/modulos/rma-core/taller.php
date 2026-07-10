<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id_usuario_tecnico = $_SESSION['id'] ?? '';
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">🔬</span>
            <h2>[02] RMA_CORE // DIAGNÓSTICO DE LABORATORIO (TALLER)</h2>
            <span class="system-badge-live">LAB_STATUS_OK</span>
        </div>
    </header>

    <section class="cyber-content mt-4">
        <div class="cyber-grid-two-columns">

            <div class="cyber-panel-card glass-panel-neon border-neon-blue"
                style="display: flex; flex-direction: column; gap: 15px;">
                <div class="panel-cyber-header"
                    style="display:flex; justify-content:space-between; align-items:center;">
                    <h4><span class="t-cyan">//</span> COLA DE DISPOSITIVOS POR EVALUAR</h4>
                    <span id="contadorCola" class="system-badge-live" style="color:#00f2ff; border-color:#00f2ff;">WAIT:
                        0</span>
                </div>

                <div class="cyber-filter-subpanel font-mono">
                    <div class="filter-row-input">
                        <input type="text" id="buscadorTallerCola" class="cyber-input input-mini"
                            placeholder="[ BUSCAR CASO, SERIE O HARDWARE... ]">
                    </div>
                    <div class="filter-row-controls">
                        <select id="filtroEstadoTaller" class="cyber-input input-mini">
                            <option value="activos">[ COMPONENTES ACTIVOS ]</option>
                            <option value="todos">[ MOSTRAR LA MATRIZ HISTÓRICA ]</option>
                        </select>
                        <select id="ordenTallerCola" class="cyber-input input-mini">
                            <option value="asc">[ ORDEN: CRONOLÓGICO ASCENDENTE ]</option>
                            <option value="desc">[ ORDEN: RECIENTES PRIMERO ]</option>
                        </select>
                    </div>
                </div>

                <div class="panel-cyber-body font-mono" style="max-height: 400px; overflow-y: auto;">
                    <table class="cyber-mini-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>N° CASO</th>
                                <th>DISPOSITIVO / S/N</th>
                                <th>ESTADO</th>
                                <th style="text-align: center;">ACCION</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyTallerCola">
                            <tr>
                                <td colspan="4" style="text-align: center; color: #506690;">
                                    [STREAMING_LAB_QUEUE...]
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="cyber-panel-card glass-panel-neon border-neon-purple">
                <div class="panel-cyber-header">
                    <h4><span class="purple-accent">//</span> CONSOLA DE REPARACIÓN Y TELEMETRÍA</h4>
                </div>
                <div class="panel-cyber-body mt-3 font-mono">

                    <form id="formDiagnostico" method="POST" enctype="multipart/form-data" class="cyber-disabled-form">
                        <input type="hidden" name="id_caso" id="diagIdCaso">
                        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario_tecnico; ?>">

                        <div class="cyber-form-group">
                            <label class="node-label">CASO SELECCIONADO:</label>
                            <input type="text" id="diagNumeroCaso" class="cyber-input locked-node"
                                placeholder="[ SELECCIONE UN HARDWARE DE LA COLA ]" readonly>
                        </div>

                        <div class="cyber-form-group-row"
                            style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="cyber-form-group">
                                <label class="node-label">COMPONENTE / MARCA:</label>
                                <input type="text" id="diagHardware" class="cyber-input locked-node" readonly>
                            </div>
                            <div class="cyber-form-group">
                                <label class="node-label">NÚMERO DE SERIE (S/N):</label>
                                <input type="text" id="diagSerie" class="cyber-input locked-node" readonly>
                            </div>
                        </div>

                        <div class="cyber-form-group">
                            <label class="node-label">PROBLEMA REPORTADO EN INGRESO:</label>
                            <textarea id="diagProblemaOriginal" class="cyber-input locked-node" rows="2"
                                style="resize:none;" readonly></textarea>
                        </div>

                        <hr class="cyber-hr">

                        <div class="cyber-form-group">
                            <label class="node-label text-yellow">// DIAGNÓSTICO FINAL / ACCIONES REALIZADAS:</label>
                            <textarea name="diagnostico_final" id="diagDiagnosticoFinal" class="cyber-input" rows="4"
                                placeholder="Describa la falla localizada y los componentes sustituidos..."
                                required></textarea>
                        </div>

                        <div class="cyber-form-group-row"
                            style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; align-items: end;">
                            <div class="cyber-form-group">
                                <label class="node-label text-yellow">// ACTUALIZAR ESTADO DE LA MATRIZ:</label>
                                <select name="id_estado_actual" id="selectEstadoDiag" class="cyber-input" required>
                                    <option value="">[SELECCIONE NUEVO ESTADO]</option>
                                </select>
                            </div>
                            <div class="cyber-form-group">
                                <label class="node-label text-yellow">// ADJUNTAR ARCHIVO / EVIDENCIA:</label>
                                <input type="file" name="foto_archivo" id="fotoDiag" class="cyber-input-file">
                                <label for="fotoDiag" class="cyber-file-trigger">[UPDATE_IMG_STREAM]</label>
                                <span id="fileNameDiagDisplay" class="t-cyan"
                                    style="display:block; font-size:0.75rem; margin-top:4px;"></span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" id="btnGuardarDiag" class="node-submit-btn text-neon-green" disabled>
                                [COMMIT_DIAGNOSTIC_DATA]
                            </button>
                        </div>
                    </form>

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

    .border-neon-blue {
        border-left: 3px solid #00f2ff;
    }

    .border-neon-purple {
        border-left: 3px solid #9d4edd;
    }

    .t-cyan {
        color: #00f2ff;
        margin-right: 5px;
    }

    .purple-accent {
        color: #9d4edd;
        margin-right: 5px;
    }

    .text-yellow {
        color: #ffca28;
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
        border-color: #9d4edd;
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
        grid-template-columns: 1fr 1fr;
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
        background: rgba(0, 242, 255, 0.02);
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

    .cyber-input-file {
        display: none;
    }

    .cyber-file-trigger {
        display: block;
        width: 100%;
        padding: 10px;
        background: rgba(157, 78, 221, 0.05);
        border: 1px dashed #9d4edd;
        color: #9d4edd;
        text-align: center;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .cyber-file-trigger:hover {
        background: rgba(157, 78, 221, 0.15);
        box-shadow: 0 0 8px rgba(157, 78, 221, 0.3);
    }

    .node-submit-btn {
        background: rgba(0, 255, 102, 0.1);
        border: 1px solid #00ff66;
        color: #00ff66;
        width: 100%;
        padding: 12px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
    }

    .node-submit-btn:hover:not([disabled]) {
        background: rgba(0, 255, 102, 0.2);
        box-shadow: 0 0 10px rgba(0, 255, 102, 0.4);
    }

    .node-submit-btn:disabled {
        border-color: #506690;
        color: #506690;
        background: transparent;
        cursor: not-allowed;
    }
</style>

<script>
    document.getElementById('fotoDiag')?.addEventListener('change', function (e) {
        const d = document.getElementById('fileNameDiagDisplay');
        if (d) d.textContent = e.target.files[0] ? `[BUFF_READY]: ${e.target.files[0].name}` : '';
    });
</script>
<script src="vistas/js/rma-core/taller.js"></script>