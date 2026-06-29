(function () {
    console.log("⚡ [SYS_AUTH] Sistema Usuarios iniciado...");

    const API_URL = "../api-rma/usuarios/usuarios.php";

    let modoEdicion = false;
    let usuarioOriginal = null;
    let bloqueandoSubmit = false;

    const form = document.getElementById("formUsuario");

    // 🔥 BLOQUEO SUBMIT NATIVO
    if (form) {
        form.onsubmit = (e) => e.preventDefault();
    }

    // ==========================================
    // 📡 LISTAR USUARIOS
    // ==========================================
    async function cargarUsuarios() {
        const tbody = document.querySelector("#tablaUsuarios tbody");
        if (!tbody) return;

        const res = await fetch(`${API_URL}?action=listar`);
        const data = await res.json();

        if (data.status !== "success") return;

        tbody.innerHTML = "";

        data.usuarios.forEach(u => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${u.id}</td>
                <td>${u.usuario}</td>
                <td>${u.nombre}</td>
                <td>${u.email}</td>
                <td>${u.rol}</td>
                <td>${u.created_at}</td>
                <td>
                    <button class="btn-cyber btn-cyber-edit" data-id="${u.id}">
                        <i class="fa fa-edit"></i> EDIT
                    </button>

                    <button class="btn-cyber btn-cyber-delete" data-id="${u.id}">
                        <i class="fa fa-trash"></i> DEL
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

        // 🔥 FIX CRÍTICO: usar form.id (no DOM query)
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
                    title: modoEdicion ? "USUARIO ACTUALIZADO" : "USUARIO CREADO",
                    text: r.message
                });

                resetForm();
                cargarUsuarios();
            } else {
                Swal.fire("ERROR", r.message, "error");
            }

        } catch (err) {
            Swal.fire("ERROR", err.message, "error");
        }

        bloqueandoSubmit = false;
    });

    // ==========================================
    // 🔁 RESET TOTAL
    // ==========================================
    function resetForm() {
        form.reset();

        form.id.value = "";

        modoEdicion = false;
        usuarioOriginal = null;

        const btn = document.querySelector(".node-submit-btn");
        if (btn) btn.textContent = "[EXECUTE_DEPLOYMENT]";
    }

    // ==========================================
    // ❌ CANCELAR EDICIÓN (FIX REAL)
    // ==========================================
    function cancelarEdicion() {
        resetForm();

        Swal.fire({
            icon: "info",
            title: "EDICIÓN CANCELADA",
            text: "Modo creación activado"
        });
    }

    // 🔥 bind seguro (evita null crash)
    document.addEventListener("DOMContentLoaded", () => {
        const btnCancel = document.getElementById("btnCancelarEdicion");
        if (btnCancel) {
            btnCancel.addEventListener("click", cancelarEdicion);
        }
    });

    // ==========================================
    // ⚙️ BOTONES TABLA
    // ==========================================
    function bindButtons() {

        document.querySelectorAll(".btn-cyber-edit").forEach(b => {
            b.onclick = async () => {
                const id = b.dataset.id;

                const res = await fetch(`${API_URL}?action=obtener&id=${id}`);
                const data = await res.json();

                if (data.status === "success") {
                    const u = data.usuario;

                    form.usuario.value = u.usuario;
                    form.nombre.value = u.nombre;
                    form.email.value = u.email;
                    form.id_rol.value = u.id_rol;
                    form.id.value = u.id;

                    modoEdicion = true;
                    usuarioOriginal = u;

                    const btn = document.querySelector(".node-submit-btn");
                    if (btn) btn.textContent = "[UPDATE_DEPLOY]";
                }
            };
        });

        document.querySelectorAll(".btn-cyber-delete").forEach(b => {
            b.onclick = async () => {
                const id = b.dataset.id;

                const ok = await Swal.fire({
                    title: "Eliminar usuario?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "SI"
                });

                if (!ok.isConfirmed) return;

                await fetch(`${API_URL}?action=eliminar&id=${id}`, {
                    method: "DELETE"
                });

                cargarUsuarios();
            };
        });
    }

    // ==========================================
    // 🚀 INIT
    // ==========================================
    const init = setInterval(() => {
        if (document.getElementById("tablaUsuarios")) {
            clearInterval(init);
            cargarUsuarios();
        }
    }, 100);

})();