(function () {
    console.log("⚡ [SYS_AUTH] Sistema de Configuración del Core Operativo iniciado...");

    // Endpoints API
    const API_TIPOS_URL = "/rma-app/api-rma/configuracion/tipos_caso.php";
    const API_ESTADOS_URL = "/rma-app/api-rma/configuracion/estados_caso.php";

    let modoEdicionTipo = false;
    let modoEdicionEstado = false;
    let bloqueandoSubmitTipo = false;
    let bloqueandoSubmitEstado = false;

    let listaTiposCache = [];
    let listaEstadosCache = [];

    // --- ELEMENTOS ABM TIPOS DE CASO ---
    const formTipo = document.getElementById("formTipoCaso");
    const buscarTipoInput = document.getElementById("buscarTipo");
    const btnNuevoTipo = document.getElementById("btnNuevoTipo");
    const lblTituloModalTipo = document.getElementById("lblTituloModalTipo");
    const btnGuardarTipo = document.getElementById("btnGuardarTipo");
    const modalTipoOverlay = document.getElementById("modalTipoOverlay");
    const btnCerrarModalTipoX = document.getElementById("btnCerrarModalTipoX");
    const btnCancelarModalTipo = document.getElementById("btnCancelarModalTipo");

    // --- ELEMENTOS ABM ESTADOS DE CASO ---
    const formEstado = document.getElementById("formEstadoCaso");
    const buscarEstadoInput = document.getElementById("buscarEstado");
    const btnNuevoEstado = document.getElementById("btnNuevoEstado");
    const lblTituloModalEstado = document.getElementById("lblTituloModalEstado");
    const btnGuardarEstado = document.getElementById("btnGuardarEstado");
    const modalEstadoOverlay = document.getElementById("modalEstadoOverlay");
    const btnCerrarModalEstadoX = document.getElementById("btnCerrarModalEstadoX");
    const btnCancelarModalEstado = document.getElementById("btnCancelarModalEstado");

    // ==========================================
    // 🔓 MODALES NATIVOS (TIPOS Y ESTADOS)
    // ==========================================
    function abrirModalTipo() { if (modalTipoOverlay) modalTipoOverlay.style.display = "flex"; }
    function cerrarModalTipo() { if (modalTipoOverlay) modalTipoOverlay.style.display = "none"; }
    if (btnCerrarModalTipoX) btnCerrarModalTipoX.addEventListener("click", cerrarModalTipo);
    if (btnCancelarModalTipo) btnCancelarModalTipo.addEventListener("click", cerrarModalTipo);

    function abrirModalEstado() { if (modalEstadoOverlay) modalEstadoOverlay.style.display = "flex"; }
    function cerrarModalEstado() { if (modalEstadoOverlay) modalEstadoOverlay.style.display = "none"; }
    if (btnCerrarModalEstadoX) btnCerrarModalEstadoX.addEventListener("click", cerrarModalEstado);
    if (btnCancelarModalEstado) btnCancelarModalEstado.addEventListener("click", cerrarModalEstado);

    // ==========================================
    // 📡 1. SECCIÓN: TIPOS DE CASO (RMA)
    // ==========================================
    async function cargarTiposCaso() {
        const tbody = document.querySelector("#tablaTiposCaso tbody");
        if (!tbody) return;

        try {
            const res = await fetch(`${API_TIPOS_URL}?action=listar`);
            const data = await res.json();

            if (data.status === "success") {
                listaTiposCache = data.tipos || [];
                renderizarTablaTipos(listaTiposCache);
            } else {
                tbody.innerHTML = `<tr><td colspan="3" class="font-mono text-center">NO SE ENCONTRARON TIPOS DE RMA</td></tr>`;
            }
        } catch (error) {
            console.error("🔴 Error cargando tipos de caso:", error);
        }
    }

    function renderizarTablaTipos(tipos) {
        const tbody = document.querySelector("#tablaTiposCaso tbody");
        if (!tbody) return;

        tbody.innerHTML = "";

        if (tipos.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="font-mono text-center">NO HAY REGISTROS COINCIDENTES</td></tr>`;
            return;
        }

        tipos.forEach(t => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td class="t-cyan font-mono">${t.id}</td>
                <td class="font-mono font-weight-bold">${t.nombre}</td>
                <td>
                    <button type="button" class="btn-terminal-edit btn-edit-tipo" data-id="${t.id}" title="Editar Tipo">
                        <i class="fa fa-edit"></i> EDIT
                    </button>
                    <button type="button" class="btn-terminal-delete btn-delete-tipo" data-id="${t.id}" title="Eliminar Tipo">
                        <i class="fa fa-trash"></i> DEL
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    }

    if (buscarTipoInput) {
        buscarTipoInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaTiposCache.filter(t =>
                t.id.toString().includes(termino) ||
                t.nombre.toLowerCase().includes(termino)
            );
            renderizarTablaTipos(filtrados);
        });
    }

    if (formTipo) {
        formTipo.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (bloqueandoSubmitTipo) return;
            bloqueandoSubmitTipo = true;

            const datos = Object.fromEntries(new FormData(formTipo));
            if (!datos.id || datos.id.trim() === "") delete datos.id;

            const action = modoEdicionTipo ? "actualizar" : "guardar";

            try {
                const res = await fetch(`${API_TIPOS_URL}?action=${action}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });

                const r = await res.json();

                if (r.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: modoEdicionTipo ? "TIPO ACTUALIZADO" : "TIPO INYECTADO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#0284c7'
                    });

                    cerrarModalTipo();
                    resetFormTipo();
                    cargarTiposCaso();
                } else {
                    Swal.fire("ERROR", r.message, "error");
                }
            } catch (err) {
                Swal.fire("CRITICAL_FAIL", err.message, "error");
            }

            bloqueandoSubmitTipo = false;
        });
    }

    function resetFormTipo() {
        if (!formTipo) return;
        formTipo.reset();
        document.getElementById("idTipoCaso").value = "";
        modoEdicionTipo = false;

        if (lblTituloModalTipo) lblTituloModalTipo.textContent = "[INYECTAR_TIPO_CASO]";
        if (btnGuardarTipo) btnGuardarTipo.textContent = "[EXECUTE_DEPLOYMENT]";
        if (btnCancelarModalTipo) btnCancelarModalTipo.classList.add("hidden");
    }

    if (btnNuevoTipo) {
        btnNuevoTipo.addEventListener("click", (e) => {
            e.preventDefault();
            resetFormTipo();
            abrirModalTipo();
        });
    }

    // ==========================================
    // 📡 2. SECCIÓN: ESTADOS DE CASO (RMA)
    // ==========================================
    async function cargarEstadosCaso() {
        const tbody = document.querySelector("#tablaEstadosCaso tbody");
        if (!tbody) return;

        try {
            const res = await fetch(`${API_ESTADOS_URL}?action=listar`);
            const data = await res.json();

            if (data.status === "success") {
                listaEstadosCache = data.estados || [];
                renderizarTablaEstados(listaEstadosCache);
            } else {
                tbody.innerHTML = `<tr><td colspan="3" class="font-mono text-center">NO SE ENCONTRARON ESTADOS DE RMA</td></tr>`;
            }
        } catch (error) {
            console.error("🔴 Error cargando estados de caso:", error);
        }
    }

    function renderizarTablaEstados(estados) {
        const tbody = document.querySelector("#tablaEstadosCaso tbody");
        if (!tbody) return;

        tbody.innerHTML = "";

        if (estados.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="font-mono text-center">NO HAY REGISTROS COINCIDENTES</td></tr>`;
            return;
        }

        estados.forEach(e => {
            const tr = document.createElement("tr");

            // Formateo dinámico de badges según ID/Nombre de estado
            let badgeClass = "badge-diag";
            if (e.id == 2) badgeClass = "badge-repair";
            if (e.id == 3) badgeClass = "badge-external";
            if (e.id == 4) badgeClass = "badge-ready";

            tr.innerHTML = `
                <td class="t-cyan font-mono">${e.id}</td>
                <td><span class="badge-status ${badgeClass}">${e.nombre}</span></td>
                <td>
                    <button type="button" class="btn-terminal-edit btn-edit-estado" data-id="${e.id}" title="Editar Estado">
                        <i class="fa fa-edit"></i> EDIT
                    </button>
                    <button type="button" class="btn-terminal-delete btn-delete-estado" data-id="${e.id}" title="Eliminar Estado">
                        <i class="fa fa-trash"></i> DEL
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    }

    if (buscarEstadoInput) {
        buscarEstadoInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaEstadosCache.filter(e =>
                e.id.toString().includes(termino) ||
                e.nombre.toLowerCase().includes(termino)
            );
            renderizarTablaEstados(filtrados);
        });
    }

    if (formEstado) {
        formEstado.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (bloqueandoSubmitEstado) return;
            bloqueandoSubmitEstado = true;

            const datos = Object.fromEntries(new FormData(formEstado));
            if (!datos.id || datos.id.trim() === "") delete datos.id;

            const action = modoEdicionEstado ? "actualizar" : "guardar";

            try {
                const res = await fetch(`${API_ESTADOS_URL}?action=${action}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });

                const r = await res.json();

                if (r.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: modoEdicionEstado ? "ESTADO ACTUALIZADO" : "ESTADO INYECTADO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#0284c7'
                    });

                    cerrarModalEstado();
                    resetFormEstado();
                    cargarEstadosCaso();
                } else {
                    Swal.fire("ERROR", r.message, "error");
                }
            } catch (err) {
                Swal.fire("CRITICAL_FAIL", err.message, "error");
            }

            bloqueandoSubmitEstado = false;
        });
    }

    function resetFormEstado() {
        if (!formEstado) return;
        formEstado.reset();
        document.getElementById("idEstadoCaso").value = "";
        modoEdicionEstado = false;

        if (lblTituloModalEstado) lblTituloModalEstado.textContent = "[INYECTAR_ESTADO_CASO]";
        if (btnGuardarEstado) btnGuardarEstado.textContent = "[EXECUTE_DEPLOYMENT]";
        if (btnCancelarModalEstado) btnCancelarModalEstado.classList.add("hidden");
    }

    if (btnNuevoEstado) {
        btnNuevoEstado.addEventListener("click", (e) => {
            e.preventDefault();
            resetFormEstado();
            abrirModalEstado();
        });
    }

    // ==========================================
    // ⚙️ 3. DELEGACIÓN DE EVENTOS EN TABLAS (EDITAR / ELIMINAR)
    // ==========================================
    document.addEventListener("click", async function (e) {
        // --- EDITAR TIPO DE CASO ---
        const btnEditTipo = e.target.closest(".btn-edit-tipo");
        if (btnEditTipo) {
            e.preventDefault();
            const id = btnEditTipo.getAttribute("data-id");

            try {
                const res = await fetch(`${API_TIPOS_URL}?action=obtener&id=${id}`);
                const data = await res.json();

                if (data.status === "success") {
                    const t = data.tipo;
                    document.getElementById("idTipoCaso").value = t.id;
                    document.getElementById("tipo_nombre").value = t.nombre;

                    modoEdicionTipo = true;

                    if (lblTituloModalTipo) lblTituloModalTipo.textContent = `[EDITAR_TIPO: ${t.nombre}]`;
                    if (btnGuardarTipo) btnGuardarTipo.textContent = "[UPDATE_DEPLOY]";
                    if (btnCancelarModalTipo) btnCancelarModalTipo.classList.remove("hidden");

                    abrirModalTipo();
                }
            } catch (err) {
                console.error("🔴 Error obteniendo tipo:", err);
            }
            return;
        }

        // --- ELIMINAR TIPO DE CASO ---
        const btnDeleteTipo = e.target.closest(".btn-delete-tipo");
        if (btnDeleteTipo) {
            e.preventDefault();
            const id = btnDeleteTipo.getAttribute("data-id");

            const ok = await Swal.fire({
                title: "¿PURGAR TIPO DE CASO?",
                text: `Confirmar eliminación del ID: ${id}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "[CONFIRM_PURGE]",
                cancelButtonText: "CANCELAR",
                background: '#ffffff',
                color: '#0f172a',
                confirmButtonColor: '#dc2626'
            });

            if (!ok.isConfirmed) return;

            try {
                const res = await fetch(`${API_TIPOS_URL}?action=eliminar&id=${id}`, { method: "DELETE" });
                const r = await res.json();

                if (r.status === "success") {
                    Swal.fire("PURGADO", r.message, "success");
                    cargarTiposCaso();
                } else {
                    Swal.fire("PURGA FALLIDA", r.message, "error");
                }
            } catch (err) {
                Swal.fire("ERROR", err.message, "error");
            }
            return;
        }

        // --- EDITAR ESTADO DE CASO ---
        const btnEditEstado = e.target.closest(".btn-edit-estado");
        if (btnEditEstado) {
            e.preventDefault();
            const id = btnEditEstado.getAttribute("data-id");

            try {
                const res = await fetch(`${API_ESTADOS_URL}?action=obtener&id=${id}`);
                const data = await res.json();

                if (data.status === "success") {
                    const est = data.estado;
                    document.getElementById("idEstadoCaso").value = est.id;
                    document.getElementById("estado_nombre").value = est.nombre;

                    modoEdicionEstado = true;

                    if (lblTituloModalEstado) lblTituloModalEstado.textContent = `[EDITAR_ESTADO: ${est.nombre}]`;
                    if (btnGuardarEstado) btnGuardarEstado.textContent = "[UPDATE_DEPLOY]";
                    if (btnCancelarModalEstado) btnCancelarModalEstado.classList.remove("hidden");

                    abrirModalEstado();
                }
            } catch (err) {
                console.error("🔴 Error obteniendo estado:", err);
            }
            return;
        }

        // --- ELIMINAR ESTADO DE CASO ---
        const btnDeleteEstado = e.target.closest(".btn-delete-estado");
        if (btnDeleteEstado) {
            e.preventDefault();
            const id = btnDeleteEstado.getAttribute("data-id");

            const ok = await Swal.fire({
                title: "¿PURGAR ESTADO DE CASO?",
                text: `Confirmar eliminación del ID: ${id}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "[CONFIRM_PURGE]",
                cancelButtonText: "CANCELAR",
                background: '#ffffff',
                color: '#0f172a',
                confirmButtonColor: '#dc2626'
            });

            if (!ok.isConfirmed) return;

            try {
                const res = await fetch(`${API_ESTADOS_URL}?action=eliminar&id=${id}`, { method: "DELETE" });
                const r = await res.json();

                if (r.status === "success") {
                    Swal.fire("PURGADO", r.message, "success");
                    cargarEstadosCaso();
                } else {
                    Swal.fire("PURGA FALLIDA", r.message, "error");
                }
            } catch (err) {
                Swal.fire("ERROR", err.message, "error");
            }
        }
    });

    // ==========================================
    // 🚀 INICIALIZACIÓN AUTOMÁTICA
    // ==========================================
    const init = setInterval(() => {
        if (document.getElementById("tablaTiposCaso") && document.getElementById("tablaEstadosCaso")) {
            clearInterval(init);
            cargarTiposCaso();
            cargarEstadosCaso();
        }
    }, 100);

})();