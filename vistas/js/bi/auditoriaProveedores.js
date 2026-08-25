(function () {
    console.log("⚡ [BI_VENDOR] Inicializando Auditoría de Proveedores...");

    const API_PROV = "../api-rma/bi/auditoria-proveedores.php";

    let chartVolumenInst = null;
    let chartSlaInst = null;

    const loader = document.getElementById("cyberLoaderProv");
    const tablaProvBody = document.querySelector("#tablaAuditoriaProveedores tbody");
    const buscarProvInput = document.getElementById("buscarProveedor");
    let listaProveedoresCache = [];

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

    async function cargarMetricasProveedores() {
        mostrarLoader();
        try {
            const res = await fetch(`${API_PROV}?action=consultar_proveedores`);

            if (!res.ok) {
                throw new Error(`HTTP Error Status: ${res.status}`);
            }

            const data = await res.json();

            if (data.status === "success") {
                renderizarKPIs(data.kpis);
                renderizarGraficoVolumen(data.proveedores);
                renderizarGraficoSla(data.proveedores);

                listaProveedoresCache = data.proveedores || [];
                renderizarTablaProveedores(listaProveedoresCache);
            } else {
                console.error("🔴 Error API Proveedores:", data.message);
            }
        } catch (err) {
            console.error("🔴 Error de conexión en Auditoría de Proveedores:", err);
        } finally {
            ocultarLoader();
        }
    }

    function renderizarKPIs(kpis) {
        if (!kpis) return;
        if (document.getElementById("kpiTotalProveedores")) document.getElementById("kpiTotalProveedores").textContent = kpis.total_proveedores || 0;
        if (document.getElementById("kpiSlaProveedorPromedio")) document.getElementById("kpiSlaProveedorPromedio").textContent = `${kpis.sla_promedio_global || 0}d`;
        if (document.getElementById("kpiCasosEnProveedor")) document.getElementById("kpiCasosEnProveedor").textContent = kpis.casos_en_gestion_externa || 0;
        if (document.getElementById("kpiTopProveedorIncidencia")) document.getElementById("kpiTopProveedorIncidencia").textContent = kpis.top_proveedor || 'N/A';
    }

    function renderizarGraficoVolumen(proveedores) {
        const ctx = document.getElementById('chartVolumenProveedores');
        if (!ctx || !proveedores) return;

        const labels = proveedores.map(p => p.nombre);
        const casos = proveedores.map(p => parseInt(p.casos_derivados));

        if (chartVolumenInst) chartVolumenInst.destroy();

        chartVolumenInst = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Casos RMA Derivados',
                    data: casos,
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

    function renderizarGraficoSla(proveedores) {
        const ctx = document.getElementById('chartSlaProveedores');
        if (!ctx || !proveedores) return;

        const labels = proveedores.map(p => p.nombre);
        const slas = proveedores.map(p => parseFloat(p.sla_dias));

        if (chartSlaInst) chartSlaInst.destroy();

        chartSlaInst = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Promedio Días de Respuesta',
                    data: slas,
                    backgroundColor: '#0284c7',
                    borderRadius: 3
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

    function renderizarTablaProveedores(items) {
        if (!tablaProvBody) return;
        tablaProvBody.innerHTML = "";

        if (items.length === 0) {
            tablaProvBody.innerHTML = `<tr><td colspan="7" class="font-mono text-center">[NO_VENDOR_AUDIT_DATA]</td></tr>`;
            return;
        }

        items.forEach(p => {
            const fila = document.createElement("tr");

            const sla = parseFloat(p.sla_dias);
            let badgeStyle = "color:#15803d; border-color:#15803d; background:rgba(21,128,61,0.08);";
            let calificacion = "ÓPTIMO (< 7d)";

            if (sla > 14) {
                badgeStyle = "color:#dc2626; border-color:#dc2626; background:rgba(220,38,38,0.08);";
                calificacion = "CRÍTICO (> 14d)";
            } else if (sla > 7) {
                badgeStyle = "color:#d97706; border-color:#d97706; background:rgba(217,119,6,0.08);";
                calificacion = "MODERADO (7-14d)";
            }

            fila.innerHTML = `
                <td class="font-weight-bold font-mono t-purple">${p.nombre.toUpperCase()}</td>
                <td class="font-mono">${p.contacto || 'SIN DATOS'}</td>
                <td class="font-mono font-weight-bold">${p.insumos_habituales} u.</td>
                <td class="font-mono font-weight-bold t-cyan">${p.casos_derivados}</td>
                <td class="font-mono font-weight-bold">${p.sla_dias}d</td>
                <td class="font-mono text-neon-yellow font-weight-bold">${p.casos_pendientes}</td>
                <td><span class="system-badge-live" style="${badgeStyle}">${calificacion}</span></td>
            `;

            tablaProvBody.appendChild(fila);
        });
    }

    if (buscarProvInput) {
        buscarProvInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaProveedoresCache.filter(p =>
                p.nombre.toLowerCase().includes(termino) ||
                (p.contacto && p.contacto.toLowerCase().includes(termino))
            );
            renderizarTablaProveedores(filtrados);
        });
    }

    const pollDom = setInterval(() => {
        if (document.getElementById("tablaAuditoriaProveedores")) {
            clearInterval(pollDom);
            cargarMetricasProveedores();
        }
    }, 100);
})();