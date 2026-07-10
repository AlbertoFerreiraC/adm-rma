(function () {
    console.log("⚡ [RMA_CORE] Sistema de Diagnóstico de Laboratorio con Filtrado Avanzado Activo...");

    const API_URL = "../api-rma/rma-core/casos/taller.php";
    const form = document.getElementById("formDiagnostico");
    const tbody = document.getElementById("tbodyTallerCola");
    const buscadorCola = document.getElementById("buscadorTallerCola");
    const filtroEstado = document.getElementById("filtroEstadoTaller");
    const ordenCola = document.getElementById("ordenTallerCola");

    async function inicializarTaller() {
        try {
            const selectEstado = document.getElementById("selectEstadoDiag");
            if (selectEstado && selectEstado.options.length <= 1) {
                const resEstados = await fetch(`${API_URL}?action=aux_estados`);
                const dataEst = await resEstados.json();
                if (dataEst.status === "success") {
                    selectEstado.innerHTML = '<option value="">[SELECCIONE NUEVO ESTADO]</option>';
                    dataEst.estados.forEach(e => {
                        const opt = document.createElement("option");
                        opt.value = e.id;
                        opt.textContent = `// TRANSLATION: ${e.nombre.toUpperCase()}`;
                        selectEstado.appendChild(opt);
                    });
                }
            }

            await listarColaDispositivos();

        } catch (err) {
            console.error("Fallo de inicialización crítica en taller:", err);
        }
    }

    async function listarColaDispositivos() {
        try {
            const buscar = buscadorCola ? buscadorCola.value.trim() : "";
            const alcance = filtroEstado ? filtroEstado.value : "activos";
            const orden = ordenCola ? ordenCola.value : "asc";
            const queryUrl = `${API_URL}?action=listar_cola&buscar=${encodeURIComponent(buscar)}&alcance=${alcance}&orden=${orden}`;

            const resCola = await fetch(queryUrl);
            const dataCola = await resCola.json();

            if (dataCola.status === "success") {
                tbody.innerHTML = "";
                document.getElementById("contadorCola").textContent = `WAIT: ${dataCola.cola.length}`;

                if (dataCola.cola.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" style="text-align:center; color:#506690;">[NO_PENDING_HARDWARE_FOUND]</td></tr>`;
                    return;
                }

                dataCola.cola.forEach(c => {
                    const tr = document.createElement("tr");
                    const badgeClass = c.id_estado_actual == 1 ? "status-1" : "status-default";

                    tr.innerHTML = `
                        <td class="text-neon-cyan font-weight-bold">${c.numero_caso}</td>
                        <td style="text-transform:uppercase;">${c.equipo} <span style="color:#506690;">${c.marca}</span><br><small style="color:#ffca28;">S/N: ${c.numero_serie}</small></td>
                        <td><span class="badge-status-cyber ${badgeClass}">${c.estado_nombre.toUpperCase()}</span></td>
                        <td style="text-align:center;"><button class="btn-cyber-action btn-select-node" data-id="${c.id}">[LOAD_NODE]</button></td>
                    `;

                    tr.querySelector(".btn-select-node").onclick = (e) => {
                        e.stopPropagation();
                        cargarCasoEnConsola(c.id);
                    };
                    tr.onclick = () => cargarCasoEnConsola(c.id);

                    tbody.appendChild(tr);
                });
            }
        } catch (err) {
            console.error("Error mapeando el streaming de la cola:", err);
        }
    }

    async function cargarCasoEnConsola(id) {
        try {
            const res = await fetch(`${API_URL}?action=obtener_caso&id=${id}`);
            const data = await res.json();

            if (data.status === "success") {
                const c = data.caso;

                form.classList.add("cyber-active-form");
                document.getElementById("btnGuardarDiag").disabled = false;

                document.getElementById("diagIdCaso").value = c.id;
                document.getElementById("diagNumeroCaso").value = c.numero_caso;
                document.getElementById("diagHardware").value = `${c.equipo} ${c.marca}`.toUpperCase();
                document.getElementById("diagSerie").value = c.numero_serie;
                document.getElementById("diagProblemaOriginal").value = c.descripcion_problema;

                document.getElementById("diagDiagnosticoFinal").value = c.diagnostico_final ?? "";
                document.getElementById("selectEstadoDiag").value = c.id_estado_actual;
                document.getElementById("fileNameDiagDisplay").textContent = c.foto_archivo ? `[CURRENT]: ${c.foto_archivo}` : '';
            }
        } catch (err) {
            Swal.fire("ERROR DE TRANSMISIÓN", "No se pudo recuperar la telemetría del nodo.", "error");
        }
    }

    form?.addEventListener("submit", async (e) => {
        e.preventDefault();

        const datos = new FormData(form);

        try {
            Swal.fire({
                title: "[WRITING_DIAGNOSTIC_LOGS...]",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const res = await fetch(`${API_URL}?action=guardar_diagnostico`, {
                method: "POST",
                body: datos
            });
            const r = await res.json();

            if (r.status === "success") {
                Swal.fire({
                    icon: "success",
                    title: "MATRIZ SINCRONIZADA",
                    text: r.message
                });

                form.reset();
                form.classList.remove("cyber-active-form");
                document.getElementById("btnGuardarDiag").disabled = true;
                document.getElementById("fileNameDiagDisplay").textContent = '';

                await listarColaDispositivos();
            } else {
                Swal.fire("FALLO EN REGISTRO", r.message, "error");
            }
        } catch (err) {
            Swal.fire("CRITICAL CORRUPT ERROR", "La inyección de diagnóstico falló.", "error");
        }
    });

    buscadorCola?.addEventListener("input", () => {
        listarColaDispositivos();
    });

    filtroEstado?.addEventListener("change", () => {
        listarColaDispositivos();
    });

    ordenCola?.addEventListener("change", () => {
        listarColaDispositivos();
    });

    const verifTaller = setInterval(() => {
        if (document.getElementById("formDiagnostico")) {
            clearInterval(verifTaller);
            inicializarTaller();
        }
    }, 100);

})();