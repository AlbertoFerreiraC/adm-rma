<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">👤</span>
            <h2>[01] SYS_AUTH // CREDENCIALES DE PERFIL PERSONAL</h2>
            <span class="system-badge-live">NODE_USER_INFO</span>
        </div>
    </header>

    <section class="cyber-content mt-4">
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
                            value="<?php echo $_SESSION['nombre'] ?? 'Administrador'; ?>" required>
                    </div>

                    <div class="cyber-form-group">
                        <label class="node-label">CORREO ELECTRÓNICO (EMAIL):</label>
                        <input type="email" name="email" id="perfilEmail" class="cyber-input"
                            value="<?php echo $_SESSION['email'] ?? 'admin@matrix.com'; ?>" required>
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
        border: 1px solid #00ff66;
        color: #00ff66;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .cyber-container-center {
        max-width: 600px;
        margin: 0 auto;
    }

    .cyber-panel-card {
        background: rgba(10, 16, 32, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 25px;
    }

    .border-neon-green {
        border-left: 3px solid #00ff66;
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
        /* 🔥 FIX: Fuerza a que el texto (y los puntos del password) sean blancos */
        padding: 10px;
        border-radius: 4px;
        box-sizing: border-box;
        outline: none;
    }

    .locked-node {
        color: #506690;
        background: rgba(255, 255, 255, 0.01);
        border-color: #0d162d;
    }

    .cyber-hr {
        border: 0;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        margin: 20px 0;
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

    .node-submit-btn:hover {
        background: rgba(0, 255, 102, 0.2);
        box-shadow: 0 0 10px rgba(0, 255, 102, 0.4);
    }
</style>

<script src="vistas/js/clientes/perfil.js"></script>