(function () {
    console.log("⚡ [SYS_AUTH] Sistema de Gestión de Usuarios iniciado (Modo Modal Nativo)...");

    // Endpoint de la API
    const API_URL = "/rma-app/api-rma/usuarios/usuarios.php";

    let modoEdicion = false;
    let bloqueandoSubmit = false;
    let listaUsuariosCache = [];

    // Elementos del DOM
    const form = document.getElementById("formUsuario");
    const buscarUsuarioInput = document.getElementById("buscarUsuario");
    const btnNuevoUsuario = document.getElementById("btnNuevoUsuario");
    const lblTituloModal = document.getElementById("lblTituloModal");
    const btnGuardar = document.getElementById("btnGuardarUsuario");
    const passInput = document.getElementById("usuario_pass");

    // Modal Nativo
    const modalOverlay = document.getElementById("modalUsuarioOverlay");
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
    // 📡 1. CARGAR USUARIOS DESDE LA BD
    // ==========================================
    async function cargarUsuarios() {
        const tbody = document.querySelector("#tablaUsuarios tbody");
        if (!tbody) return;

        try {
            const res = await fetch(`${API_URL}?action=listar`);
            const data = await res.json();

            if (data.status === "success") {
                listaUsuariosCache = data.usuarios || [];
                renderizarTablaUsuarios(listaUsuariosCache);
            } else {
                tbody.innerHTML = `<tr><td colspan="7" class="font-mono text-center">NO SE ENCONTRARON REGISTROS DE USUARIOS</td></tr>`;
            }
        } catch (error) {
            console.error("🔴 Error cargando usuarios:", error);
        }
    }

    function renderizarTablaUsuarios(usuarios) {
        const tbody = document.querySelector("#tablaUsuarios tbody");
        if (!tbody) return;

        tbody.innerHTML = "";

        if (usuarios.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="font-mono text-center">NO HAY USUARIOS REGISTRADOS</td></tr>`;
            return;
        }

        usuarios.forEach(u => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td class="t-cyan font-mono">${u.id}</td>
                <td class="font-mono font-weight-bold">${u.usuario}</td>
                <td>${u.nombre}</td>
                <td class="font-mono">${u.email}</td>
                <td><span class="system-badge-live">${u.rol ? u.rol.toUpperCase() : 'N/A'}</span></td>
                <td class="font-mono">${u.created_at}</td>
                <td>
                    <button type="button" class="btn-terminal-edit" data-id="${u.id}" title="Editar Usuario">
                        <i class="fa fa-edit"></i> EDIT
                    </button>
                    <button type="button" class="btn-terminal-delete" data-id="${u.id}" title="Eliminar Usuario">
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
    if (buscarUsuarioInput) {
        buscarUsuarioInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaUsuariosCache.filter(u =>
                u.usuario.toLowerCase().includes(termino) ||
                u.nombre.toLowerCase().includes(termino) ||
                u.email.toLowerCase().includes(termino) ||
                (u.rol && u.rol.toLowerCase().includes(termino))
            );
            renderizarTablaUsuarios(filtrados);
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

            if (!modoEdicion && (!datos.contrasena || datos.contrasena.trim() === "")) {
                Swal.fire({
                    icon: "warning",
                    title: "CAMPO REQUERIDO",
                    text: "Debe ingresar una contraseña para el nuevo usuario.",
                    background: '#ffffff',
                    color: '#0f172a'
                });
                bloqueandoSubmit = false;
                return;
            }

            if (modoEdicion && (!datos.contrasena || datos.contrasena.trim() === "")) {
                delete datos.contrasena;
            }

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
                        title: modoEdicion ? "USUARIO ACTUALIZADO" : "USUARIO CREADO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#0284c7'
                    });

                    cerrarModal();
                    resetForm();
                    cargarUsuarios();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "ERROR OPERACIONAL",
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

        document.getElementById("idUsuario").value = "";
        modoEdicion = false;

        if (lblTituloModal) lblTituloModal.textContent = "[INYECTAR_NODO_USUARIO]";
        if (btnGuardar) btnGuardar.textContent = "[EXECUTE_DEPLOYMENT]";
        if (passInput) passInput.setAttribute("required", "required");

        // OCULTAR EL BOTÓN DE CANCELAR EDICIÓN EN NUEVO REGISTRO
        if (btnCancelarModal) btnCancelarModal.classList.add("hidden");
    }

    // Botón [+ AGREGAR USUARIO]
    if (btnNuevoUsuario) {
        btnNuevoUsuario.addEventListener("click", (e) => {
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
                    text: 'Cargando usuario ID: ' + id,
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
                    const u = data.usuario;

                    document.getElementById("idUsuario").value = u.id;
                    document.getElementById("usuario_alias").value = u.usuario;
                    document.getElementById("usuario_nombre").value = u.nombre;
                    document.getElementById("usuario_email").value = u.email;
                    document.getElementById("usuario_rol").value = u.id_rol;
                    document.getElementById("usuario_pass").value = "";

                    if (passInput) passInput.removeAttribute("required");

                    modoEdicion = true;

                    if (lblTituloModal) lblTituloModal.textContent = `[EDITAR_USUARIO: ${u.usuario}]`;
                    if (btnGuardar) btnGuardar.textContent = "[UPDATE_DEPLOY]";

                    // MOSTRAR BOTÓN DE CANCELAR EN MODO EDICIÓN
                    if (btnCancelarModal) btnCancelarModal.classList.remove("hidden");

                    abrirModal();
                } else {
                    Swal.fire("ERROR", data.message, "error");
                }
            } catch (err) {
                Swal.close();
                console.error("🔴 Error en petición editar:", err);
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
                title: "¿PURGAR OPERADOR?",
                text: `Confirmar eliminación del usuario ID: ${id}`,
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
                        title: "PURGADO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#0f172a'
                    });
                    cargarUsuarios();
                } else {
                    Swal.fire("ERROR", r.message, "error");
                }
            } catch (err) {
                Swal.fire("ERROR", err.message, "error");
            }
        }
    });

    // ==========================================
    // 🚀 INICIALIZACIÓN
    // ==========================================
    const init = setInterval(() => {
        if (document.getElementById("tablaUsuarios")) {
            clearInterval(init);
            cargarUsuarios();
        }
    }, 100);

})();