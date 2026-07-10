(function () {
    console.log("⚡ [RMA_CORE] Consola Logística Externa Inicializada...");

    const API_URL = "../api-rma/rma-core/casos/proveedoresRma.php";
    const form = document.getElementById("formFlujoExterno");
    const tbody = document.getElementById("tbodyLogistica");
    const buscador = document.getElementById("buscadorLogistica");
    const filtroAlcance = document.getElementById("filtroLogisticaAlcance");
    const inputFecha = document.getElementById("logFechaEnvio");

    if (inputFecha) {
        inputFecha.value = new Date().toISOString().split('T')[0];
    }

    async function inicializarModuloLogistica() {
        try {
            const resProv = await fetch(`${API_URL}?action=aux_proveedores`);
            const dataProv = await resProv.json();
            if (dataProv.status === "success") {
                const selectP = document.getElementById("selectProveedorLog");
                selectP.innerHTML = '<option value="">[SELECCIONE SERVICE AUTORIZADO]</option>';
                dataProv.proveedores.forEach(p => {
                    const opt = document.createElement("option");
                    opt.value = p.id;
                    opt.textContent = `// SERVICE: ${p.nombre.toUpperCase()} (CON: ${p.contacto})`;
                    selectP.appendChild(opt);
                });
            }

            const resEst = await fetch(`${API_URL}?action=aux_estados`);
            const dataEst = await resEst.json();
            if (dataEst.status === "success") {
                const selectE = document.getElementById("selectEstadoLog");
                selectE.innerHTML = '<option value="">[SELECCIONE TRANSICIÓN DE ESTADO]</option>';
                dataEst.estados.forEach(e => {
                    const opt = document.createElement("option");
                    opt.value = e.id;
                    opt.textContent = `// TRANSLATION: ${e.nombre.toUpperCase()}`;
                    selectE.appendChild(opt);
                });
            }

            await listarMatrizLogistica();

        } catch (err) {
            console.error("Fallo crítico cargando catálogos de logística:", err);
        }
    }

    async function listarMatrizLogistica() {
        try {
            const buscar = buscador ? buscador.value.trim() : "";
            const alcance = filtroAlcance ? filtroAlcance.value : "todos";

            const res = await fetch(`${API_URL}?action=listar_logistica&buscar=${encodeURIComponent(buscar)}&alcance=${alcance}`);
            const data = await res.json();

            if (data.status === "success") {
                tbody.innerHTML = "";
                document.getElementById("contadorLogistica").textContent = `TOTAL: ${data.casos.length}`;

                if (data.casos.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" style="text-align:center; color:#506690;">[NO_LOGISTIC_NODES_FOUND]</td></tr>`;
                    return;
                }

                data.casos.forEach(c => {
                    const tr = document.createElement("tr");
                    const serviceAsignado = c.proveedor_nombre ? c.proveedor_nombre.toUpperCase() : '<span style="color:#506690;">[INTERNO / SIN ASIGNAR]</span>';

                    tr.innerHTML = `
                        <td class="text-neon-cyan" style="font-weight:bold;">${c.numero_caso}</td>
                        <td style="text-transform:uppercase;">${c.equipo} <span style="color:#506690;">${c.marca}</span><br><small style="color:#ffca28;">S/N: ${c.numero_serie}</small></td>
                        <td>${serviceAsignado}</td>
                        <td style="text-align:center;"><button class="btn-cyber-action btn-select-node" data-id="${c.id}">[OPEN_DESPATCH]</button></td>
                    `;

                    tr.querySelector(".btn-select-node").onclick = (e) => {
                        e.stopPropagation();
                        cargarCasoEnTerminalExterno(c.id);
                    };
                    tr.onclick = () => cargarCasoEnTerminalExterno(c.id);

                    tbody.appendChild(tr);
                });
            }
        } catch (err) {
            console.error("Fallo de stream en listado logístico:", err);
        }
    }

    async function cargarCasoEnTerminalExterno(id) {
        try {
            const res = await fetch(`${API_URL}?action=obtener_caso&id=${id}`);
            const data = await res.json();

            if (data.status === "success") {
                const c = data.caso;

                form.classList.add("cyber-active-form");
                document.getElementById("btnGuardarLogistica").disabled = false;

                document.getElementById("logIdCaso").value = c.id;
                document.getElementById("logNumeroCaso").value = c.numero_caso;
                document.getElementById("logHardware").value = `${c.equipo} ${c.marca}`.toUpperCase();
                document.getElementById("logSerie").value = c.numero_serie;
                document.getElementById("logFallaReportada").value = c.descripcion_problema;

                document.getElementById("selectProveedorLog").value = c.id_proveedor ?? "";
                document.getElementById("logReferencia").value = c.referencia_proveedor ?? "";
                document.getElementById("selectEstadoLog").value = c.id_estado_actual ?? "";

                if (c.fecha_envio_proveedor) {
                    document.getElementById("logFechaEnvio").value = c.fecha_envio_proveedor.split(' ')[0];
                } else {
                    document.getElementById("logFechaEnvio").value = new Date().toISOString().split('T')[0];
                }
            }
        } catch (err) {
            Swal.fire("ERROR LOGÍSTICO", "Fallo al mapear datos del nodo externo.", "error");
        }
    }

    form?.addEventListener("submit", async (e) => {
        e.preventDefault();

        const datos = new FormData(form);

        try {
            Swal.fire({
                title: "[SYNCHRONIZING_EXTERNAL_ROUTING...]",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const res = await fetch(`${API_URL}?action=guardar_flujo_externo`, {
                method: "POST",
                body: datos
            });
            const r = await res.json();

            if (r.status === "success") {
                Swal.fire({
                    icon: "success",
                    title: "[DISPATCH_COMMITTED]",
                    text: r.message
                });

                form.reset();
                form.classList.remove("cyber-active-form");
                document.getElementById("btnGuardarLogistica").disabled = true;
                if (inputFecha) inputFecha.value = new Date().toISOString().split('T')[0];

                await listarMatrizLogistica();
            } else {
                Swal.fire("FALLO LOGÍSTICO", r.message, "error");
            }
        } catch (err) {
            Swal.fire("CRITICAL TRACKING ERROR", "Fallo la inyección asíncrona de logística.", "error");
        }
    });

    buscador?.addEventListener("input", () => listarMatrizLogistica());
    filtroAlcance?.addEventListener("change", () => listarMatrizLogistica());

    const triggerLogistica = setInterval(() => {
        if (document.getElementById("formFlujoExterno")) {
            clearInterval(triggerLogistica);
            inicializarModuloLogistica();
        }
    }, 100);

})();