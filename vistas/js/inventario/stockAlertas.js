(function () {
    console.log("⚡ [STOCK_ALERT] Módulo de Alertas de Stock Crítico Inicializado...");

    const API_ALERTAS = "../api-rma/inventario/stock-alertas.php";

    // Elementos DOM
    const tablaAlertasBody = document.querySelector("#tablaAlertas tbody");
    const buscarAlertaInput = document.getElementById("buscarAlerta");
    const filtroTipoAlerta = document.getElementById("filtroTipoAlerta");
    const btnRecargarAlertas = document.getElementById("btnRecargarAlertas");

    // Elementos KPIs
    const kpiAgotados = document.getElementById("kpiAgotados");
    const kpiCriticos = document.getElementById("kpiCriticos");
    const kpiProveedoresImpactados = document.getElementById("kpiProveedoresImpactados");
    const kpiCostoEstimado = document.getElementById("kpiCostoEstimado");

    // Elementos Modal Restock Nativo
    const modalRestockOverlay = document.getElementById("modalRestockOverlay");
    const btnCerrarRestockX = document.getElementById("btnCerrarRestockX");
    const btnCancelarRestock = document.getElementById("btnCancelarRestock");
    const formRestock = document.getElementById("formRestock");
    const restockIdInput = document.getElementById("restock_id");
    const restockNombreDisplay = document.getElementById("restock_nombre_display");
    const restockStockActualDisplay = document.getElementById("restock_stock_actual_display");

    let listaAlertasCache = [];

    // ==========================================
    // 🔓 MODAL HANDLERS
    // ==========================================
    function abrirModalRestock() {
        if (modalRestockOverlay) modalRestockOverlay.style.display = "flex";
    }

    function cerrarModalRestock() {
        if (modalRestockOverlay) modalRestockOverlay.style.display = "none";
        if (formRestock) formRestock.reset();
    }

    if (btnCerrarRestockX) btnCerrarRestockX.addEventListener("click", cerrarModalRestock);
    if (btnCancelarRestock) btnCancelarRestock.addEventListener("click", cerrarModalRestock);

    // ==========================================
    // 📡 1. CONSULTAR ALERTAS Y KPIS
    // ==========================================
    async function cargarAlertas() {
        if (!tablaAlertasBody) return;

        try {
            const res = await fetch(`${API_ALERTAS}?action=consultar_alertas`);
            const data = await res.json();

            if (data.status === "success") {
                listaAlertasCache = data.alertas || [];
                actualizarKpis(data.kpis || {});
                renderizarTablaAlertas(listaAlertasCache);
            } else {
                tablaAlertasBody.innerHTML = `<tr><td colspan="9" class="font-mono text-center">[NO_CRITICAL_STOCK_ALERTS]</td></tr>`;
            }
        } catch (err) {
            console.error("🔴 Error al cargar alertas de stock:", err);
            tablaAlertasBody.innerHTML = `<tr><td colspan="9" class="font-mono text-center text-danger">[ERROR_DE_CONEXION_SERVIDOR]</td></tr>`;
        }
    }

    function actualizarKpis(kpis) {
        if (kpiAgotados) kpiAgotados.textContent = kpis.agotados || 0;
        if (kpiCriticos) kpiCriticos.textContent = kpis.criticos || 0;
        if (kpiProveedoresImpactados) kpiProveedoresImpactados.textContent = kpis.proveedores_impactados || 0;

        if (kpiCostoEstimado) {
            const monto = parseFloat(kpis.costo_reposicion_estimado || 0).toLocaleString('es-PY');
            kpiCostoEstimado.textContent = `₲ ${monto}`;
        }
    }

    function renderizarTablaAlertas(items) {
        if (!tablaAlertasBody) return;
        tablaAlertasBody.innerHTML = "";

        if (items.length === 0) {
            tablaAlertasBody.innerHTML = `<tr><td colspan="9" class="font-mono text-center">[NO_CRITICAL_STOCK_ALERTS]</td></tr>`;
            return;
        }

        items.forEach(item => {
            const fila = document.createElement("tr");

            const esAgotado = item.cantidad <= 0;
            const badgeClass = esAgotado ? "color:#dc2626; border-color:#dc2626; background:rgba(220,38,38,0.08);" : "color:#d97706; border-color:#d97706; background:rgba(217,119,6,0.08);";
            const estadoTexto = esAgotado ? "AGOTADO (0)" : "STOCK CRÍTICO";

            const deficit = item.stock_minimo - item.cantidad;

            fila.innerHTML = `
                <td class="font-mono">${item.codigo_sku || 'S/N'}</td>
                <td class="font-weight-bold">${item.nombre ? item.nombre.toUpperCase() : ''}</td>
                <td class="font-mono">${item.categoria_nombre ? item.categoria_nombre.toUpperCase() : 'GENERAL'}</td>
                <td class="font-mono">${item.proveedor_nombre ? item.proveedor_nombre.toUpperCase() : 'NO ASIGNADO'}</td>
                <td class="font-mono font-weight-bold" style="font-size:0.95rem;">${item.cantidad}</td>
                <td class="font-mono">${item.stock_minimo}</td>
                <td class="font-mono text-neon-red font-weight-bold">+${deficit > 0 ? deficit : 1} u.</td>
                <td><span class="system-badge-live" style="${badgeClass}">${estadoTexto}</span></td>
                <td>
                    <button type="button" class="btn-terminal-restock" data-id="${item.id}" data-nombre="${item.nombre}" data-actual="${item.cantidad}">
                        <i class="fa fa-plus-circle"></i> [REABASTECER]
                    </button>
                </td>
            `;

            tablaAlertasBody.appendChild(fila);
        });

        asignarEventosRestock();
    }

    // ==========================================
    // 🔍 2. FILTRADO LOCAL
    // ==========================================
    function aplicarFiltros() {
        const termino = buscarAlertaInput ? buscarAlertaInput.value.toLowerCase().trim() : "";
        const tipo = filtroTipoAlerta ? filtroTipoAlerta.value : "todos";

        const filtrados = listaAlertasCache.filter(item => {
            const coincideTexto = (item.nombre && item.nombre.toLowerCase().includes(termino)) ||
                (item.codigo_sku && item.codigo_sku.toLowerCase().includes(termino)) ||
                (item.proveedor_nombre && item.proveedor_nombre.toLowerCase().includes(termino));

            let coincideTipo = true;
            if (tipo === "agotados") coincideTipo = item.cantidad <= 0;
            if (tipo === "criticos") coincideTipo = item.cantidad > 0 && item.cantidad <= item.stock_minimo;

            return coincideTexto && coincideTipo;
        });

        renderizarTablaAlertas(filtrados);
    }

    if (buscarAlertaInput) buscarAlertaInput.addEventListener("input", aplicarFiltros);
    if (filtroTipoAlerta) filtroTipoAlerta.addEventListener("change", aplicarFiltros);
    if (btnRecargarAlertas) btnRecargarAlertas.addEventListener("click", cargarAlertas);

    // ==========================================
    // 📦 3. EVENTOS Y PROCESAMIENTO DE REABASTECIMIENTO
    // ==========================================
    function asignarEventosRestock() {
        document.querySelectorAll("#tablaAlertas .btn-terminal-restock").forEach(btn => {
            btn.onclick = function (e) {
                e.stopPropagation();
                const id = this.getAttribute("data-id");
                const nombre = this.getAttribute("data-nombre");
                const actual = this.getAttribute("data-actual");

                if (restockIdInput) restockIdInput.value = id;
                if (restockNombreDisplay) restockNombreDisplay.value = nombre.toUpperCase();
                if (restockStockActualDisplay) restockStockActualDisplay.value = `${actual} Unidades`;

                abrirModalRestock();
            };
        });
    }

    if (formRestock) {
        formRestock.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const datos = Object.fromEntries(new FormData(formRestock));

            try {
                Swal.fire({
                    title: "PROCESANDO REABASTECIMIENTO...",
                    background: '#ffffff',
                    color: '#0f172a',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const res = await fetch(`${API_ALERTAS}?action=reabastecer`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });

                const resultado = await res.json();

                if (resultado.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'REABASTECIMIENTO EXITOSO',
                        text: resultado.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#f97316'
                    });
                    cerrarModalRestock();
                    cargarAlertas();
                } else {
                    throw new Error(resultado.message);
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'RESTOCK_FAILED',
                    text: err.message,
                    background: '#ffffff',
                    color: '#dc2626',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    // Inicialización automática al cargar el DOM
    const verificarDom = setInterval(() => {
        if (document.getElementById("tablaAlertas")) {
            clearInterval(verificarDom);
            cargarAlertas();
        }
    }, 100);
})();