(function () {
    console.log("⚡ [KARDEX_LOG] Módulo de Auditoría de Movimientos Inicializado...");

    const API_MOVIMIENTOS = "../api-rma/inventario/stock-movimientos.php";

    // Elementos DOM
    const tablaMovimientosBody = document.querySelector("#tablaMovimientos tbody");
    const buscarMovimientoInput = document.getElementById("buscarMovimiento");
    const filtroTipoMovimiento = document.getElementById("filtroTipoMovimiento");
    const btnNuevoAjuste = document.getElementById("btnNuevoAjuste");

    // Modal Nativo
    const modalAjusteOverlay = document.getElementById("modalAjusteOverlay");
    const btnCerrarX = document.getElementById("btnCerrarAjusteX");
    const btnCancelar = document.getElementById("btnCancelarAjuste");
    const formAjuste = document.getElementById("formAjuste");

    const ajusteStockSelect = document.getElementById("ajuste_id_stock");

    let listaMovimientosCache = [];

    function abrirModal() { if (modalAjusteOverlay) modalAjusteOverlay.style.display = "flex"; }
    function cerrarModal() { if (modalAjusteOverlay) modalAjusteOverlay.style.display = "none"; if (formAjuste) formAjuste.reset(); }

    if (btnCerrarX) btnCerrarX.addEventListener("click", cerrarModal);
    if (btnCancelar) btnCancelar.addEventListener("click", cerrarModal);

    // ==========================================
    // 📚 1. CARGAR INSUMOS AUXILIARES EN MODAL
    // ==========================================
    async function cargarInsumosAux() {
        try {
            const res = await fetch(`${API_MOVIMIENTOS}?action=aux_stock`);
            const data = await res.json();

            if (data.status === "success" && ajusteStockSelect) {
                ajusteStockSelect.innerHTML = '<option value="">[SELECCIONE INSUMO]</option>' +
                    data.stock.map(s => `<option value="${s.id}">${s.nombre.toUpperCase()} (ACTUAL: ${s.cantidad} u.)</option>`).join('');
            }
        } catch (err) {
            console.error("🔴 Error cargando insumos auxiliares:", err);
        }
    }

    // ==========================================
    // 📡 2. CONSULTAR Y RENDERIZAR KARDEX
    // ==========================================
    async function cargarMovimientos() {
        if (!tablaMovimientosBody) return;

        try {
            const res = await fetch(`${API_MOVIMIENTOS}?action=listar`);
            const data = await res.json();

            if (data.status === "success") {
                listaMovimientosCache = data.movimientos || [];
                renderizarTabla(listaMovimientosCache);
            } else {
                tablaMovimientosBody.innerHTML = `<tr><td colspan="11" class="font-mono text-center">[NO_KARDEX_MOVEMENTS_FOUND]</td></tr>`;
            }
        } catch (err) {
            console.error("🔴 Error consultando movimientos de kardex:", err);
            tablaMovimientosBody.innerHTML = `<tr><td colspan="11" class="font-mono text-center text-danger">[ERROR_DE_CONEXION_SERVIDOR]</td></tr>`;
        }
    }

    function renderizarTabla(items) {
        if (!tablaMovimientosBody) return;
        tablaMovimientosBody.innerHTML = "";

        if (items.length === 0) {
            tablaMovimientosBody.innerHTML = `<tr><td colspan="11" class="font-mono text-center">[NO_KARDEX_MOVEMENTS_FOUND]</td></tr>`;
            return;
        }

        items.forEach(mov => {
            const fila = document.createElement("tr");

            // Badges por tipo de movimiento
            let badgeStyle = "color:#7e22ce; border-color:#7e22ce; background:rgba(126,34,206,0.08);";
            if (mov.tipo_movimiento === "ENTRADA") {
                badgeStyle = "color:#15803d; border-color:#15803d; background:rgba(21,128,61,0.08);";
            } else if (mov.tipo_movimiento === "SALIDA_RMA") {
                badgeStyle = "color:#dc2626; border-color:#dc2626; background:rgba(220,38,38,0.08);";
            } else if (mov.tipo_movimiento === "DEVOLUCION") {
                badgeStyle = "color:#d97706; border-color:#d97706; background:rgba(217,119,6,0.08);";
            }

            fila.innerHTML = `
                <td class="t-purple font-mono">${mov.id}</td>
                <td class="font-mono">${mov.fecha || ''}</td>
                <td><span class="system-badge-live" style="${badgeStyle}">${mov.tipo_movimiento}</span></td>
                <td class="font-weight-bold">${mov.insumo_nombre ? mov.insumo_nombre.toUpperCase() : ''}</td>
                <td class="font-mono">${mov.codigo_sku || 'S/N'}</td>
                <td class="font-mono font-weight-bold" style="font-size:0.95rem;">${mov.cantidad} u.</td>
                <td class="font-mono">${mov.stock_anterior} u.</td>
                <td class="font-mono font-weight-bold t-purple">${mov.stock_nuevo} u.</td>
                <td class="font-mono">${mov.numero_caso || '-'}</td>
                <td class="font-mono">${mov.usuario_nombre ? mov.usuario_nombre.toUpperCase() : 'SISTEMA'}</td>
                <td class="font-mono text-left">${mov.motivo || '-'}</td>
            `;

            tablaMovimientosBody.appendChild(fila);
        });
    }

    // ==========================================
    // 🔍 3. FILTROS Y BÚSQUEDA LOCAL
    // ==========================================
    function aplicarFiltros() {
        const termino = buscarMovimientoInput ? buscarMovimientoInput.value.toLowerCase().trim() : "";
        const tipo = filtroTipoMovimiento ? filtroTipoMovimiento.value : "";

        const filtrados = listaMovimientosCache.filter(mov => {
            const coincideTexto = (mov.insumo_nombre && mov.insumo_nombre.toLowerCase().includes(termino)) ||
                (mov.codigo_sku && mov.codigo_sku.toLowerCase().includes(termino)) ||
                (mov.usuario_nombre && mov.usuario_nombre.toLowerCase().includes(termino)) ||
                (mov.numero_caso && mov.numero_caso.toLowerCase().includes(termino)) ||
                (mov.motivo && mov.motivo.toLowerCase().includes(termino));

            const coincideTipo = tipo === "" || mov.tipo_movimiento === tipo;

            return coincideTexto && coincideTipo;
        });

        renderizarTabla(filtrados);
    }

    if (buscarMovimientoInput) buscarMovimientoInput.addEventListener("input", aplicarFiltros);
    if (filtroTipoMovimiento) filtroTipoMovimiento.addEventListener("change", aplicarFiltros);

    // ==========================================
    // 📥 4. POST AJUSTE MANUAL
    // ==========================================
    if (btnNuevoAjuste) {
        btnNuevoAjuste.addEventListener("click", () => {
            cargarInsumosAux();
            abrirModal();
        });
    }

    if (formAjuste) {
        formAjuste.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const datos = Object.fromEntries(new FormData(formAjuste));

            try {
                Swal.fire({
                    title: "EJECUTANDO AJUSTE...",
                    text: "Sincronizando inventario y registrando auditoría en kardex...",
                    background: '#ffffff',
                    color: '#0f172a',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const res = await fetch(`${API_MOVIMIENTOS}?action=ajustar`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });

                const resultado = await res.json();

                if (resultado.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'AJUSTE APLICADO',
                        text: resultado.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#7e22ce'
                    });
                    cerrarModal();
                    cargarMovimientos();
                } else {
                    throw new Error(resultado.message);
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'ADJUSTMENT_FAILED',
                    text: err.message,
                    background: '#ffffff',
                    color: '#dc2626',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    // Inicialización al cargar el DOM
    const verificarDom = setInterval(() => {
        if (document.getElementById("tablaMovimientos")) {
            clearInterval(verificarDom);
            cargarMovimientos();
        }
    }, 100);
})();