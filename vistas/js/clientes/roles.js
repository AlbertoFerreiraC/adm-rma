(function () {
    console.log("⚡ [SYS_AUTH] Sistema de Gestión de Roles iniciado (Modo Modal Nativo)...");

    // Endpoint de la API
    const API_URL = "/rma-app/api-rma/usuarios/roles.php";

    let modoEdicion = false;
    let bloqueandoSubmit = false;
    let listaRolesCache = [];

    // Elementos del DOM
    const form = document.getElementById("formRol");
    const buscarRolInput = document.getElementById("buscarRol");
    const btnNuevoRol = document.getElementById("btnNuevoRol");
    const lblTituloModal = document.getElementById("lblTituloModal");
    const btnGuardar = document.getElementById("btnGuardarRol");

    // Modal Nativo
    const modalOverlay = document.getElementById("modalRolOverlay");
    const btnCerrarModalX = document.getElementById("btnCerrarModalX");
    const btnCancelarModal = document.getElementById("btnCancelarModal");

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
    }

    if (btnCerrarModalX) btnCerrarModalX.addEventListener("click", cerrarModal);
    if (btnCancelarModal) btnCancelarModal.addEventListener("click", cerrarModal);

    // ==========================================
    // 📡 1. CARGAR ROLES DESDE LA BD
    // ==========================================
    async function cargarRoles() {
        const tbody = document.querySelector("#tablaRoles tbody");
        if (!tbody) return;

        try {
            const res = await fetch(`${API_URL}?action=listar`);
            const data = await res.json();

            if (data.status === "success") {
                listaRolesCache = data.roles || [];
                renderizarTablaRoles(listaRolesCache);
            } else {
                tbody.innerHTML = `<tr><td colspan="4" class="font-mono text-center">NO SE ENCONTRARON REGISTROS DE ROLES</td></tr>`;
            }
        } catch (error) {
            console.error("🔴 Error cargando roles:", error);
        }
    }

    function renderizarTablaRoles(roles) {
        const tbody = document.querySelector("#tablaRoles tbody");
        if (!tbody) return;

        tbody.innerHTML = "";

        if (roles.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="font-mono text-center">NO HAY ROLES REGISTRADOS</td></tr>`;
            return;
        }

        roles.forEach(r => {
            const tr = document.createElement("tr");

            const badgeEstado = r.estado === 'activo'
                ? `<span class="system-badge-live">OPERATIVO (ACTIVO)</span>`
                : `<span class="system-badge-live" style="border-color:#dc2626; color:#dc2626; background:rgba(220,38,38,0.08);">CORRUPTO (INACTIVO)</span>`;

            tr.innerHTML = `
                <td class="t-cyan font-mono">${r.id}</td>
                <td class="font-mono font-weight-bold">${r.nombre ? r.nombre.toUpperCase() : ''}</td>
                <td>${badgeEstado}</td>
                <td>
                    <button type="button" class="btn-terminal-edit" data-id="${r.id}" title="Editar Rol">
                        <i class="fa fa-edit"></i> EDIT
                    </button>
                    <button type="button" class="btn-terminal-delete" data-id="${r.id}" title="Eliminar Rol">
                        <i class="fa fa-trash"></i> DEL
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    }

    // ==========================================
    // 🔍 2. BUSCADOR EN TIEMPO REAL
    // ==========================================
    if (buscarRolInput) {
        buscarRolInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaRolesCache.filter(r =>
                r.id.toString().includes(termino) ||
                (r.nombre && r.nombre.toLowerCase().includes(termino)) ||
                (r.estado && r.estado.toLowerCase().includes(termino))
            );
            renderizarTablaRoles(filtrados);
        });
    }

    // ==========================================
    // 📥 3. ENVIAR FORMULARIO (CREAR / EDITAR)
    // ==========================================
    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (bloqueandoSubmit) return;
            bloqueandoSubmit = true;

            const datos = Object.fromEntries(new FormData(form));

            if (!datos.id || datos.id.trim() === "") {
                delete datos.id;
            }

            const action = modoEdicion ? "actualizar" : "guardar";

            try {
                const res = await fetch(`${API_URL}?action=${action}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });

                const r = await res.json();

                if (r.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: modoEdicion ? "NODO ROL ACTUALIZADO" : "NODO ROL INYECTADO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#0284c7'
                    });

                    cerrarModal();
                    resetForm();
                    cargarRoles();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "ERROR DE MATRIZ",
                        text: r.message,
                        background: '#ffffff',
                        color: '#dc2626'
                    });
                }

            } catch (err) {
                Swal.fire({
                    icon: "error",
                    title: "CRITICAL_FAIL",
                    text: err.message,
                    background: '#ffffff',
                    color: '#dc2626'
                });
            }

            bloqueandoSubmit = false;
        });
    }

    // ==========================================
    // 🔁 RESET Y PREPARACIÓN DEL FORMULARIO
    // ==========================================
    function resetForm() {
        if (!form) return;
        form.reset();

        document.getElementById("idRol").value = "";
        modoEdicion = false;

        if (lblTituloModal) lblTituloModal.textContent = "[INJECT_NEW_ROLE]";
        if (btnGuardar) btnGuardar.textContent = "[EXECUTE_DEPLOYMENT]";

        // OCULTAR BOTÓN DE CANCELAR EDICIÓN
        if (btnCancelarModal) btnCancelarModal.classList.add("hidden");
    }

    // Botón [+ DECLARAR ROL]
    if (btnNuevoRol) {
        btnNuevoRol.addEventListener("click", (e) => {
            e.preventDefault();
            resetForm();
            abrirModal();
        });
    }

    // ==========================================
    // ⚙️ 4. DELEGACIÓN DE EVENTOS EN TABLA (EDITAR / ELIMINAR)
    // ==========================================
    document.addEventListener("click", async function (e) {
        // Clic en EDITAR
        const btnEdit = e.target.closest(".btn-terminal-edit");
        if (btnEdit) {
            e.preventDefault();
            const id = btnEdit.getAttribute("data-id");

            try {
                Swal.fire({
                    title: 'CONSULTANDO BASE DE DATOS...',
                    text: 'Cargando rol ID: ' + id,
                    background: '#ffffff',
                    color: '#0f172a',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const res = await fetch(`${API_URL}?action=obtener&id=${id}`);
                const data = await res.json();

                Swal.close();

                if (data.status === "success") {
                    const r = data.rol;

                    document.getElementById("idRol").value = r.id;
                    document.getElementById("rol_nombre").value = r.nombre;
                    document.getElementById("rol_estado").value = r.estado;

                    modoEdicion = true;

                    if (lblTituloModal) lblTituloModal.textContent = `[EDITAR_ROL: ${r.nombre.toUpperCase()}]`;
                    if (btnGuardar) btnGuardar.textContent = "[UPDATE_DEPLOY]";

                    // MOSTRAR BOTÓN DE CANCELAR EN MODO EDICIÓN
                    if (btnCancelarModal) btnCancelarModal.classList.remove("hidden");

                    abrirModal();
                } else {
                    Swal.fire("ERROR", data.message, "error");
                }
            } catch (err) {
                Swal.close();
                console.error("🔴 Error en petición editar rol:", err);
                Swal.fire("ERROR", "No se pudo consultar el servicio para editar.", "error");
            }
            return;
        }

        // Clic en ELIMINAR
        const btnDelete = e.target.closest(".btn-terminal-delete");
        if (btnDelete) {
            e.preventDefault();
            const id = btnDelete.getAttribute("data-id");

            const ok = await Swal.fire({
                title: "¿PURGAR ROL?",
                text: `Esta acción desconectará permanentemente el nodo de la matriz. ID: ${id}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "[CONFIRM_PURGE]",
                cancelButtonText: "CANCELAR",
                background: '#ffffff',
                color: '#0f172a',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#475569'
            });

            if (!ok.isConfirmed) return;

            try {
                const res = await fetch(`${API_URL}?action=eliminar&id=${id}`, {
                    method: "DELETE"
                });
                const r = await res.json();

                if (r.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "NODO PURGADO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#0f172a'
                    });
                    cargarRoles();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "PURGA FALLIDA",
                        text: r.message,
                        background: '#ffffff',
                        color: '#dc2626'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: "error",
                    title: "ERROR DE CONEXIÓN",
                    text: "No se pudo comunicar con el protocolo de eliminación.",
                    background: '#ffffff',
                    color: '#dc2626'
                });
            }
        }
    });

    // ==========================================
    // 🚀 INICIALIZACIÓN AUTOMÁTICA
    // ==========================================
    const init = setInterval(() => {
        if (document.getElementById("tablaRoles")) {
            clearInterval(init);
            cargarRoles();
        }
    }, 100);

})();