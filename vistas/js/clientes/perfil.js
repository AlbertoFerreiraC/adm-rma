(function () {
    console.log("⚡ [SYS_AUTH] Sistema de Perfil Personal / Operadores iniciado...");

    // Endpoint de la API
    const API_URL = "/rma-app/api-rma/usuarios/usuarios.php";

    let modoEdicion = false;
    let bloqueandoSubmit = false;
    let listaPerfilesCache = [];

    // Formulario de Perfil Propio (Operador Estándar)
    const formPerfil = document.getElementById("formPerfil");

    // Formulario e Interfaz ABM (Administrador)
    const formAdmin = document.getElementById("formPerfilAdmin");
    const buscarPerfilInput = document.getElementById("buscarPerfil");
    const btnNuevoPerfil = document.getElementById("btnNuevoPerfil");
    const lblTituloModal = document.getElementById("lblTituloModal");
    const btnGuardar = document.getElementById("btnGuardarPerfil");
    const passInputModal = document.getElementById("perfil_pass");

    // Modal Nativo
    const modalOverlay = document.getElementById("modalPerfilOverlay");
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
    // 👤 1. ACTUALIZAR PERFIL PROPIO (OPERADOR ESTÁNDAR)
    // ==========================================
    if (formPerfil) {
        formPerfil.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (bloqueandoSubmit) return;

            const pass = document.getElementById("perfilPassword") ? document.getElementById("perfilPassword").value : "";
            const confirmPass = document.getElementById("perfilConfirmPassword") ? document.getElementById("perfilConfirmPassword").value : "";

            if (pass !== "" || confirmPass !== "") {
                if (pass !== confirmPass) {
                    Swal.fire({
                        icon: "error",
                        title: "ERROR DE AUTENTICACIÓN",
                        text: "Las nuevas claves ingresadas no coinciden.",
                        background: '#ffffff',
                        color: '#0f172a'
                    });
                    return;
                }
            }

            bloqueandoSubmit = true;
            const datos = Object.fromEntries(new FormData(formPerfil));

            if (!datos.contrasena || datos.contrasena.trim() === "") {
                delete datos.contrasena;
            }

            try {
                const res = await fetch(`${API_URL}?action=actualizar_perfil_propio`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });

                const r = await res.json();

                if (r.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "PERFIL ACTUALIZADO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#0284c7'
                    });

                    if (document.getElementById("perfilPassword")) document.getElementById("perfilPassword").value = "";
                    if (document.getElementById("perfilConfirmPassword")) document.getElementById("perfilConfirmPassword").value = "";
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "FALLO DE INYECCIÓN",
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
    // 📡 2. CARGAR OPERADORES (MODO ADMIN)
    // ==========================================
    async function cargarPerfiles() {
        const tbody = document.querySelector("#tablaPerfiles tbody");
        if (!tbody) return;

        try {
            const res = await fetch(`${API_URL}?action=listar`);
            const data = await res.json();

            if (data.status === "success") {
                listaPerfilesCache = data.usuarios || [];
                renderizarTablaPerfiles(listaPerfilesCache);
            } else {
                tbody.innerHTML = `<tr><td colspan="7" class="font-mono text-center">NO SE ENCONTRARON OPERADORES</td></tr>`;
            }
        } catch (error) {
            console.error("🔴 Error cargando perfiles:", error);
        }
    }

    function renderizarTablaPerfiles(perfiles) {
        const tbody = document.querySelector("#tablaPerfiles tbody");
        if (!tbody) return;

        tbody.innerHTML = "";

        if (perfiles.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="font-mono text-center">NO HAY OPERADORES REGISTRADOS</td></tr>`;
            return;
        }

        perfiles.forEach(p => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td class="t-cyan font-mono">${p.id}</td>
                <td class="font-mono font-weight-bold">${p.usuario}</td>
                <td>${p.nombre}</td>
                <td class="font-mono">${p.email}</td>
                <td><span class="system-badge-live">${p.rol ? p.rol.toUpperCase() : 'N/A'}</span></td>
                <td class="font-mono">${p.created_at}</td>
                <td>
                    <button type="button" class="btn-terminal-edit" data-id="${p.id}" title="Editar Operador">
                        <i class="fa fa-edit"></i> EDIT
                    </button>
                    <button type="button" class="btn-terminal-delete" data-id="${p.id}" title="Eliminar Operador">
                        <i class="fa fa-trash"></i> DEL
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    }

    // ==========================================
    // 🔍 3. BUSCADOR EN TIEMPO REAL (MODO ADMIN)
    // ==========================================
    if (buscarPerfilInput) {
        buscarPerfilInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaPerfilesCache.filter(p =>
                p.id.toString().includes(termino) ||
                p.usuario.toLowerCase().includes(termino) ||
                p.nombre.toLowerCase().includes(termino) ||
                p.email.toLowerCase().includes(termino) ||
                (p.rol && p.rol.toLowerCase().includes(termino))
            );
            renderizarTablaPerfiles(filtrados);
        });
    }

    // ==========================================
    // 📥 4. ENVIAR FORMULARIO ABM (MODO ADMIN)
    // ==========================================
    if (formAdmin) {
        formAdmin.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (bloqueandoSubmit) return;
            bloqueandoSubmit = true;

            const datos = Object.fromEntries(new FormData(formAdmin));

            if (!modoEdicion && (!datos.contrasena || datos.contrasena.trim() === "")) {
                Swal.fire({
                    icon: "warning",
                    title: "CAMPO REQUERIDO",
                    text: "Debe ingresar una contraseña para el operador.",
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
                        title: modoEdicion ? "OPERADOR ACTUALIZADO" : "OPERADOR CREADO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#0284c7'
                    });

                    cerrarModal();
                    resetFormAdmin();
                    cargarPerfiles();
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
    // 🔁 RESET Y PREPARACIÓN DEL FORMULARIO ADMIN
    // ==========================================
    function resetFormAdmin() {
        if (!formAdmin) return;
        formAdmin.reset();

        document.getElementById("idPerfilModal").value = "";
        modoEdicion = false;

        if (lblTituloModal) lblTituloModal.textContent = "[INYECTAR_NODO_OPERADOR]";
        if (btnGuardar) btnGuardar.textContent = "[EXECUTE_DEPLOYMENT]";
        if (passInputModal) passInputModal.setAttribute("required", "required");

        if (btnCancelarModal) btnCancelarModal.classList.add("hidden");
    }

    // Botón [+ AGREGAR OPERADOR]
    if (btnNuevoPerfil) {
        btnNuevoPerfil.addEventListener("click", (e) => {
            e.preventDefault();
            resetFormAdmin();
            abrirModal();
        });
    }

    // ==========================================
    // ⚙️ 5. DELEGACIÓN DE EVENTOS EN TABLA (EDITAR / ELIMINAR)
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
                    text: 'Cargando datos del operador ID: ' + id,
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
                    const p = data.usuario;

                    document.getElementById("idPerfilModal").value = p.id;
                    document.getElementById("perfil_alias").value = p.usuario;
                    document.getElementById("perfil_nombre").value = p.nombre;
                    document.getElementById("perfil_email").value = p.email;
                    document.getElementById("perfil_rol").value = p.id_rol;
                    document.getElementById("perfil_pass").value = "";

                    if (passInputModal) passInputModal.removeAttribute("required");

                    modoEdicion = true;

                    if (lblTituloModal) lblTituloModal.textContent = `[EDITAR_OPERADOR: ${p.usuario}]`;
                    if (btnGuardar) btnGuardar.textContent = "[UPDATE_DEPLOY]";

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
                text: `Confirmar eliminación del operador ID: ${id}`,
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
                    cargarPerfiles();
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
        if (document.getElementById("tablaPerfiles")) {
            clearInterval(init);
            cargarPerfiles();
        }
    }, 100);

})();