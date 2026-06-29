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
                <form class="panel-cyber-body-stats font-mono mt-3" method="POST">
                    <div class="cyber-form-group">
                        <label class="node-label">NOMBRE DEL ROL:</label>
                        <input type="text" class="cyber-input" placeholder="EJ: SUPERVISOR" required>
                    </div>
                    <button type="submit" class="node-submit-btn text-neon-purple">
                        [INJECT_NEW_ROLE]
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
                                <th>Nivel de Acceso</th>
                                <th>Estado del Nodo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="t-cyan font-mono">1</td>
                                <td class="neon-text-blue font-weight-bold">TÉCNICO</td>
                                <td class="font-mono">Módulo 02, Módulo 03 (Parcial)</td>
                                <td><span class="badge-status badge-diag">OPERATIVO</span></td>
                            </tr>
                            <tr>
                                <td class="t-cyan font-mono">2</td>
                                <td class="neon-text-purple font-weight-bold">ADMINISTRADOR</td>
                                <td class="font-mono">Acceso Root Completo [01-04]</td>
                                <td><span class="badge-status badge-ready">ROOT_ACTIVE</span></td>
                            </tr>
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
        background: rgba(0, 255, 102, 0.1);
        color: #00ff66;
        border: 1px solid rgba(0, 255, 102, 0.2);
    }
</style>