(function () {
    console.log("⚡ [COMMS_NODE] Inicializando módulo de Directorio de Proveedores (Modo Modal Nativo)...");

    const API_PROVEEDORES = "/rma-app/api-rma/clientes/proveedores.php";

    // Formulario y Modal Nativo
    const formProveedor = document.getElementById("formProveedor");
    const modalOverlay = document.getElementById("modalProveedorOverlay");
    const btnCerrarModalX = document.getElementById("btnCerrarModalX");
    const btnCancelarModal = document.getElementById("btnCancelarModal");
    const lblTituloModal = document.getElementById("lblTituloModal");
    const btnNuevoProveedor = document.getElementById("btnNuevoProveedor");

    // Elementos de Tabla y Buscador
    const tablaProveedoresBody = document.querySelector("#tablaProveedores tbody");
    const buscarProveedorInput = document.getElementById("buscarProveedor");

    // Historial Proveedor
    const panelHistorial = document.getElementById("panelHistorialProveedor");
    const labelProveedorNombre = document.getElementById("labelProveedorNombre");
    const tablaHistorialBody = document.querySelector("#tablaHistorialProvRma tbody");

    // Campos del Formulario Modal
    const proveedorIdInput = document.getElementById("proveedor_id");
    const proveedorNombreInput = document.getElementById("proveedor_nombre");
    const proveedorContactoInput = document.getElementById("proveedor_contacto");
    const btnGuardar = document.getElementById("btnGuardarProveedor");

    let listaProveedoresCache = [];
    let esModoEdicion = false;

    // ==========================================
    // 🔓 APERTURA / CIERRE DE MODAL NATIVO
    // ==========================================
    function abrirModal() {
        if (modalOverlay) {
            modalOverlay.style.display = "flex";
        }
    }

    function cerrarModal() {
        if (modalOverlay) {
            modalOverlay.style.display = "none";
        }
        limpiarFormulario();
    }

    if (btnCerrarModalX) btnCerrarModalX.addEventListener("click", cerrarModal);
    if (btnCancelarModal) btnCancelarModal.addEventListener("click", cerrarModal);

    // ==========================================
    // 📡 1. CARGAR Y RENDERIZAR TABLA PROVEEDORES
    // ==========================================
    async function cargarProveedores() {
        if (!tablaProveedoresBody) return;

        try {
            const respuesta = await fetch(`${API_PROVEEDORES}?action=listar`);
            const data = await respuesta.json();

            if (data.status === "success") {
                listaProveedoresCache = data.proveedores || [];
                renderizarTablaProveedores(listaProveedoresCache);
            } else {
                console.warn("⚠️ Error en listado de proveedores:", data.message);
                tablaProveedoresBody.innerHTML = `<tr><td colspan="5" class="font-mono text-center">NO SE ENCONTRARON REGISTROS</td></tr>`;
            }
        } catch (error) {
            console.error("🔴 Fallo de conexión Fetch (Proveedores):", error);
            tablaProveedoresBody.innerHTML = `<tr><td colspan="5" class="font-mono text-center">ERROR DE CONEXIÓN AL SERVIDOR</td></tr>`;
        }
    }

    function renderizarTablaProveedores(proveedores) {
        if (!tablaProveedoresBody) return;
        tablaProveedoresBody.innerHTML = "";

        if (proveedores.length === 0) {
            tablaProveedoresBody.innerHTML = `
                <tr>
                    <td colspan="5" class="font-mono text-center">NO SE ENCONTRARON REGISTROS DE PROVEEDORES</td>
                </tr>`;
            return;
        }

        proveedores.forEach(prov => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td class="t-cyan font-mono">${prov.id}</td>
                <td><strong>${prov.nombre ? prov.nombre.toUpperCase() : ''}</strong></td>
                <td class="font-mono">${prov.contacto || 'N/A'}</td>
                <td class="font-mono">${prov.created_at || 'N/A'}</td>
                <td>
                    <button class="btn-terminal-view" data-id="${prov.id}" data-nombre="${prov.nombre}" title="Ver Equipos Enviados">
                        <i class="fa fa-truck"></i> [RMA_SENT]
                    </button>
                    <button class="btn-terminal-edit" data-id="${prov.id}" data-nombre="${prov.nombre}" data-contacto="${prov.contacto}" title="Editar Proveedor">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn-terminal-delete" data-id="${prov.id}" title="Eliminar Proveedor">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            `;
            tablaProveedoresBody.appendChild(fila);
        });

        AsignarEventosAcciones();
    }

    // ==========================================
    // 🔍 2. BUSCADOR EN TIEMPO REAL
    // ==========================================
    if (buscarProveedorInput) {
        buscarProveedorInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaProveedoresCache.filter(prov =>
                (prov.nombre && prov.nombre.toLowerCase().includes(termino)) ||
                (prov.contacto && prov.contacto.toLowerCase().includes(termino))
            );
            renderizarTablaProveedores(filtrados);
        });
    }

    // ==========================================
    // 📥 3. MANEJADOR GUARDAR / ACTUALIZAR
    // ==========================================
    const guardarProveedorHandler = async function (e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        const formData = new FormData(formProveedor);
        const datos = Object.fromEntries(formData.entries());
        const esEdicion = datos.id && datos.id.trim() !== "";
        const accion = esEdicion ? "actualizar" : "guardar";

        try {
            const respuesta = await fetch(`${API_PROVEEDORES}?action=${accion}`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(datos)
            });
            const resultado = await respuesta.json();

            if (resultado.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: esEdicion ? 'NODO PROVEEDOR ACTUALIZADO' : 'NODO PROVEEDOR INYECTADO',
                    text: resultado.message,
                    background: '#ffffff',
                    color: '#0f172a',
                    confirmButtonColor: '#0284c7'
                });
                cerrarModal();
                cargarProveedores();
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
        return false;
    };

    if (formProveedor) {
        formProveedor.addEventListener("submit", guardarProveedorHandler);
    }

    // Botón [+ AGREGAR PROVEEDOR] para abrir modal en modo creación
    if (btnNuevoProveedor) {
        btnNuevoProveedor.addEventListener("click", () => {
            limpiarFormulario();
            if (lblTituloModal) lblTituloModal.textContent = "[INJECT_SUPPLIER_NODE]";
            if (btnGuardar) btnGuardar.textContent = "[EXECUTE_DEPLOYMENT]";
            if (btnCancelarModal) btnCancelarModal.classList.add("hidden");
            abrirModal();
        });
    }

    // ==========================================
    // 📊 4. CARGAR HISTORIAL DE EQUIPOS DERIVADOS
    // ==========================================
    async function cargarHistorialProveedor(idProveedor, nombre) {
        if (labelProveedorNombre) labelProveedorNombre.textContent = nombre.toUpperCase();

        if (tablaHistorialBody) {
            tablaHistorialBody.innerHTML = `
                <tr>
                    <td colspan="6" class="font-mono t-cyan text-center">📡 CONSULTANDO EQUIPOS DERIVADOS EN BASE DE DATOS...</td>
                </tr>`;
        }

        if (panelHistorial) {
            panelHistorial.style.display = "block";
            panelHistorial.scrollIntoView({ behavior: 'smooth' });
        }

        try {
            const respuesta = await fetch(`${API_PROVEEDORES}?action=historial&id_proveedor=${idProveedor}`);
            const data = await respuesta.json();

            if (data.status === "success" && tablaHistorialBody) {
                tablaHistorialBody.innerHTML = "";

                if (!data.casos || data.casos.length === 0) {
                    tablaHistorialBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="font-mono text-center">NO HAY EQUIPOS DERIVADOS REGISTRADOS PARA ESTE PROVEEDOR</td>
                        </tr>`;
                    return;
                }

                data.casos.forEach(caso => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td class="t-cyan font-mono font-weight-bold">${caso.numero_caso}</td>
                        <td><strong>${caso.equipo}</strong> (${caso.marca} ${caso.modelo || ''})</td>
                        <td class="font-mono">${caso.numero_serie || 'N/A'}</td>
                        <td class="font-mono text-yellow">${caso.referencia_proveedor || 'SIN_REF'}</td>
                        <td class="font-mono">${caso.fecha_envio_proveedor || 'N/A'}</td>
                        <td><span class="system-badge-live">${caso.estado_actual}</span></td>
                    `;
                    tablaHistorialBody.appendChild(fila);
                });
            } else if (tablaHistorialBody) {
                tablaHistorialBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="font-mono text-center">ERROR AL RECUPERAR EL HISTORIAL DE GARANTÍA EXTERNA</td>
                    </tr>`;
            }
        } catch (error) {
            console.error("🔴 Fallo cargando historial de proveedor:", error);
        }
    }

    // ==========================================
    // ⚙️ 5. ASIGNACIÓN DE EVENTOS EN FILAS
    // ==========================================
    function AsignarEventosAcciones() {
        // Clic en Ver Historial
        document.querySelectorAll(".btn-terminal-view").forEach(btn => {
            btn.onclick = function (e) {
                e.stopPropagation();
                const id = this.getAttribute("data-id");
                const nombre = this.getAttribute("data-nombre");
                cargarHistorialProveedor(id, nombre);
            };
        });

        // Clic en Editar (Abre Modal Nativo)
        document.querySelectorAll(".btn-terminal-edit").forEach(btn => {
            btn.onclick = function (e) {
                e.stopPropagation();
                esModoEdicion = true;

                if (proveedorIdInput) proveedorIdInput.value = this.getAttribute("data-id");
                if (proveedorNombreInput) proveedorNombreInput.value = this.getAttribute("data-nombre");
                if (proveedorContactoInput) proveedorContactoInput.value = this.getAttribute("data-contacto");

                if (lblTituloModal) lblTituloModal.textContent = `[EDITAR_PROVEEDOR: ${this.getAttribute("data-nombre").toUpperCase()}]`;
                if (btnGuardar) btnGuardar.textContent = "[UPDATE_DEPLOY]";
                if (btnCancelarModal) btnCancelarModal.classList.remove("hidden");

                abrirModal();
            };
        });

        // Clic en Eliminar
        document.querySelectorAll(".btn-terminal-delete").forEach(btn => {
            btn.onclick = async function (e) {
                e.stopPropagation();
                const idProveedor = this.getAttribute("data-id");

                const confirmar = await Swal.fire({
                    title: '¿PURGAR PROVEEDOR?',
                    text: `Confirmar eliminación del proveedor ID: ${idProveedor}`,
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
                        const respuesta = await fetch(`${API_PROVEEDORES}?action=eliminar&id=${idProveedor}`, { method: "DELETE" });
                        const resultado = await respuesta.json();

                        if (resultado.status === "success") {
                            Swal.fire({
                                title: 'PURGADO',
                                text: resultado.message,
                                icon: 'success',
                                background: '#ffffff',
                                color: '#0f172a',
                                confirmButtonColor: '#0284c7'
                            });
                            cargarProveedores();
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

    // Reset del Formulario y Variables de Estado
    function limpiarFormulario() {
        if (formProveedor) formProveedor.reset();
        if (proveedorIdInput) proveedorIdInput.value = "";
        esModoEdicion = false;
        if (btnGuardar) btnGuardar.textContent = "[EXECUTE_DEPLOYMENT]";
        if (btnCancelarModal) btnCancelarModal.classList.add("hidden");
    }

    // Inicialización al cargar el DOM
    const verificarDom = setInterval(() => {
        if (document.getElementById("tablaProveedores")) {
            clearInterval(verificarDom);
            cargarProveedores();
        }
    }, 100);
})();