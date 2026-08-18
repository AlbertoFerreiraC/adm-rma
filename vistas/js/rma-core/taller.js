(function () {
    console.log("⚡ [RMA_CORE] Sistema de Diagnóstico de Laboratorio Activo...");

    const API_URL = "../api-rma/rma-core/casos/taller.php";

    const form = document.getElementById("formDiagnostico");
    const tbody = document.getElementById("tbodyTallerCola");
    const buscadorCola = document.getElementById("buscadorTallerCola");
    const filtroEstado = document.getElementById("filtroEstadoTaller");
    const ordenCola = document.getElementById("ordenTallerCola");

    const modalOverlay = document.getElementById("modalTallerOverlay");
    const btnCerrarModalX = document.getElementById("btnCerrarModalX");
    const btnCancelarModal = document.getElementById("btnCancelarModal");
    const lblTituloModal = document.getElementById("lblTituloModal");

    function abrirModal() {
        if (modalOverlay) modalOverlay.style.display = "flex";
    }

    function cerrarModal() {
        if (modalOverlay) modalOverlay.style.display = "none";
        resetFormModal();
    }

    if (btnCerrarModalX) btnCerrarModalX.addEventListener("click", cerrarModal);
    if (btnCancelarModal) btnCancelarModal.addEventListener("click", cerrarModal);

    function resetFormModal() {
        if (form) form.reset();
        const idCasoInput = document.getElementById("diagIdCaso");
        if (idCasoInput) idCasoInput.value = "";
        const display = document.getElementById("fileNameDiagDisplay");
        if (display) display.textContent = "";
    }

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
                        opt.textContent = `// ESTADO: ${e.nombre.toUpperCase()}`;
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
        if (!tbody) return;

        try {
            const buscar = buscadorCola ? buscadorCola.value.trim() : "";
            const alcance = filtroEstado ? filtroEstado.value : "activos";
            const orden = ordenCola ? ordenCola.value : "asc";
            const queryUrl = `${API_URL}?action=listar_cola&buscar=${encodeURIComponent(buscar)}&alcance=${alcance}&orden=${orden}`;

            const resCola = await fetch(queryUrl);
            const dataCola = await resCola.json();

            if (dataCola.status === "success") {
                tbody.innerHTML = "";

                const contador = document.getElementById("contadorCola");
                if (contador) contador.textContent = `WAIT: ${dataCola.cola.length}`;

                if (!dataCola.cola || dataCola.cola.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center font-mono">[NO_PENDING_HARDWARE_FOUND]</td></tr>`;
                    return;
                }

                dataCola.cola.forEach(c => {
                    const tr = document.createElement("tr");

                    // Manejo seguro de valores nulos o indefinidos
                    const numeroCaso = c.numero_caso || 'N/A';
                    const fechaIngreso = c.fecha_ingreso || 'N/A';
                    const clienteNombre = c.cliente_nombre ? c.cliente_nombre.toUpperCase() : 'CLIENTE S/N';
                    const equipo = c.equipo ? c.equipo.toUpperCase() : 'COMPONENTE';
                    const marca = c.marca ? c.marca.toUpperCase() : 'S/M';
                    const numeroSerie = c.numero_serie ? c.numero_serie.toUpperCase() : 'S/N';
                    const estadoNombre = c.estado_nombre ? c.estado_nombre.toUpperCase() : 'EN DIAGNÓSTICO';

                    const badgeStyle = c.id_estado_actual == 1
                        ? "color:#0284c7; border-color:#0284c7; background:rgba(2,132,199,0.08);"
                        : "color:#d97706; border-color:#d97706; background:rgba(217,119,6,0.08);";

                    tr.innerHTML = `
                        <td class="t-cyan font-mono">${numeroCaso}</td>
                        <td class="font-mono">${fechaIngreso}</td>
                        <td class="font-weight-bold">${clienteNombre}</td>
                        <td class="font-mono">${equipo} <span style="color:#64748b;">(${marca})</span></td>
                        <td class="font-mono">${numeroSerie}</td>
                        <td><span class="system-badge-live" style="${badgeStyle}">${estadoNombre}</span></td>
                        <td>
                            <button type="button" class="btn-terminal-edit btn-select-node" data-id="${c.id}" title="Evaluar Hardware">
                                <i class="fa fa-wrench"></i> [EVALUAR]
                            </button>
                        </td>
                    `;

                    const btnSelect = tr.querySelector(".btn-select-node");
                    if (btnSelect) {
                        btnSelect.onclick = (e) => {
                            e.stopPropagation();
                            cargarCasoEnConsola(c.id);
                        };
                    }

                    tr.onclick = () => cargarCasoEnConsola(c.id);

                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center font-mono">[ERROR_CARGANDO_DATOS]</td></tr>`;
            }
        } catch (err) {
            console.error("Error mapeando la cola:", err);
            tbody.innerHTML = `<tr><td colspan="7" class="text-center font-mono text-danger">[ERROR_DE_CONEXION]</td></tr>`;
        }
    }

    async function cargarCasoEnConsola(id) {
        try {
            Swal.fire({
                title: 'CARGANDO TELEMETRÍA...',
                text: 'Obteniendo datos del caso ID: ' + id,
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

                document.getElementById("diagIdCaso").value = c.id;
                document.getElementById("diagNumeroCaso").value = c.numero_caso || '';
                document.getElementById("diagHardware").value = `${c.equipo || ''} ${c.marca || ''} ${c.modelo || ''}`.toUpperCase().trim();
                document.getElementById("diagSerie").value = c.numero_serie || '';
                document.getElementById("diagProblemaOriginal").value = c.descripcion_problema || '';

                document.getElementById("diagDiagnosticoFinal").value = c.diagnostico_final ?? "";
                document.getElementById("selectEstadoDiag").value = c.id_estado_actual || '';

                const display = document.getElementById("fileNameDiagDisplay");
                if (display) display.textContent = c.foto_archivo ? `[CURRENT_IMG]: ${c.foto_archivo}` : '';

                if (lblTituloModal) {
                    lblTituloModal.textContent = `[EVALUACIÓN_DE_HARDWARE: ${c.numero_caso}]`;
                }

                abrirModal();
            } else {
                Swal.fire("ERROR", data.message, "error");
            }
        } catch (err) {
            Swal.close();
            Swal.fire({
                icon: "error",
                title: "ERROR DE TRANSMISIÓN",
                text: "No se pudo recuperar la telemetría del nodo.",
                background: '#ffffff',
                color: '#dc2626'
            });
        }
    }

    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const datos = new FormData(form);

            try {
                Swal.fire({
                    title: "PERSISTIENDO REGISTRO...",
                    text: "Actualizando estado y guardando diagnóstico...",
                    background: '#ffffff',
                    color: '#0f172a',
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
                        text: r.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#0284c7'
                    });

                    cerrarModal();
                    await listarColaDispositivos();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "FALLO EN REGISTRO",
                        text: r.message,
                        background: '#ffffff',
                        color: '#dc2626'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: "error",
                    title: "CRITICAL CORRUPT ERROR",
                    text: "La inyección de diagnóstico falló al comunicar con la API.",
                    background: '#ffffff',
                    color: '#dc2626'
                });
            }
        });
    }

    if (buscadorCola) buscadorCola.addEventListener("input", listarColaDispositivos);
    if (filtroEstado) filtroEstado.addEventListener("change", listarColaDispositivos);
    if (ordenCola) ordenCola.addEventListener("change", listarColaDispositivos);

    const verifTaller = setInterval(() => {
        if (document.getElementById("tablaTaller")) {
            clearInterval(verifTaller);
            inicializarTaller();
        }
    }, 100);

})();