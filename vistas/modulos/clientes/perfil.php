<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idRol = $_SESSION['id_rol'] ?? null;
$esAdmin = ($idRol == 2);
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">👤</span>
            <h2>[01] SYS_AUTH //
                <?php echo $esAdmin ? 'GESTIÓN CENTRAL DE OPERADORES Y PERFILES' : 'CREDENCIALES DE PERFIL PERSONAL'; ?>
            </h2>
            <span class="system-badge-live"><?php echo $esAdmin ? 'SEC_MATRIX_PRO' : 'NODE_USER_INFO'; ?></span>
        </div>
    </header>

    <section class="cyber-content mt-4">

        <?php if ($esAdmin): ?>
            <!-- =========================================================
                 VISTA ADMINISTRADOR: PANEL TABLA ABM COMPLETO
            ========================================================= -->
            <div class="cyber-panel-card glass-panel-neon border-neon-cyan">

                <!-- BARRA SUPERIOR: Buscador y Botón Agregar -->
                <div class="panel-cyber-header flex-header-toolbar">
                    <div class="cyber-search-box">
                        <i class="fa fa-search search-icon-hud"></i>
                        <input type="text" id="buscarPerfil" class="cyber-input-search"
                            placeholder="🔍 Buscar por id, usuario, operador o email...">
                    </div>

                    <button type="button" class="btn-cyber-add" id="btnNuevoPerfil">
                        <i class="fa fa-user-plus"></i> [+ AGREGAR OPERADOR]
                    </button>
                </div>

                <!-- TABLA DE OPERADORES -->
                <div class="panel-cyber-body-table mt-3">
                    <table class="cyber-mini-table" id="tablaPerfiles">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Nombre del Operador</th>
                                <th>Email Principal</th>
                                <th>Rol Asignado</th>
                                <th>Fecha Alta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Carga dinámica vía JS -->
                        </tbody>
                    </table>
                </div>

            </div>

        <?php else: ?>
            <!-- =========================================================
                 VISTA OPERADOR ESTÁNDAR: FICHA DE PERFIL PERSONAL
            ========================================================= -->
            <div class="cyber-container-center">
                <div class="cyber-panel-card glass-panel-neon border-neon-green">
                    <div class="panel-cyber-header">
                        <h4><span class="green-accent">//</span> MODIFICAR PERFIL / ACCESS_TOKEN</h4>
                    </div>

                    <form class="panel-cyber-body-stats font-mono mt-3" method="POST" id="formPerfil">
                        <input type="hidden" name="id" id="perfilId" value="<?php echo $_SESSION['id'] ?? ''; ?>">

                        <div class="cyber-form-group">
                            <label class="node-label">USUARIO ACTUAL (NO MODIFICABLE):</label>
                            <input type="text" id="perfilUsuario" class="cyber-input locked-node"
                                value="<?php echo $_SESSION['usuario'] ?? 'root_admin'; ?>" readonly>
                        </div>

                        <div class="cyber-form-group">
                            <label class="node-label">NOMBRE DEL OPERADOR:</label>
                            <input type="text" name="nombre" id="perfilNombre" class="cyber-input"
                                value="<?php echo $_SESSION['nombre'] ?? ''; ?>" required>
                        </div>

                        <div class="cyber-form-group">
                            <label class="node-label">CORREO ELECTRÓNICO (EMAIL):</label>
                            <input type="email" name="email" id="perfilEmail" class="cyber-input"
                                value="<?php echo $_SESSION['email'] ?? ''; ?>" required>
                        </div>

                        <hr class="cyber-hr">

                        <div class="cyber-form-group">
                            <label class="node-label text-yellow">// NUEVO PASSWORD (DEJAR VACÍO PARA MANTENER
                                ACTUAL):</label>
                            <input type="password" name="contrasena" id="perfilPassword" class="cyber-input"
                                placeholder="Ingresar nueva clave">
                        </div>

                        <div class="cyber-form-group">
                            <label class="node-label text-yellow">// CONFIRMAR NUEVO PASSWORD:</label>
                            <input type="password" id="perfilConfirmPassword" class="cyber-input"
                                placeholder="Confirmar nueva clave">
                        </div>

                        <button type="submit" class="node-submit-btn text-neon-green">
                            [UPDATE_SECURITY_HASH]
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    </section>
</div>

<!-- ==========================================
     MODAL EMERGENTE: REGISTRAR / EDITAR OPERADOR (NATIVO - MODO ADMIN)
========================================== -->
<div class="custom-cyber-modal-overlay" id="modalPerfilOverlay" style="display: none;">
    <div class="custom-cyber-modal-container border-neon-blue">

        <div class="cyber-modal-header">
            <button type="button" class="text-cyber-close" id="btnCerrarModalX">&times;</button>
            <h4 class="modal-title font-mono" id="modalPerfilLabel">
                <span class="cyan-accent">//</span> <span id="lblTituloModal">[INYECTAR_NODO_OPERADOR]</span>
            </h4>
        </div>

        <form id="formPerfilAdmin" autocomplete="off" method="POST" onsubmit="return false;">
            <div class="modal-body font-mono">
                <input type="hidden" name="id" id="idPerfilModal">

                <div class="cyber-form-group">
                    <label class="node-label">ID OPERADOR (ALIAS):</label>
                    <input type="text" name="usuario" id="perfil_alias" class="cyber-input" placeholder="ej: jsilva"
                        required>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">NOMBRE DEL OPERADOR:</label>
                    <input type="text" name="nombre" id="perfil_nombre" class="cyber-input" placeholder="ej: Juan Silva"
                        required>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">EMAIL PRINCIPAL:</label>
                    <input type="email" name="email" id="perfil_email" class="cyber-input"
                        placeholder="ej: jsilva@microexpress.com" required>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">ACCESS_TOKEN (CONTRASEÑA):</label>
                    <input type="password" name="contrasena" id="perfil_pass" class="cyber-input"
                        placeholder="********">
                    <small class="text-help-cyber">* Dejar en blanco si no desea cambiar la contraseña al
                        editar.</small>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label">ROL ASIGNADO:</label>
                    <select name="id_rol" id="perfil_rol" class="cyber-input" required>
                        <option value="1">Técnico</option>
                        <option value="2">Administrador</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer cyber-modal-footer">
                <button type="button" class="btn-cyber-cancel hidden" id="btnCancelarModal">[CANCEL_EDIT]</button>
                <button type="submit" id="btnGuardarPerfil"
                    class="node-submit-btn text-neon-blue">[EXECUTE_DEPLOYMENT]</button>
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
        border: 1px solid var(--neon-cyan-dark);
        color: var(--neon-cyan-dark);
        background: rgba(2, 132, 199, 0.08);
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: bold;
    }

    .cyber-container-center {
        max-width: 600px;
        margin: 0 auto;
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

    .border-neon-green {
        border-left: 4px solid var(--neon-green-dark);
    }

    .green-accent {
        color: var(--neon-green-dark);
        font-weight: bold;
    }

    .text-yellow {
        color: var(--neon-yellow-dark);
        font-weight: bold;
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
        box-shadow: 0 0 10px rgba(2, 132, 199, 0.15);
        outline: none;
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
        box-shadow: 0 0 12px rgba(2, 132, 199, 0.3);
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
    }

    .btn-terminal-edit:hover {
        color: var(--neon-cyan-dark);
        border-color: var(--neon-cyan-dark);
        background: rgba(2, 132, 199, 0.08);
    }

    .btn-terminal-delete:hover {
        color: var(--neon-red-dark);
        border-color: var(--neon-red-dark);
        background: rgba(220, 38, 38, 0.08);
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
        margin: 20px 0;
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
        width: 100%;
    }

    .node-submit-btn:hover {
        background: var(--neon-cyan-dark);
        color: #ffffff;
        box-shadow: 0 0 10px rgba(2, 132, 199, 0.3);
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
        max-width: 500px;
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
        justify-content: flex-end;
        gap: 10px;
        background: #f8fafc;
    }
</style>

<script src="vistas/js/clientes/perfil.js"></script>