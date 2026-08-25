<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper style-relative">

    <!-- OVERLAY LOADER CYBERPUNK EN VIVO -->
    <div id="cyberLoaderSla" class="cyber-loader-overlay">
        <div class="loader-content-hud">
            <div class="cyber-spinner-ring border-neon-purple"></div>
            <span class="hud-loading-text font-mono">[04] BI_SLA // INICIALIZANDO_MÉTRICAS...</span>
            <div class="hud-progress-bar">
                <div class="hud-progress-fill"></div>
            </div>
        </div>
    </div>

    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">⏱️</span>
            <h2>[04] BI_SLA // TIEMPOS DE CICLO Y CONTROL DE PROCESOS</h2>
            <span class="system-badge-live-purple">SLA_MONITOR_ACTIVE</span>
        </div>
    </header>

    <section class="cyber-content mt-4">

        <!-- TARJETAS KPIS SLA -->
        <div class="tec-metrics-row mb-4">
            <div class="cyber-kpi-card glass-panel-neon border-neon-purple">
                <div class="kpi-header-inline font-mono">
                    <span class="t-purple">PRIMERA // RESPUESTA</span>
                    <span>MTTR_INIT</span>
                </div>
                <div class="kpi-body-compact font-mono">
                    <h3 class="t-purple" id="kpiPrimeraRespuesta">0.0h</h3>
                    <p class="kpi-title-text">Tiempo promedio primer diagnóstico</p>
                </div>
                <div class="kpi-footer-meta font-mono">Desde ingreso hasta asignación</div>
            </div>

            <div class="cyber-kpi-card glass-panel-neon border-neon-yellow">
                <div class="kpi-header-inline font-mono">
                    <span class="text-neon-yellow">RETROCESOS // RE-WORK</span>
                    <span class="pulse-dot-yellow"></span>
                </div>
                <div class="kpi-body-compact font-mono">
                    <h3 class="text-neon-yellow" id="kpiIndiceRetrabajo">0</h3>
                    <p class="kpi-title-text">Casos con retrabajo registrado</p>
                </div>
                <div class="kpi-footer-meta font-mono">Reingresos a estados previos</div>
            </div>

            <div class="cyber-kpi-card glass-panel-neon border-neon-blue">
                <div class="kpi-header-inline font-mono">
                    <span class="t-cyan">SLA // CUMPLIMIENTO</span>
                    <span>META &lt; 3d</span>
                </div>
                <div class="kpi-body-compact font-mono">
                    <h3 class="t-cyan" id="kpiSlaCumplimiento">0%</h3>
                    <p class="kpi-title-text">Casos cerrados dentro de norma</p>
                </div>
                <div class="kpi-footer-meta font-mono">En tiempo óptimo de respuesta</div>
            </div>

            <div class="cyber-kpi-card glass-panel-neon border-neon-red">
                <div class="kpi-header-inline font-mono">
                    <span class="text-neon-red">CUELLO // BOTELLA</span>
                    <span class="blink-anim">🚨 BOTTLENECK</span>
                </div>
                <div class="kpi-body-compact font-mono">
                    <h3 class="text-neon-red" id="kpiEstadoCritico">--</h3>
                    <p class="kpi-title-text">Estado con mayor latencia</p>
                </div>
                <div class="kpi-footer-meta font-mono">Requiere atención en taller</div>
            </div>
        </div>

        <!-- GRÁFICOS SLA Y ESTANCIA -->
        <div class="cyber-grid-two-unequal mb-4">
            <div class="cyber-panel-card glass-panel-neon border-neon-purple">
                <div class="panel-cyber-header">
                    <h4><span class="purple-accent">//</span> TIEMPO PROMEDIO DE PERMANENCIA POR ESTADO (DÍAS)</h4>
                </div>
                <div class="panel-cyber-body mt-3">
                    <canvas id="chartTiempoEstados" style="max-height: 220px; width: 100%;"></canvas>
                </div>
            </div>

            <div class="cyber-panel-card glass-panel-neon border-neon-blue">
                <div class="panel-cyber-header">
                    <h4><span class="cyan-accent">//</span> CUMPLIMIENTO DE SLA DE RESOLUCIÓN</h4>
                </div>
                <div class="panel-cyber-body mt-3" style="display:flex; justify-content:center; align-items:center;">
                    <canvas id="chartDonutSLA" style="max-height: 200px; width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- DETALLE DE CASOS CON TIEMPO DE LATENCIA -->
        <div class="cyber-panel-card glass-panel-neon border-neon-purple">
            <div class="panel-cyber-header flex-header-toolbar">
                <h4><span class="purple-accent">//</span> AUDITORÍA DE TIEMPOS DE CICLO POR CASO RMA</h4>
                <div class="cyber-search-box">
                    <i class="fa fa-search search-icon-hud"></i>
                    <input type="text" id="buscarSlaCaso" class="cyber-input-search"
                        placeholder="🔍 Buscar por N° Caso, Cliente o Estado...">
                </div>
            </div>

            <div class="panel-cyber-body-table mt-3 font-mono">
                <table class="cyber-mini-table" id="tablaSlaCasos">
                    <thead>
                        <tr>
                            <th>N° Caso</th>
                            <th>Cliente</th>
                            <th>Estado Actual</th>
                            <th>Fecha Ingreso</th>
                            <th>1ª Respuesta</th>
                            <th>Días en Estado</th>
                            <th>Días Totales</th>
                            <th>Retrabajos</th>
                            <th>SLA Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Carga dinámica vía JS -->
                    </tbody>
                </table>
            </div>
        </div>

    </section>
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
        --neon-green-dark: #15803d;
        --neon-red-dark: #dc2626;
        --neon-purple-dark: #7e22ce;
        --neon-yellow-dark: #d97706;
    }

    .style-relative {
        position: relative;
    }

    /* ESTILOS DEL LOADER CYBERPUNK OVERLAY */
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
        transition: opacity 0.4s ease, visibility 0.4s ease;
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
        border-top: 4px solid var(--neon-purple-dark);
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
        color: var(--neon-purple-dark);
        font-weight: bold;
        letter-spacing: 1px;
    }

    .hud-progress-bar {
        width: 200px;
        height: 4px;
        background: #cbd5e1;
        border-radius: 2px;
        overflow: hidden;
    }

    .hud-progress-fill {
        width: 100%;
        height: 100%;
        background: var(--neon-purple-dark);
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

    /* ESTILOS DE ESTRUCTURA Y TARJETAS */
    .dashboard-cyber-wrapper {
        background-color: var(--bg-cyber-light);
        background-image:
            radial-gradient(circle at 50% 10%, rgba(126, 34, 206, 0.04) 0%, transparent 60%),
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
        border-bottom: 2px solid var(--neon-purple-dark);
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
    }

    .system-badge-live-purple {
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.65rem;
        border: 1px solid var(--neon-purple-dark);
        color: var(--neon-purple-dark);
        background: rgba(126, 34, 206, 0.08);
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: bold;
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

    .border-neon-purple {
        border-left: 4px solid var(--neon-purple-dark);
    }

    .border-neon-yellow {
        border-left: 4px solid var(--neon-yellow-dark);
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

    .cyber-grid-two-unequal {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 20px;
        width: 100%;
    }

    @media(max-width: 1000px) {
        .cyber-grid-two-unequal {
            grid-template-columns: 1fr;
        }
    }

    .cyber-panel-card {
        background: var(--card-cyber-light);
        border: 1px solid var(--border-cyber-subtle);
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
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
        max-width: 350px;
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

    .t-purple,
    .purple-accent {
        color: var(--neon-purple-dark);
        font-weight: bold;
    }

    .t-cyan,
    .cyan-accent {
        color: var(--neon-cyan-dark);
        font-weight: bold;
    }

    .text-neon-yellow {
        color: var(--neon-yellow-dark);
        font-weight: bold;
    }

    .text-neon-red {
        color: var(--neon-red-dark);
        font-weight: bold;
    }

    .blink-anim {
        animation: blink 1.2s infinite;
    }

    @keyframes blink {
        to {
            opacity: 0.3;
        }
    }

    .pulse-dot-yellow {
        width: 8px;
        height: 8px;
        background: var(--neon-yellow-dark);
        border-radius: 50%;
        animation: blink 1s infinite;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="vistas/js/bi/slaProcesos.js"></script>