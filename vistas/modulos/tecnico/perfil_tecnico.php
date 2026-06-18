<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Datos ficticios de sesión para pruebas visuales directas
$nombreTecnico = $_SESSION['nombre_usuario'] ?? 'Alejandro Rodríguez';
$rolTecnico = 'Técnico Especialista en Microelectrónica';
$avatarTecnico = 'https://i.imgur.com/wHSp640.png'; // Avatar dummy tecnológico
?>

<input type="hidden" name="rol" id="rol" value="<?php echo $_SESSION['id_rol'] ?? '3'; ?>">
<input type="hidden" name="id_tecnico" id="id_tecnico" value="<?php echo $_SESSION['id_usuario'] ?? '102'; ?>">

<div class="content-wrapper estacion-tecnico-cyber">

    <header class="tec-header-cyber glass-node">
        <div class="tec-brand-title">
            <span class="tec-neon-pulse-icon">⚡</span>
            <div class="brand-text-stack">
                <h2>Estación de Trabajo</h2>
                <span class="tec-sub-system">LABORATORIO DE HARDWARE & RMA // V5.0</span>
            </div>
        </div>

        <div class="tec-session-profile glass-node-inner">
            <div class="profile-avatar-wrapper">
                <img src="<?php echo $avatarTecnico; ?>" alt="Avatar Operario" class="profile-avatar">
                <span class="online-indicator-dot"></span>
            </div>
            <div class="profile-info-stack">
                <span class="profile-label">OPERARIO EN SESIÓN</span>
                <span class="profile-name text-neon-cyan"><?php echo $nombreTecnico; ?></span>
                <span class="profile-role"><?php echo $rolTecnico; ?></span>
            </div>
            <div class="profile-bahia-tag">
                <span class="tag-title">BAHÍA ASIGNADA</span>
                <span class="tag-val text-neon-purple">TALLER_B04</span>
            </div>
        </div>
    </header>

    <div class="tec-action-control-bar glass-node border-cyan-glow mt-4">
        <button class="tec-btn-action btn-glow-blue" id="btnNuevoRmaTecnico">
            <span class="btn-ic">➕</span> INGRESO NUEVO RMA (NUEVA ORDEN)
        </button>
        <div class="tec-utility-buttons">
            <button class="tec-btn-secondary btn-border-purple" id="btnReimprimirQr">
                <span class="btn-ic">📷</span> REIMPRIMIR CÓDIGO QR
            </button>
            <button class="tec-btn-secondary btn-border-green" id="btnImprimirEtiqueta">
                <span class="btn-ic">🖨️</span> ETQUETA TÉRMICA DE EMBALAJE
            </button>
        </div>
    </div>

    <div class="tec-metrics-row mt-4">

        <div class="tec-kpi-card-neon border-left-yellow glass-node">
            <div class="kpi-meta-top">
                <span class="kpi-label-code">MI_BANCO // COLA_TRABAJO</span>
                <span class="kpi-pulse-yellow"></span>
            </div>
            <div class="kpi-body-compact">
                <h3 class="text-neon-yellow">14</h3>
                <p class="kpi-title-text">Máquinas asignadas</p>
            </div>
            <div class="kpi-footer-meta">5 en diagnóstico · 9 en reparación</div>
        </div>

        <div class="tec-kpi-card-neon border-left-green glass-node">
            <div class="kpi-meta-top">
                <span class="kpi-label-code">PRODUCCIÓN // DIARIA</span>
                <span class="kpi-badge-ok">CUOTA ALCANZADA</span>
            </div>
            <div class="kpi-body-compact">
                <h3 class="text-neon-green">6</h3>
                <p class="kpi-title-text">Reparados hoy</p>
            </div>
            <div class="kpi-footer-meta">Meta base del laboratorio: 5</div>
        </div>

        <div class="tec-kpi-card-neon border-left-blue glass-node">
            <div class="kpi-meta-top">
                <span class="kpi-label-code">CONTROLES // EFECTIVIDAD</span>
                <span class="text-neon-blue">CALIDAD GLOBAL</span>
            </div>
            <div class="kpi-body-compact">
                <h3 class="text-neon-blue">97.8%</h3>
                <p class="kpi-title-text">Tasa de éxito</p>
            </div>
            <div class="kpi-footer-meta">0 reingresos reportados este mes</div>
        </div>

        <div class="tec-kpi-card-neon border-left-red glass-node critical-alert-pulse">
            <div class="kpi-meta-top">
                <span class="text-neon-red">LOGÍSTICA // STOCK</span>
                <span class="text-neon-red blink-anim">⚠️ RETRASO</span>
            </div>
            <div class="kpi-body-compact">
                <h3 class="text-neon-red">2</h3>
                <p class="kpi-title-text text-neon-red">Órdenes trabadas</p>
            </div>
            <div class="kpi-footer-meta text-neon-red">En espera de repuestos de proveedor</div>
        </div>

    </div>

    <div class="tec-workspace-layout mt-4">

        <div class="tec-workspace-panel glass-node border-left-yellow">
            <div class="panel-header-cyber">
                <h4><span class="text-neon-yellow">//</span> MÁQUINAS EN PROCESO DE REPARACIÓN</h4>
                <span class="panel-tag-status background-yellow">MONITOREANDO_BANCO</span>
            </div>

            <div class="panel-table-responsive">
                <table class="tec-table-cyber">
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
                            <td class="text-neon-cyan font-mono">RMA-0247</td>
                            <td><strong>PC Gamer Custom</strong><br><small class="text-muted-cyan">M. García (Asus
                                    Z790)</small></td>
                            <td><span class="badge-status-tec badge-tec-repair">En Reparación</span></td>
                            <td class="text-truncate-tec">Corto en línea de 12V VRM</td>
                            <td class="text-right">
                                <button class="btn-table-tec-action hover-blue"
                                    onclick="abrirGestionTrabajo('RMA-0247')">⚙️</button>
                                <button class="btn-table-tec-action hover-green"
                                    onclick="imprimirTicketEquipo('RMA-0247')">🖨️</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-neon-cyan font-mono">RMA-0246</td>
                            <td><strong>Laptop Lenovo V15</strong><br><small class="text-muted-cyan">J. Benítez</small>
                            </td>
                            <td><span class="badge-status-tec badge-tec-diag">En Diagnóstico</span></td>
                            <td class="text-truncate-tec">No da video, parpadea LED</td>
                            <td class="text-right">
                                <button class="btn-table-tec-action hover-blue"
                                    onclick="abrirGestionTrabajo('RMA-0246')">⚙️</button>
                                <button class="btn-table-tec-action hover-green"
                                    onclick="imprimirTicketEquipo('RMA-0246')">🖨️</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-neon-cyan font-mono">RMA-0241</td>
                            <td><strong>Tarjeta de Video RTX 4070</strong><br><small class="text-muted-cyan">Soporte
                                    Corporativo S.A.</small></td>
                            <td><span class="badge-status-tec badge-tec-delay">Espera de Repuesto</span></td>
                            <td class="text-truncate-tec">Faltan integrados VRAM</td>
                            <td class="text-right">
                                <button class="btn-table-tec-action hover-blue"
                                    onclick="abrirGestionTrabajo('RMA-0241')">⚙️</button>
                                <button class="btn-table-tec-action hover-green"
                                    onclick="imprimirTicketEquipo('RMA-0241')">🖨️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tec-workspace-panel glass-node border-left-green">
            <div class="panel-header-cyber">
                <h4><span class="text-neon-green">//</span> HISTORIAL RECIENTE DE DESPACHOS</h4>
                <span class="panel-tag-status background-green">ALTAS_CONFIRMADAS</span>
            </div>

            <div class="panel-table-responsive">
                <table class="tec-table-cyber">
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
                            <td class="text-neon-cyan font-mono">RMA-0239</td>
                            <td><strong>MacBook Pro M1</strong><br><small class="text-neon-green">Reballing de
                                    procesador exitoso</small></td>
                            <td class="font-mono">Hoy 11:30</td>
                            <td class="text-right">
                                <button class="btn-table-tec-action hover-purple"
                                    onclick="reimprimirEtiquetaRma('RMA-0239')">📷</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-neon-cyan font-mono">RMA-0235</td>
                            <td><strong>Monitor ViewSonic 24"</strong><br><small class="text-neon-green">Cambio de
                                    capacitores inflados</small></td>
                            <td class="font-mono">Ayer</td>
                            <td class="text-right">
                                <button class="btn-table-tec-action hover-purple"
                                    onclick="reimprimirEtiquetaRma('RMA-0235')">📷</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-neon-cyan font-mono">RMA-0230</td>
                            <td><strong>PC de Escritorio HP</strong><br><small class="text-neon-green">Mantenimiento
                                    químico completo</small></td>
                            <td class="font-mono">15 Jun 2026</td>
                            <td class="text-right">
                                <button class="btn-table-tec-action hover-purple"
                                    onclick="reimprimirEtiquetaRma('RMA-0230')">📷</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<div id="modalGestionTrabajo" class="modal-cyber-frame" style="display:none;">
    <div class="modal-cyber-box glass-node border-left-blue">
        <div class="modal-cyber-header">
            <h4 id="modalTituloRma">// ACTUALIZAR DIAGNÓSTICO / ORDEN</h4>
            <button type="button" class="close-cyber-btn" onclick="cerrarModalGestion()">&times;</button>
        </div>
        <div class="modal-cyber-body">
            <input type="hidden" id="modalIdRma">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="cyber-form-label">ESTADO DEL EQUIPO EN BANCO</label>
                    <select id="selectEstadoRma" class="cyber-select-input w-100">
                        <option value="diagnostico">🔬 En Diagnóstico Avanzado</option>
                        <option value="reparacion">🔧 En Proceso de Reparación</option>
                        <option value="espera_repuesto">⏳ Detenido - Falta de Insumo</option>
                        <option value="listo">✅ Reparado / Pasar a Control</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="cyber-form-label">INSUMOS APLICADOS</label>
                    <select id="selectInsumoTaller" class="cyber-select-input w-100">
                        <option value="">-- Sin consumibles añadidos --</option>
                        <option value="pasta">Pasta Térmica Arctic MX-4</option>
                        <option value="pads">Thermal Pads Alta Densidad</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="cyber-form-label">REPORTE TÉCNICO INTERNO DE LA FALLA Y SOLUCIÓN</label>
                <textarea id="txtDetalleTecnico" class="cyber-select-input w-100" rows="4"
                    placeholder="Detalla los voltajes medidos, componentes sustituidos, etc..."></textarea>
            </div>
        </div>
        <div class="modal-cyber-footer">
            <button class="tec-btn-secondary btn-border-purple"
                onclick="imprimirTicketEquipo(document.getElementById('modalIdRma').value)">🖨️ IMPRIMIR AVANCE</button>
            <div>
                <button class="tec-btn-secondary btn-border-red" onclick="cerrarModalGestion()">CANCELAR</button>
                <button class="tec-btn-action btn-glow-blue" id="btnGuardarAvanceTecnico"
                    style="padding: 7px 15px; font-size: 0.85rem; margin-left: 10px;">GUARDAR AVANCE</button>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@500;600;700&display=swap');

    /* RESET DE MARGEN IZQUIERDO Y CONFIGURACIÓN DE FONDO UNIFORME OSCURO */
    .estacion-tecnico-cyber {
        background-color: #060913 !important;
        background-image: radial-gradient(circle at 50% 10%, #0c152b 0%, #060913 70%) !important;
        min-height: 100vh !important;
        padding: 24px !important;
        margin-left: 0 !important;
        /* Elimina el bloque lateral blanco/gris por completo */
        color: #e2e8f0 !important;
        font-family: 'Rajdhani', sans-serif !important;
    }

    /* PANEL DE CONTENEDOR GLASS CON LUZ TRASERA */
    .glass-node {
        background: rgba(10, 16, 32, 0.65) !important;
        border: 1px solid rgba(255, 255, 255, 0.04) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border-radius: 10px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }

    .glass-node-inner {
        background: rgba(4, 7, 15, 0.75) !important;
        border: 1px solid rgba(255, 255, 255, 0.02) !important;
        border-radius: 8px !important;
    }

    /* HEADER DE SESIÓN */
    .tec-header-cyber {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .tec-brand-title {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .tec-neon-pulse-icon {
        font-size: 1.4rem;
        text-shadow: 0 0 10px #00f2ff;
    }

    .brand-text-stack h2 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        color: #fff;
        text-transform: uppercase;
    }

    .tec-sub-system {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.72rem;
        color: #516995;
    }

    /* COMPONENTE INTERACTIVO DE PERFIL DE SESIÓN */
    .tec-session-profile {
        display: flex;
        align-items: center;
        padding: 8px 18px;
        gap: 14px;
    }

    .profile-avatar-wrapper {
        position: relative;
        width: 40px;
        height: 40px;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 1px solid #00f2ff;
        background: #0c152b;
    }

    .online-indicator-dot {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 8px;
        height: 8px;
        background: #00ff66;
        border-radius: 50%;
        box-shadow: 0 0 6px #00ff66;
    }

    .profile-info-stack {
        display: flex;
        flex-direction: column;
    }

    .profile-label {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        color: #516995;
    }

    .profile-name {
        font-size: 0.98rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .profile-role {
        font-size: 0.75rem;
        color: #8fa0bd;
    }

    .profile-bahia-tag {
        border-left: 1px solid rgba(255, 255, 255, 0.08);
        padding-left: 16px;
        display: flex;
        flex-direction: column;
    }

    .profile-bahia-tag .tag-title {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        color: #516995;
    }

    .profile-bahia-tag .tag-val {
        font-size: 0.9rem;
        font-weight: 700;
    }

    /* CONTROL DE ACCIONES */
    .tec-action-control-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 20px;
    }

    .tec-utility-buttons {
        display: flex;
        gap: 10px;
    }

    .tec-btn-action {
        font-family: 'Rajdhani', sans-serif;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 9px 18px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-glow-blue {
        background: #00f2ff;
        color: #000;
        box-shadow: 0 0 10px rgba(0, 242, 255, 0.25);
    }

    .btn-glow-blue:hover {
        transform: translateY(-1px);
        box-shadow: 0 0 18px #00f2ff;
    }

    .tec-btn-secondary {
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 0.82rem;
        padding: 8px 14px;
        background: rgba(255, 255, 255, 0.01);
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-border-purple {
        border: 1px solid #9d4edd;
    }

    .btn-border-purple:hover {
        background: rgba(157, 78, 221, 0.1);
        box-shadow: 0 0 8px #9d4edd;
    }

    .btn-border-green {
        border: 1px solid #00ff66;
        color: #75db95;
    }

    .btn-border-green:hover {
        background: rgba(0, 255, 102, 0.08);
        box-shadow: 0 0 8px #00ff66;
    }

    .btn-border-red {
        border: 1px solid #ff3333;
        color: #eb7a7a;
    }

    /* GRID DE KPIS TOTALMENTE HORIZONTAL EN ESCRITORIO */
    .tec-metrics-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
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

    .tec-kpi-card-neon {
        position: relative;
        padding: 14px;
        min-height: 105px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .border-left-blue {
        border-left: 3px solid #00f2ff;
    }

    .border-left-yellow {
        border-left: 3px solid #ffca28;
    }

    .border-left-green {
        border-left: 3px solid #00ff66;
    }

    .border-left-red {
        border-left: 3px solid #ff3333;
    }

    .kpi-meta-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        color: #617594;
    }

    .kpi-body-compact h3 {
        font-size: 2.1rem;
        font-weight: 700;
        margin: 0;
        line-height: 1;
    }

    .kpi-title-text {
        font-size: 0.85rem;
        color: #a2b4cd;
        margin: 2px 0 0 0;
        font-weight: 600;
        text-transform: uppercase;
    }

    .kpi-footer-meta {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.68rem;
        color: #4a5d7e;
        margin-top: 4px;
    }

    .kpi-badge-ok {
        color: #00ff66;
    }

    .kpi-pulse-yellow {
        width: 6px;
        height: 6px;
        background: #ffca28;
        border-radius: 50%;
        box-shadow: 0 0 6px #ffca28;
    }

    /* FILA DE BANCOS DE PANEL DOBLE */
    .tec-workspace-layout {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 16px;
    }

    @media(max-width: 1200px) {
        .tec-workspace-layout {
            grid-template-columns: 1fr;
        }
    }

    .tec-workspace-panel {
        border-radius: 8px;
        padding: 16px;
        min-height: 250px;
    }

    .panel-header-cyber {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        padding-bottom: 10px;
        margin-bottom: 12px;
    }

    .panel-header-cyber h4 {
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0;
        color: #fff;
    }

    .panel-tag-status {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.68rem;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .background-yellow {
        background: rgba(255, 202, 40, 0.08);
        color: #ffca28;
    }

    .background-green {
        background: rgba(0, 255, 102, 0.08);
        color: #00ff66;
    }

    /* DISEÑO DE TABLA ADAPTADO */
    .panel-table-responsive {
        overflow-x: auto;
    }

    .tec-table-cyber {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .tec-table-cyber th {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.72rem;
        color: #516995;
        padding: 8px 6px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        text-transform: uppercase;
    }

    .tec-table-cyber td {
        padding: 9px 6px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        color: #cbd5e1;
        vertical-align: middle;
    }

    .tec-table-cyber tr:hover td {
        background: rgba(255, 255, 255, 0.01);
    }

    .text-muted-cyan {
        color: #647b9b;
        font-size: 0.76rem;
    }

    .text-truncate-tec {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.82rem;
        color: #92a4bd;
    }

    /* BADGES DE ESTADO INTERNO */
    .badge-status-tec {
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 600;
    }

    .badge-tec-repair {
        background: rgba(255, 202, 40, 0.08);
        color: #ffca28;
        border: 1px solid rgba(255, 202, 40, 0.15);
    }

    .badge-tec-diag {
        background: rgba(0, 242, 255, 0.08);
        color: #00f2ff;
        border: 1px solid rgba(0, 242, 255, 0.15);
    }

    .badge-tec-delay {
        background: rgba(255, 51, 51, 0.08);
        color: #ff3333;
        border: 1px solid rgba(255, 51, 51, 0.15);
    }

    /* ACCIONES EN FILA */
    .btn-table-tec-action {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 4px;
        padding: 3px 6px;
        cursor: pointer;
        font-size: 0.82rem;
        margin-left: 2px;
    }

    .btn-table-tec-action.hover-blue:hover {
        border-color: #00f2ff;
        background: rgba(0, 242, 255, 0.08);
    }

    .btn-table-tec-action.hover-green:hover {
        border-color: #00ff66;
        background: rgba(0, 255, 102, 0.08);
    }

    .btn-table-tec-action.hover-purple:hover {
        border-color: #9d4edd;
        background: rgba(157, 78, 221, 0.08);
    }

    /* MARCADORES E INTERFACES DE MODALES CYBER */
    .modal-cyber-frame {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(3, 5, 12, 0.8);
        backdrop-filter: blur(6px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        padding: 20px;
    }

    .modal-cyber-box {
        width: 100%;
        max-width: 650px;
        padding: 20px;
        border-radius: 10px;
        background: #080e1a;
    }

    .modal-cyber-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding-bottom: 10px;
        margin-bottom: 15px;
    }

    .modal-cyber-header h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }

    .close-cyber-btn {
        background: none;
        border: 0;
        color: #fff;
        font-size: 1.4rem;
        cursor: pointer;
    }

    .cyber-form-label {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.72rem;
        color: #516995;
        display: block;
        margin-bottom: 4px;
    }

    .cyber-select-input {
        background: #04070f;
        border: 1px solid rgba(255, 255, 255, 0.06);
        padding: 8px 12px;
        border-radius: 5px;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        outline: none;
    }

    .cyber-select-input:focus {
        border-color: #00f2ff;
    }

    .modal-cyber-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 12px;
        margin-top: 15px;
    }

    /* IDENTIFICADORES DE TEXTO GLOBAL */
    .text-neon-cyan {
        color: #00f2ff;
        text-shadow: 0 0 6px rgba(0, 242, 255, 0.2);
    }

    .text-neon-purple {
        color: #9d4edd;
    }

    .text-neon-yellow {
        color: #ffca28;
    }

    .text-neon-green {
        color: #00ff66;
    }

    .text-neon-blue {
        color: #00b4d8;
    }

    .text-neon-red {
        color: #ff3333;
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

    @keyframes alertGlow {
        100% {
            box-shadow: 0 0 12px rgba(255, 51, 51, 0.2);
            border-color: rgba(255, 51, 51, 0.3);
        }
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

    document.getElementById('btnGuardarAvanceTecnico').addEventListener('click', function () {
        const rma = document.getElementById('modalIdRma').value;
        alert(`[TALLER] Avance de reparación consolidado con éxito en la orden ${rma}.`);
        cerrarModalGestion();
    });
</script>

<script src="vistas/js/tecnicos_laboratorio.js"></script>