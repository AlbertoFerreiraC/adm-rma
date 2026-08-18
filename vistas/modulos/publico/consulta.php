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
            <span class="hero-badge">TRACKING_SYSTEM_v5.0</span>
            <h2>CONSULTA EL ESTADO DE TU EQUIPO EN TALLER</h2>
            <p>Ingresa tu número de orden de servicio (ej: <strong>RMA-0247</strong>), número de serie o tu cédula.</p>

            <form id="formConsultaPublica" class="search-form" autocomplete="off" onsubmit="return false;">
                <div class="search-input-wrapper">
                    <i class="fa fa-search search-icon-hud"></i>
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
                        <h3 id="txtNumeroCaso" class="t-cyan font-mono m-0">RMA-0000</h3>
                    </div>
                    <div class="text-right">
                        <span class="node-label">ESTADO ACTUAL EN TALLER</span>
                        <div id="badgeEstadoActual" class="badge-status-lg status-1">-</div>
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
                            <span id="txtNumeroSerie" class="spec-value t-cyan">-</span>
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
                            <span id="txtFechaCierre" class="spec-value green-accent">-</span>
                        </div>
                    </div>

                    <hr class="cyber-hr">

                    <div class="problem-box">
                        <span class="node-label">SÍNTOMA / PROBLEMA REPORTADO:</span>
                        <p id="txtDescripcionProblema" class="desc-text">-</p>
                    </div>

                    <div class="problem-box mt-3">
                        <span class="node-label text-yellow">// DIAGNÓSTICO DEL TÉCNICO:</span>
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

                    <div class="timeline-container mt-3 font-mono" id="timelineContenedor">
                        <!-- Carga dinámica del historial de estados -->
                    </div>
                </div>

            </div>
        </section>

    </main>

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

        body.public-portal-body {
            background-color: var(--bg-cyber-light);
            background-image:
                radial-gradient(circle at 50% 10%, rgba(2, 132, 199, 0.05) 0%, transparent 60%),
                linear-gradient(rgba(203, 213, 225, 0.2) 1px, transparent 1px),
                linear-gradient(90deg, rgba(203, 213, 225, 0.2) 1px, transparent 1px);
            background-size: 100% 100%, 20px 20px, 20px 20px;
            min-height: 100vh;
            color: var(--text-cyber-dark);
            font-family: 'Rajdhani', sans-serif;
            margin: 0;
            padding-bottom: 50px;
        }

        /* HEADER E-COMMERCE STYLE */
        .public-navbar {
            background: #ffffff;
            border-bottom: 2px solid var(--neon-cyan-dark);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
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
            color: var(--text-cyber-dark);
            margin: 0;
            letter-spacing: 1px;
        }

        .sub-brand {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.65rem;
            color: var(--neon-cyan-dark);
            letter-spacing: 2px;
            font-weight: bold;
        }

        .btn-staff-login {
            background: rgba(2, 132, 199, 0.08);
            border: 1px solid var(--border-cyber-subtle);
            color: var(--text-cyber-muted);
            padding: 8px 16px;
            border-radius: 4px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: bold;
        }

        .btn-staff-login:hover {
            color: var(--neon-cyan-dark);
            border-color: var(--neon-cyan-dark);
            background: rgba(2, 132, 199, 0.15);
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
            background: var(--card-cyber-light);
            border: 1px solid var(--border-cyber-subtle);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
            position: relative;
            overflow: hidden;
        }

        .hero-badge {
            display: inline-block;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.7rem;
            color: var(--neon-green-dark);
            border: 1px solid var(--neon-green-dark);
            padding: 2px 10px;
            border-radius: 20px;
            margin-bottom: 15px;
            background: rgba(21, 128, 61, 0.08);
            font-weight: bold;
        }

        .search-hero-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-cyber-dark);
            margin: 0 0 10px 0;
        }

        .search-hero-card p {
            color: var(--text-cyber-muted);
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
            background: #f8fafc;
            border: 1px solid var(--border-cyber-subtle);
            border-radius: 6px;
            padding: 4px 6px;
            transition: all 0.3s;
        }

        .search-input-wrapper:focus-within {
            border-color: var(--neon-cyan-dark);
            box-shadow: 0 0 15px rgba(2, 132, 199, 0.15);
            background: #ffffff;
        }

        .search-icon-hud {
            color: var(--text-cyber-muted);
            margin-left: 15px;
            font-size: 1.1rem;
        }

        .cyber-search-input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--text-cyber-dark);
            padding: 12px 15px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.95rem;
        }

        .cyber-search-input:focus {
            outline: none;
        }

        .btn-search-trigger {
            background: rgba(2, 132, 199, 0.1);
            border: 1px solid var(--neon-cyan-dark);
            color: var(--neon-cyan-dark);
            padding: 12px 24px;
            font-family: 'Share Tech Mono', monospace;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-search-trigger:hover {
            background: var(--neon-cyan-dark);
            color: #ffffff;
            box-shadow: 0 0 12px rgba(2, 132, 199, 0.3);
        }

        /* RESULT SECTION */
        .result-section {
            margin-top: 30px;
        }

        .hidden {
            display: none !important;
        }

        .cyber-panel-card {
            background: var(--card-cyber-light);
            border: 1px solid var(--border-cyber-subtle);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
        }

        .border-neon-cyan {
            border-left: 4px solid var(--neon-cyan-dark);
        }

        .border-neon-blue {
            border-left: 4px solid var(--neon-cyan-glow);
        }

        .border-neon-purple {
            border-left: 4px solid var(--neon-purple-dark);
        }

        .result-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .node-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.7rem;
            color: var(--text-cyber-muted);
            display: block;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .badge-status-lg {
            padding: 6px 16px;
            border-radius: 4px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-block;
            border: 1px solid;
        }

        .status-1 {
            background: rgba(2, 132, 199, 0.08);
            color: var(--neon-cyan-dark);
            border-color: rgba(2, 132, 199, 0.3);
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
            color: var(--text-cyber-dark);
            margin: 0;
        }

        .cyan-accent {
            color: var(--neon-cyan-dark);
            font-weight: bold;
        }

        .purple-accent {
            color: var(--neon-purple-dark);
            font-weight: bold;
        }

        .green-accent {
            color: var(--neon-green-dark);
            font-weight: bold;
        }

        .text-yellow {
            color: var(--neon-yellow-dark);
            font-weight: bold;
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
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 6px;
        }

        .spec-label {
            color: var(--text-cyber-muted);
        }

        .spec-value {
            color: var(--text-cyber-dark);
            font-weight: bold;
        }

        .text-white {
            color: var(--text-cyber-dark) !important;
        }

        .t-cyan {
            color: var(--neon-cyan-dark) !important;
            font-weight: bold;
        }

        .cyber-hr {
            border: 0;
            border-top: 1px solid var(--border-cyber-subtle);
            margin: 18px 0;
        }

        .desc-text {
            background: #f8fafc;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid var(--border-cyber-subtle);
            font-size: 0.88rem;
            margin: 5px 0 0 0;
            color: var(--text-cyber-dark);
        }

        .btn-print-receipt {
            background: rgba(126, 34, 206, 0.08);
            border: 1px solid var(--neon-purple-dark);
            color: var(--neon-purple-dark);
            padding: 10px 20px;
            font-family: 'Share Tech Mono', monospace;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-print-receipt:hover {
            background: var(--neon-purple-dark);
            color: #ffffff;
            box-shadow: 0 0 10px rgba(126, 34, 206, 0.3);
        }

        /* TIMELINE STYLES */
        .timeline-container {
            position: relative;
            padding-left: 20px;
            border-left: 2px solid var(--border-cyber-subtle);
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
            background: var(--neon-purple-dark);
            box-shadow: 0 0 6px var(--neon-purple-dark);
        }

        .timeline-item.active::before {
            background: var(--neon-green-dark);
            box-shadow: 0 0 6px var(--neon-green-dark);
        }

        .time-date {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.72rem;
            color: var(--text-cyber-muted);
        }

        .time-status {
            font-weight: bold;
            color: var(--text-cyber-dark);
            font-size: 0.95rem;
            margin: 2px 0;
        }

        .time-obs {
            font-size: 0.82rem;
            color: var(--text-cyber-muted);
        }

        .m-0 {
            margin: 0 !important;
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

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .font-mono {
            font-family: 'Share Tech Mono', monospace;
        }
    </style>

    <script src="vistas/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="vistas/js/publico/consulta.js"></script>
</body>

</html>