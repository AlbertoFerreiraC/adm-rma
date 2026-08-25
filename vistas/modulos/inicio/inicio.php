<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<input type="hidden" name="rol" id="rol" value="<?php echo $_SESSION['id_rol'] ?? ''; ?>">

<div class="content-wrapper dashboard-cyber-wrapper style-relative">

  <!-- OVERLAY LOADER CYBERPUNK EN VIVO -->
  <div id="cyberLoaderDashboard" class="cyber-loader-overlay">
    <div class="loader-content-hud">
      <div class="cyber-spinner-ring border-neon-cyan"></div>
      <span class="hud-loading-text font-mono">[04] BI_ANALYTICS // INICIALIZANDO_DASHBOARD...</span>
      <div class="hud-progress-bar">
        <div class="hud-progress-fill"></div>
      </div>
    </div>
  </div>

  <header class="cyber-header">
    <div class="header-brand-glitch">
      <span class="cyber-logo-icon">⚡</span>
      <h2 class="glitch-text">Microexpress — Centro de Control RMA</h2>
      <span class="system-badge-live">LAB_CORE_v5.0</span>
    </div>

    <div class="cyber-meta-nodes font-mono">
      <div class="meta-node">
        <span class="node-label">OPERADOR:</span>
        <span class="node-val text-neon-cyan">👤 <?php echo $_SESSION['nombre'] ?? 'Administrador'; ?></span>
      </div>
      <div class="meta-node">
        <span class="node-label">PERÍODO:</span>
        <span class="node-val text-neon-purple">📅 <?php echo date('M Y'); ?></span>
      </div>
      <div class="meta-node system-status-pulse">
        <span class="pulse-dot"></span> <span class="node-val">MÓDULO OPERATIVO</span>
      </div>
    </div>
  </header>

  <section class="cyber-content">

    <!-- FILA SUPERIOR: TARJETAS KPI DENSAS -->
    <div class="cyber-grid-super-dense">

      <div class="cyber-kpi-card glass-panel-neon border-neon-blue">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">GLOBAL_RMA</span>
          <span class="trend-indicator neon-text-green" id="kpiTotalTrend">--</span>
        </div>
        <div class="kpi-main-data-compact font-mono">
          <h3 id="kpiTotal" class="neon-text-blue">0</h3>
          <p class="kpi-desc-title">Casos totales</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-yellow">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">COLA_LAB</span>
          <span class="pulse-dot-yellow"></span>
        </div>
        <div class="kpi-main-data-compact font-mono">
          <h3 id="kpiPendiente" class="neon-text-yellow">0</h3>
          <p class="kpi-desc-title">En proceso</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-green">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">CALIDAD</span>
          <span class="trend-indicator neon-text-green" id="kpiEficPct">--</span>
        </div>
        <div class="kpi-main-data-compact font-mono">
          <h3 id="kpiConcretado" class="neon-text-green">0</h3>
          <p class="kpi-desc-title">Resueltos mes</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-purple">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">LATENCIA</span>
          <span class="trend-indicator neon-text-red">SLA_MES</span>
        </div>
        <div class="kpi-main-data-compact font-mono">
          <h3 id="kpiDiasPromedio" class="neon-text-purple">0.0</h3>
          <p class="kpi-desc-title">Días promedio</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-pink">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">COBERTURA</span>
          <span class="neon-text-pink">TIPOS_CASO</span>
        </div>
        <div class="cyber-mini-donut-wrapper">
          <canvas id="chartSubtiposMini" style="max-height: 55px; max-width: 55px;"></canvas>
          <div class="mini-donut-legend font-mono" id="legendTiposCaso">
            <span>Cargando...</span>
          </div>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-cyan">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">RENDIMIENTO</span>
          <span class="neon-text-cyan">LAB_PROMEDIO</span>
        </div>
        <div class="cyber-mini-radar-wrapper">
          <canvas id="chartRadarMini" style="max-height: 60px; max-width: 60px;"></canvas>
        </div>
      </div>

    </div>

    <!-- FILA CENTRAL: LISTAS DE FALLAS, ULTIMOS CASOS Y ALERTAS -->
    <div class="cyber-grid-three-columns mt-4">

      <div class="cyber-panel-card glass-panel-neon border-neon-blue">
        <div class="panel-cyber-header">
          <h4><span class="cyan-accent">//</span> TOP MODELOS Y PRODUCTOS CON FALLAS</h4>
        </div>
        <div class="panel-cyber-body-list mt-3" id="contenedorTopFallas"></div>
      </div>

      <div class="cyber-panel-card glass-panel-neon border-neon-purple">
        <div class="panel-cyber-header">
          <h4><span class="purple-accent">//</span> CASOS ACTIVOS — ÚLTIMOS INGRESADOS</h4>
        </div>
        <div class="panel-cyber-body-table mt-3 font-mono">
          <table class="cyber-mini-table" id="tablaUltimosCasos">
            <thead>
              <tr>
                <th>Caso</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Días</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

      <div class="cyber-panel-card glass-panel-neon border-neon-red">
        <div class="panel-cyber-header">
          <h4><span class="text-neon-red">//</span> ALERTAS DEL SISTEMA EN VIVO</h4>
        </div>
        <div class="panel-cyber-body-alerts mt-3 font-mono" id="contenedorAlertasSistema"></div>
      </div>

    </div>

    <!-- FILA INFERIOR: CONCILIACIÓN DE MARCAS Y ESTADÍSTICAS -->
    <div class="cyber-grid-two-unequal mt-4">

      <div class="cyber-panel-card glass-panel-neon border-neon-cyan">
        <div class="panel-cyber-header">
          <h4><span class="cyan-accent">//</span> FALLAS Y CASOS RMA POR MARCA</h4>
        </div>
        <div class="panel-cyber-body mt-3">
          <canvas id="chartConciliacionMarcas" style="max-height: 200px; width: 100%;"></canvas>
        </div>
      </div>

      <div class="cyber-panel-card glass-panel-neon border-neon-green">
        <div class="panel-cyber-header">
          <h4><span class="green-accent">//</span> ESTADÍSTICAS FINANCIERAS Y SLA</h4>
        </div>
        <div class="panel-cyber-body-stats font-mono mt-3">
          <div class="stat-row"><span>Total Casos Registrados:</span><span class="text-dark-bold"
              id="statTotalCasos">0</span></div>
          <div class="stat-row"><span>Casos Cerrados/Resueltos:</span><span class="text-neon-green"
              id="statCasosCerrados">0</span></div>
          <div class="stat-row"><span>Marca con Mayor Incidencia:</span><span class="text-yellow"
              id="statMarcaMayor">--</span></div>
          <div class="stat-row"><span>Tasa Global de Eficiencia:</span><span class="green-accent"
              id="statTasaEfectividad">0%</span></div>
          <div class="stat-row"><span>Inversión/Costo en Repuestos:</span><span class="purple-accent"
              id="statCostoInsumos">₲ 0</span></div>
          <div class="stat-row border-0 mt-2">
            <span class="t-cyan">SLA Meta de Resolución:</span>
            <span class="system-badge-live"
              style="color:var(--neon-green-dark); border-color:var(--neon-green-dark); background:rgba(21,128,61,0.08);">&lt;
              3 días</span>
          </div>
        </div>
      </div>

    </div>

    <!-- TERMINAL DE AUDITORÍA EN VIVO -->
    <div class="cyber-panel-card glass-panel-neon border-neon-blue mt-4">
      <div class="panel-cyber-header flex-header-toolbar">
        <h4><span class="cyan-accent">//</span> AUDITORÍA EN VIVO: HISTORIAL RECIENTE Y ACTIVIDAD EN TALLER</h4>
        <span class="terminal-blink-text">● MONITOR ACTIVO</span>
      </div>
      <div class="terminal-console-box mt-3 font-mono" id="consoleLiveTerminal"></div>
    </div>

  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    --neon-pink-dark: #be185d;
  }

  .style-relative {
    position: relative;
  }

  /* OVERLAY LOADER CYBERPUNK HUD */
  .cyber-loader-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(240, 244, 248, 0.92);
    backdrop-filter: blur(6px);
    z-index: 2000;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: opacity 0.3s ease, visibility 0.3s ease;
  }

  .loader-content-hud {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
  }

  .cyber-spinner-ring {
    width: 50px;
    height: 50px;
    border: 4px solid #cbd5e1;
    border-top: 4px solid var(--neon-cyan-dark);
    border-radius: 50%;
    animation: spinHUD 0.8s linear infinite;
  }

  @keyframes spinHUD {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  .hud-loading-text {
    font-size: 0.85rem;
    color: var(--neon-cyan-dark);
    font-weight: bold;
    letter-spacing: 1px;
  }

  .hud-progress-bar {
    width: 220px;
    height: 4px;
    background: #cbd5e1;
    border-radius: 2px;
    overflow: hidden;
  }

  .hud-progress-fill {
    width: 100%;
    height: 100%;
    background: var(--neon-cyan-dark);
    animation: loadingFill 1.2s ease-in-out infinite;
  }

  @keyframes loadingFill {
    0% {
      transform: translateX(-100%);
    }

    100% {
      transform: translateX(100%);
    }
  }

  /* ESTRUCTURA GENERAL DE LA PÁGINA */
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

  .cyber-meta-nodes {
    display: flex;
    gap: 20px;
    font-size: 0.8rem;
    flex-wrap: wrap;
  }

  .node-label {
    color: var(--text-cyber-muted);
    font-weight: bold;
  }

  .system-status-pulse {
    color: var(--neon-green-dark) !important;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: bold;
  }

  .pulse-dot {
    width: 8px;
    height: 8px;
    background-color: var(--neon-green-dark);
    border-radius: 50%;
    animation: blink-animation 1s infinite;
  }

  .pulse-dot-yellow {
    width: 8px;
    height: 8px;
    background-color: var(--neon-yellow-dark);
    border-radius: 50%;
    animation: blink-animation 1s infinite;
  }

  /* GRIDS DEL DASHBOARD */
  .cyber-grid-super-dense {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 15px;
    width: 100%;
    margin-top: 20px;
  }

  .cyber-grid-three-columns {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    width: 100%;
  }

  .cyber-grid-two-unequal {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 20px;
    width: 100%;
  }

  @media(max-width: 1200px) {
    .cyber-grid-super-dense {
      grid-template-columns: repeat(3, 1fr);
    }

    .cyber-grid-three-columns {
      grid-template-columns: 1fr;
    }

    .cyber-grid-two-unequal {
      grid-template-columns: 1fr;
    }
  }

  @media(max-width: 600px) {
    .cyber-grid-super-dense {
      grid-template-columns: 1fr;
    }
  }

  /* TARJETAS Y PANELES */
  .cyber-panel-card {
    background: var(--card-cyber-light);
    border: 1px solid var(--border-cyber-subtle);
    border-radius: 8px;
    padding: 18px;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
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

  .border-neon-blue {
    border-left: 4px solid var(--neon-cyan-dark);
  }

  .border-neon-yellow {
    border-left: 4px solid var(--neon-yellow-dark);
  }

  .border-neon-green {
    border-left: 4px solid var(--neon-green-dark);
  }

  .border-neon-purple {
    border-left: 4px solid var(--neon-purple-dark);
  }

  .border-neon-cyan {
    border-left: 4px solid var(--neon-cyan-glow);
  }

  .border-neon-red {
    border-left: 4px solid var(--neon-red-dark);
  }

  .border-neon-pink {
    border-left: 4px solid var(--neon-pink-dark);
  }

  .kpi-header-inline {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Share Tech Mono', monospace;
    font-size: 0.7rem;
    color: var(--text-cyber-muted);
  }

  .kpi-main-data-compact h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 4px 0 0 0;
    line-height: 1;
  }

  .kpi-desc-title {
    font-size: 0.75rem;
    color: var(--text-cyber-muted);
    margin: 4px 0 0 0;
    font-weight: bold;
    text-transform: uppercase;
  }

  .cyber-mini-donut-wrapper,
  .cyber-mini-radar-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 5px;
  }

  .mini-donut-legend {
    font-size: 0.7rem;
    display: flex;
    flex-direction: column;
    color: var(--text-cyber-muted);
  }

  .flex-header-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
  }

  /* LISTA DE FALLAS */
  .panel-cyber-body-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .fault-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    font-size: 0.85rem;
  }

  .fault-name {
    width: 130px;
    color: var(--text-cyber-dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: bold;
  }

  .fault-bar-container {
    flex: 1;
    height: 8px;
    background: #f1f5f9;
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid var(--border-cyber-subtle);
  }

  .fault-bar {
    height: 100%;
    border-radius: 4px;
    background-color: var(--neon-cyan-dark);
  }

  .fault-qty {
    font-size: 0.85rem;
    font-weight: bold;
    color: var(--text-cyber-dark);
    width: 25px;
    text-align: right;
  }

  /* TABLAS DE CASOS ACTIVOS */
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
    padding: 8px 6px;
    border-bottom: 2px solid var(--border-cyber-subtle);
    text-transform: uppercase;
    text-align: center;
    background: #f8fafc;
  }

  .cyber-mini-table td {
    padding: 8px 6px;
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

  /* ALERTAS DEL SISTEMA */
  .panel-cyber-body-alerts {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .alert-node-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: #f8fafc;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 0.82rem;
    color: var(--text-cyber-dark);
    border: 1px solid var(--border-cyber-subtle);
  }

  .alert-node-item p {
    margin: 0;
    line-height: 1.3;
  }

  .alert-bullet {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-top: 4px;
    flex-shrink: 0;
  }

  .dot-red {
    background-color: var(--neon-red-dark);
  }

  .dot-yellow {
    background-color: var(--neon-yellow-dark);
  }

  .dot-blue {
    background-color: var(--neon-cyan-dark);
  }

  .b-left-red {
    border-left: 3px solid var(--neon-red-dark);
  }

  .b-left-yellow {
    border-left: 3px solid var(--neon-yellow-dark);
  }

  .b-left-blue {
    border-left: 3px solid var(--neon-cyan-dark);
  }

  /* ESTADÍSTICAS */
  .panel-cyber-body-stats {
    display: flex;
    flex-direction: column;
    gap: 10px;
    font-size: 0.88rem;
  }

  .stat-row {
    display: flex;
    justify-content: space-between;
    padding-bottom: 8px;
    border-bottom: 1px solid #f1f5f9;
    color: var(--text-cyber-muted);
  }

  /* TERMINAL CONSOLA */
  .terminal-console-box {
    background-color: #0f172a;
    border: 1px solid var(--border-cyber-subtle);
    border-radius: 6px;
    padding: 14px;
    font-size: 0.82rem;
    min-height: 100px;
    line-height: 1.6;
    color: #e2e8f0;
  }

  .term-line {
    margin-bottom: 4px;
  }

  .cyan-accent,
  .t-cyan,
  .neon-text-blue,
  .neon-text-cyan {
    color: var(--neon-cyan-dark);
    font-weight: bold;
  }

  .purple-accent,
  .neon-text-purple {
    color: var(--neon-purple-dark);
    font-weight: bold;
  }

  .green-accent,
  .neon-text-green {
    color: var(--neon-green-dark);
    font-weight: bold;
  }

  .text-yellow,
  .neon-text-yellow,
  .neon-text-orange {
    color: var(--neon-yellow-dark);
    font-weight: bold;
  }

  .text-neon-red {
    color: var(--neon-red-dark);
    font-weight: bold;
  }

  .neon-text-pink {
    color: var(--neon-pink-dark);
    font-weight: bold;
  }

  .text-dark-bold {
    color: var(--text-cyber-dark);
    font-weight: bold;
  }

  .font-mono {
    font-family: 'Share Tech Mono', monospace;
  }

  .terminal-blink-text {
    font-family: 'Share Tech Mono', monospace;
    font-size: 0.72rem;
    color: var(--neon-green-dark);
    font-weight: bold;
    animation: blink-animation 1s infinite;
  }

  @keyframes blink-animation {
    to {
      opacity: 0.3;
    }
  }

  .mt-2 {
    margin-top: 0.5rem;
  }

  .mt-3 {
    margin-top: 1rem;
  }

  .mt-4 {
    margin-top: 1.5rem;
  }
</style>

<script src="vistas/js/bi/dashboard.js"></script>