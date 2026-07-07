(function () {
    console.log("⚡ [SYS_AUTH] Sistema de Perfil Personal iniciado...");

    // URL dirigida al endpoint que gestionará el perfil de usuario
    const API_URL = "../api-rma/usuarios/perfil.php";

    let bloqueandoSubmit = false;

    // Vinculamos con el ID exacto del formulario de perfil
    const form = document.getElementById("formPerfil");

    // 🔥 BLOQUEO SUBMIT NATIVO
    if (form) {
        form.onsubmit = (e) => e.preventDefault();
    }

    // ==========================================
    // 📥 SUBMIT (ACTUALIZAR PERFIL)
    // ==========================================
    form?.addEventListener("submit", async (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (bloqueandoSubmit) return;

        // --- VALIDACIÓN DE CONTRASENAS ---
        const pass = document.getElementById("perfilPassword").value;
        const confirmPass = document.getElementById("perfilConfirmPassword").value;

        if (pass !== "" || confirmPass !== "") {
            if (pass !== confirmPass) {
                Swal.fire({
                    icon: "error",
                    title: "ERROR DE AUTENTICACIÓN",
                    text: "Las nuevas claves ingresadas no coinciden en la matriz."
                });
                return;
            }
        }

        bloqueandoSubmit = true;

        // Empaquetamos los datos del formulario (id, nombre, email, contrasena)
        const datos = Object.fromEntries(new FormData(form));

        try {
            const res = await fetch(`${API_URL}?action=actualizar`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(datos)
            });

            const r = await res.json();

            if (r.status === "success") {
                Swal.fire({
                    icon: "success",
                    title: "PERFIL ACTUALIZADO",
                    text: r.message
                });

                // Limpiamos los campos de contraseña por seguridad
                document.getElementById("perfilPassword").value = "";
                document.getElementById("perfilConfirmPassword").value = "";

            } else {
                Swal.fire("FALLO DE INYECCIÓN", r.message, "error");
            }

        } catch (err) {
            Swal.fire("CRITICAL ERROR", err.message, "error");
        }

        bloqueandoSubmit = false;
    });

})();