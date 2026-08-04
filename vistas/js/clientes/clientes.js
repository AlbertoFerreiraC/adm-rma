(function () {
    console.log("⚡ [COMMS_NODE] Inicializando módulo de Directorio de Clientes...");

    const API_CLIENTES = "../api-rma/clientes/clientes.php";
    const formCliente = document.getElementById("formCliente");
    const tablaClientesBody = document.querySelector("#tablaClientes tbody");
    const buscarClienteInput = document.getElementById("buscarCliente");

    const panelHistorial = document.getElementById("panelHistorialCliente");
    const labelClienteNombre = document.getElementById("labelClienteNombre");
    const labelClienteCedula = document.getElementById("labelClienteCedula");
    const tablaHistorialBody = document.querySelector("#tablaHistorialRma tbody");

    const clienteIdInput = document.getElementById("cliente_id");
    const clienteNombreInput = document.getElementById("cliente_nombre");
    const clienteCedulaInput = document.getElementById("cliente_cedula");
    const clienteCelularInput = document.getElementById("cliente_celular");
    const btnGuardar = document.getElementById("btnGuardarCliente");
    const btnCancelar = document.getElementById("btnCancelarEdicion");

    let listaClientesCache = [];

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
            }
        } catch (error) {
            console.error("🔴 Fallo de conexión Fetch (Clientes):", error);
        }
    }

    function renderizarTablaClientes(clientes) {
        tablaClientesBody.innerHTML = "";

        if (clientes.length === 0) {
            tablaClientesBody.innerHTML = `
                <tr>
                    <td colspan="6" class="font-mono text-white">NO SE ENCONTRARON REGISTROS DE CLIENTES</td>
                </tr>`;
            return;
        }

        clientes.forEach(cli => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td class="t-cyan font-mono">${cli.id}</td>
                <td class="font-mono text-white">${cli.cedula}</td>
                <td><strong>${cli.nombre}</strong></td>
                <td class="font-mono">${cli.celular}</td>
                <td class="font-mono">${cli.created_at}</td>
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

    if (buscarClienteInput) {
        buscarClienteInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaClientesCache.filter(cli =>
                cli.nombre.toLowerCase().includes(termino) ||
                cli.cedula.toLowerCase().includes(termino) ||
                cli.celular.toLowerCase().includes(termino)
            );
            renderizarTablaClientes(filtrados);
        });
    }

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
                    title: esEdicion ? 'CLIENT_UPDATED' : 'CLIENT_REGISTERED',
                    text: resultado.message,
                    background: '#060b19',
                    color: '#00ff66',
                    confirmButtonColor: '#00b4d8'
                });
                limpiarFormulario();
                cargarClientes();
            } else {
                throw new Error(resultado.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'OPERATIONAL_FAIL',
                text: error.message,
                background: '#060b19',
                color: '#ff3333',
                confirmButtonColor: '#ff3333'
            });
        }
        return false;
    };

    document.addEventListener("submit", function (e) {
        if (e.target && e.target.id === "formCliente") {
            guardarClienteHandler(e);
        }
    });

    if (btnGuardar) {
        btnGuardar.addEventListener("click", guardarClienteHandler);
    }

    async function cargarHistorialCliente(idCliente, nombre, cedula) {
        labelClienteNombre.textContent = nombre.toUpperCase();
        labelClienteCedula.textContent = `CED: ${cedula}`;
        tablaHistorialBody.innerHTML = `
            <tr>
                <td colspan="7" class="font-mono t-cyan">📡 CONSULTANDO HISTORIAL EN BASE DE DATOS...</td>
            </tr>`;

        panelHistorial.style.display = "block";
        panelHistorial.scrollIntoView({ behavior: 'smooth' });

        try {
            const respuesta = await fetch(`${API_CLIENTES}?action=historial&id_cliente=${idCliente}`);
            const data = await respuesta.json();

            if (data.status === "success") {
                tablaHistorialBody.innerHTML = "";

                if (!data.casos || data.casos.length === 0) {
                    tablaHistorialBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="font-mono text-white">EL CLIENTE NO POSEE CASOS REGISTRADOS EN SOPORTE TÉCNICO</td>
                        </tr>`;
                    return;
                }

                data.casos.forEach(caso => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td class="t-cyan font-mono font-weight-bold">${caso.numero_caso}</td>
                        <td class="text-white"><strong>${caso.equipo}</strong> (${caso.marca} ${caso.modelo})</td>
                        <td class="font-mono">${caso.numero_serie || 'N/A'}</td>
                        <td class="font-mono">${caso.tipo_caso}</td>
                        <td><span class="badge-status badge-ready">${caso.estado_actual}</span></td>
                        <td class="font-mono">${caso.fecha_ingreso}</td>
                        <td class="font-mono">${caso.fecha_cierre ? caso.fecha_cierre : '<span class="t-cyan">EN PROCESO</span>'}</td>
                    `;
                    tablaHistorialBody.appendChild(fila);
                });
            } else {
                tablaHistorialBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="font-mono text-neon-red">ERROR AL RECUPERAR EL HISTORIAL DE EQUIPOS</td>
                    </tr>`;
            }
        } catch (error) {
            console.error("🔴 Fallo cargando historial:", error);
        }
    }

    function AsignarEventosAcciones() {
        document.querySelectorAll(".btn-terminal-view").forEach(btn => {
            btn.onclick = function () {
                const id = this.getAttribute("data-id");
                const nombre = this.getAttribute("data-nombre");
                const cedula = this.getAttribute("data-cedula");
                cargarHistorialCliente(id, nombre, cedula);
            };
        });

        document.querySelectorAll(".btn-terminal-edit").forEach(btn => {
            btn.onclick = function () {
                clienteIdInput.value = this.getAttribute("data-id");
                clienteNombreInput.value = this.getAttribute("data-nombre");
                clienteCedulaInput.value = this.getAttribute("data-cedula");
                clienteCelularInput.value = this.getAttribute("data-celular");

                btnGuardar.textContent = "[UPDATE_CLIENT_NODE]";
                btnCancelar.classList.remove("hidden");
                formCliente.scrollIntoView({ behavior: 'smooth' });
            };
        });

        document.querySelectorAll(".btn-terminal-delete").forEach(btn => {
            btn.onclick = async function () {
                const idCliente = this.getAttribute("data-id");

                const confirmar = await Swal.fire({
                    title: '¿PURGAR REGISTRO DE CLIENTE?',
                    text: `Confirmar destrucción del cliente ID: ${idCliente}`,
                    icon: 'warning',
                    showCancelButton: true,
                    background: '#060b19',
                    color: '#ffca28',
                    confirmButtonColor: '#ff3333',
                    cancelButtonColor: '#506690',
                    confirmButtonText: '[CONFIRM_PURGE]',
                    cancelButtonText: 'CANCEL'
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
                                background: '#060b19',
                                color: '#00ff66',
                                confirmButtonColor: '#00b4d8'
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
                            background: '#060b19',
                            color: '#ff3333',
                            confirmButtonColor: '#ff3333'
                        });
                    }
                }
            };
        });
    }

    if (btnCancelar) {
        btnCancelar.addEventListener("click", limpiarFormulario);
    }

    function limpiarFormulario() {
        formCliente.reset();
        clienteIdInput.value = "";
        btnGuardar.textContent = "[INJECT_CLIENT_NODE]";
        btnCancelar.classList.add("hidden");
    }

    const verificarDom = setInterval(() => {
        if (document.getElementById("tablaClientes")) {
            clearInterval(verificarDom);
            cargarClientes();
        }
    }, 100);
})();