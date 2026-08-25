(function () {
    console.log("⚡ [BI_QUALITY] Inicializando Análisis de Confiabilidad y Fallas...");

    const API_FALLAS = "../api-rma/bi/confiabilidad-producto.php";

    let chartParetoInst = null;
    let chartClusterInst = null;

    const loader = document.getElementById("cyberLoaderFallas");
    const tablaModelosBody = document.querySelector("#tablaConfiabilidadModelos tbody");
    const buscarModeloInput = document.getElementById("buscarModelo");
    let listaModelosCache = [];

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

    async function cargarMetricasConfiabilidad() {
        mostrarLoader();
        try {
            const res = await fetch(`${API_FALLAS}?action=consultar_fallas`);

            if (!res.ok) {
                throw new Error(`HTTP Error Status: ${res.status}`);
            }

            const data = await res.json();

            if (data.status === "success") {
                renderizarKPIs(data.kpis);
                renderizarGraficoPareto(data.pareto_marcas);
                renderizarGraficoCluster(data.cluster_fallas);

                listaModelosCache = data.top_modelos || [];
                renderizarTablaModelos(listaModelosCache);
            } else {
                console.error("🔴 Error API Confiabilidad:", data.message);
            }
        } catch (err) {
            console.error("🔴 Error de conexión en Confiabilidad:", err);
        } finally {
            ocultarLoader();
        }
    }

    function renderizarKPIs(kpis) {
        if (!kpis) return;
        if (document.getElementById("kpiModeloCritico")) document.getElementById("kpiModeloCritico").textContent = kpis.modelo_critico || 'N/A';
        if (document.getElementById("kpiIndiceSeveridad")) document.getElementById("kpiIndiceSeveridad").textContent = `${kpis.indice_severidad || 0}%`;
        if (document.getElementById("kpiTotalModelos")) document.getElementById("kpiTotalModelos").textContent = kpis.total_modelos || 0;
        if (document.getElementById("kpiFallaTop")) document.getElementById("kpiFallaTop").textContent = kpis.falla_top || 'N/A';
    }

    function renderizarGraficoPareto(pareto) {
        const ctx = document.getElementById('chartParetoMarcas');
        if (!ctx || !pareto) return;

        const labels = pareto.map(p => p.marca);
        const cantidades = pareto.map(p => parseInt(p.cantidad));
        const acumulados = pareto.map(p => parseFloat(p.porcentaje_acumulado));

        if (chartParetoInst) chartParetoInst.destroy();

        chartParetoInst = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'line',
                        label: '% Acumulado (Pareto)',
                        data: acumulados,
                        borderColor: '#dc2626',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        yAxisID: 'y1',
                        pointRadius: 3
                    },
                    {
                        type: 'bar',
                        label: 'Total Reclamos',
                        data: cantidades,
                        backgroundColor: 'rgba(126, 34, 206, 0.25)',
                        borderColor: '#7e22ce',
                        borderWidth: 1.5,
                        borderRadius: 4,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#475569', font: { family: "'Share Tech Mono', monospace", size: 10 } } } },
                scales: {
                    y: { type: 'linear', position: 'left', grid: { color: '#cbd5e1' }, ticks: { font: { family: "'Share Tech Mono', monospace", size: 10 } } },
                    y1: { type: 'linear', position: 'right', max: 100, grid: { display: false }, ticks: { font: { family: "'Share Tech Mono', monospace", size: 10 }, callback: v => v + '%' } },
                    x: { grid: { display: false }, ticks: { font: { family: "'Share Tech Mono', monospace", size: 10 } } }
                }
            }
        });
    }

    function renderizarGraficoCluster(clusters) {
        const ctx = document.getElementById('chartClusterFallas');
        if (!ctx || !clusters) return;

        const labels = clusters.map(c => c.concepto);
        const totales = clusters.map(c => parseInt(c.total));

        if (chartClusterInst) chartClusterInst.destroy();

        chartClusterInst = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Frecuencia de Falla',
                    data: totales,
                    backgroundColor: '#0284c7',
                    borderRadius: 3
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: '#cbd5e1' }, ticks: { font: { family: "'Share Tech Mono', monospace", size: 10 } } },
                    y: { grid: { display: false }, ticks: { font: { family: "'Share Tech Mono', monospace", size: 10 } } }
                }
            }
        });
    }

    function renderizarTablaModelos(items) {
        if (!tablaModelosBody) return;
        tablaModelosBody.innerHTML = "";

        if (items.length === 0) {
            tablaModelosBody.innerHTML = `<tr><td colspan="7" class="font-mono text-center">[NO_FAILURE_DATA_FOUND]</td></tr>`;
            return;
        }

        items.forEach(m => {
            const fila = document.createElement("tr");

            let badgeStyle = "color:#15803d; border-color:#15803d; background:rgba(21,128,61,0.08);";
            let severidadTexto = "LEVE";

            if (m.severidad === "ALTA") {
                badgeStyle = "color:#dc2626; border-color:#dc2626; background:rgba(220,38,38,0.08);";
                severidadTexto = "ALTA";
            } else if (m.severidad === "MEDIA") {
                badgeStyle = "color:#d97706; border-color:#d97706; background:rgba(217,119,6,0.08);";
                severidadTexto = "MEDIA";
            }

            fila.innerHTML = `
                <td class="font-weight-bold font-mono">${m.marca.toUpperCase()}</td>
                <td class="font-mono t-purple font-weight-bold">${m.modelo.toUpperCase()}</td>
                <td class="font-mono font-weight-bold">${m.total_reclamos}</td>
                <td class="font-mono">${m.porcentaje_total}%</td>
                <td class="font-mono text-left">${m.falla_frecuente}</td>
                <td class="font-mono t-cyan">${m.tipo_caso_predominante}</td>
                <td><span class="system-badge-live" style="${badgeStyle}">${severidadTexto}</span></td>
            `;

            tablaModelosBody.appendChild(fila);
        });
    }

    if (buscarModeloInput) {
        buscarModeloInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaModelosCache.filter(m =>
                m.marca.toLowerCase().includes(termino) ||
                m.modelo.toLowerCase().includes(termino) ||
                m.falla_frecuente.toLowerCase().includes(termino)
            );
            renderizarTablaModelos(filtrados);
        });
    }

    const pollDom = setInterval(() => {
        if (document.getElementById("tablaConfiabilidadModelos")) {
            clearInterval(pollDom);
            cargarMetricasConfiabilidad();
        }
    }, 100);
})();