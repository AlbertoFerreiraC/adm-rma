(function () {
    console.log("⚡ [BI_SLA] Inicializando Módulo de Tiempos de Ciclo...");

    const API_SLA = "../api-rma/bi/sla-procesos.php";

    let chartTiempoEstadosInst = null;
    let chartDonutSlaInst = null;

    const loader = document.getElementById("cyberLoaderSla");
    const tablaSlaBody = document.querySelector("#tablaSlaCasos tbody");
    const buscarSlaInput = document.getElementById("buscarSlaCaso");
    let listaCasosSlaCache = [];

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

    async function cargarMetricasSLA() {
        mostrarLoader();
        try {
            const res = await fetch(`${API_SLA}?action=consultar_sla`);

            if (!res.ok) {
                throw new Error(`HTTP Error Status: ${res.status}`);
            }

            const data = await res.json();

            if (data.status === "success") {
                renderizarKPIs(data.kpis);
                renderizarGraficoTiempos(data.tiempos_estado);
                renderizarGraficoDonut(data.kpis);

                listaCasosSlaCache = data.casos_detalle || [];
                renderizarTablaSla(listaCasosSlaCache);
            } else {
                console.error("🔴 Error API SLA:", data.message);
            }
        } catch (err) {
            console.error("🔴 Error de conexión o ejecución en SLA:", err);
        } finally {
            // Se garantiza la ocultación del loader sin importar si hubo éxito o fallo
            ocultarLoader();
        }
    }

    function renderizarKPIs(kpis) {
        if (!kpis) return;
        if (document.getElementById("kpiPrimeraRespuesta")) document.getElementById("kpiPrimeraRespuesta").textContent = `${kpis.primera_respuesta_hrs || 0}h`;
        if (document.getElementById("kpiIndiceRetrabajo")) document.getElementById("kpiIndiceRetrabajo").textContent = kpis.casos_retrabajo || 0;
        if (document.getElementById("kpiSlaCumplimiento")) document.getElementById("kpiSlaCumplimiento").textContent = `${kpis.sla_cumplimiento_pct || 0}%`;
        if (document.getElementById("kpiEstadoCritico")) document.getElementById("kpiEstadoCritico").textContent = kpis.estado_critico || 'N/A';
    }

    function renderizarGraficoTiempos(tiempos) {
        const ctx = document.getElementById('chartTiempoEstados');
        if (!ctx || !tiempos) return;

        const labels = tiempos.map(t => t.estado);
        const dias = tiempos.map(t => parseFloat(t.dias_promedio));

        if (chartTiempoEstadosInst) chartTiempoEstadosInst.destroy();

        chartTiempoEstadosInst = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Días Promedio de Estancia',
                    data: dias,
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

    function renderizarGraficoDonut(kpis) {
        const ctx = document.getElementById('chartDonutSLA');
        if (!ctx || !kpis) return;

        const dentro = kpis.casos_en_sla || 0;
        const fuera = kpis.casos_fuera_sla || 0;

        if (chartDonutSlaInst) chartDonutSlaInst.destroy();

        chartDonutSlaInst = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Dentro de SLA (< 3d)', 'Fuera de SLA (> 3d)'],
                datasets: [{
                    data: [dentro, fuera],
                    backgroundColor: ['#15803d', '#dc2626'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#475569', font: { family: "'Share Tech Mono', monospace", size: 11 } } } },
                cutout: '65%'
            }
        });
    }

    function renderizarTablaSla(items) {
        if (!tablaSlaBody) return;
        tablaSlaBody.innerHTML = "";

        if (items.length === 0) {
            tablaSlaBody.innerHTML = `<tr><td colspan="9" class="font-mono text-center">[NO_SLA_CASES_FOUND]</td></tr>`;
            return;
        }

        items.forEach(c => {
            const fila = document.createElement("tr");

            const esEnSla = c.dias_totales <= 3;
            const badgeSlaStyle = esEnSla
                ? "color:#15803d; border-color:#15803d; background:rgba(21,128,61,0.08);"
                : "color:#dc2626; border-color:#dc2626; background:rgba(220,38,38,0.08);";

            const badgeTexto = esEnSla ? "EN SLA" : "DEMORADO";

            fila.innerHTML = `
                <td class="t-purple font-mono">${c.numero_caso}</td>
                <td class="font-mono">${c.cliente_nombre || 'N/A'}</td>
                <td class="font-weight-bold">${c.estado_actual || 'DESCONOCIDO'}</td>
                <td class="font-mono">${c.fecha_ingreso || '-'}</td>
                <td class="font-mono">${c.horas_primera_resp || 0}h</td>
                <td class="font-mono font-weight-bold">${c.dias_en_estado_actual || 0}d</td>
                <td class="font-mono font-weight-bold">${c.dias_totales || 0}d</td>
                <td class="font-mono text-neon-yellow font-weight-bold">${c.retrabajos || 0}</td>
                <td><span class="system-badge-live" style="${badgeSlaStyle}">${badgeTexto}</span></td>
            `;

            tablaSlaBody.appendChild(fila);
        });
    }

    if (buscarSlaInput) {
        buscarSlaInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaCasosSlaCache.filter(c =>
                (c.numero_caso && c.numero_caso.toLowerCase().includes(termino)) ||
                (c.cliente_nombre && c.cliente_nombre.toLowerCase().includes(termino)) ||
                (c.estado_actual && c.estado_actual.toLowerCase().includes(termino))
            );
            renderizarTablaSla(filtrados);
        });
    }

    const pollDom = setInterval(() => {
        if (document.getElementById("tablaSlaCasos")) {
            clearInterval(pollDom);
            cargarMetricasSLA();
        }
    }, 100);
})();