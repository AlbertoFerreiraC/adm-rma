(function () {
    console.log("⚡ [COMMS_NODE] Inicializando módulo de Directorio de Proveedores...");

    const API_PROVEEDORES = "../api-rma/clientes/proveedores.php";

    const formProveedor = document.getElementById("formProveedor");
    const tablaProveedoresBody = document.querySelector("#tablaProveedores tbody");
    const buscarProveedorInput = document.getElementById("buscarProveedor");

    const panelHistorial = document.getElementById("panelHistorialProveedor");
    const labelProveedorNombre = document.getElementById("labelProveedorNombre");
    const tablaHistorialBody = document.querySelector("#tablaHistorialProvRma tbody");

    const proveedorIdInput = document.getElementById("proveedor_id");
    const proveedorNombreInput = document.getElementById("proveedor_nombre");
    const proveedorContactoInput = document.getElementById("proveedor_contacto");
    const btnGuardar = document.getElementById("btnGuardarProveedor");
    const btnCancelar = document.getElementById("btnCancelarEdicionProv");

    let listaProveedoresCache = [];

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
            }
        } catch (error) {
            console.error("🔴 Fallo de conexión Fetch (Proveedores):", error);
        }
    }

    function renderizarTablaProveedores(proveedores) {
        tablaProveedoresBody.innerHTML = "";

        if (proveedores.length === 0) {
            tablaProveedoresBody.innerHTML = `
                <tr>
                    <td colspan="5" class="font-mono text-white">NO SE ENCONTRARON REGISTROS DE PROVEEDORES</td>
                </tr>`;
            return;
        }

        proveedores.forEach(prov => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td class="t-cyan font-mono">${prov.id}</td>
                <td><strong>${prov.nombre}</strong></td>
                <td class="font-mono">${prov.contacto}</td>
                <td class="font-mono">${prov.created_at}</td>
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

    if (buscarProveedorInput) {
        buscarProveedorInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaProveedoresCache.filter(prov =>
                prov.nombre.toLowerCase().includes(termino) ||
                prov.contacto.toLowerCase().includes(termino)
            );
            renderizarTablaProveedores(filtrados);
        });
    }

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
                    title: esEdicion ? 'SUPPLIER_UPDATED' : 'SUPPLIER_REGISTERED',
                    text: resultado.message,
                    background: '#060b19',
                    color: '#00ff66',
                    confirmButtonColor: '#00b4d8'
                });
                limpiarFormulario();
                cargarProveedores();
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
        if (e.target && e.target.id === "formProveedor") {
            guardarProveedorHandler(e);
        }
    });

    if (btnGuardar) {
        btnGuardar.addEventListener("click", guardarProveedorHandler);
    }

    async function cargarHistorialProveedor(idProveedor, nombre) {
        labelProveedorNombre.textContent = nombre.toUpperCase();
        tablaHistorialBody.innerHTML = `
            <tr>
                <td colspan="6" class="font-mono t-cyan">📡 CONSULTANDO EQUIPOS DERIVADOS EN BASE DE DATOS...</td>
            </tr>`;

        panelHistorial.style.display = "block";
        panelHistorial.scrollIntoView({ behavior: 'smooth' });

        try {
            const respuesta = await fetch(`${API_PROVEEDORES}?action=historial&id_proveedor=${idProveedor}`);
            const data = await respuesta.json();

            if (data.status === "success") {
                tablaHistorialBody.innerHTML = "";

                if (!data.casos || data.casos.length === 0) {
                    tablaHistorialBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="font-mono text-white">NO HAY EQUIPOS DERIVADOS REGISTRADOS PARA ESTE PROVEEDOR</td>
                        </tr>`;
                    return;
                }

                data.casos.forEach(caso => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td class="t-cyan font-mono font-weight-bold">${caso.numero_caso}</td>
                        <td class="text-white"><strong>${caso.equipo}</strong> (${caso.marca} ${caso.modelo})</td>
                        <td class="font-mono">${caso.numero_serie || 'N/A'}</td>
                        <td class="font-mono text-neon-yellow">${caso.referencia_proveedor || 'SIN_REF'}</td>
                        <td class="font-mono">${caso.fecha_envio_proveedor || 'N/A'}</td>
                        <td><span class="badge-status badge-ready">${caso.estado_actual}</span></td>
                    `;
                    tablaHistorialBody.appendChild(fila);
                });
            } else {
                tablaHistorialBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="font-mono text-neon-red">ERROR AL RECUPERAR EL HISTORIAL DE GARANTÍA EXTERNA</td>
                    </tr>`;
            }
        } catch (error) {
            console.error("🔴 Fallo cargando historial de proveedor:", error);
        }
    }

    function AsignarEventosAcciones() {
        document.querySelectorAll(".btn-terminal-view").forEach(btn => {
            btn.onclick = function () {
                const id = this.getAttribute("data-id");
                const nombre = this.getAttribute("data-nombre");
                cargarHistorialProveedor(id, nombre);
            };
        });

        document.querySelectorAll(".btn-terminal-edit").forEach(btn => {
            btn.onclick = function () {
                proveedorIdInput.value = this.getAttribute("data-id");
                proveedorNombreInput.value = this.getAttribute("data-nombre");
                proveedorContactoInput.value = this.getAttribute("data-contacto");

                btnGuardar.textContent = "[UPDATE_SUPPLIER_NODE]";
                btnCancelar.classList.remove("hidden");
                formProveedor.scrollIntoView({ behavior: 'smooth' });
            };
        });

        document.querySelectorAll(".btn-terminal-delete").forEach(btn => {
            btn.onclick = async function () {
                const idProveedor = this.getAttribute("data-id");

                const confirmar = await Swal.fire({
                    title: '¿PURGAR PROVEEDOR?',
                    text: `Confirmar destrucción del proveedor ID: ${idProveedor}`,
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
                        const respuesta = await fetch(`${API_PROVEEDORES}?action=eliminar&id=${idProveedor}`, { method: "DELETE" });
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
                            cargarProveedores();
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
        formProveedor.reset();
        proveedorIdInput.value = "";
        btnGuardar.textContent = "[INJECT_SUPPLIER_NODE]";
        btnCancelar.classList.add("hidden");
    }

    const verificarDom = setInterval(() => {
        if (document.getElementById("tablaProveedores")) {
            clearInterval(verificarDom);
            cargarProveedores();
        }
    }, 100);
})();