(function () {
    console.log("⚡ [BI_PERF] Inicializando Monitor de Performance Técnico...");

    const API_PERF = "../api-rma/bi/performance-tecnico.php";

    let chartCargaInst = null;
    let chartMttrInst = null;

    const loader = document.getElementById("cyberLoaderPerf");
    const tablaPerfBody = document.querySelector("#tablaPerformanceTecnicos tbody");
    const buscarTecnicoInput = document.getElementById("buscarTecnico");
    let listaTecnicosCache = [];

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

    async function cargarMetricasPerformance() {
        mostrarLoader();
        try {
            const res = await fetch(`${API_PERF}?action=consultar_performance`);

            if (!res.ok) {
                throw new Error(`HTTP Error Status: ${res.status}`);
            }

            const data = await res.json();

            if (data.status === "success") {
                renderizarKPIs(data.kpis);
                renderizarGraficoCarga(data.tecnicos);
                renderizarGraficoMttr(data.tecnicos);

                listaTecnicosCache = data.tecnicos || [];
                renderizarTablaTecnicos(listaTecnicosCache);
            } else {
                console.error("🔴 Error API Performance:", data.message);
            }
        } catch (err) {
            console.error("🔴 Error de conexión en Performance:", err);
        } finally {
            ocultarLoader();
        }
    }

    function renderizarKPIs(kpis) {
        if (!kpis) return;
        if (document.getElementById("kpiTasaCierre")) document.getElementById("kpiTasaCierre").textContent = `${kpis.tasa_cierre_global || 0}%`;
        if (document.getElementById("kpiMttr")) document.getElementById("kpiMttr").textContent = `${kpis.mttr_global || 0}d`;
        if (document.getElementById("kpiTopTecnico")) document.getElementById("kpiTopTecnico").textContent = kpis.top_tecnico || 'N/A';
        if (document.getElementById("kpiCostoInsumosTec")) {
            const monto = parseFloat(kpis.costo_total_insumos || 0).toLocaleString('es-PY');
            document.getElementById("kpiCostoInsumosTec").textContent = `₲ ${monto}`;
        }
    }

    function renderizarGraficoCarga(tecnicos) {
        const ctx = document.getElementById('chartCargaTecnicos');
        if (!ctx || !tecnicos) return;

        const labels = tecnicos.map(t => t.nombre.split(" ")[0]);
        const resueltos = tecnicos.map(t => parseInt(t.casos_resueltos));
        const pendientes = tecnicos.map(t => parseInt(t.casos_pendientes));

        if (chartCargaInst) chartCargaInst.destroy();

        chartCargaInst = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Resueltos',
                        data: resueltos,
                        backgroundColor: '#15803d',
                        borderRadius: 3
                    },
                    {
                        label: 'Pendientes',
                        data: pendientes,
                        backgroundColor: '#d97706',
                        borderRadius: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#475569', font: { family: "'Share Tech Mono', monospace", size: 10 } } } },
                scales: {
                    y: { grid: { color: '#cbd5e1' }, ticks: { font: { family: "'Share Tech Mono', monospace", size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { family: "'Share Tech Mono', monospace", size: 10 } } }
                }
            }
        });
    }

    function renderizarGraficoMttr(tecnicos) {
        const ctx = document.getElementById('chartMttrTecnicos');
        if (!ctx || !tecnicos) return;

        const labels = tecnicos.map(t => t.nombre.split(" ")[0]);
        const mttr = tecnicos.map(t => parseFloat(t.mttr_dias));

        if (chartMttrInst) chartMttrInst.destroy();

        chartMttrInst = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'MTTR (Días)',
                    data: mttr,
                    backgroundColor: 'rgba(126, 34, 206, 0.25)',
                    borderColor: '#7e22ce',
                    borderWidth: 1.5,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#cbd5e1' }, ticks: { font: { family: "'Share Tech Mono', monospace", size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { family: "'Share Tech Mono', monospace", size: 10 } } }
                }
            }
        });
    }

    function renderizarTablaTecnicos(items) {
        if (!tablaPerfBody) return;
        tablaPerfBody.innerHTML = "";

        if (items.length === 0) {
            tablaPerfBody.innerHTML = `<tr><td colspan="8" class="font-mono text-center">[NO_TECH_METRICS_FOUND]</td></tr>`;
            return;
        }

        items.forEach(t => {
            const fila = document.createElement("tr");

            const tc = parseFloat(t.tasa_cierre);
            let badgeStyle = "color:#15803d; border-color:#15803d; background:rgba(21,128,61,0.08);";
            let estadoTexto = "ALTA";

            if (tc < 50) {
                badgeStyle = "color:#dc2626; border-color:#dc2626; background:rgba(220,38,38,0.08);";
                estadoTexto = "BAJA";
            } else if (tc < 85) {
                badgeStyle = "color:#d97706; border-color:#d97706; background:rgba(217,119,6,0.08);";
                estadoTexto = "MEDIA";
            }

            const costoInsumos = parseFloat(t.costo_insumos || 0).toLocaleString('es-PY');

            fila.innerHTML = `
                <td class="font-weight-bold font-mono">${t.nombre.toUpperCase()}</td>
                <td class="font-mono">${t.casos_asignados}</td>
                <td class="font-mono text-neon-green font-weight-bold">${t.casos_resueltos}</td>
                <td class="font-mono text-neon-yellow font-weight-bold">${t.casos_pendientes}</td>
                <td class="font-mono t-cyan font-weight-bold">${t.tasa_cierre}%</td>
                <td class="font-mono t-purple font-weight-bold">${t.mttr_dias}d</td>
                <td class="font-mono">₲ ${costoInsumos}</td>
                <td><span class="system-badge-live" style="${badgeStyle}">${estadoTexto}</span></td>
            `;

            tablaPerfBody.appendChild(fila);
        });
    }

    if (buscarTecnicoInput) {
        buscarTecnicoInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaTecnicosCache.filter(t =>
                t.nombre.toLowerCase().includes(termino)
            );
            renderizarTablaTecnicos(filtrados);
        });
    }

    const pollDom = setInterval(() => {
        if (document.getElementById("tablaPerformanceTecnicos")) {
            clearInterval(pollDom);
            cargarMetricasPerformance();
        }
    }, 100);
})();