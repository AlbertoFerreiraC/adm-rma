(function () {
    console.log("⚡ [RMA_CORE] Consola Logística Externa (Modo Modal Nativo) Inicializada...");

    const API_URL = "../api-rma/rma-core/casos/proveedoresRma.php";

    const form = document.getElementById("formFlujoExterno");
    const tbody = document.getElementById("tbodyLogistica");
    const buscador = document.getElementById("buscadorLogistica");
    const filtroAlcance = document.getElementById("filtroLogisticaAlcance");
    const inputFecha = document.getElementById("logFechaEnvio");

    // Elementos del Modal Nativo
    const modalOverlay = document.getElementById("modalLogisticaOverlay");
    const btnCerrarModalX = document.getElementById("btnCerrarModalX");
    const btnCancelarModal = document.getElementById("btnCancelarModal");
    const lblTituloModal = document.getElementById("lblTituloModal");

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
        resetFormModal();
    }

    if (btnCerrarModalX) btnCerrarModalX.addEventListener("click", cerrarModal);
    if (btnCancelarModal) btnCancelarModal.addEventListener("click", cerrarModal);

    function resetFormModal() {
        if (form) form.reset();
        const idCasoInput = document.getElementById("logIdCaso");
        if (idCasoInput) idCasoInput.value = "";

        if (inputFecha) {
            inputFecha.value = new Date().toISOString().split('T')[0];
        }
    }

    if (inputFecha) {
        inputFecha.value = new Date().toISOString().split('T')[0];
    }

    // ==========================================
    // 🚀 INICIALIZACIÓN
    // ==========================================
    async function inicializarModuloLogistica() {
        try {
            const resProv = await fetch(`${API_URL}?action=aux_proveedores`);
            const dataProv = await resProv.json();
            if (dataProv.status === "success") {
                const selectP = document.getElementById("selectProveedorLog");
                if (selectP) {
                    selectP.innerHTML = '<option value="">[SELECCIONE SERVICE AUTORIZADO]</option>';
                    dataProv.proveedores.forEach(p => {
                        const opt = document.createElement("option");
                        opt.value = p.id;
                        opt.textContent = `// SERVICE: ${p.nombre.toUpperCase()} (CON: ${p.contacto || 'N/A'})`;
                        selectP.appendChild(opt);
                    });
                }
            }

            const resEst = await fetch(`${API_URL}?action=aux_estados`);
            const dataEst = await resEst.json();
            if (dataEst.status === "success") {
                const selectE = document.getElementById("selectEstadoLog");
                if (selectE) {
                    selectE.innerHTML = '<option value="">[SELECCIONE TRANSICIÓN DE ESTADO]</option>';
                    dataEst.estados.forEach(e => {
                        const opt = document.createElement("option");
                        opt.value = e.id;
                        opt.textContent = `// TRANSLATION: ${e.nombre.toUpperCase()}`;
                        selectE.appendChild(opt);
                    });
                }
            }

            await listarMatrizLogistica();

        } catch (err) {
            console.error("Fallo crítico cargando catálogos de logística:", err);
        }
    }

    // ==========================================
    // 📡 LISTAR MATRIZ LOGÍSTICA
    // ==========================================
    async function listarMatrizLogistica() {
        if (!tbody) return;

        try {
            const buscar = buscador ? buscador.value.trim() : "";
            const alcance = filtroAlcance ? filtroAlcance.value : "todos";

            const res = await fetch(`${API_URL}?action=listar_logistica&buscar=${encodeURIComponent(buscar)}&alcance=${alcance}`);
            const data = await res.json();

            if (data.status === "success") {
                tbody.innerHTML = "";
                const contador = document.getElementById("contadorLogistica");
                if (contador) contador.textContent = `TOTAL: ${data.casos.length}`;

                if (!data.casos || data.casos.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center font-mono">[NO_LOGISTIC_NODES_FOUND]</td></tr>`;
                    return;
                }

                data.casos.forEach(c => {
                    const tr = document.createElement("tr");

                    const numeroCaso = c.numero_caso || 'N/A';
                    const equipo = c.equipo ? c.equipo.toUpperCase() : 'DISPOSITIVO';
                    const marca = c.marca ? c.marca.toUpperCase() : 'S/M';
                    const numeroSerie = c.numero_serie ? c.numero_serie.toUpperCase() : 'S/N';
                    const serviceAsignado = c.proveedor_nombre
                        ? c.proveedor_nombre.toUpperCase()
                        : '<span style="color:#64748b;">[INTERNO / SIN ASIGNAR]</span>';
                    const estadoNombre = c.estado_nombre ? c.estado_nombre.toUpperCase() : 'EN TALLER';

                    const badgeStyle = c.id_estado_actual == 3
                        ? "color:#d97706; border-color:#d97706; background:rgba(217,119,6,0.08);"
                        : "color:#0284c7; border-color:#0284c7; background:rgba(2,132,199,0.08);";

                    tr.innerHTML = `
                        <td class="t-cyan font-mono">${numeroCaso}</td>
                        <td class="font-mono">${equipo} <span style="color:#64748b;">(${marca})</span></td>
                        <td class="font-mono">${numeroSerie}</td>
                        <td class="font-mono">${serviceAsignado}</td>
                        <td><span class="system-badge-live" style="${badgeStyle}">${estadoNombre}</span></td>
                        <td>
                            <button type="button" class="btn-terminal-edit btn-select-node" data-id="${c.id}" title="Despacho Logístico">
                                <i class="fa fa-truck"></i> [DESPACHO]
                            </button>
                        </td>
                    `;

                    const btnSelect = tr.querySelector(".btn-select-node");
                    if (btnSelect) {
                        btnSelect.onclick = (e) => {
                            e.stopPropagation();
                            cargarCasoEnTerminalExterno(c.id);
                        };
                    }

                    tr.onclick = () => cargarCasoEnTerminalExterno(c.id);

                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center font-mono">[ERROR_CARGANDO_LOGISTICA]</td></tr>`;
            }
        } catch (err) {
            console.error("Fallo de stream en listado logístico:", err);
            tbody.innerHTML = `<tr><td colspan="6" class="text-center font-mono text-danger">[ERROR_DE_CONEXION]</td></tr>`;
        }
    }

    // ==========================================
    // 📥 CARGAR DATOS EN EL MODAL NATIVO
    // ==========================================
    async function cargarCasoEnTerminalExterno(id) {
        try {
            Swal.fire({
                title: 'CONSULTANDO REGISTRO...',
                text: 'Mapeando datos logísticos del caso ID: ' + id,
                background: '#ffffff',
                color: '#0f172a',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            const res = await fetch(`${API_URL}?action=obtener_caso&id=${id}`);
            const data = await res.json();

            Swal.close();

            if (data.status === "success") {
                const c = data.caso;

                document.getElementById("logIdCaso").value = c.id;
                document.getElementById("logNumeroCaso").value = c.numero_caso || '';
                document.getElementById("logHardware").value = `${c.equipo || ''} ${c.marca || ''} ${c.modelo || ''}`.toUpperCase().trim();
                document.getElementById("logSerie").value = c.numero_serie || '';
                document.getElementById("logFallaReportada").value = c.descripcion_problema || '';

                document.getElementById("selectProveedorLog").value = c.id_proveedor ?? "";
                document.getElementById("logReferencia").value = c.referencia_proveedor ?? "";
                document.getElementById("selectEstadoLog").value = c.id_estado_actual ?? "";

                if (c.fecha_envio_proveedor) {
                    document.getElementById("logFechaEnvio").value = c.fecha_envio_proveedor.split(' ')[0];
                } else if (inputFecha) {
                    inputFecha.value = new Date().toISOString().split('T')[0];
                }

                if (lblTituloModal) {
                    lblTituloModal.textContent = `[DESPACHO_LOGÍSTICO: ${c.numero_caso}]`;
                }

                abrirModal();
            } else {
                Swal.fire("ERROR", data.message, "error");
            }
        } catch (err) {
            Swal.close();
            Swal.fire({
                icon: "error",
                title: "ERROR LOGÍSTICO",
                text: "Fallo al mapear datos del nodo externo.",
                background: '#ffffff',
                color: '#dc2626'
            });
        }
    }

    // ==========================================
    // 💾 CONFIRMAR DESPACHO DESDE EL MODAL
    // ==========================================
    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const datos = new FormData(form);

            try {
                Swal.fire({
                    title: "SINCRONIZANDO GARANTÍA...",
                    text: "Guardando datos de despacho logístico...",
                    background: '#ffffff',
                    color: '#0f172a',
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
                        title: "DESPACHO REGISTRADO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#0284c7'
                    });

                    cerrarModal();
                    await listarMatrizLogistica();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "FALLO LOGÍSTICO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#dc2626'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: "error",
                    title: "CRITICAL TRACKING ERROR",
                    text: "Fallo la inyección asíncrona de logística.",
                    background: '#ffffff',
                    color: '#dc2626'
                });
            }
        });
    }

    // Listeners de Filtros
    if (buscador) buscador.addEventListener("input", () => listarMatrizLogistica());
    if (filtroAlcance) filtroAlcance.addEventListener("change", () => listarMatrizLogistica());

    // Inicialización automática
    const triggerLogistica = setInterval(() => {
        if (document.getElementById("tablaLogistica")) {
            clearInterval(triggerLogistica);
            inicializarModuloLogistica();
        }
    }, 100);

})();