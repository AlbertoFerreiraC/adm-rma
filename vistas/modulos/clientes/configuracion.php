<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="content-wrapper dashboard-cyber-wrapper">
    <header class="cyber-header">
        <div class="header-brand-glitch">
            <span class="cyber-logo-icon">⚙️</span>
            <h2>[01] SYS_AUTH // PARÁMETROS DEL CORE OPERATIVO</h2>
            <span class="system-badge-live">SYS_PARAMS_CONFIG</span>
        </div>
    </header>

    <section class="cyber-content mt-4">
        <div class="cyber-grid-two-equal">

            <div class="cyber-panel-card glass-panel-neon border-neon-yellow">
                <div class="panel-cyber-header">
                    <h4><span class="badge-time-lbl val-yellow">TABLA: tipos_caso</span></h4>
                </div>
                <div class="panel-cyber-body-table mt-3">
                    <table class="cyber-mini-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Tipo de RMA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-mono t-cyan">1</td>
                                <td class="font-mono text-white">Garantía Local</td>
                            </tr>
                            <tr>
                                <td class="font-mono t-cyan">2</td>
                                <td class="font-mono text-white">Servicio Técnico Condicional</td>
                            </tr>
                            <tr>
                                <td class="font-mono t-cyan">3</td>
                                <td class="font-mono text-white">Garantía Oficial de Marca</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="cyber-panel-card glass-panel-neon border-neon-blue">
                <div class="panel-cyber-header">
                    <h4><span class="badge-time-lbl val-blue">TABLA: estados_caso</span></h4>
                </div>
                <div class="panel-cyber-body-table mt-3">
                    <table class="cyber-mini-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-mono t-cyan">1</td>
                                <td><span class="badge-status badge-diag">Ingresado / En Diagnóstico</span></td>
                            </tr>
                            <tr>
                                <td class="font-mono t-cyan">2</td>
                                <td><span class="badge-status badge-repair">En Taller / Reparación</span></td>
                            </tr>
                            <tr>
                                <td class="font-mono t-cyan">3</td>
                                <td><span class="badge-status badge-external">Enviado a Service Oficial</span></td>
                            </tr>
                            <tr>
                                <td class="font-mono t-cyan">4</td>
                                <td><span class="badge-status badge-ready">Listo para Entregar</span></td>
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
        border: 1px solid #ffca28;
        color: #ffca28;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .cyber-grid-two-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media(max-width: 768px) {
        .cyber-grid-two-equal {
            grid-template-columns: 1fr;
        }
    }

    .cyber-panel-card {
        background: rgba(10, 16, 32, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 20px;
    }

    .border-neon-yellow {
        border-left: 3px solid #ffca28;
    }

    .border-neon-blue {
        border-left: 3px solid #00f2ff;
    }

    .badge-time-lbl {
        padding: 4px 10px;
        border-radius: 4px;
        font-family: 'Share Tech Mono', monospace;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .val-yellow {
        background: rgba(255, 202, 40, 0.1);
        color: #ffca28;
        border: 1px solid rgba(255, 202, 40, 0.2);
    }

    .val-blue {
        background: rgba(0, 242, 255, 0.1);
        color: #00f2ff;
        border: 1px solid rgba(0, 242, 255, 0.2);
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
        color: #516995;
        padding: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        text-transform: uppercase;
        text-align: left;
    }

    .cyber-mini-table td {
        padding: 12px 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.02);
    }

    .font-mono {
        font-family: 'Share Tech Mono', monospace;
    }

    .text-white {
        color: #fff;
    }

    .t-cyan {
        color: #00b4d8;
    }

    .badge-status {
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-diag {
        background: rgba(0, 242, 255, 0.1);
        color: #00f2ff;
        border: 1px solid rgba(0, 242, 255, 0.2);
    }

    .badge-repair {
        background: rgba(255, 202, 40, 0.1);
        color: #ffca28;
        border: 1px solid rgba(255, 202, 40, 0.2);
    }

    .badge-external {
        background: rgba(255, 51, 51, 0.1);
        color: #ff3333;
        border: 1px solid rgba(255, 51, 51, 0.2);
    }

    .badge-ready {
        background: rgba(0, 255, 102, 0.1);
        color: #00ff66;
        border: 1px solid rgba(0, 255, 102, 0.2);
    }
</style>