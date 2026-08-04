<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">📡</span>
            <h2>[03] COMMS_NODE // DIRECTORIO DE CLIENTES Y TRAZABILIDAD</h2>
            <span class="system-badge-live">DB_CLIENT_TRAILS</span>
        </div>
    </header>

    <section class="cyber-content mt-4">
        <div class="cyber-grid-three-columns">

            <div class="cyber-panel-card glass-panel-neon border-neon-green">
                <div class="panel-cyber-header">
                    <h4><span class="green-accent">//</span> REGISTRAR / EDITAR CLIENTE</h4>
                </div>
                <form id="formCliente" class="panel-cyber-body-stats font-mono mt-3" autocomplete="off" method="POST"
                    onsubmit="return false;">
                    <input type="hidden" name="id" id="cliente_id">

                    <div class="cyber-form-group">
                        <label class="node-label">NOMBRE Y APELLIDO:</label>
                        <input type="text" name="nombre" id="cliente_nombre" class="cyber-input"
                            placeholder="ej: Carlos Benítez" required>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">CÉDULA DE IDENTIDAD / RUC:</label>
                        <input type="text" name="cedula" id="cliente_cedula" class="cyber-input"
                            placeholder="ej: 4589201" required>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">CELULAR / WHATSAPP:</label>
                        <input type="text" name="celular" id="cliente_celular" class="cyber-input"
                            placeholder="ej: 0981123456" required>
                    </div>

                    <button type="submit" id="btnGuardarCliente" class="node-submit-btn text-neon-green">
                        [INJECT_CLIENT_NODE]
                    </button>
                    <button type="button" id="btnCancelarEdicion" class="node-cancel-btn hidden mt-2">
                        [CANCEL_EDIT]
                    </button>
                </form>
            </div>

            <div class="cyber-panel-card glass-panel-neon border-neon-cyan table-span-2">
                <div class="panel-cyber-header flex-header">
                    <h4><span class="cyan-accent">//</span> REGISTROS EN TABLA: `clientes`</h4>
                    <div class="cyber-search-box">
                        <input type="text" id="buscarCliente" class="cyber-input-search"
                            placeholder="🔍 Buscar por nombre o cédula...">
                    </div>
                </div>
                <div class="panel-cyber-body-table mt-3">
                    <table class="cyber-mini-table" id="tablaClientes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cédula</th>
                                <th>Nombre Completo</th>
                                <th>Celular</th>
                                <th>Fecha Alta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="cyber-panel-card glass-panel-neon border-neon-purple mt-4" id="panelHistorialCliente"
            style="display: none;">
            <div class="panel-cyber-header flex-header">
                <h4>
                    <span class="purple-accent">//</span> HISTORIAL DE EQUIPOS TRAÍDOS (RMA) —
                    <span id="labelClienteNombre" class="text-white font-mono">SELECCIONE UN CLIENTE</span>
                </h4>
                <span class="badge-status badge-ready font-mono" id="labelClienteCedula">CED: -</span>
            </div>

            <div class="panel-cyber-body-table mt-3">
                <table class="cyber-mini-table" id="tablaHistorialRma">
                    <thead>
                        <tr>
                            <th>N° Caso</th>
                            <th>Equipo / Marca / Modelo</th>
                            <th>N° Serie</th>
                            <th>Tipo RMA</th>
                            <th>Estado Actual</th>
                            <th>Fecha Ingreso</th>
                            <th>Fecha Cierre</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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

    .header-brand-glitch {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-brand-glitch h2 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
        letter-spacing: 0.5px;
    }

    .system-badge-live {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        border: 1px solid #00ff66;
        color: #00ff66;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .cyber-grid-three-columns {
        display: grid;
        grid-template-columns: 1fr 2fr;
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
        background: rgba(10, 16, 32, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 20px;
    }

    .border-neon-green {
        border-left: 3px solid #00ff66;
    }

    .border-neon-cyan {
        border-left: 3px solid #00b4d8;
    }

    .border-neon-purple {
        border-left: 3px solid #9d4edd;
    }

    .panel-cyber-header h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }

    .flex-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .green-accent {
        color: #00ff66;
        margin-right: 5px;
    }

    .cyan-accent {
        color: #00f2ff;
        margin-right: 5px;
    }

    .purple-accent {
        color: #9d4edd;
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

    .cyber-input {
        width: 100%;
        background: #03050c;
        border: 1px solid #101c38;
        color: #fff;
        padding: 8px 12px;
        font-family: monospace;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .cyber-input:focus {
        border-color: #00ff66;
        outline: none;
    }

    .cyber-input-search {
        background: #03050c;
        border: 1px solid #101c38;
        color: #00f2ff;
        padding: 6px 12px;
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.8rem;
        border-radius: 4px;
        width: 240px;
    }

    .node-submit-btn {
        background: rgba(0, 255, 102, 0.1);
        border: 1px solid #00ff66;
        color: #00ff66;
        width: 100%;
        padding: 10px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 10px;
    }

    .node-submit-btn:hover {
        background: rgba(0, 255, 102, 0.2);
        box-shadow: 0 0 10px rgba(0, 255, 102, 0.4);
    }

    .node-cancel-btn {
        background: rgba(255, 51, 51, 0.1);
        border: 1px solid #ff3333;
        color: #ff3333;
        width: 100%;
        padding: 8px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
    }

    .hidden {
        display: none;
    }

    .panel-cyber-body-table {
        overflow-x: auto;
    }

    .cyber-mini-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .cyber-mini-table th {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.75rem;
        color: #516995;
        padding: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        text-transform: uppercase;
        text-align: center;
    }

    .cyber-mini-table td {
        padding: 10px 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        color: #cbd5e1;
        text-align: center;
    }

    .t-cyan {
        color: #00b4d8;
    }

    .text-white {
        color: #fff;
    }

    .badge-status {
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 600;
    }

    .badge-ready {
        background: rgba(0, 255, 102, 0.1);
        color: #00ff66;
        border: 1px solid rgba(0, 255, 102, 0.2);
    }

    .badge-repair {
        background: rgba(255, 202, 40, 0.1);
        color: #ffca28;
        border: 1px solid rgba(255, 202, 40, 0.2);
    }

    .badge-diag {
        background: rgba(0, 242, 255, 0.1);
        color: #00f2ff;
        border: 1px solid rgba(0, 242, 255, 0.2);
    }

    .btn-terminal-view,
    .btn-terminal-edit,
    .btn-terminal-delete {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #a2b4cd;
        padding: 4px 8px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.75rem;
        margin: 0 2px;
    }

    .btn-terminal-view:hover {
        color: #9d4edd;
        border-color: #9d4edd;
        box-shadow: 0 0 5px rgba(157, 78, 221, 0.4);
    }

    .btn-terminal-edit:hover {
        color: #00f2ff;
        border-color: #00f2ff;
    }

    .btn-terminal-delete:hover {
        color: #ff3333;
        border-color: #ff3333;
    }
</style>

<script src="vistas/js/clientes/clientes.js"></script>