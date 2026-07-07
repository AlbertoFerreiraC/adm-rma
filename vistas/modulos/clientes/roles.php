<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">⚡</span>
            <h2>[01] SYS_AUTH // CONTROL DE MATRIZ DE ROLES</h2>
            <span class="system-badge-live">SEC_MATRIX_v1</span>
        </div>
    </header>

    <section class="cyber-content mt-4">
        <div class="cyber-grid-three-columns">
            <div class="cyber-panel-card glass-panel-neon border-neon-purple">
                <div class="panel-cyber-header">
                    <h4><span class="purple-accent">//</span> DECLARAR NUEVO ROL</h4>
                </div>
                <form class="panel-cyber-body-stats font-mono mt-3" method="POST" id="formRol">
                    <input type="hidden" name="id" value="">

                    <div class="cyber-form-group">
                        <label class="node-label">NOMBRE DEL ROL:</label>
                        <input type="text" name="nombre" class="cyber-input" placeholder="EJ: SUPERVISOR" required>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">ESTADO DEL NODO:</label>
                        <select name="estado" class="cyber-input" required>
                            <option value="activo">1 - OPERATIVO (ACTIVO)</option>
                            <option value="inactivo">0 - CORRUPTO (INACTIVO)</option>
                        </select>
                    </div>

                    <button type="submit" class="node-submit-btn text-neon-purple">
                        [INJECT_NEW_ROLE]
                    </button>

                    <button type="button" id="btnCancelarEdicion" class="node-submit-btn text-neon-purple mt-2"
                        style="border-color: #506690; color: #506690; background: rgba(80,102,144,0.1); display:none;">
                        [ABORT_UPDATE]
                    </button>
                </form>
            </div>

            <div class="cyber-panel-card glass-panel-neon border-neon-blue table-span-2">
                <div class="panel-cyber-header">
                    <h4><span class="cyan-accent">//</span> ACCESOS ASOCIADOS A LA TABLA: `roles`</h4>
                </div>
                <div class="panel-cyber-body-table mt-3">
                    <table class="cyber-mini-table">
                        <thead>
                            <tr>
                                <th>ID_ROL</th>
                                <th>Nombre Rol</th>
                                <th>Estado</th>
                                <th style="text-align: center;">Acciones [ABM]</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
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
        border: 1px solid #9d4edd;
        color: #9d4edd;
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

    .border-neon-purple {
        border-left: 3px solid #9d4edd;
    }

    .border-neon-blue {
        border-left: 3px solid #00f2ff;
    }

    .purple-accent {
        color: #9d4edd;
        margin-right: 5px;
    }

    .cyan-accent {
        color: #00f2ff;
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
        border-radius: 4px;
        outline: none;
    }

    select.cyber-input option {
        background: #060913;
        color: #fff;
    }

    .node-submit-btn {
        background: rgba(157, 78, 221, 0.1);
        border: 1px solid #9d4edd;
        color: #9d4edd;
        width: 100%;
        padding: 10px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
    }

    .node-submit-btn:hover {
        background: rgba(157, 78, 221, 0.2);
        box-shadow: 0 0 10px rgba(157, 78, 221, 0.4);
    }

    .cyber-mini-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .cyber-mini-table th {
        font-family: 'Share Tech Mono', monospace;
        color: #516995;
        padding: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        text-transform: uppercase;
    }

    .cyber-mini-table td {
        padding: 10px 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        color: #cbd5e1;
    }

    .t-cyan {
        color: #00b4d8;
    }

    .neon-text-blue {
        color: #00f2ff;
    }

    .neon-text-purple {
        color: #9d4edd;
    }

    .badge-status {
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 600;
    }

    .badge-diag {
        background: rgba(0, 242, 255, 0.1);
        color: #00f2ff;
        border: 1px solid rgba(0, 242, 255, 0.2);
    }

    .badge-ready {
        background: rgba(255, 0, 85, 0.1);
        color: #ff0055;
        border: 1px solid rgba(255, 0, 85, 0.2);
    }

    .btn-cyber-action {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.75rem;
        padding: 4px 8px;
        border: 1px solid transparent;
        background: transparent;
        cursor: pointer;
        border-radius: 4px;
        transition: all 0.2s;
        margin: 0 3px;
    }

    .btn-edit {
        color: #00f2ff;
        border-color: rgba(0, 242, 255, 0.3);
        background: rgba(0, 242, 255, 0.05);
    }

    .btn-edit:hover {
        background: rgba(0, 242, 255, 0.2);
        box-shadow: 0 0 8px rgba(0, 242, 255, 0.4);
    }

    .btn-delete {
        color: #ff0055;
        border-color: rgba(255, 0, 85, 0.3);
        background: rgba(255, 0, 85, 0.05);
    }

    .btn-delete:hover {
        background: rgba(255, 0, 85, 0.2);
        box-shadow: 0 0 8px rgba(255, 0, 85, 0.4);
    }

    .mt-2 {
        margin-top: 0.5rem;
    }
</style>

<script src="vistas/js/clientes/roles.js"></script>