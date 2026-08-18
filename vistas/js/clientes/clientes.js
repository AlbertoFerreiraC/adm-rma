(function () {
    console.log("⚡ [COMMS_NODE] Inicializando módulo de Directorio de Clientes (Modo Modal Nativo)...");

    const API_CLIENTES = "/rma-app/api-rma/clientes/clientes.php";

    // Formulario y Modal Nativo
    const formCliente = document.getElementById("formCliente");
    const modalOverlay = document.getElementById("modalClienteOverlay");
    const btnCerrarModalX = document.getElementById("btnCerrarModalX");
    const btnCancelarModal = document.getElementById("btnCancelarModal");
    const lblTituloModal = document.getElementById("lblTituloModal");
    const btnNuevoCliente = document.getElementById("btnNuevoCliente");

    // Elementos de Tabla y Buscador
    const tablaClientesBody = document.querySelector("#tablaClientes tbody");
    const buscarClienteInput = document.getElementById("buscarCliente");

    // Historial RMA
    const panelHistorial = document.getElementById("panelHistorialCliente");
    const labelClienteNombre = document.getElementById("labelClienteNombre");
    const labelClienteCedula = document.getElementById("labelClienteCedula");
    const tablaHistorialBody = document.querySelector("#tablaHistorialRma tbody");

    // Campos del Formulario Modal
    const clienteIdInput = document.getElementById("cliente_id");
    const clienteNombreInput = document.getElementById("cliente_nombre");
    const clienteCedulaInput = document.getElementById("cliente_cedula");
    const clienteCelularInput = document.getElementById("cliente_celular");
    const btnGuardar = document.getElementById("btnGuardarCliente");

    let listaClientesCache = [];
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
    // 📡 1. CARGAR Y RENDERIZAR TABLA CLIENTES
    // ==========================================
    async function cargarClientes() {
        if (!tablaClientesBody) return;

        try {
            const respuesta = await fetch(`${API_CLIENTES}?action=listar`);
            const data = await respuesta.json();

            if (data.status === "success") {
                listaClientesCache = data.clientes || [];
                renderizarTablaClientes(listaClientesCache);
            } else {
                console.warn("⚠️ Error en listado de clientes:", data.message);
                tablaClientesBody.innerHTML = `<tr><td colspan="6" class="font-mono text-center">NO SE ENCONTRARON REGISTROS</td></tr>`;
            }
        } catch (error) {
            console.error("🔴 Fallo de conexión Fetch (Clientes):", error);
            tablaClientesBody.innerHTML = `<tr><td colspan="6" class="font-mono text-center">ERROR DE CONEXIÓN AL SERVIDOR</td></tr>`;
        }
    }

    function renderizarTablaClientes(clientes) {
        if (!tablaClientesBody) return;
        tablaClientesBody.innerHTML = "";

        if (clientes.length === 0) {
            tablaClientesBody.innerHTML = `
                <tr>
                    <td colspan="6" class="font-mono text-center">NO SE ENCONTRARON REGISTROS DE CLIENTES</td>
                </tr>`;
            return;
        }

        clientes.forEach(cli => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td class="t-cyan font-mono">${cli.id}</td>
                <td class="font-mono">${cli.cedula}</td>
                <td><strong>${cli.nombre ? cli.nombre.toUpperCase() : ''}</strong></td>
                <td class="font-mono">${cli.celular || 'S/N'}</td>
                <td class="font-mono">${cli.created_at || 'N/A'}</td>
                <td>
                    <button class="btn-terminal-view" data-id="${cli.id}" data-nombre="${cli.nombre}" data-cedula="${cli.cedula}" title="Ver Historial RMA">
                        <i class="fa fa-eye"></i> [TRAILS]
                    </button>
                    <button class="btn-terminal-edit" data-id="${cli.id}" data-nombre="${cli.nombre}" data-cedula="${cli.cedula}" data-celular="${cli.celular}" title="Editar Cliente">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn-terminal-delete" data-id="${cli.id}" title="Eliminar Cliente">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            `;
            tablaClientesBody.appendChild(fila);
        });

        AsignarEventosAcciones();
    }

    // ==========================================
    // 🔍 2. BUSCADOR EN TIEMPO REAL
    // ==========================================
    if (buscarClienteInput) {
        buscarClienteInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaClientesCache.filter(cli =>
                (cli.nombre && cli.nombre.toLowerCase().includes(termino)) ||
                (cli.cedula && cli.cedula.toLowerCase().includes(termino)) ||
                (cli.celular && cli.celular.toLowerCase().includes(termino))
            );
            renderizarTablaClientes(filtrados);
        });
    }

    // ==========================================
    // 📥 3. MANEJADOR GUARDAR / ACTUALIZAR
    // ==========================================
    const guardarClienteHandler = async function (e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        const formData = new FormData(formCliente);
        const datos = Object.fromEntries(formData.entries());
        const esEdicion = datos.id && datos.id.trim() !== "";
        const accion = esEdicion ? "actualizar" : "guardar";

        try {
            const respuesta = await fetch(`${API_CLIENTES}?action=${accion}`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(datos)
            });
            const resultado = await respuesta.json();

            if (resultado.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: esEdicion ? 'NODO CLIENTE ACTUALIZADO' : 'NODO CLIENTE INYECTADO',
                    text: resultado.message,
                    background: '#ffffff',
                    color: '#0f172a',
                    confirmButtonColor: '#0284c7'
                });
                cerrarModal();
                cargarClientes();
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

    if (formCliente) {
        formCliente.addEventListener("submit", guardarClienteHandler);
    }

    // Botón [+ AGREGAR CLIENTE] para abrir modal en modo creación
    if (btnNuevoCliente) {
        btnNuevoCliente.addEventListener("click", () => {
            limpiarFormulario();
            if (lblTituloModal) lblTituloModal.textContent = "[INJECT_CLIENT_NODE]";
            if (btnGuardar) btnGuardar.textContent = "[EXECUTE_DEPLOYMENT]";
            if (btnCancelarModal) btnCancelarModal.classList.add("hidden");
            abrirModal();
        });
    }

    // ==========================================
    // 📊 4. CARGAR HISTORIAL DE CASOS (RMA)
    // ==========================================
    async function cargarHistorialCliente(idCliente, nombre, cedula) {
        if (labelClienteNombre) labelClienteNombre.textContent = nombre.toUpperCase();
        if (labelClienteCedula) labelClienteCedula.textContent = `CED: ${cedula}`;

        if (tablaHistorialBody) {
            tablaHistorialBody.innerHTML = `
                <tr>
                    <td colspan="7" class="font-mono t-cyan text-center">📡 CONSULTANDO HISTORIAL EN BASE DE DATOS...</td>
                </tr>`;
        }

        if (panelHistorial) {
            panelHistorial.style.display = "block";
            panelHistorial.scrollIntoView({ behavior: 'smooth' });
        }

        try {
            const respuesta = await fetch(`${API_CLIENTES}?action=historial&id_cliente=${idCliente}`);
            const data = await respuesta.json();

            if (data.status === "success" && tablaHistorialBody) {
                tablaHistorialBody.innerHTML = "";

                if (!data.casos || data.casos.length === 0) {
                    tablaHistorialBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="font-mono text-center">EL CLIENTE NO POSEE CASOS REGISTRADOS EN SOPORTE TÉCNICO</td>
                        </tr>`;
                    return;
                }

                data.casos.forEach(caso => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td class="t-cyan font-mono font-weight-bold">${caso.numero_caso}</td>
                        <td><strong>${caso.equipo}</strong> (${caso.marca} ${caso.modelo || ''})</td>
                        <td class="font-mono">${caso.numero_serie || 'N/A'}</td>
                        <td class="font-mono">${caso.tipo_caso}</td>
                        <td><span class="system-badge-live">${caso.estado_actual}</span></td>
                        <td class="font-mono">${caso.fecha_ingreso}</td>
                        <td class="font-mono">${caso.fecha_cierre ? caso.fecha_cierre : '<span class="t-cyan">EN PROCESO</span>'}</td>
                    `;
                    tablaHistorialBody.appendChild(fila);
                });
            } else if (tablaHistorialBody) {
                tablaHistorialBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="font-mono text-center">ERROR AL RECUPERAR EL HISTORIAL DE EQUIPOS</td>
                    </tr>`;
            }
        } catch (error) {
            console.error("🔴 Fallo cargando historial:", error);
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
                const cedula = this.getAttribute("data-cedula");
                cargarHistorialCliente(id, nombre, cedula);
            };
        });

        // Clic en Editar (Abre el Modal Nativo)
        document.querySelectorAll(".btn-terminal-edit").forEach(btn => {
            btn.onclick = function (e) {
                e.stopPropagation();
                esModoEdicion = true;

                if (clienteIdInput) clienteIdInput.value = this.getAttribute("data-id");
                if (clienteNombreInput) clienteNombreInput.value = this.getAttribute("data-nombre");
                if (clienteCedulaInput) clienteCedulaInput.value = this.getAttribute("data-cedula");
                if (clienteCelularInput) clienteCelularInput.value = this.getAttribute("data-celular");

                if (lblTituloModal) lblTituloModal.textContent = `[EDITAR_CLIENTE: ${this.getAttribute("data-nombre").toUpperCase()}]`;
                if (btnGuardar) btnGuardar.textContent = "[UPDATE_DEPLOY]";
                if (btnCancelarModal) btnCancelarModal.classList.remove("hidden");

                abrirModal();
            };
        });

        // Clic en Eliminar
        document.querySelectorAll(".btn-terminal-delete").forEach(btn => {
            btn.onclick = async function (e) {
                e.stopPropagation();
                const idCliente = this.getAttribute("data-id");

                const confirmar = await Swal.fire({
                    title: '¿PURGAR REGISTRO DE CLIENTE?',
                    text: `Confirmar eliminación del cliente ID: ${idCliente}`,
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
                        const respuesta = await fetch(`${API_CLIENTES}?action=eliminar&id=${idCliente}`, { method: "DELETE" });
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
                            cargarClientes();
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
        if (formCliente) formCliente.reset();
        if (clienteIdInput) clienteIdInput.value = "";
        esModoEdicion = false;
        if (btnGuardar) btnGuardar.textContent = "[EXECUTE_DEPLOYMENT]";
        if (btnCancelarModal) btnCancelarModal.classList.add("hidden");
    }

    // Inicialización al cargar el DOM
    const verificarDom = setInterval(() => {
        if (document.getElementById("tablaClientes")) {
            clearInterval(verificarDom);
            cargarClientes();
        }
    }, 100);
})();