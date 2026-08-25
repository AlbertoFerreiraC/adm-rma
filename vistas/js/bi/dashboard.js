(function () {
    console.log("⚡ [BI_ANALYTICS] Inicializando Dashboard Ejecutivo...");

    const API_DASHBOARD = "../api-rma/bi/dashboard.php";

    let chartSubtiposInstance = null;
    let chartRadarInstance = null;
    let chartMarcasInstance = null;

    const loader = document.getElementById("cyberLoaderDashboard");

    function mostrarLoader() {
        if (loader) {
            loader.style.display = "flex";
            loader.style.opacity = "1";
            loader.style.visibility = "visible";
        }
    }

    function ocultarLoader() {
        if (loader) {
            loader.style.opacity = "0";
            setTimeout(() => {
                loader.style.visibility = "hidden";
                loader.style.display = "none";
            }, 300);
        }
    }

    async function cargarDashboard() {
        mostrarLoader();
        try {
            const response = await fetch(`${API_DASHBOARD}?action=cargar_metricas`);

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            const data = await response.json();

            if (data.status === "success") {
                renderizarKPIs(data.kpis);
                renderizarSubtiposDonut(data.tipos_caso);
                renderizarRadarPerf(data.radar_perf);
                renderizarTopFallas(data.top_fallas);
                renderizarUltimosCasos(data.ultimos_casos);
                renderizarAlertas(data.alertas);
                renderizarGraficoMarcas(data.fallas_marcas);
                renderizarEstadisticas(data.estadisticas);
                renderizarConsolaLive(data.consola_live);
            } else {
                console.error("🔴 Error API Dashboard:", data.message);
            }
        } catch (error) {
            console.error("🔴 Error de conexión en Dashboard:", error);
        } finally {
            ocultarLoader();
        }
    }

    // 1. RENDERIZAR KPIS SUPERIORES
    function renderizarKPIs(kpis) {
        if (!kpis) return;

        if (document.getElementById("kpiTotal")) document.getElementById("kpiTotal").textContent = kpis.total_casos || 0;
        if (document.getElementById("kpiTotalTrend")) document.getElementById("kpiTotalTrend").textContent = `${kpis.tendencia_pct >= 0 ? '▲' : '▼'} ${Math.abs(kpis.tendencia_pct)}% MoM`;
        if (document.getElementById("kpiPendiente")) document.getElementById("kpiPendiente").textContent = kpis.en_proceso || 0;
        if (document.getElementById("kpiConcretado")) document.getElementById("kpiConcretado").textContent = kpis.resueltos_mes || 0;
        if (document.getElementById("kpiEficPct")) document.getElementById("kpiEficPct").textContent = `${kpis.tasa_eficiencia || 0}% EFIC.`;
        if (document.getElementById("kpiDiasPromedio")) document.getElementById("kpiDiasPromedio").textContent = parseFloat(kpis.dias_promedio || 0).toFixed(1);
    }

    // 2. MINI DONUT KPI (TIPOS DE CASO)
    function renderizarSubtiposDonut(tipos) {
        const ctx = document.getElementById('chartSubtiposMini');
        const legend = document.getElementById('legendTiposCaso');
        if (!ctx || !tipos) return;

        const labels = tipos.map(t => t.nombre);
        const values = tipos.map(t => t.total);
        const colors = ['#0284c7', '#d97706', '#15803d', '#dc2626', '#7e22ce'];

        if (legend) {
            legend.innerHTML = tipos.slice(0, 2).map(t => `<span>${t.nombre.substring(0, 6)}: ${t.pct}%</span>`).join('');
        }

        if (chartSubtiposInstance) chartSubtiposInstance.destroy();

        chartSubtiposInstance = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '72%' }
        });
    }

    // 3. MINI RADAR RENDIMIENTO
    function renderizarRadarPerf(perf) {
        const ctx = document.getElementById('chartRadarMini');
        if (!ctx) return;

        if (chartRadarInstance) chartRadarInstance.destroy();

        chartRadarInstance = new Chart(ctx.getContext('2d'), {
            type: 'radar',
            data: {
                labels: ['', '', '', '', ''],
                datasets: [{
                    data: perf || [80, 85, 90, 75, 88],
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

    // 4. TOP FALLAS POR PRODUCTO / MODELO
    function renderizarTopFallas(fallas) {
        const contenedor = document.getElementById("contenedorTopFallas");
        if (!contenedor) return;

        if (!fallas || fallas.length === 0) {
            contenedor.innerHTML = '<div class="font-mono text-center text-muted">[NO_DATA_AVAILABLE]</div>';
            return;
        }

        const maxQty = Math.max(...fallas.map(f => f.total));
        const barColors = ['fill-hp', 'fill-viewsonic', 'fill-lenovo', 'fill-logitech', 'fill-genius'];

        contenedor.innerHTML = fallas.map((item, idx) => {
            const pct = Math.round((item.total / maxQty) * 100);
            const colorClass = barColors[idx % barColors.length];
            return `
                <div class="fault-item font-mono">
                    <span class="fault-name" title="${item.modelo}">${item.modelo}</span>
                    <div class="fault-bar-container">
                        <div class="fault-bar ${colorClass}" style="width: ${pct}%;"></div>
                    </div>
                    <span class="fault-qty">${item.total}</span>
                </div>
            `;
        }).join('');
    }

    // 5. ÚLTIMOS CASOS INGRESADOS
    function renderizarUltimosCasos(casos) {
        const tbody = document.querySelector("#tablaUltimosCasos tbody");
        if (!tbody) return;

        if (!casos || casos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="font-mono text-center">[NO_ACTIVE_CASES]</td></tr>';
            return;
        }

        tbody.innerHTML = casos.map(c => {
            let badgeClass = "status-1";
            if (c.estado_nombre.toLowerCase().includes("reparación") || c.estado_nombre.toLowerCase().includes("taller")) badgeClass = "status-default";
            if (c.estado_nombre.toLowerCase().includes("listo") || c.estado_nombre.toLowerCase().includes("entregado")) badgeClass = "status-ready";
            if (c.estado_nombre.toLowerCase().includes("proveedor") || c.estado_nombre.toLowerCase().includes("externo")) badgeClass = "status-external";

            return `
                <tr>
                    <td class="t-cyan font-mono">${c.numero_caso}</td>
                    <td class="font-mono">${c.cliente_nombre ? c.cliente_nombre.split(" ")[0] : 'N/A'}</td>
                    <td><span class="badge-status-cyber ${badgeClass}">${c.estado_nombre}</span></td>
                    <td class="font-mono">${c.dias_estancia}d</td>
                </tr>
            `;
        }).join('');
    }

    // 6. ALERTAS EN VIVO DEL SISTEMA
    function renderizarAlertas(alertas) {
        const contenedor = document.getElementById("contenedorAlertasSistema");
        if (!contenedor) return;

        if (!alertas || alertas.length === 0) {
            contenedor.innerHTML = '<div class="alert-node-item b-left-blue"><p>Sin alertas críticas pendientes en el sistema.</p></div>';
            return;
        }

        contenedor.innerHTML = alertas.map(a => `
            <div class="alert-node-item ${a.tipo_border}">
                <span class="alert-bullet ${a.tipo_bullet}"></span>
                <p>${a.mensaje}</p>
            </div>
        `).join('');
    }

    // 7. GRÁFICO BARRA DE FALLAS POR MARCA
    function renderizarGraficoMarcas(marcasData) {
        const ctx = document.getElementById('chartConciliacionMarcas');
        if (!ctx || !marcasData) return;

        const labels = marcasData.map(m => m.marca);
        const totalesRma = marcasData.map(m => m.total_rma);

        if (chartMarcasInstance) chartMarcasInstance.destroy();

        chartMarcasInstance = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Retornos RMA',
                        data: totalesRma,
                        backgroundColor: '#0284c7',
                        borderRadius: 3,
                        barThickness: 18
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

    // 8. ESTADÍSTICAS FINANCIERAS Y RESUMEN
    function renderizarEstadisticas(st) {
        if (!st) return;

        if (document.getElementById("statTotalCasos")) document.getElementById("statTotalCasos").textContent = `${st.total_casos} uds.`;
        if (document.getElementById("statCasosCerrados")) document.getElementById("statCasosCerrados").textContent = `${st.cerrados} (${st.pct_cerrados}%)`;
        if (document.getElementById("statMarcaMayor")) document.getElementById("statMarcaMayor").textContent = st.marca_mayor || 'N/A';
        if (document.getElementById("statTasaEfectividad")) document.getElementById("statTasaEfectividad").textContent = `${st.tasa_efectividad}%`;
        if (document.getElementById("statCostoInsumos")) document.getElementById("statCostoInsumos").textContent = `₲ ${parseFloat(st.costo_insumos || 0).toLocaleString('es-PY')}`;
    }

    // 9. CONSOLA TERMINAL DE AUDITORÍA
    function renderizarConsolaLive(logs) {
        const consola = document.getElementById("consoleLiveTerminal");
        if (!consola) return;

        if (!logs || logs.length === 0) {
            consola.innerHTML = '<div class="term-line"><span class="t-cyan">[SYS]</span> Escaneando eventos de taller...</div>';
            return;
        }

        consola.innerHTML = logs.map(l => `
            <div class="term-line">
                <span class="t-cyan">[${l.hora}]</span> 
                <span class="${l.color_accion}">${l.accion}:</span> 
                Caso <span class="t-cyan">${l.numero_caso}</span> | 
                <span class="purple-accent">Cliente:</span> ${l.cliente || 'Anonimo'} | 
                <span class="text-yellow">Detalle:</span> ${l.detalle}
            </div>
        `).join('') + '<div class="term-line blink-line"><span class="t-cyan">[>&nbsp;]</span> Monitoreando eventos de laboratorio en tiempo real...</div>';
    }

    // Autocarga al verificar el DOM
    const pollDom = setInterval(() => {
        if (document.getElementById("kpiTotal")) {
            clearInterval(pollDom);
            cargarDashboard();
        }
    }, 100);
})();