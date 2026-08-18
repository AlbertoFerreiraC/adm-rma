<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Datos de sesión para perfil de operario
$nombreTecnico = $_SESSION['nombre_usuario'] ?? 'Alejandro Rodríguez';
$rolTecnico = 'Técnico Especialista en Microelectrónica';
$avatarTecnico = 'https://i.imgur.com/wHSp640.png'; // Avatar dummy tecnológico
?>

<input type="hidden" name="rol" id="rol" value="<?php echo $_SESSION['id_rol'] ?? '3'; ?>">
<input type="hidden" name="id_tecnico" id="id_tecnico" value="<?php echo $_SESSION['id_usuario'] ?? '102'; ?>">

<div class="content-wrapper dashboard-cyber-wrapper">

    <!-- HEADER PRINCIPAL DE PERFIL DE TÉCNICO -->
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">⚡</span>
            <div class="brand-text-stack">
                <h2>Estación de Trabajo</h2>
                <span class="system-badge-live">LABORATORIO DE HARDWARE & RMA // V5.0</span>
            </div>
        </div>

        <div class="tec-session-profile">
            <div class="profile-avatar-wrapper">
                <img src="<?php echo $avatarTecnico; ?>" alt="Avatar Operario" class="profile-avatar">
                <span class="online-indicator-dot"></span>
            </div>
            <div class="profile-info-stack">
                <span class="profile-label">OPERARIO EN SESIÓN</span>
                <span class="profile-name text-neon-cyan font-mono"><?php echo $nombreTecnico; ?></span>
                <span class="profile-role font-mono"><?php echo $rolTecnico; ?></span>
            </div>
            <div class="profile-bahia-tag font-mono">
                <span class="tag-title">BAHÍA ASIGNADA</span>
                <span class="tag-val text-neon-purple">TALLER_B04</span>
            </div>
        </div>
    </header>

    <!-- BARRA DE CONTROL DE ACCIONES TÉCNICAS -->
    <div class="cyber-panel-card glass-panel-neon border-neon-cyan tec-action-control-bar mt-4">
        <button type="button" class="btn-cyber-add" id="btnNuevoRmaTecnico">
            <i class="fa fa-plus-circle"></i> INGRESO NUEVO RMA (NUEVA ORDEN)
        </button>
        <div class="tec-utility-buttons">
            <button type="button" class="btn-terminal-edit" id="btnReimprimirQr">
                <i class="fa fa-qrcode"></i> REIMPRIMIR CÓDIGO QR
            </button>
            <button type="button" class="btn-terminal-view" id="btnImprimirEtiqueta">
                <i class="fa fa-print"></i> ETIQUETA TÉRMICA DE EMBALAJE
            </button>
        </div>
    </div>

    <!-- TARJETAS DE KPIS DE PRODUCCIÓN -->
    <div class="tec-metrics-row mt-4">

        <div class="cyber-kpi-card glass-panel-neon border-neon-yellow">
            <div class="kpi-header-inline font-mono">
                <span class="kpi-label-code">MI_BANCO // COLA_TRABAJO</span>
                <span class="pulse-dot-yellow"></span>
            </div>
            <div class="kpi-body-compact font-mono">
                <h3 class="text-neon-yellow">14</h3>
                <p class="kpi-title-text">Máquinas asignadas</p>
            </div>
            <div class="kpi-footer-meta font-mono">5 en diagnóstico · 9 en reparación</div>
        </div>

        <div class="cyber-kpi-card glass-panel-neon border-neon-green">
            <div class="kpi-header-inline font-mono">
                <span class="kpi-label-code">PRODUCCIÓN // DIARIA</span>
                <span class="system-badge-live"
                    style="color: var(--neon-green-dark); border-color: var(--neon-green-dark); background: rgba(21,128,61,0.08);">CUOTA
                    ALCANZADA</span>
            </div>
            <div class="kpi-body-compact font-mono">
                <h3 class="green-accent">6</h3>
                <p class="kpi-title-text">Reparados hoy</p>
            </div>
            <div class="kpi-footer-meta font-mono">Meta base del laboratorio: 5</div>
        </div>

        <div class="cyber-kpi-card glass-panel-neon border-neon-blue">
            <div class="kpi-header-inline font-mono">
                <span class="kpi-label-code">CONTROLES // EFECTIVIDAD</span>
                <span class="t-cyan">CALIDAD GLOBAL</span>
            </div>
            <div class="kpi-body-compact font-mono">
                <h3 class="t-cyan">97.8%</h3>
                <p class="kpi-title-text">Tasa de éxito</p>
            </div>
            <div class="kpi-footer-meta font-mono">0 reingresos reportados este mes</div>
        </div>

        <div class="cyber-kpi-card glass-panel-neon border-neon-red critical-alert-pulse">
            <div class="kpi-header-inline font-mono">
                <span class="text-neon-red">LOGÍSTICA // STOCK</span>
                <span class="text-neon-red blink-anim">⚠️ RETRASO</span>
            </div>
            <div class="kpi-body-compact font-mono">
                <h3 class="text-neon-red">2</h3>
                <p class="kpi-title-text text-neon-red">Órdenes trabadas</p>
            </div>
            <div class="kpi-footer-meta font-mono text-neon-red">En espera de repuestos de proveedor</div>
        </div>

    </div>

    <!-- PANELES DE TRABAJO EN TALLER -->
    <div class="tec-workspace-layout mt-4">

        <!-- PANEL MÁQUINAS EN PROCESO -->
        <div class="cyber-panel-card glass-panel-neon border-neon-yellow">
            <div class="panel-cyber-header flex-header-toolbar">
                <h4><span class="text-yellow">//</span> MÁQUINAS EN PROCESO DE REPARACIÓN</h4>
                <span class="system-badge-live"
                    style="color: var(--neon-yellow-dark); border-color: var(--neon-yellow-dark); background: rgba(217,119,6,0.08);">MONITOREANDO_BANCO</span>
            </div>

            <div class="panel-cyber-body-table mt-3 font-mono">
                <table class="cyber-mini-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Hardware / Cliente</th>
                            <th>Estado Interno</th>
                            <th>Falla Reportada</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="t-cyan font-mono">RMA-0247</td>
                            <td><strong>PC Gamer Custom</strong><br><small class="text-muted-cyan">M. García (Asus
                                    Z790)</small></td>
                            <td><span class="badge-status-cyber status-default">En Reparación</span></td>
                            <td class="text-truncate-tec">Corto en línea de 12V VRM</td>
                            <td class="text-right">
                                <button type="button" class="btn-terminal-edit"
                                    onclick="abrirGestionTrabajo('RMA-0247')" title="Procesar Orden">⚙️</button>
                                <button type="button" class="btn-terminal-view"
                                    onclick="imprimirTicketEquipo('RMA-0247')" title="Imprimir Ticket">🖨️</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="t-cyan font-mono">RMA-0246</td>
                            <td><strong>Laptop Lenovo V15</strong><br><small class="text-muted-cyan">J. Benítez</small>
                            </td>
                            <td><span class="badge-status-cyber status-1">En Diagnóstico</span></td>
                            <td class="text-truncate-tec">No da video, parpadea LED</td>
                            <td class="text-right">
                                <button type="button" class="btn-terminal-edit"
                                    onclick="abrirGestionTrabajo('RMA-0246')" title="Procesar Orden">⚙️</button>
                                <button type="button" class="btn-terminal-view"
                                    onclick="imprimirTicketEquipo('RMA-0246')" title="Imprimir Ticket">🖨️</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="t-cyan font-mono">RMA-0241</td>
                            <td><strong>Tarjeta de Video RTX 4070</strong><br><small class="text-muted-cyan">Soporte
                                    Corp.</small></td>
                            <td><span class="badge-status-cyber status-external">Espera de Repuesto</span></td>
                            <td class="text-truncate-tec">Faltan integrados VRAM</td>
                            <td class="text-right">
                                <button type="button" class="btn-terminal-edit"
                                    onclick="abrirGestionTrabajo('RMA-0241')" title="Procesar Orden">⚙️</button>
                                <button type="button" class="btn-terminal-view"
                                    onclick="imprimirTicketEquipo('RMA-0241')" title="Imprimir Ticket">🖨️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PANEL DESPACHOS RECIENTES -->
        <div class="cyber-panel-card glass-panel-neon border-neon-green">
            <div class="panel-cyber-header flex-header-toolbar">
                <h4><span class="green-accent">//</span> HISTORIAL RECIENTE DE DESPACHOS</h4>
                <span class="system-badge-live"
                    style="color: var(--neon-green-dark); border-color: var(--neon-green-dark); background: rgba(21,128,61,0.08);">ALTAS_CONFIRMADAS</span>
            </div>

            <div class="panel-cyber-body-table mt-3 font-mono">
                <table class="cyber-mini-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Equipo / Diagnóstico Solución</th>
                            <th>Finalizado</th>
                            <th class="text-right">Reimpresión</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="t-cyan font-mono">RMA-0239</td>
                            <td><strong>MacBook Pro M1</strong><br><small class="green-accent">Reballing de procesador
                                    exitoso</small></td>
                            <td class="font-mono">Hoy 11:30</td>
                            <td class="text-right">
                                <button type="button" class="btn-terminal-view"
                                    onclick="reimprimirEtiquetaRma('RMA-0239')" title="Reimprimir QR">📷</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="t-cyan font-mono">RMA-0235</td>
                            <td><strong>Monitor ViewSonic 24"</strong><br><small class="green-accent">Cambio de
                                    capacitores inflados</small></td>
                            <td class="font-mono">Ayer</td>
                            <td class="text-right">
                                <button type="button" class="btn-terminal-view"
                                    onclick="reimprimirEtiquetaRma('RMA-0235')" title="Reimprimir QR">📷</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="t-cyan font-mono">RMA-0230</td>
                            <td><strong>PC de Escritorio HP</strong><br><small class="green-accent">Mantenimiento
                                    químico completo</small></td>
                            <td class="font-mono">15 Jun 2026</td>
                            <td class="text-right">
                                <button type="button" class="btn-terminal-view"
                                    onclick="reimprimirEtiquetaRma('RMA-0230')" title="Reimprimir QR">📷</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- ==========================================
     MODAL EMERGENTE: ACTUALIZAR DIAGNÓSTICO / ORDEN (NATIVO)
========================================== -->
<div class="custom-cyber-modal-overlay" id="modalGestionTrabajo" style="display: none;">
    <div class="custom-cyber-modal-container border-neon-blue" style="max-width: 650px;">

        <div class="cyber-modal-header">
            <button type="button" class="text-cyber-close" onclick="cerrarModalGestion()">&times;</button>
            <h4 class="modal-title font-mono" id="modalTituloRma">
                <span class="cyan-accent">//</span> [ACTUALIZAR DIAGNÓSTICO / ORDEN]
            </h4>
        </div>

        <form id="formAvanceTecnico" autocomplete="off" method="POST" onsubmit="return false;">
            <div class="modal-body font-mono">
                <input type="hidden" id="modalIdRma">

                <div class="cyber-form-group-row mb-3"
                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="cyber-form-group">
                        <label class="node-label">ESTADO DEL EQUIPO EN BANCO</label>
                        <select id="selectEstadoRma" class="cyber-input">
                            <option value="diagnostico">🔬 En Diagnóstico Avanzado</option>
                            <option value="reparacion">🔧 En Proceso de Reparación</option>
                            <option value="espera_repuesto">⏳ Detenido - Falta de Insumo</option>
                            <option value="listo">✅ Reparado / Pasar a Control</option>
                        </select>
                    </div>
                    <div class="cyber-form-group">
                        <label class="node-label">INSUMOS APLICADOS</label>
                        <select id="selectInsumoTaller" class="cyber-input">
                            <option value="">-- Sin consumibles añadidos --</option>
                            <option value="pasta">Pasta Térmica Arctic MX-4</option>
                            <option value="pads">Thermal Pads Alta Densidad</option>
                        </select>
                    </div>
                </div>

                <div class="cyber-form-group">
                    <label class="node-label text-yellow">// REPORTE TÉCNICO INTERNO DE LA FALLA Y SOLUCIÓN:</label>
                    <textarea id="txtDetalleTecnico" class="cyber-input cyber-textarea" rows="4"
                        placeholder="Detalla los voltajes medidos, componentes sustituidos, etc..."></textarea>
                </div>
            </div>

            <div class="modal-footer cyber-modal-footer">
                <button type="button" class="btn-terminal-view"
                    onclick="imprimirTicketEquipo(document.getElementById('modalIdRma').value)">
                    <i class="fa fa-print"></i> [IMPRIMIR AVANCE]
                </button>
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn-cyber-cancel" onclick="cerrarModalGestion()">CANCELAR</button>
                    <button type="button" class="node-submit-btn text-neon-blue" id="btnGuardarAvanceTecnico">GUARDAR
                        AVANCE</button>
                </div>
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
        --neon-purple-dark: #7e22ce;
        --neon-yellow-dark: #d97706;
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
        flex-wrap: wrap;
        gap: 15px;
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

    .brand-text-stack {
        display: flex;
        flex-direction: column;
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

    /* PERFIL DE TÉCNICO EN HEADER */
    .tec-session-profile {
        display: flex;
        align-items: center;
        padding: 6px 14px;
        gap: 12px;
        background: #f8fafc;
        border: 1px solid var(--border-cyber-subtle);
        border-radius: 6px;
    }

    .profile-avatar-wrapper {
        position: relative;
        width: 38px;
        height: 38px;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 1px solid var(--neon-cyan-dark);
        background: #e2e8f0;
    }

    .online-indicator-dot {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 8px;
        height: 8px;
        background: var(--neon-green-dark);
        border-radius: 50%;
        box-shadow: 0 0 6px var(--neon-green-dark);
    }

    .profile-info-stack {
        display: flex;
        flex-direction: column;
    }

    .profile-label {
        font-size: 0.65rem;
        color: var(--text-cyber-muted);
        font-weight: bold;
    }

    .profile-name {
        font-size: 0.9rem;
        font-weight: 700;
        line-height: 1.1;
        color: var(--neon-cyan-dark);
    }

    .profile-role {
        font-size: 0.72rem;
        color: var(--text-cyber-muted);
    }

    .profile-bahia-tag {
        border-left: 1px solid var(--border-cyber-subtle);
        padding-left: 12px;
        display: flex;
        flex-direction: column;
    }

    .profile-bahia-tag .tag-title {
        font-size: 0.65rem;
        color: var(--text-cyber-muted);
        font-weight: bold;
    }

    .profile-bahia-tag .tag-val {
        font-size: 0.85rem;
        font-weight: bold;
        color: var(--neon-purple-dark);
    }

    /* BARRA DE ACCIONES Y KPIS */
    .tec-action-control-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .tec-utility-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
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

    .tec-metrics-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        width: 100%;
    }

    @media(max-width: 1100px) {
        .tec-metrics-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 600px) {
        .tec-metrics-row {
            grid-template-columns: 1fr;
        }
    }

    .cyber-kpi-card {
        position: relative;
        border-radius: 8px;
        padding: 14px;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: var(--card-cyber-light);
        border: 1px solid var(--border-cyber-subtle);
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
    }

    .cyber-panel-card {
        background: var(--card-cyber-light);
        border: 1px solid var(--border-cyber-subtle);
        border-radius: 8px;
        padding: 18px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    }

    .border-neon-yellow {
        border-left: 4px solid var(--neon-yellow-dark);
    }

    .border-neon-green {
        border-left: 4px solid var(--neon-green-dark);
    }

    .border-neon-blue {
        border-left: 4px solid var(--neon-cyan-dark);
    }

    .border-neon-red {
        border-left: 4px solid var(--neon-red-dark);
    }

    .kpi-header-inline {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.7rem;
        color: var(--text-cyber-muted);
    }

    .kpi-body-compact h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 4px 0 0 0;
        line-height: 1;
    }

    .kpi-title-text {
        font-size: 0.75rem;
        color: var(--text-cyber-muted);
        margin: 4px 0 0 0;
        font-weight: bold;
        text-transform: uppercase;
    }

    .kpi-footer-meta {
        font-size: 0.7rem;
        color: var(--text-cyber-muted);
        margin-top: 4px;
    }

    .pulse-dot-yellow {
        width: 8px;
        height: 8px;
        background: var(--neon-yellow-dark);
        border-radius: 50%;
        animation: blink-animation 1s infinite;
    }

    /* PANELES DE TABLA Y LAYOUT */
    .tec-workspace-layout {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 20px;
    }

    @media(max-width: 1200px) {
        .tec-workspace-layout {
            grid-template-columns: 1fr;
        }
    }

    .flex-header-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
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
        font-size: 0.72rem;
        color: var(--text-cyber-muted);
        padding: 10px 6px;
        border-bottom: 2px solid var(--border-cyber-subtle);
        text-transform: uppercase;
        text-align: center;
        background: #f8fafc;
    }

    .cyber-mini-table td {
        padding: 10px 6px;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text-cyber-dark);
        text-align: center;
    }

    .badge-status-cyber {
        font-size: 0.72rem;
        font-weight: bold;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
        border: 1px solid;
    }

    .status-1 {
        background: rgba(2, 132, 199, 0.08);
        color: var(--neon-cyan-dark);
        border-color: rgba(2, 132, 199, 0.3);
    }

    .status-default {
        background: rgba(217, 119, 6, 0.08);
        color: var(--neon-yellow-dark);
        border-color: rgba(217, 119, 6, 0.3);
    }

    .status-external {
        background: rgba(220, 38, 38, 0.08);
        color: var(--neon-red-dark);
        border-color: rgba(220, 38, 38, 0.3);
    }

    .btn-terminal-view,
    .btn-terminal-edit {
        background: transparent;
        border: 1px solid var(--border-cyber-subtle);
        color: var(--text-cyber-muted);
        padding: 4px 8px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.75rem;
        font-family: 'Share Tech Mono', monospace;
        transition: all 0.2s;
        margin: 0 2px;
    }

    .btn-terminal-view:hover {
        color: var(--neon-purple-dark);
        border-color: var(--neon-purple-dark);
        background: rgba(126, 34, 206, 0.08);
    }

    .btn-terminal-edit:hover {
        color: var(--neon-cyan-dark);
        border-color: var(--neon-cyan-dark);
        background: rgba(2, 132, 199, 0.08);
    }

    .text-muted-cyan {
        color: var(--text-cyber-muted);
        font-size: 0.75rem;
    }

    .text-truncate-tec {
        max-width: 140px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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

    .cyber-modal-footer {
        border-top: 1px solid var(--border-cyber-subtle);
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
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
    }

    .node-submit-btn:hover {
        background: var(--neon-cyan-dark);
        color: #ffffff;
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

    /* ESTILOS DE TEXTO Y GLOW */
    .cyan-accent,
    .t-cyan,
    .text-neon-cyan {
        color: var(--neon-cyan-dark);
        font-weight: bold;
    }

    .purple-accent,
    .text-neon-purple {
        color: var(--neon-purple-dark);
        font-weight: bold;
    }

    .green-accent,
    .text-neon-green {
        color: var(--neon-green-dark);
        font-weight: bold;
    }

    .text-yellow,
    .text-neon-yellow {
        color: var(--neon-yellow-dark);
        font-weight: bold;
    }

    .text-neon-red {
        color: var(--neon-red-dark);
        font-weight: bold;
    }

    .font-mono {
        font-family: 'Share Tech Mono', monospace;
    }

    .text-right {
        text-align: right !important;
    }

    .critical-alert-pulse {
        animation: alertGlow 2s infinite alternate;
    }

    .blink-anim {
        animation: blink 1.2s infinite;
    }

    @keyframes blink {
        to {
            opacity: 0.3;
        }
    }

    @keyframes blink-animation {
        to {
            opacity: 0.3;
        }
    }

    @keyframes alertGlow {
        100% {
            box-shadow: 0 0 12px rgba(220, 38, 38, 0.2);
            border-color: rgba(220, 38, 38, 0.3);
        }
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }
</style>

<script>
    function abrirGestionTrabajo(idRma) {
        document.getElementById('modalIdRma').value = idRma;
        document.getElementById('modalTituloRma').innerText = `// PROCESAR ORDEN EN BANCO: ${idRma}`;
        document.getElementById('modalGestionTrabajo').style.display = 'flex';
    }

    function cerrarModalGestion() {
        document.getElementById('modalGestionTrabajo').style.display = 'none';
    }

    function imprimirTicketEquipo(idRma) {
        alert(`[COLA DE IMPRESIÓN] Generando comprobante térmico de hardware de la orden: ${idRma}`);
    }

    function reimprimirEtiquetaRma(idRma) {
        alert(`[MÓDULO QR] Re-imprimiendo código QR autoadhesivo para chasis: ${idRma}`);
    }

    document.getElementById('btnGuardarAvanceTecnico')?.addEventListener('click', function () {
        const rma = document.getElementById('modalIdRma').value;
        alert(`[TALLER] Avance de reparación consolidado con éxito en la orden ${rma}.`);
        cerrarModalGestion();
    });
</script>

<script src="vistas/js/tecnicos_laboratorio.js"></script>