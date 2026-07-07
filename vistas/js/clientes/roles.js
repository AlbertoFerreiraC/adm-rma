(function () {
    console.log("⚡ [SYS_AUTH] Matrix de Roles iniciada...");

    // Corrige esta URL si tu endpoint está en otra ubicación relativa
    const API_URL = "../api-rma/usuarios/roles.php";

    let modoEdicion = false;
    let rolOriginal = null;
    let bloqueandoSubmit = false;

    // Vinculamos con el ID exacto del HTML corregido
    const form = document.getElementById("formRol");

    // 🔥 BLOQUEO SUBMIT NATIVO
    if (form) {
        form.onsubmit = (e) => e.preventDefault();
    }

    // ==========================================
    // 📡 LISTAR ROLES
    // ==========================================
    async function cargarRoles() {
        const tbody = document.querySelector(".cyber-mini-table tbody");
        if (!tbody) return;

        const res = await fetch(`${API_URL}?action=listar`);
        const data = await res.json();

        if (data.status !== "success") return;

        tbody.innerHTML = "";

        data.roles.forEach(r => {
            const tr = document.createElement("tr");

            // 🔥 COMPARA CON PALABRAS DIRECTAMENTE
            const badgeClass = r.estado === "activo" ? "badge-diag" : "badge-ready";
            const estadoTexto = r.estado === "activo" ? "OPERATIVO" : "CORRUPTO";
            const textoColorClass = r.id % 2 === 0 ? "neon-text-purple" : "neon-text-blue";

            tr.innerHTML = `
                <td class="t-cyan font-mono">${r.id}</td>
                <td class="${textoColorClass} font-weight-bold">${r.nombre.toUpperCase()}</td>
                <td><span class="badge-status ${badgeClass}">${estadoTexto}</span></td>
                <td style="text-align: center;">
                    <button class="btn-cyber-action btn-edit" data-id="${r.id}">
                        EDIT
                    </button>
                    <button class="btn-cyber-action btn-delete" data-id="${r.id}">
                        DELETE
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });

        bindButtons();
    }

    // ==========================================
    // 📥 SUBMIT (CREAR / ACTUALIZAR)
    // ==========================================
    form?.addEventListener("submit", async (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (bloqueandoSubmit) return;
        bloqueandoSubmit = true;

        const datos = Object.fromEntries(new FormData(form));

        // Evitamos mandar un ID vacío en modo creación
        if (!form.id.value || form.id.value.trim() === "") {
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
                    text: r.message
                });

                resetForm();
                cargarRoles();
            } else {
                Swal.fire("ERROR DE MATRIZ", r.message, "error");
            }

        } catch (err) {
            Swal.fire("CRITICAL ERROR", err.message, "error");
        }

        bloqueandoSubmit = false;
    });

    // ==========================================
    // 🔁 RESET TOTAL
    // ==========================================
    function resetForm() {
        if (!form) return;
        form.reset();
        form.id.value = "";

        modoEdicion = false;
        rolOriginal = null;

        const btnSubmit = document.querySelector(".node-submit-btn");
        if (btnSubmit) btnSubmit.textContent = "[INJECT_NEW_ROLE]";

        const btnCancel = document.getElementById("btnCancelarEdicion");
        if (btnCancel) btnCancel.style.display = "none";
    }

    // ==========================================
    // ❌ CANCELAR EDICIÓN
    // ==========================================
    function cancelarEdicion() {
        resetForm();
        Swal.fire({
            icon: "info",
            title: "INYECCIÓN REINICIADA",
            text: "Modo declaración activado"
        });
    }

    // ==========================================
    // ⚙️ BOTONES TABLA (EDITAR / ELIMINAR)
    // ==========================================
    function bindButtons() {
        // Botón Modificar (EDIT)
        document.querySelectorAll(".btn-edit").forEach(b => {
            b.onclick = async () => {
                const id = b.dataset.id;

                const res = await fetch(`${API_URL}?action=obtener&id=${id}`);
                const data = await res.json();

                if (data.status === "success") {
                    const r = data.rol;

                    form.nombre.value = r.nombre;
                    form.estado.value = r.estado;
                    form.id.value = r.id;

                    modoEdicion = true;
                    rolOriginal = r;

                    const btnSubmit = document.querySelector(".node-submit-btn");
                    if (btnSubmit) btnSubmit.textContent = "[UPDATE_ROLE_MATRIX]";

                    const btnCancel = document.getElementById("btnCancelarEdicion");
                    if (btnCancel) btnCancel.style.display = "block";
                }
            };
        });

        // Botón Baja (PURGE)
        document.querySelectorAll(".btn-delete").forEach(b => {
            b.onclick = async () => {
                const id = b.dataset.id;

                const ok = await Swal.fire({
                    title: "¿Purgar rol del sistema?",
                    text: "Esta acción desconectará permanentemente el nodo de la matriz.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#ff0055",
                    cancelButtonColor: "#101c38",
                    confirmButtonText: "[CONFIRM_PURGE]",
                    cancelButtonText: "[ABORT]"
                });

                if (!ok.isConfirmed) return;

                try {
                    // Capturamos la respuesta del servidor
                    const res = await fetch(`${API_URL}?action=eliminar&id=${id}`, {
                        method: "DELETE"
                    });

                    const r = await res.json();

                    // Validamos si el backend dio luz verde o reportó dependencias
                    if (r.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "NODO PURGADO",
                            text: r.message
                        });
                        cargarRoles(); // Recargamos la tabla si se borró con éxito
                    } else {
                        // Aquí es donde saltará el error de "Existen dependencias activas"
                        Swal.fire({
                            icon: "error",
                            title: "PURGA FALLIDA",
                            text: r.message
                        });
                    }

                } catch (err) {
                    Swal.fire({
                        icon: "error",
                        title: "ERROR DE CONEXIÓN",
                        text: "No se pudo comunicar con el protocolo de eliminación."
                    });
                }
            };
        });
    }

    // Event listener nativo para el botón de abortar
    document.getElementById("btnCancelarEdicion")?.addEventListener("click", cancelarEdicion);

    // ==========================================
    // 🚀 INIT AUTOMÁTICO
    // ==========================================
    const init = setInterval(() => {
        if (document.querySelector(".cyber-mini-table")) {
            clearInterval(init);
            cargarRoles();
        }
    }, 100);

})();