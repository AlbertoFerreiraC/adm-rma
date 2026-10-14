(function () {
    console.log("📦 [SYS_INV] Módulo de Control Logístico y Kardex iniciado...");

    // Endpoints de la API de Inventario
    const API_INVENTARIO = "../api-rma/inventario/inventario.php";
    const API_ALERTAS = "../api-rma/inventario/alertas.php";

    let listaStockCache = [];

    // ==========================================
    // 🚀 INICIALIZACIÓN Y CARGA DE DATOS
    // ==========================================
    async function inicializarDashboardInventario() {
        mostrarLoader(true);
        await Promise.all([
            cargarMetricasKPIs(),
            cargarTablaInventario()
        ]);
        mostrarLoader(false);
    }

    function mostrarLoader(mostrar) {
        const loader = document.getElementById("cyberLoaderDashboard");
        if (loader) {
            loader.style.opacity = mostrar ? "1" : "0";
            loader.style.visibility = mostrar ? "visible" : "hidden";
        }
    }

    // ==========================================
    // 📊 1. CARGAR KPIs Y MÉTRICAS GLOBALES
    // ==========================================
    async function cargarMetricasKPIs() {
        try {
            const res = await fetch(`${API_ALERTAS}?action=consultar_alertas`);
            const data = await res.json();

            if (data.status === "success") {
                const kpis = data.kpis || {};

                // Actualizar contadores en las tarjetas KPI superiores
                document.getElementById("kpiTotalProductos").textContent = kpis.total_productos || 0;
                document.getElementById("kpiUnidadesStock").textContent = kpis.unidades_totales || 0;
                document.getElementById("kpiStockRma").textContent = kpis.en_proceso_rma || 0;

                const totalAlertas = (kpis.sin_stock || 0) + (kpis.stock_bajo || 0);
                const elAlertas = document.getElementById("kpiAlertasStock");
                elAlertas.textContent = totalAlertas;

                if (totalAlertas > 0) {
                    elAlertas.classList.add("text-danger");
                } else {
                    elAlertas.classList.remove("text-danger");
                }
            }
        } catch (error) {
            console.error("🔴 Error cargando KPIs de inventario:", error);
        }
    }

    // ==========================================
    // 📋 2. CARGAR TABLA DE INVENTARIO Y STOCK
    // ==========================================
    async function cargarTablaInventario() {
        const tbody = document.querySelector("#tablaStockInventario tbody");
        if (!tbody) return;

        try {
            const res = await fetch(`${API_INVENTARIO}?action=listar`);
            const data = await res.json();

            if (data.status === "success" && data.stock) {
                listaStockCache = data.stock;
                renderizarTablaStock(listaStockCache);
            } else {
                tbody.innerHTML = `<tr><td colspan="9" class="font-mono text-center">NO SE ENCONTRARON REGISTROS DE STOCK</td></tr>`;
            }
        } catch (error) {
            console.error("🔴 Error cargando inventario:", error);
            tbody.innerHTML = `<tr><td colspan="9" class="font-mono text-center text-danger">ERROR DE CONEXIÓN CON LA MATRIZ DE STOCK</td></tr>`;
        }
    }

    function renderizarTablaStock(items) {
        const tbody = document.querySelector("#tablaStockInventario tbody");
        if (!tbody) return;

        tbody.innerHTML = "";

        if (items.length === 0) {
            tbody.innerHTML = `<tr><td colspan="9" class="font-mono text-center">NO HAY RESULTADOS DISPONIBLES</td></tr>`;
            return;
        }

        items.forEach(item => {
            const tr = document.createElement("tr");

            // Determinar color de badge según alerta
            let badgeClass = "system-badge-live";
            if (item.estado_alerta === "SIN STOCK") {
                badgeClass = "badge-status-danger";
            } else if (item.estado_alerta === "ALERTA - STOCK BAJO") {
                badgeClass = "badge-status-warning";
            }

            tr.innerHTML = `
                <td class="t-cyan font-mono font-weight-bold">${item.codigo}</td>
                <td style="text-align: left; padding-left: 12px;">${item.nombre}</td>
                <td>${item.categoria || 'N/A'}</td>
                <td>${item.marca || 'N/A'}</td>
                <td class="font-mono">${item.stock_actual}</td>
                <td class="font-mono text-yellow">${item.stock_rma}</td>
                <td class="font-mono font-weight-bold">${item.stock_disponible}</td>
                <td><span class="${badgeClass}">${item.estado_alerta}</span></td>
                <td>
                    <button type="button" class="btn-terminal-edit" data-id="${item.id}" title="Editar / Ajustar Insumo">
                        <i class="fa fa-edit"></i> EDIT
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    }

    // ==========================================
    // 🔍 3. BUSCADOR EN TIEMPO REAL
    // ==========================================
    const buscarInput = document.getElementById("buscarInventario");
    if (buscarInput) {
        buscarInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaStockCache.filter(item =>
                item.codigo.toLowerCase().includes(termino) ||
                item.nombre.toLowerCase().includes(termino) ||
                (item.marca && item.marca.toLowerCase().includes(termino)) ||
                (item.categoria && item.categoria.toLowerCase().includes(termino))
            );
            renderizarTablaStock(filtrados);
        });
    }

    // ==========================================
    // ⚙️ 4. ACCIONES DE BOTONERA (Remisiones / Movimientos)
    // ==========================================
    const btnNuevaRemision = document.getElementById("btnNuevaRemision");
    if (btnNuevaRemision) {
        btnNuevaRemision.addEventListener("click", () => {
            Swal.fire({
                icon: 'info',
                title: 'MÓDULO DE REMISIONES',
                text: 'Asistente de notas de remisión en desarrollo para el siguiente ciclo operativo.',
                background: '#ffffff',
                color: '#0f172a',
                confirmButtonColor: '#f97316'
            });
        });
    }

    const btnRegistrarMovimiento = document.getElementById("btnRegistrarMovimiento");
    if (btnRegistrarMovimiento) {
        btnRegistrarMovimiento.addEventListener("click", () => {
            Swal.fire({
                icon: 'info',
                title: 'KARDEX Y MOVIMIENTOS',
                text: 'Utiliza el panel de reabastecimiento o asignación en taller para actualizar existencias.',
                background: '#ffffff',
                color: '#0f172a',
                confirmButtonColor: '#f97316'
            });
        });
    }

    // Delegación de eventos para edición rápida desde la tabla
    document.addEventListener("click", function (e) {
        const btnEdit = e.target.closest(".btn-terminal-edit");
        if (btnEdit) {
            const id = btnEdit.getAttribute("data-id");
            Swal.fire({
                title: 'CONFIGURAR INSUMO',
                text: `Redirigiendo a parámetros del producto ID: ${id}`,
                icon: 'question',
                background: '#ffffff',
                color: '#0f172a',
                confirmButtonColor: '#f97316'
            });
        }
    });

    // Iniciar ejecución automática
    inicializarDashboardInventario();

})();