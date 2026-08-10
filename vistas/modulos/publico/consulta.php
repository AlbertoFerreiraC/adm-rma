<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Microexpress — Portal Público de Consulta RMA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="vistas/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vistas/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="public-portal-body">

    <!-- BARRA SUPERIOR E-COMMERCE TECH / HUD -->
    <header class="public-navbar">
        <div class="brand-container">
            <span class="cyber-logo-icon">⚡</span>
            <div class="brand-title">
                <h1>MICROEXPRESS</h1>
                <span class="sub-brand">SERVICE & SUPPORT HUB</span>
            </div>
        </div>
        <div class="header-actions">
            <a href="login" class="btn-staff-login">
                <i class="fa fa-user-secret"></i> [STAFF_LOGIN]
            </a>
        </div>
    </header>

    <main class="portal-container">

        <!-- HERO SEARCH SECTION -->
        <section class="search-hero-card glass-panel-neon">
            <div class="hero-badge">TRACKING_SYSTEM_v5.0</div>
            <h2>CONSULTA EL ESTADO DE TU EQUIPO EN TALLER</h2>
            <p>Ingresa tu número de orden de servicio (ej: <strong>RMA-0247</strong>), número de serie o tu cédula.</p>

            <form id="formConsultaPublica" class="search-form" autocomplete="off" onsubmit="return false;">
                <div class="search-input-wrapper">
                    <i class="fa fa-search search-icon"></i>
                    <input type="text" id="inputTracking" class="cyber-search-input"
                        placeholder="INGRESA TU N° DE CASO, SERIE O CÉDULA..." required>
                    <button type="submit" id="btnBuscarCaso" class="btn-search-trigger">
                        [RASTREAR_EQUIPO]
                    </button>
                </div>
            </form>
        </section>

        <!-- CONTENEDOR DE RESULTADOS DEL CASO (Oculto hasta buscar) -->
        <section id="panelResultadoCaso" class="result-section hidden">

            <!-- TARJETA CABECERA DE ESTADO -->
            <div class="cyber-panel-card glass-panel-neon border-neon-cyan mb-4">
                <div class="result-header-flex">
                    <div>
                        <span class="node-label">CÓDIGO DE SEGUIMIENTO</span>
                        <h3 id="txtNumeroCaso" class="neon-text-blue font-mono m-0">RMA-0000</h3>
                    </div>
                    <div class="text-right">
                        <span class="node-label">ESTADO ACTUAL EN TALLER</span>
                        <div id="badgeEstadoActual" class="badge-status-lg badge-diag">-</div>
                    </div>
                </div>
            </div>

            <div class="portal-grid-two">

                <!-- DETALLES DEL EQUIPO Y CLIENTE -->
                <div class="cyber-panel-card glass-panel-neon border-neon-blue">
                    <div class="panel-cyber-header">
                        <h4><span class="cyan-accent">//</span> ESPECIFICACIONES DEL ACTIVO</h4>
                    </div>
                    <div class="spec-list font-mono mt-3">
                        <div class="spec-item">
                            <span class="spec-label">CLIENTE:</span>
                            <span id="txtClienteNombre" class="spec-value text-white">-</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">EQUIPO:</span>
                            <span id="txtEquipo" class="spec-value text-white">-</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">MARCA / MODELO:</span>
                            <span id="txtMarcaModelo" class="spec-value text-white">-</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">NÚMERO DE SERIE:</span>
                            <span id="txtNumeroSerie" class="spec-value text-cyan">-</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">TIPO DE SERVICIO:</span>
                            <span id="txtTipoCaso" class="spec-value">-</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">FECHA INGRESO:</span>
                            <span id="txtFechaIngreso" class="spec-value">-</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">FECHA CIERRE / ENTREGA:</span>
                            <span id="txtFechaCierre" class="spec-value text-green">-</span>
                        </div>
                    </div>

                    <hr class="cyber-hr">

                    <div class="problem-box">
                        <span class="node-label">SÍNTOMA / PROBLEMA REPORTADO:</span>
                        <p id="txtDescripcionProblema" class="desc-text">-</p>
                    </div>

                    <div class="problem-box mt-3">
                        <span class="node-label" style="color: var(--neon-green);">DIAGNÓSTICO DEL TÉCNICO:</span>
                        <p id="txtDiagnosticoFinal" class="desc-text text-white font-mono">-</p>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" id="btnImprimirComprobante" class="btn-print-receipt">
                            <i class="fa fa-print"></i> [IMPRIMIR_COMPROBANTE_PDF]
                        </button>
                    </div>
                </div>

                <!-- LÍNEA DE TIEMPO / TIMELINE DE HISTORIAL DE ESTADOS -->
                <div class="cyber-panel-card glass-panel-neon border-neon-purple">
                    <div class="panel-cyber-header">
                        <h4><span class="purple-accent">//</span> LÍNEA DE TIEMPO DEL PROCESO</h4>
                    </div>

                    <div class="timeline-container mt-3" id="timelineContenedor">
                        <!-- Carga dinámica del historial de estados -->
                    </div>
                </div>

            </div>
        </section>

    </main>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@500;600;700&display=swap');

        :root {
            --cyber-bg: #040711;
            --glass-bg: rgba(10, 16, 32, 0.75);
            --neon-blue: #00f2ff;
            --neon-yellow: #ffca28;
            --neon-green: #00ff66;
            --neon-purple: #9d4edd;
            --neon-cyan: #00b4d8;
            --neon-red: #ff3333;
        }

        body.public-portal-body {
            background-color: var(--cyber-bg);
            background-image:
                radial-gradient(circle at 50% 10%, rgba(0, 242, 255, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(157, 78, 221, 0.05) 0%, transparent 50%);
            min-height: 100vh;
            color: #e2e8f0;
            font-family: 'Rajdhani', sans-serif;
            margin: 0;
            padding-bottom: 50px;
        }

        /* HEADER E-COMMERCE STYLE */
        .public-navbar {
            background: rgba(6, 11, 25, 0.95);
            border-bottom: 1px solid rgba(0, 242, 255, 0.2);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cyber-logo-icon {
            font-size: 1.8rem;
        }

        .brand-title h1 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
            letter-spacing: 1px;
        }

        .sub-brand {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.65rem;
            color: var(--neon-blue);
            letter-spacing: 2px;
        }

        .btn-staff-login {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #a2b4cd;
            padding: 8px 16px;
            border-radius: 4px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-staff-login:hover {
            color: var(--neon-blue);
            border-color: var(--neon-blue);
            box-shadow: 0 0 10px rgba(0, 242, 255, 0.3);
            text-decoration: none;
        }

        /* PORTAL CONTAINER */
        .portal-container {
            max-width: 1100px;
            margin: 40px auto 0 auto;
            padding: 0 20px;
        }

        /* HERO CARD */
        .search-hero-card {
            background: var(--glass-bg);
            border: 1px solid rgba(0, 242, 255, 0.15);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .hero-badge {
            display: inline-block;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.7rem;
            color: var(--neon-green);
            border: 1px solid rgba(0, 255, 102, 0.3);
            padding: 2px 10px;
            border-radius: 20px;
            margin-bottom: 15px;
            background: rgba(0, 255, 102, 0.05);
        }

        .search-hero-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 10px 0;
        }

        .search-hero-card p {
            color: #a2b4cd;
            font-size: 1rem;
            margin-bottom: 25px;
        }

        .search-form {
            max-width: 700px;
            margin: 0 auto;
        }

        .search-input-wrapper {
            display: flex;
            align-items: center;
            background: #03050c;
            border: 1px solid #101c38;
            border-radius: 6px;
            padding: 4px 6px;
            transition: all 0.3s;
        }

        .search-input-wrapper:focus-within {
            border-color: var(--neon-blue);
            box-shadow: 0 0 15px rgba(0, 242, 255, 0.25);
        }

        .search-icon {
            color: #506690;
            margin-left: 15px;
            font-size: 1.1rem;
        }

        .cyber-search-input {
            flex: 1;
            background: transparent;
            border: none;
            color: #fff;
            padding: 12px 15px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.95rem;
        }

        .cyber-search-input:focus {
            outline: none;
        }

        .btn-search-trigger {
            background: rgba(0, 242, 255, 0.15);
            border: 1px solid var(--neon-blue);
            color: var(--neon-blue);
            padding: 12px 24px;
            font-family: 'Share Tech Mono', monospace;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-search-trigger:hover {
            background: rgba(0, 242, 255, 0.3);
            box-shadow: 0 0 12px rgba(0, 242, 255, 0.5);
        }

        /* RESULT SECTION */
        .result-section {
            margin-top: 30px;
        }

        .hidden {
            display: none !important;
        }

        .cyber-panel-card {
            background: var(--glass-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 25px;
            backdrop-filter: blur(10px);
        }

        .border-neon-cyan {
            border-left: 4px solid var(--neon-cyan);
        }

        .border-neon-blue {
            border-left: 4px solid var(--neon-blue);
        }

        .border-neon-purple {
            border-left: 4px solid var(--neon-purple);
        }

        .result-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .node-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.7rem;
            color: #506690;
            display: block;
            margin-bottom: 4px;
        }

        .badge-status-lg {
            padding: 6px 16px;
            border-radius: 4px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-block;
        }

        .badge-diag {
            background: rgba(0, 242, 255, 0.1);
            color: var(--neon-blue);
            border: 1px solid rgba(0, 242, 255, 0.3);
        }

        .badge-ready {
            background: rgba(0, 255, 102, 0.1);
            color: var(--neon-green);
            border: 1px solid rgba(0, 255, 102, 0.3);
        }

        .portal-grid-two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media(max-width: 850px) {
            .portal-grid-two {
                grid-template-columns: 1fr;
            }
        }

        .panel-cyber-header h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        .cyan-accent {
            color: var(--neon-blue);
        }

        .purple-accent {
            color: var(--neon-purple);
        }

        .spec-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 0.88rem;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            padding-bottom: 6px;
        }

        .spec-label {
            color: #617594;
        }

        .spec-value {
            color: #e2e8f0;
            font-weight: bold;
        }

        .text-white {
            color: #fff !important;
        }

        .text-cyan {
            color: var(--neon-cyan) !important;
        }

        .text-green {
            color: var(--neon-green) !important;
        }

        .neon-text-blue {
            color: var(--neon-blue);
            text-shadow: 0 0 8px rgba(0, 242, 255, 0.3);
        }

        .cyber-hr {
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            margin: 18px 0;
        }

        .desc-text {
            background: rgba(0, 0, 0, 0.3);
            padding: 10px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.03);
            font-size: 0.88rem;
            margin: 5px 0 0 0;
            color: #cbd5e1;
        }

        .btn-print-receipt {
            background: rgba(157, 78, 221, 0.15);
            border: 1px solid var(--neon-purple);
            color: var(--neon-purple);
            padding: 10px 20px;
            font-family: 'Share Tech Mono', monospace;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-print-receipt:hover {
            background: rgba(157, 78, 221, 0.3);
            box-shadow: 0 0 10px rgba(157, 78, 221, 0.4);
        }

        /* TIMELINE STYLES */
        .timeline-container {
            position: relative;
            padding-left: 20px;
            border-left: 2px solid rgba(255, 255, 255, 0.08);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--neon-purple);
            box-shadow: 0 0 8px var(--neon-purple);
        }

        .timeline-item.active::before {
            background: var(--neon-green);
            box-shadow: 0 0 8px var(--neon-green);
        }

        .time-date {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.72rem;
            color: #617594;
        }

        .time-status {
            font-weight: bold;
            color: #fff;
            font-size: 0.95rem;
            margin: 2px 0;
        }

        .time-obs {
            font-size: 0.82rem;
            color: #94a3b8;
        }
    </style>

    <script src="vistas/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="vistas/js/publico/consulta.js"></script>
</body>

</html>