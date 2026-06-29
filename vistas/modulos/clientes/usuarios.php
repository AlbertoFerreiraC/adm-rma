<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">⚡</span>
            <h2>[01] SYS_AUTH // GESTIÓN DE OPERADORES Y USUARIOS</h2>
            <span class="system-badge-live">DB_SEC_USERS</span>
        </div>
    </header>

    <section class="cyber-content mt-4">
        <div class="cyber-grid-three-columns">
            <div class="cyber-panel-card glass-panel-neon border-neon-blue">
                <div class="panel-cyber-header">
                    <h4><span class="cyan-accent">//</span> REGISTRAR / EDITAR NODO_USUARIO</h4>
                </div>
                <form id="formUsuario" class="panel-cyber-body-stats font-mono mt-3" autocomplete="off">
                    <input type="hidden" name="id" id="idUsuario">
                    <div class="cyber-form-group">
                        <label class="node-label">ID OPERADOR (ALIAS):</label>
                        <input type="text" name="usuario" class="cyber-input" placeholder="ej: jsilva" required>
                    </div>
                    <div class="cyber-form-group">
                        <label class="node-label">NOMBRE COMPLETO:</label>
                        <input type="text" name="nombre" class="cyber-input" placeholder="Juan Silva" required>
                    </div>
                    <div class="cyber-form-group">
                        <label class="node-label">EMAIL PRINCIPAL:</label>
                        <input type="email" name="email" class="cyber-input" placeholder="jsilva@microexpress.com"
                            required>
                    </div>
                    <div class="cyber-form-group">
                        <label class="node-label">ACCESS_TOKEN (CONTRASEÑA):</label>
                        <input type="password" name="contrasena" class="cyber-input" placeholder="********" required>
                    </div>
                    <div class="cyber-form-group">
                        <label class="node-label">ROL ASIGNADO:</label>
                        <select name="id_rol" class="cyber-input">
                            <option value="1">Técnico</option>
                            <option value="2">Administrador</option>
                        </select>
                    </div>
                    <button type="submit" class="node-submit-btn text-neon-blue">
                        [EXECUTE_DEPLOYMENT]
                    </button>

                    <button type="button" id="btnCancelarEdicion" class="node-submit-btn"
                        style="margin-top:10px; background:rgba(255,0,0,0.1); border-color:#ff3333; color:#ff3333;">
                        [CANCEL_EDIT]
                    </button>
                </form>
            </div>

            <div class="cyber-panel-card glass-panel-neon border-neon-cyan table-span-2">
                <div class="panel-cyber-header">
                    <h4><span class="cyan-accent">//</span> REGISTROS EN TABLA: `usuarios`</h4>
                </div>
                <div class="panel-cyber-body-table mt-3">
                    <table class="cyber-mini-table" id="tablaUsuarios">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
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
    </section>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@500;600;700&display=swap');

    .btn-cyber {
        padding: 5px 10px;
        border-radius: 4px;
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.7rem;
        cursor: pointer;
        transition: 0.2s;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: transparent;
    }

    .btn-cyber-edit {
        color: #00f2ff;
        border-color: rgba(0, 242, 255, 0.3);
    }

    .btn-cyber-edit:hover {
        background: rgba(0, 242, 255, 0.1);
        box-shadow: 0 0 8px rgba(0, 242, 255, 0.3);
    }

    .btn-cyber-delete {
        color: #ff3333;
        border-color: rgba(255, 51, 51, 0.3);
    }

    .btn-cyber-delete:hover {
        background: rgba(255, 51, 51, 0.1);
        box-shadow: 0 0 8px rgba(255, 51, 51, 0.3);
    }

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
        border: 1px solid #00f2ff;
        color: #00f2ff;
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

    .border-neon-blue {
        border-left: 3px solid #00f2ff;
    }

    .border-neon-cyan {
        border-left: 3px solid #00b4d8;
    }

    .panel-cyber-header h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
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
        font-family: monospace;
        border-radius: 4px;
    }

    .cyber-input:focus {
        border-color: #00f2ff;
        outline: none;
    }

    .node-submit-btn {
        background: rgba(0, 242, 255, 0.1);
        border: 1px solid #00f2ff;
        color: #00f2ff;
        width: 100%;
        padding: 10px;
        font-family: 'Share Tech Mono', monospace;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 10px;
    }

    .node-submit-btn:hover {
        background: rgba(0, 242, 255, 0.2);
        box-shadow: 0 0 10px rgba(0, 242, 255, 0.4);
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

    .btn-terminal-edit,
    .btn-terminal-delete {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #a2b4cd;
        padding: 4px 8px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.75rem;
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

<script src="vistas/js/clientes/usuarios.js"></script>