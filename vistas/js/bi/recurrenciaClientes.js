(function () {
    console.log("⚡ [BI_LOYALTY] Inicializando Módulo de Recurrencia de Clientes...");

    const API_CLIENTES = "../api-rma/bi/recurrencia-clientes.php";

    let chartTopClientesInst = null;
    let chartSegmentacionInst = null;

    const loader = document.getElementById("cyberLoaderClientes");
    const tablaClientesBody = document.querySelector("#tablaRecurrenciaClientes tbody");
    const buscarClienteInput = document.getElementById("buscarCliente");
    let listaClientesCache = [];

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

    async function cargarMetricasClientes() {
        mostrarLoader();
        try {
            const res = await fetch(`${API_CLIENTES}?action=consultar_recurrencia`);

            if (!res.ok) {
                throw new Error(`HTTP Error Status: ${res.status}`);
            }

            const data = await res.json();

            if (data.status === "success") {
                renderizarKPIs(data.kpis);
                renderizarGraficoTopClientes(data.top_clientes);
                renderizarGraficoSegmentacion(data.segmentacion);

                listaClientesCache = data.clientes || [];
                renderizarTablaClientes(listaClientesCache);
            } else {
                console.error("🔴 Error API Clientes:", data.message);
            }
        } catch (err) {
            console.error("🔴 Error de conexión en Recurrencia de Clientes:", err);
        } finally {
            ocultarLoader();
        }
    }

    function renderizarKPIs(kpis) {
        if (!kpis) return;
        if (document.getElementById("kpiTotalClientesRma")) document.getElementById("kpiTotalClientesRma").textContent = kpis.total_clientes || 0;
        if (document.getElementById("kpiIndiceRecurrencia")) document.getElementById("kpiIndiceRecurrencia").textContent = `${kpis.indice_recurrencia || 0}%`;
        if (document.getElementById("kpiTopCliente")) document.getElementById("kpiTopCliente").textContent = kpis.top_cliente_nombre || 'N/A';
        if (document.getElementById("kpiSlaPromedioCliente")) document.getElementById("kpiSlaPromedioCliente").textContent = `${kpis.sla_promedio_cliente || 0}d`;
    }

    function renderizarGraficoTopClientes(clientes) {
        const ctx = document.getElementById('chartTopClientesRma');
        if (!ctx || !clientes) return;

        const labels = clientes.map(c => c.nombre.length > 15 ? c.nombre.substring(0, 15) + '...' : c.nombre);
        const cantidades = clientes.map(c => parseInt(c.total_casos));

        if (chartTopClientesInst) chartTopClientesInst.destroy();

        chartTopClientesInst = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Casos de RMA Tramitados',
                    data: cantidades,
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

    function renderizarGraficoSegmentacion(segmentos) {
        const ctx = document.getElementById('chartSegmentacionClientes');
        if (!ctx || !segmentos) return;

        if (chartSegmentacionInst) chartSegmentacionInst.destroy();

        chartSegmentacionInst = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['1 Único RMA', '2 - 3 RMAs (Ocasional)', '> 3 RMAs (Alta Recurrencia)'],
                datasets: [{
                    data: [segmentos.unico || 0, segmentos.ocasional || 0, segmentos.alto || 0],
                    backgroundColor: ['#15803d', '#d97706', '#dc2626'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#475569', font: { family: "'Share Tech Mono', monospace", size: 10 } } } },
                cutout: '65%'
            }
        });
    }

    function renderizarTablaClientes(items) {
        if (!tablaClientesBody) return;
        tablaClientesBody.innerHTML = "";

        if (items.length === 0) {
            tablaClientesBody.innerHTML = `<tr><td colspan="7" class="font-mono text-center">[NO_CLIENT_LOYALTY_DATA]</td></tr>`;
            return;
        }

        items.forEach(c => {
            const fila = document.createElement("tr");

            const total = parseInt(c.total_casos);
            let badgeStyle = "color:#15803d; border-color:#15803d; background:rgba(21,128,61,0.08);";
            let estatusTexto = "ESTÁNDAR (1)";

            if (total > 3) {
                badgeStyle = "color:#dc2626; border-color:#dc2626; background:rgba(220,38,38,0.08);";
                estatusTexto = "ALTA RECURRENCIA";
            } else if (total > 1) {
                badgeStyle = "color:#d97706; border-color:#d97706; background:rgba(217,119,6,0.08);";
                estatusTexto = "RECURRENTE (2-3)";
            }

            fila.innerHTML = `
                <td class="font-weight-bold font-mono t-purple">${c.nombre.toUpperCase()}</td>
                <td class="font-mono">${c.documento || 'S/N'}</td>
                <td class="font-mono">${c.telefono || 'S/N'}</td>
                <td class="font-mono font-weight-bold t-cyan">${c.total_casos}</td>
                <td class="font-mono text-neon-green font-weight-bold">${c.casos_resueltos}</td>
                <td class="font-mono font-weight-bold">${c.sla_promedio}d</td>
                <td><span class="system-badge-live" style="${badgeStyle}">${estatusTexto}</span></td>
            `;

            tablaClientesBody.appendChild(fila);
        });
    }

    if (buscarClienteInput) {
        buscarClienteInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaClientesCache.filter(c =>
                c.nombre.toLowerCase().includes(termino) ||
                (c.documento && c.documento.toLowerCase().includes(termino)) ||
                (c.telefono && c.telefono.toLowerCase().includes(termino))
            );
            renderizarTablaClientes(filtrados);
        });
    }

    const pollDom = setInterval(() => {
        if (document.getElementById("tablaRecurrenciaClientes")) {
            clearInterval(pollDom);
            cargarMetricasClientes();
        }
    }, 100);
})();