<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<input type="hidden" name="rol" id="rol" value="<?php echo $_SESSION['id_rol'] ?? ''; ?>">

<div class="content-wrapper dashboard-cyber-wrapper">

  <header class="cyber-header">
    <div class="header-brand-glitch">
      <span class="cyber-logo-icon">⚡</span>
      <h2 class="glitch-text">Microexpress — Centro de Control RMA</h2>
      <span class="system-badge-live">LAB_CORE_v5.0</span>
    </div>

    <div class="cyber-meta-nodes font-mono">
      <div class="meta-node">
        <span class="node-label">OPERADOR:</span>
        <span class="node-val text-neon-cyan">👤 Administrador</span>
      </div>
      <div class="meta-node">
        <span class="node-label">PERÍODO:</span>
        <span class="node-val text-neon-purple">📅 Ago 2026</span>
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
          <span class="trend-indicator neon-text-green">▲ 18%</span>
        </div>
        <div class="kpi-main-data-compact font-mono">
          <h3 id="kpiTotal" class="neon-text-blue">247</h3>
          <p class="kpi-desc-title">Casos totales</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-yellow">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">COLA_LAB</span>
          <span class="pulse-dot-yellow"></span>
        </div>
        <div class="kpi-main-data-compact font-mono">
          <h3 id="kpiPendiente" class="neon-text-yellow">34</h3>
          <p class="kpi-desc-title">En proceso</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-green">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">CALIDAD</span>
          <span class="trend-indicator neon-text-green">94% EFIC.</span>
        </div>
        <div class="kpi-main-data-compact font-mono">
          <h3 id="kpiConcretado" class="neon-text-green">89</h3>
          <p class="kpi-desc-title">Resueltos mes</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-purple">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">LATENCIA</span>
          <span class="trend-indicator neon-text-red">▼ 0.4d</span>
        </div>
        <div class="kpi-main-data-compact font-mono">
          <h3 class="neon-text-purple">3.2</h3>
          <p class="kpi-desc-title">Días promedio</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-pink">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">COBERTURA</span>
          <span class="neon-text-pink">SUBTIPOS</span>
        </div>
        <div class="cyber-mini-donut-wrapper">
          <canvas id="chartSubtiposMini" style="max-height: 55px; max-width: 55px;"></canvas>
          <div class="mini-donut-legend font-mono">
            <span>Gar: 41%</span>
            <span>Cond: 28%</span>
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

      <!-- PRODUCTOS CON MÁS FALLAS -->
      <div class="cyber-panel-card glass-panel-neon border-neon-blue">
        <div class="panel-cyber-header">
          <h4><span class="cyan-accent">//</span> PRODUCTOS CON MÁS FALLAS</h4>
        </div>
        <div class="panel-cyber-body-list mt-3">
          <div class="fault-item font-mono">
            <span class="fault-name">Laptop HP 15s</span>
            <div class="fault-bar-container">
              <div class="fault-bar fill-hp" style="width: 80%;"></div>
            </div>
            <span class="fault-qty">28</span>
          </div>
          <div class="fault-item font-mono">
            <span class="fault-name">Monitor ViewSonic</span>
            <div class="fault-bar-container">
              <div class="fault-bar fill-viewsonic" style="width: 65%;"></div>
            </div>
            <span class="fault-qty">22</span>
          </div>
          <div class="fault-item font-mono">
            <span class="fault-name">Laptop Lenovo V15</span>
            <div class="fault-bar-container">
              <div class="fault-bar fill-lenovo" style="width: 50%;"></div>
            </div>
            <span class="fault-qty">18</span>
          </div>
          <div class="fault-item font-mono">
            <span class="fault-name">Teclado Logitech</span>
            <div class="fault-bar-container">
              <div class="fault-bar fill-logitech" style="width: 38%;"></div>
            </div>
            <span class="fault-qty">13</span>
          </div>
          <div class="fault-item font-mono">
            <span class="fault-name">Mouse Genius</span>
            <div class="fault-bar-container">
              <div class="fault-bar fill-genius" style="width: 22%;"></div>
            </div>
            <span class="fault-qty">8</span>
          </div>
        </div>
      </div>

      <!-- ÚLTIMOS INGRESADOS -->
      <div class="cyber-panel-card glass-panel-neon border-neon-purple">
        <div class="panel-cyber-header">
          <h4><span class="purple-accent">//</span> CASOS ACTIVOS — ÚLTIMOS INGRESADOS</h4>
        </div>
        <div class="panel-cyber-body-table mt-3 font-mono">
          <table class="cyber-mini-table">
            <thead>
              <tr>
                <th>Caso</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Días</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="t-cyan font-mono">RMA-0247</td>
                <td>M. García</td>
                <td><span class="badge-status-cyber status-default">Reparación</span></td>
                <td class="font-mono">2</td>
              </tr>
              <tr>
                <td class="t-cyan font-mono">RMA-0246</td>
                <td>J. Benítez</td>
                <td><span class="badge-status-cyber status-1">Diagnóstico</span></td>
                <td class="font-mono">1</td>
              </tr>
              <tr>
                <td class="t-cyan font-mono">RMA-0245</td>
                <td>A. Rojas</td>
                <td><span class="badge-status-cyber status-ready">Listo</span></td>
                <td class="font-mono">4</td>
              </tr>
              <tr>
                <td class="t-cyan font-mono">RMA-0244</td>
                <td>P. Núñez</td>
                <td><span class="badge-status-cyber status-external">Externo</span></td>
                <td class="font-mono">8</td>
              </tr>
              <tr>
                <td class="t-cyan font-mono">RMA-0243</td>
                <td>R. López</td>
                <td><span class="badge-status-cyber status-default">Reparación</span></td>
                <td class="font-mono">3</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ALERTAS DEL SISTEMA -->
      <div class="cyber-panel-card glass-panel-neon border-neon-red">
        <div class="panel-cyber-header">
          <h4><span class="text-neon-red">//</span> ALERTAS DEL SISTEMA</h4>
        </div>
        <div class="panel-cyber-body-alerts mt-3 font-mono">
          <div class="alert-node-item b-left-red">
            <span class="alert-bullet dot-red"></span>
            <p>Stock crítico: <span class="text-dark-bold">Pasta térmica Arctic</span> — 3 unidades</p>
          </div>
          <div class="alert-node-item b-left-red">
            <span class="alert-bullet dot-red"></span>
            <p>Stock crítico: <span class="text-dark-bold">Cable HDMI 2m</span> — 2 unidades</p>
          </div>
          <div class="alert-node-item b-left-yellow">
            <span class="alert-bullet dot-yellow"></span>
            <p><span class="t-cyan">RMA-0231</span> lleva <span class="text-yellow font-weight-bold">12 días</span> sin
              respuesta del proveedor</p>
          </div>
          <div class="alert-node-item b-left-yellow">
            <span class="alert-bullet dot-yellow"></span>
            <p><span class="t-cyan">RMA-0238</span> lleva <span class="text-yellow font-weight-bold">9 días</span> en
              reparación</p>
          </div>
          <div class="alert-node-item b-left-blue">
            <span class="alert-bullet dot-blue"></span>
            <p>Tasa de garantía HP superó el <span class="t-cyan font-weight-bold">35%</span> este mes</p>
          </div>
        </div>
      </div>

    </div>

    <!-- FILA INFERIOR: CONCILIACIÓN DE MARCAS Y ESTADÍSTICAS -->
    <div class="cyber-grid-two-unequal mt-4">

      <div class="cyber-panel-card glass-panel-neon border-neon-cyan">
        <div class="panel-cyber-header">
          <h4><span class="cyan-accent">//</span> CONCILIACIÓN DE STOCK VS RMA — FALLAS POR MARCA</h4>
        </div>
        <div class="panel-cyber-body mt-3">
          <canvas id="chartConciliacionMarcas" style="max-height: 200px; width: 100%;"></canvas>
        </div>
      </div>

      <div class="cyber-panel-card glass-panel-neon border-neon-green">
        <div class="panel-cyber-header">
          <h4><span class="green-accent">//</span> ESTADÍSTICAS DE CONCILIACIÓN</h4>
        </div>
        <div class="panel-cyber-body-stats font-mono mt-3">
          <div class="stat-row"><span>Total productos vendidos:</span><span class="text-dark-bold">1,284 uds.</span>
          </div>
          <div class="stat-row"><span>Retornaron por RMA:</span><span class="text-neon-red">247 (19.2%)</span></div>
          <div class="stat-row"><span>Mayor tasa de retorno:</span><span class="text-yellow">ViewSonic (14.6%)</span>
          </div>
          <div class="stat-row"><span>Menor tasa de retorno:</span><span class="green-accent">Logitech (3.1%)</span>
          </div>
          <div class="stat-row"><span>Costo estimado garantías:</span><span class="purple-accent">₲ 18,400,000</span>
          </div>
          <div class="stat-row border-0 mt-2">
            <span class="t-cyan">Tiempo resolución óptimo:</span>
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
        <h4><span class="cyan-accent">//</span> AUDITORÍA EN VIVO: CONTROL DE MÁQUINAS Y ACTIVOS EN TALLER</h4>
        <span class="terminal-blink-text">● MONITOR ACTIVO</span>
      </div>
      <div class="terminal-console-box mt-3 font-mono">
        <div class="term-line"><span class="t-cyan">[13:54]</span> <span class="green-accent">INGRESO:</span> Laptop
          ASUS ROG (N/S: GR5921) | <span class="purple-accent">Cliente:</span> Carlos Gómez | <span
            class="text-yellow">Asignado a:</span> Tec. Marcos Silva</div>
        <div class="term-line"><span class="t-cyan">[13:55]</span> <span class="purple-accent">ESTADO:</span> PC
          Escritorio Gamer -> <span class="green-accent">Reparado (Cambio de Fuente EVGA 600W)</span> | <span
            class="text-yellow">Técnico:</span> Alejandro R.</div>
        <div class="term-line"><span class="t-cyan">[13:56]</span> <span class="text-neon-red">ALERTA:</span> Intel Core
          i7-14700K solicitado para reemplazo físico sin stock en depósito central.</div>
        <div class="term-line blink-line"><span class="t-cyan">[>&nbsp;]</span> Escaneando nuevas bahías de trabajo de
          hardware... Esperando token de validación...</div>
      </div>
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
    width: 120px;
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
  }

  .fill-hp {
    background-color: var(--neon-cyan-dark);
  }

  .fill-viewsonic {
    background-color: var(--neon-green-dark);
  }

  .fill-lenovo {
    background-color: var(--neon-purple-dark);
  }

  .fill-logitech {
    background-color: var(--neon-yellow-dark);
  }

  .fill-genius {
    background-color: var(--neon-pink-dark);
  }

  .fault-qty {
    font-size: 0.85rem;
    font-weight: bold;
    color: var(--text-cyber-dark);
    width: 20px;
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

  .status-ready {
    background: rgba(21, 128, 61, 0.08);
    color: var(--neon-green-dark);
    border-color: rgba(21, 128, 61, 0.3);
  }

  .status-external {
    background: rgba(220, 38, 38, 0.08);
    color: var(--neon-red-dark);
    border-color: rgba(220, 38, 38, 0.3);
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

  /* ATRIBUTOS Y COLORES NEÓN */
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

  .blink-line {
    animation: blink-animation 1.5s infinite;
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

<script>
  document.addEventListener("DOMContentLoaded", function () {
    Chart.defaults.color = '#475569';
    Chart.defaults.font.family = "'Share Tech Mono', monospace";

    // 1. MINI DONUT EN KPI CARD (Segmentación Cobertura)
    const ctxSubtipos = document.getElementById('chartSubtiposMini');
    if (ctxSubtipos) {
      new Chart(ctxSubtipos.getContext('2d'), {
        type: 'doughnut',
        data: {
          datasets: [{
            data: [41, 28, 19, 12],
            backgroundColor: ['#0284c7', '#d97706', '#15803d', '#dc2626'],
            borderWidth: 0
          }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '72%' }
      });
    }

    // 2. MINI RADAR EN KPI CARD (Rendimiento Laboratorio)
    const ctxRadar = document.getElementById('chartRadarMini');
    if (ctxRadar) {
      new Chart(ctxRadar.getContext('2d'), {
        type: 'radar',
        data: {
          labels: ['', '', '', '', ''],
          datasets: [{
            data: [88, 91, 95, 74, 82],
            borderColor: '#15803d',
            backgroundColor: 'rgba(21, 128, 61, 0.1)',
            borderWidth: 1.5,
            pointRadius: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: { r: { grid: { color: 'rgba(15, 23, 42, 0.1)' }, angleLines: { display: false }, ticks: { display: false } } }
        }
      });
    }

    // 3. GRÁFICO DE CONCILIACIÓN DE STOCK VS RMA - FALLAS POR MARCA
    const ctxConciliacion = document.getElementById('chartConciliacionMarcas');
    if (ctxConciliacion) {
      new Chart(ctxConciliacion.getContext('2d'), {
        type: 'bar',
        data: {
          labels: ['HP', 'ViewSonic', 'Lenovo', 'Logitech', 'Genius'],
          datasets: [
            {
              label: 'Unidades Vendidas',
              data: [450, 280, 320, 150, 84],
              backgroundColor: 'rgba(2, 132, 199, 0.2)',
              borderColor: '#0284c7',
              borderWidth: 1,
              barThickness: 16
            },
            {
              label: 'Retornos RMA',
              data: [28, 22, 18, 13, 8],
              backgroundColor: '#dc2626',
              borderRadius: 3,
              barThickness: 16
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { labels: { color: '#475569', font: { size: 10 } } } },
          scales: {
            y: { grid: { color: '#cbd5e1' }, ticks: { font: { size: 10 } } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
          }
        }
      });
    }
  });
</script>

<script src="vistas/js/inicio.js"></script>