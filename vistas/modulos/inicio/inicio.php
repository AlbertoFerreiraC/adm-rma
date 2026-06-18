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

    <div class="cyber-meta-nodes">
      <div class="meta-node">
        <span class="node-label">OPERADOR:</span>
        <span class="node-val text-neon-cyan">👤 Administrador</span>
      </div>
      <div class="meta-node">
        <span class="node-label">PERÍODO:</span>
        <span class="node-val text-neon-purple">📅 Jun 2026</span>
      </div>
      <div class="meta-node system-status-pulse">
        <span class="pulse-dot"></span> <span class="node-val">MODULO OPERATIVO</span>
      </div>
    </div>
  </header>

  <section class="cyber-content">

    <div class="cyber-grid-super-dense">

      <div class="cyber-kpi-card glass-panel-neon border-neon-blue">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">GLOBAL_RMA</span>
          <span class="trend-indicator neon-text-green">▲ 18%</span>
        </div>
        <div class="kpi-main-data-compact">
          <h3 id="kpiTotal" class="neon-text-blue">247</h3>
          <p class="kpi-desc-title">Casos totales</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-yellow">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">COLA_LAB</span>
          <span class="status-pulse-yellow"></span>
        </div>
        <div class="kpi-main-data-compact">
          <h3 id="kpiPendiente" class="neon-text-yellow">34</h3>
          <p class="kpi-desc-title">En proceso</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-green">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">CALIDAD</span>
          <span class="trend-indicator neon-text-green">94% EFIC.</span>
        </div>
        <div class="kpi-main-data-compact">
          <h3 id="kpiConcretado" class="neon-text-green">89</h3>
          <p class="kpi-desc-title">Resueltos mes</p>
        </div>
      </div>

      <div class="cyber-kpi-card glass-panel-neon border-neon-purple">
        <div class="kpi-header-inline">
          <span class="kpi-tag-label">LATENCIA</span>
          <span class="trend-indicator neon-text-red">▼ 0.4d</span>
        </div>
        <div class="kpi-main-data-compact">
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
          <div class="mini-donut-legend">
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

    <div class="cyber-grid-three-columns mt-4">

      <div class="cyber-panel-card glass-panel-neon border-neon-blue">
        <div class="panel-cyber-header">
          <h4><span class="cyan-accent">//</span> PRODUCTOS CON MÁS FALLAS</h4>
        </div>
        <div class="panel-cyber-body-list">
          <div class="fault-item">
            <span class="fault-name">Laptop HP 15s</span>
            <div class="fault-bar-container">
              <div class="fault-bar fill-hp" style="width: 80%;"></div>
            </div>
            <span class="fault-qty font-mono">28</span>
          </div>
          <div class="fault-item">
            <span class="fault-name">Monitor ViewSonic</span>
            <div class="fault-bar-container">
              <div class="fault-bar fill-viewsonic" style="width: 65%;"></div>
            </div>
            <span class="fault-qty font-mono">22</span>
          </div>
          <div class="fault-item">
            <span class="fault-name">Laptop Lenovo V15</span>
            <div class="fault-bar-container">
              <div class="fault-bar fill-lenovo" style="width: 50%;"></div>
            </div>
            <span class="fault-qty font-mono">18</span>
          </div>
          <div class="fault-item">
            <span class="fault-name">Teclado Logitech</span>
            <div class="fault-bar-container">
              <div class="fault-bar fill-logitech" style="width: 38%;"></div>
            </div>
            <span class="fault-qty font-mono">13</span>
          </div>
          <div class="fault-item">
            <span class="fault-name">Mouse Genius</span>
            <div class="fault-bar-container">
              <div class="fault-bar fill-genius" style="width: 22%;"></div>
            </div>
            <span class="fault-qty font-mono">8</span>
          </div>
        </div>
      </div>

      <div class="cyber-panel-card glass-panel-neon border-neon-purple">
        <div class="panel-cyber-header">
          <h4><span class="purple-accent">//</span> CASOS ACTIVOS — ÚLTIMOS INGRESADOS</h4>
        </div>
        <div class="panel-cyber-body-table">
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
                <td><span class="badge-status badge-repair">Reparación</span></td>
                <td class="font-mono">2</td>
              </tr>
              <tr>
                <td class="t-cyan font-mono">RMA-0246</td>
                <td>J. Benítez</td>
                <td><span class="badge-status badge-diag">Diagnóstico</span></td>
                <td class="font-mono">1</td>
              </tr>
              <tr>
                <td class="t-cyan font-mono">RMA-0245</td>
                <td>A. Rojas</td>
                <td><span class="badge-status badge-ready">Listo</span></td>
                <td class="font-mono">4</td>
              </tr>
              <tr>
                <td class="t-cyan font-mono">RMA-0244</td>
                <td>P. Núñez</td>
                <td><span class="badge-status badge-external">Externo</span></td>
                <td class="font-mono">8</td>
              </tr>
              <tr>
                <td class="t-cyan font-mono">RMA-0243</td>
                <td>R. López</td>
                <td><span class="badge-status badge-repair">Reparación</span></td>
                <td class="font-mono">3</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="cyber-panel-card glass-panel-neon border-neon-red">
        <div class="panel-cyber-header">
          <h4><span class="text-neon-red">//</span> ALERTAS DEL SISTEMA</h4>
        </div>
        <div class="panel-cyber-body-alerts">
          <div class="alert-node-item b-left-red">
            <span class="alert-bullet dot-red"></span>
            <p>Stock crítico: <span class="text-white font-weight-bold">Pasta térmica Arctic</span> — 3 unidades</p>
          </div>
          <div class="alert-node-item b-left-red">
            <span class="alert-bullet dot-red"></span>
            <p>Stock crítico: <span class="text-white font-weight-bold">Cable HDMI 2m</span> — 2 unidades</p>
          </div>
          <div class="alert-node-item b-left-yellow">
            <span class="alert-bullet dot-yellow"></span>
            <p><span class="t-cyan font-mono">RMA-0231</span> lleva <span class="text-neon-yellow font-weight-bold">12
                días</span> sin respuesta del proveedor</p>
          </div>
          <div class="alert-node-item b-left-yellow">
            <span class="alert-bullet dot-yellow"></span>
            <p><span class="t-cyan font-mono">RMA-0238</span> lleva <span class="text-neon-yellow font-weight-bold">9
                días</span> en reparación</p>
          </div>
          <div class="alert-node-item b-left-blue">
            <span class="alert-bullet dot-blue"></span>
            <p>Tasa de garantía HP superó el <span class="text-neon-blue font-weight-bold">35%</span> este mes</p>
          </div>
        </div>
      </div>

    </div>

    <div class="row mt-4">

      <div class="col-lg-7 col-md-12 mb-4">
        <div class="cyber-panel glass-panel-neon border-neon-cyan">
          <div class="panel-cyber-header">
            <h4><span class="cyan-accent">//</span> CONCILIACIÓN DE STOCK VS RMA — FALLAS POR MARCA</h4>
          </div>
          <div class="panel-cyber-body">
            <canvas id="chartConciliacionMarcas" style="max-height: 200px; width: 100%;"></canvas>
          </div>
        </div>
      </div>

      <div class="col-lg-5 col-md-12 mb-4">
        <div class="cyber-panel glass-panel-neon border-neon-green">
          <div class="panel-cyber-header">
            <h4><span class="green-accent">//</span> ESTADÍSTICAS DE CONCILIACIÓN</h4>
          </div>
          <div class="panel-cyber-body-stats font-mono">
            <div class="stat-row"><span>Total productos vendidos:</span><span class="text-white">1,284 uds.</span></div>
            <div class="stat-row"><span>Retornaron por RMA:</span><span class="text-neon-red">247 (19.2%)</span></div>
            <div class="stat-row"><span>Mayor tasa de retorno:</span><span class="neon-text-orange">ViewSonic
                (14.6%)</span></div>
            <div class="stat-row"><span>Menor tasa de retorno:</span><span class="neon-text-green">Logitech
                (3.1%)</span></div>
            <div class="stat-row"><span>Costo estimado garantías:</span><span class="neon-text-purple">₲
                18,400,000</span></div>
            <div class="stat-row border-0 mt-2"><span class="text-neon-cyan">Tiempo resolución óptimo:</span><span
                class="badge-time">&lt; 3 días</span></div>
          </div>
        </div>
      </div>

    </div>

    <div class="cyber-panel glass-panel-neon border-neon-blue mt-2">
      <div class="panel-cyber-header">
        <h4><span class="cyan-accent">//</span> AUDITORÍA EN VIVO: CONTROL DE MAQUINAS Y ACTIVOS EN TALLER</h4>
        <span class="terminal-blink-text">● MONITOR ACTIVO</span>
      </div>
      <div class="terminal-console-box">
        <div class="term-line"><span class="t-cyan">[13:54]</span> <span class="t-green">INGRESO:</span> Laptop ASUS ROG
          (N/S: GR5921) | <span class="t-purple">Cliente:</span> Carlos Gómez | <span class="t-yellow">Asignado
            a:</span> Tec. Marcos Silva</div>
        <div class="term-line"><span class="t-cyan">[13:55]</span> <span class="t-purple">ESTADO:</span> PC Escritorio
          Gamer -> <span class="t-green">Reparado (Cambio de Fuente EVGA 600W)</span> | <span
            class="t-yellow">Técnico:</span> Alejandro R.</div>
        <div class="term-line"><span class="t-cyan">[13:56]</span> <span class="t-red">ALERTA:</span> Intel Core
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
    --cyber-bg: #060913;
    --glass-bg: rgba(10, 16, 32, 0.6);
    --glass-border: rgba(255, 255, 255, 0.04);
    --neon-blue: #00f2ff;
    --neon-yellow: #ffca28;
    --neon-green: #00ff66;
    --neon-purple: #9d4edd;
    --neon-cyan: #00b4d8;
    --neon-red: #ff3333;
    --neon-orange: #ff7b00;
    --neon-pink: #ff007f;
  }

  .dashboard-cyber-wrapper {
    background-color: var(--cyber-bg);
    min-height: 100vh;
    padding-bottom: 30px;
    color: #e2e8f0;
    font-family: 'Rajdhani', sans-serif;
  }

  /* BARRA DE NAVEGACIÓN SUPERIOR */
  .cyber-header {
    background-color: rgba(6, 11, 25, 0.9);
    border-bottom: 2px solid #101c38;
    padding: 12px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .header-brand-glitch {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .header-brand-glitch h2 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
  }

  .system-badge-live {
    font-family: 'Share Tech Mono', monospace;
    font-size: 0.65rem;
    border: 1px solid var(--neon-blue);
    color: var(--neon-blue);
    padding: 1px 6px;
    border-radius: 4px;
  }

  .cyber-meta-nodes {
    display: flex;
    gap: 20px;
    font-family: 'Share Tech Mono', monospace;
    font-size: 0.8rem;
  }

  .node-label {
    color: #506690;
  }

  .system-status-pulse {
    color: var(--neon-green) !important;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .pulse-dot {
    width: 7px;
    height: 7px;
    background-color: var(--neon-green);
    border-radius: 50%;
    box-shadow: 0 0 8px var(--neon-green);
    animation: blink-animation 1s infinite;
  }

  /* COMPACT GRID CONFIGURATION - LADO A LADO SIN REVENTAR EN VERTICAL */
  .cyber-grid-super-dense {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 12px;
    width: 100%;
    margin-top: 20px;
  }

  .cyber-grid-three-columns {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    width: 100%;
  }

  @media(max-width: 1200px) {
    .cyber-grid-super-dense {
      grid-template-columns: repeat(3, 1fr);
    }

    .cyber-grid-three-columns {
      grid-template-columns: 1fr;
    }
  }

  @media(max-width: 600px) {
    .cyber-grid-super-dense {
      grid-template-columns: 1fr;
    }
  }

  /* ESTILOS DE MÓDULOS INDIVIDUALES CARDS */
  .cyber-kpi-card {
    position: relative;
    border-radius: 8px;
    padding: 12px;
    min-height: 100px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .glass-panel-neon {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    backdrop-filter: blur(10px);
  }

  .border-neon-blue {
    border-left: 3px solid var(--neon-blue);
  }

  .border-neon-yellow {
    border-left: 3px solid var(--neon-yellow);
  }

  .border-neon-green {
    border-left: 3px solid var(--neon-green);
  }

  .border-neon-purple {
    border-left: 3px solid var(--neon-purple);
  }

  .border-neon-cyan {
    border-left: 3px solid var(--neon-cyan);
  }

  .border-neon-red {
    border-left: 3px solid var(--neon-red);
  }

  .border-neon-pink {
    border-left: 3px solid var(--neon-pink);
  }

  .border-neon-orange {
    border-left: 3px solid var(--neon-orange);
  }

  .kpi-header-inline {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'Share Tech Mono', monospace;
    font-size: 0.65rem;
    color: #617594;
  }

  .kpi-main-data-compact h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1;
  }

  .kpi-desc-title {
    font-size: 0.85rem;
    color: #a2b4cd;
    margin: 2px 0 0 0;
    font-weight: 600;
    text-transform: uppercase;
  }

  /* CONFIGURACIÓN INTERNA DE TARJETAS MINI MIX */
  .cyber-mini-donut-wrapper,
  .cyber-mini-radar-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 5px;
  }

  .mini-donut-legend {
    font-size: 0.68rem;
    font-family: 'Share Tech Mono', monospace;
    display: flex;
    flex-direction: column;
    color: #7388a9;
  }

  /* PRODUCTOS CON MÁS FALLAS ESTILOS */
  .cyber-panel-card {
    border-radius: 10px;
    padding: 15px;
    min-height: 240px;
    display: flex;
    flex-direction: column;
  }

  .panel-cyber-header h4 {
    font-size: 0.95rem;
    font-weight: 700;
    margin: 0;
    color: #fff;
    letter-spacing: 0.5px;
  }

  .panel-cyber-body-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 12px;
  }

  .fault-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    font-size: 0.88rem;
  }

  .fault-name {
    width: 110px;
    color: #cbd5e1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .fault-bar-container {
    flex: 1;
    height: 6px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 3px;
    overflow: hidden;
  }

  .fault-bar {
    height: 100%;
    border-radius: 3px;
  }

  .fill-hp {
    background-color: #1d68c5;
    box-shadow: 0 0 6px #1d68c5;
  }

  .fill-viewsonic {
    background-color: #248a48;
    box-shadow: 0 0 6px #248a48;
  }

  .fill-lenovo {
    background-color: #9d4edd;
    box-shadow: 0 0 6px #9d4edd;
  }

  .fill-logitech) {
    background-color: #ff7b00;
    box-shadow: 0 0 6px #ff7b00;
  }

  .fill-genius {
    background-color: #ffca28;
    box-shadow: 0 0 6px #ffca28;
  }

  .fault-qty {
    font-size: 0.85rem;
    font-weight: 700;
    color: #fff;
    width: 15px;
    text-align: right;
  }

  /* TABLAS COMPACTAS DE ÚLTIMOS INGRESADOS */
  .panel-cyber-body-table {
    margin-top: 8px;
    overflow-x: auto;
  }

  .cyber-mini-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    text-align: left;
  }

  .cyber-mini-table th {
    font-family: 'Share Tech Mono', monospace;
    font-size: 0.72rem;
    color: #516995;
    padding: 6px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    text-transform: uppercase;
  }

  .cyber-mini-table td {
    padding: 7px 6px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.02);
    color: #cbd5e1;
  }

  .badge-status {
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.72rem;
    font-weight: 600;
    display: inline-block;
  }

  .badge-repair {
    background: rgba(255, 202, 40, 0.1);
    color: var(--neon-yellow);
    border: 1px solid rgba(255, 202, 40, 0.2);
  }

  .badge-diag {
    background: rgba(0, 242, 255, 0.1);
    color: var(--neon-blue);
    border: 1px solid rgba(0, 242, 255, 0.2);
  }

  .badge-ready {
    background: rgba(0, 255, 102, 0.1);
    color: var(--neon-green);
    border: 1px solid rgba(0, 255, 102, 0.2);
  }

  .badge-external {
    background: rgba(255, 51, 51, 0.1);
    color: var(--neon-red);
    border: 1px solid rgba(255, 51, 51, 0.2);
  }

  /* COMPONENTE DE ALERTAS CRÍTICAS DEL TALLER */
  .panel-cyber-body-alerts {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 12px;
  }

  .alert-node-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    background: rgba(255, 255, 255, 0.01);
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 0.82rem;
    color: #a2b3cd;
  }

  .alert-node-item p {
    margin: 0;
    line-height: 1.3;
  }

  .alert-bullet {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    margin-top: 5px;
    flex-shrink: 0;
  }

  .dot-red {
    background-color: var(--neon-red);
    box-shadow: 0 0 6px var(--neon-red);
  }

  .dot-yellow {
    background-color: var(--neon-yellow);
    box-shadow: 0 0 6px var(--neon-yellow);
  }

  .dot-blue {
    background-color: var(--neon-blue);
    box-shadow: 0 0 6px var(--neon-blue);
  }

  .b-left-red {
    border-left: 2px solid rgba(255, 51, 51, 0.3);
  }

  .b-left-yellow {
    border-left: 2px solid rgba(255, 202, 40, 0.3);
  }

  .b-left-blue {
    border-left: 2px solid rgba(0, 242, 255, 0.3);
  }

  /* MÓDULO PANEL INFERIOR Y ESTADÍSTICAS */
  .cyber-panel {
    border-radius: 10px;
    padding: 18px;
    min-height: 240px;
  }

  .panel-cyber-body-stats {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 12px;
    font-size: 0.9rem;
    color: #a4b3cd;
  }

  .stat-row {
    display: flex;
    justify-content: space-between;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.03);
  }

  .badge-time {
    background: rgba(0, 255, 102, 0.1);
    color: var(--neon-green);
    padding: 1px 8px;
    border-radius: 4px;
    border: 1px solid rgba(0, 255, 102, 0.2);
  }

  /* CONSOLA TERMINAL LOGS GENERAL */
  .terminal-console-box {
    background-color: rgba(3, 5, 12, 0.85);
    border: 1px solid rgba(0, 242, 255, 0.08);
    border-radius: 6px;
    padding: 12px;
    font-family: 'Share Tech Mono', monospace;
    font-size: 0.8rem;
    min-height: 100px;
    line-height: 1.5;
  }

  .term-line {
    margin-bottom: 3px;
    color: #94a3b8;
  }

  /* ATRIBUTOS TEXTOS GLOW */
  .neon-text-blue {
    color: var(--neon-blue);
    text-shadow: 0 0 8px rgba(0, 242, 255, 0.25);
  }

  .neon-text-yellow {
    color: var(--neon-yellow);
    text-shadow: 0 0 8px rgba(255, 202, 40, 0.25);
  }

  .neon-text-green {
    color: var(--neon-green);
    text-shadow: 0 0 8px rgba(0, 255, 102, 0.25);
  }

  .neon-text-purple {
    color: var(--neon-purple);
    text-shadow: 0 0 8px rgba(157, 78, 221, 0.25);
  }

  .neon-text-cyan {
    color: var(--neon-cyan);
  }

  .text-neon-red {
    color: var(--neon-red);
    text-shadow: 0 0 8px rgba(255, 51, 51, 0.25);
  }

  .neon-text-orange {
    color: var(--neon-orange);
  }

  .neon-text-pink {
    color: var(--neon-pink);
  }

  .font-mono {
    font-family: 'Share Tech Mono', monospace;
  }

  .font-weight-bold {
    font-weight: 600;
  }

  .terminal-blink-text {
    font-family: 'Share Tech Mono', monospace;
    font-size: 0.72rem;
    color: var(--neon-red);
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
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    Chart.defaults.color = '#516995';
    Chart.defaults.font.family = "'Share Tech Mono', monospace";

    // 1. MINI DONUT EN KPI CARD (Segmentación Cobertura)
    new Chart(document.getElementById('chartSubtiposMini').getContext('2d'), {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [41, 28, 19, 12],
          backgroundColor: ['#1d68c5', '#b27d2b', '#248a48', '#c53939'],
          borderWidth: 0
        }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '72%' }
    });

    // 2. MINI RADAR EN KPI CARD (Rendimiento Laboratorio)
    new Chart(document.getElementById('chartRadarMini').getContext('2d'), {
      type: 'radar',
      data: {
        labels: ['', '', '', '', ''],
        datasets: [{
          data: [88, 91, 95, 74, 82],
          borderColor: '#00ff66',
          backgroundColor: 'rgba(0, 255, 102, 0.1)',
          borderWidth: 1,
          pointRadius: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { r: { grid: { color: 'rgba(255,255,255,0.05)' }, angleLines: { display: false }, ticks: { display: false } } }
      }
    });

    // 3. GRÁFICO DE CONCILIACIÓN DE STOCK VS RMA - FALLAS POR MARCA
    const ctxConciliacion = document.getElementById('chartConciliacionMarcas').getContext('2d');
    new Chart(ctxConciliacion, {
      type: 'bar',
      data: {
        labels: ['HP', 'ViewSonic', 'Lenovo', 'Logitech', 'Genius'],
        datasets: [
          {
            label: 'Unidades Vendidas',
            data: [450, 280, 320, 150, 84],
            backgroundColor: 'rgba(0, 242, 255, 0.15)',
            borderColor: '#00b4d8',
            borderWidth: 1,
            barThickness: 16
          },
          {
            label: 'Retornos RMA',
            data: [28, 22, 18, 13, 8],
            backgroundColor: '#ff3333',
            borderRadius: 3,
            barThickness: 16
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: '#a2b4cd', font: { size: 10 } } } },
        scales: {
          y: { grid: { color: 'rgba(255, 255, 255, 0.03)' }, ticks: { font: { size: 10 } } },
          x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
      }
    });
  });
</script>

<script src="vistas/js/inicio.js"></script>