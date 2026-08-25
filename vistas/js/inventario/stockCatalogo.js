(function () {
    console.log("⚡ [STOCK_CORE] Módulo de Catálogo y Control de Inventario Inicializado...");

    const API_STOCK = "../api-rma/inventario/stock-catalogo.php";

    // Elementos DOM
    const formStock = document.getElementById("formStock");
    const tablaStockBody = document.querySelector("#tablaStock tbody");
    const buscarStockInput = document.getElementById("buscarStock");
    const filtroCategoriaStock = document.getElementById("filtroCategoriaStock");

    // Elementos Modal Nativo
    const modalOverlay = document.getElementById("modalStockOverlay");
    const btnCerrarModalX = document.getElementById("btnCerrarModalX");
    const btnCancelarModal = document.getElementById("btnCancelarModal");
    const lblTituloModal = document.getElementById("lblTituloModal");
    const btnNuevoItemStock = document.getElementById("btnNuevoItemStock");
    const btnGuardar = document.getElementById("btnGuardarStock");

    // Campos del Formulario
    const stockIdInput = document.getElementById("stock_id");
    const stockSkuInput = document.getElementById("stock_codigo_sku");
    const stockCategoriaSelect = document.getElementById("stock_id_categoria");
    const stockNombreInput = document.getElementById("stock_nombre");
    const stockDescripcionInput = document.getElementById("stock_descripcion");
    const stockCantidadInput = document.getElementById("stock_cantidad");
    const stockMinimoInput = document.getElementById("stock_stock_minimo");
    const stockCostoInput = document.getElementById("stock_costo_unitario");
    const stockPrecioInput = document.getElementById("stock_precio_venta");
    const stockProveedorSelect = document.getElementById("stock_id_proveedor_habitual");

    let listaStockCache = [];
    let esModoEdicion = false;

    // ==========================================
    // 🔓 APERTURA / CIERRE DE MODAL NATIVO
    // ==========================================
    function abrirModal() {
        if (modalOverlay) modalOverlay.style.display = "flex";
    }

    function cerrarModal() {
        if (modalOverlay) modalOverlay.style.display = "none";
        limpiarFormulario();
    }

    if (btnCerrarModalX) btnCerrarModalX.addEventListener("click", cerrarModal);
    if (btnCancelarModal) btnCancelarModal.addEventListener("click", cerrarModal);

    // ==========================================
    // 🚀 1. INICIANILIZAR CATÁLOGOS AUXILIARES
    // ==========================================
    async function cargarAuxiliares() {
        try {
            // Cargar Categorías
            const resCat = await fetch(`${API_STOCK}?action=aux_categorias`);
            const dataCat = await resCat.json();
            if (dataCat.status === "success") {
                const options = '<option value="">[SELECCIONE CATEGORÍA]</option>' +
                    dataCat.categorias.map(c => `<option value="${c.id}">${c.nombre.toUpperCase()}</option>`).join('');

                if (stockCategoriaSelect) stockCategoriaSelect.innerHTML = options;

                if (filtroCategoriaStock) {
                    filtroCategoriaStock.innerHTML = '<option value="">[ TODAS LAS CATEGORÍAS ]</option>' +
                        dataCat.categorias.map(c => `<option value="${c.id}">${c.nombre.toUpperCase()}</option>`).join('');
                }
            }

            // Cargar Proveedores Habituales
            const resProv = await fetch(`${API_STOCK}?action=aux_proveedores`);
            const dataProv = await resProv.json();
            if (dataProv.status === "success" && stockProveedorSelect) {
                stockProveedorSelect.innerHTML = '<option value="">[NINGUNO / VARIOS]</option>' +
                    dataProv.proveedores.map(p => `<option value="${p.id}">${p.nombre.toUpperCase()}</option>`).join('');
            }

        } catch (err) {
            console.error("🔴 Error cargando catálogos auxiliares de stock:", err);
        }
    }

    // ==========================================
    // 📡 2. CARGAR Y RENDERIZAR TABLA DE STOCK
    // ==========================================
    async function cargarStock() {
        if (!tablaStockBody) return;

        try {
            const respuesta = await fetch(`${API_STOCK}?action=listar`);
            const data = await respuesta.json();

            if (data.status === "success") {
                listaStockCache = data.stock || [];
                renderizarTablaStock(listaStockCache);
            } else {
                tablaStockBody.innerHTML = `<tr><td colspan="9" class="font-mono text-center">[NO_INVENTORY_ITEMS_FOUND]</td></tr>`;
            }
        } catch (error) {
            console.error("🔴 Error de conexión al consultar el stock:", error);
            tablaStockBody.innerHTML = `<tr><td colspan="9" class="font-mono text-center text-danger">[ERROR_DE_CONEXION_SERVIDOR]</td></tr>`;
        }
    }

    function renderizarTablaStock(items) {
        if (!tablaStockBody) return;
        tablaStockBody.innerHTML = "";

        if (items.length === 0) {
            tablaStockBody.innerHTML = `<tr><td colspan="9" class="font-mono text-center">[NO_INVENTORY_ITEMS_FOUND]</td></tr>`;
            return;
        }

        items.forEach(st => {
            const fila = document.createElement("tr");

            // Indicador de Nivel de Stock
            let badgeStockStyle = "color:#15803d; border-color:#15803d; background:rgba(21,128,61,0.08);";
            let estadoTexto = "OK";

            if (st.cantidad <= 0) {
                badgeStockStyle = "color:#dc2626; border-color:#dc2626; background:rgba(220,38,38,0.08);";
                estadoTexto = "AGOTADO";
            } else if (st.cantidad <= st.stock_minimo) {
                badgeStockStyle = "color:#d97706; border-color:#d97706; background:rgba(217,119,6,0.08);";
                estadoTexto = "CRÍTICO";
            }

            const costoFormateado = parseFloat(st.costo_unitario || 0).toLocaleString('es-PY');
            const precioFormateado = st.precio_venta ? parseFloat(st.precio_venta).toLocaleString('es-PY') : 'N/A';

            fila.innerHTML = `
                <td class="t-orange font-mono">${st.id}</td>
                <td class="font-mono">${st.codigo_sku || 'S/N'}</td>
                <td class="font-weight-bold">${st.nombre ? st.nombre.toUpperCase() : ''}</td>
                <td class="font-mono">${st.categoria_nombre ? st.categoria_nombre.toUpperCase() : 'GENERAL'}</td>
                <td class="font-mono font-weight-bold" style="font-size:0.95rem;">${st.cantidad}</td>
                <td class="font-mono">₲ ${costoFormateado}</td>
                <td class="font-mono">₲ ${precioFormateado}</td>
                <td><span class="system-badge-live" style="${badgeStockStyle}">${estadoTexto}</span></td>
                <td>
                    <button type="button" class="btn-terminal-edit" data-id="${st.id}" title="Editar Insumo">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn-terminal-delete" data-id="${st.id}" title="Eliminar Insumo">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            `;

            tablaStockBody.appendChild(fila);
        });

        asignarEventosAcciones();
    }

    // ==========================================
    // 🔍 3. FILTROS Y BUSCADORES
    // ==========================================
    function aplicarFiltros() {
        const termino = buscarStockInput ? buscarStockInput.value.toLowerCase().trim() : "";
        const idCat = filtroCategoriaStock ? filtroCategoriaStock.value : "";

        const filtrados = listaStockCache.filter(st => {
            const coincideTexto = (st.nombre && st.nombre.toLowerCase().includes(termino)) ||
                (st.codigo_sku && st.codigo_sku.toLowerCase().includes(termino)) ||
                (st.descripcion && st.descripcion.toLowerCase().includes(termino));

            const coincideCategoria = idCat === "" || st.id_categoria == idCat;

            return coincideTexto && coincideCategoria;
        });

        renderizarTablaStock(filtrados);
    }

    if (buscarStockInput) buscarStockInput.addEventListener("input", aplicarFiltros);
    if (filtroCategoriaStock) filtroCategoriaStock.addEventListener("change", aplicarFiltros);

    // ==========================================
    // 📥 4. GUARDAR O ACTUALIZAR INSUMO
    // ==========================================
    if (formStock) {
        formStock.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const datos = Object.fromEntries(new FormData(formStock));
            const esEdicion = datos.id && datos.id.trim() !== "";
            const accion = esEdicion ? "actualizar" : "guardar";

            try {
                Swal.fire({
                    title: "PERSISTIENDO REGISTRO...",
                    text: "Sincronizando inventario de taller...",
                    background: '#ffffff',
                    color: '#0f172a',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const respuesta = await fetch(`${API_STOCK}?action=${accion}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });
                const resultado = await respuesta.json();

                if (resultado.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: esEdicion ? 'INSUMO ACTUALIZADO' : 'INSUMO INGRESADO',
                        text: resultado.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#f97316'
                    });
                    cerrarModal();
                    cargarStock();
                } else {
                    throw new Error(resultado.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'OPERATIONAL_FAIL',
                    text: error.message,
                    background: '#ffffff',
                    color: '#dc2626',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    if (btnNuevoItemStock) {
        btnNuevoItemStock.addEventListener("click", () => {
            limpiarFormulario();
            if (lblTituloModal) lblTituloModal.textContent = "[INJECT_STOCK_ITEM]";
            if (btnGuardar) btnGuardar.textContent = "[EXECUTE_DEPLOYMENT]";
            if (btnCancelarModal) btnCancelarModal.classList.add("hidden");
            abrirModal();
        });
    }

    // ==========================================
    // ⚙️ 5. ASIGNACIÓN DE EVENTOS EN FILAS
    // ==========================================
    function asignarEventosAcciones() {
        // Clic Editar
        document.querySelectorAll("#tablaStock .btn-terminal-edit").forEach(btn => {
            btn.onclick = async function (e) {
                e.stopPropagation();
                const id = this.getAttribute("data-id");

                try {
                    Swal.fire({
                        title: 'CONSULTANDO STOCK...',
                        background: '#ffffff',
                        color: '#0f172a',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    const res = await fetch(`${API_STOCK}?action=obtener&id=${id}`);
                    const data = await res.json();
                    Swal.close();

                    if (data.status === "success") {
                        const st = data.stock;

                        if (stockIdInput) stockIdInput.value = st.id;
                        if (stockSkuInput) stockSkuInput.value = st.codigo_sku || '';
                        if (stockCategoriaSelect) stockCategoriaSelect.value = st.id_categoria || '';
                        if (stockNombreInput) stockNombreInput.value = st.nombre || '';
                        if (stockDescripcionInput) stockDescripcionInput.value = st.descripcion || '';
                        if (stockCantidadInput) stockCantidadInput.value = st.cantidad || 0;
                        if (stockMinimoInput) stockMinimoInput.value = st.stock_minimo || 2;
                        if (stockCostoInput) stockCostoInput.value = st.costo_unitario || '';
                        if (stockPrecioInput) stockPrecioInput.value = st.precio_venta || '';
                        if (stockProveedorSelect) stockProveedorSelect.value = st.id_proveedor_habitual || '';

                        esModoEdicion = true;

                        if (lblTituloModal) lblTituloModal.textContent = `[EDITAR_INSUMO: ${st.nombre.toUpperCase()}]`;
                        if (btnGuardar) btnGuardar.textContent = "[UPDATE_DEPLOY]";
                        if (btnCancelarModal) btnCancelarModal.classList.remove("hidden");

                        abrirModal();
                    } else {
                        Swal.fire("ERROR", data.message, "error");
                    }
                } catch (err) {
                    Swal.close();
                    Swal.fire("ERROR", "Fallo al obtener datos del insumo.", "error");
                }
            };
        });

        // Clic Eliminar
        document.querySelectorAll("#tablaStock .btn-terminal-delete").forEach(btn => {
            btn.onclick = async function (e) {
                e.stopPropagation();
                const id = this.getAttribute("data-id");

                const confirmar = await Swal.fire({
                    title: '¿PURGAR INSUMO?',
                    text: `Confirmar eliminación del registro de stock ID: ${id}`,
                    icon: 'warning',
                    showCancelButton: true,
                    background: '#ffffff',
                    color: '#0f172a',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#475569',
                    confirmButtonText: '[CONFIRM_PURGE]',
                    cancelButtonText: 'CANCELAR'
                });

                if (confirmar.isConfirmed) {
                    try {
                        const respuesta = await fetch(`${API_STOCK}?action=eliminar&id=${id}`, { method: "DELETE" });
                        const resultado = await respuesta.json();

                        if (resultado.status === "success") {
                            Swal.fire({
                                title: 'PURGADO',
                                text: resultado.message,
                                icon: 'success',
                                background: '#ffffff',
                                color: '#0f172a',
                                confirmButtonColor: '#f97316'
                            });
                            cargarStock();
                        } else {
                            throw new Error(resultado.message);
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'CANNOT_PURGE',
                            text: error.message,
                            background: '#ffffff',
                            color: '#dc2626',
                            confirmButtonColor: '#dc2626'
                        });
                    }
                }
            };
        });
    }

    function limpiarFormulario() {
        if (formStock) formStock.reset();
        if (stockIdInput) stockIdInput.value = "";
        esModoEdicion = false;
        if (btnGuardar) btnGuardar.textContent = "[EXECUTE_DEPLOYMENT]";
        if (btnCancelarModal) btnCancelarModal.classList.add("hidden");
    }

    // Inicialización automática al cargar el DOM
    const verificarDom = setInterval(() => {
        if (document.getElementById("tablaStock")) {
            clearInterval(verificarDom);
            cargarAuxiliares().then(() => cargarStock());
        }
    }, 100);
})();