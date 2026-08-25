(function () {
    console.log("⚡ [STOCK_ASSIGN] Módulo de Asignaciones en Taller Inicializado...");

    const API_ASIGNACIONES = "../api-rma/inventario/stock-asignaciones.php";

    // Elementos DOM
    const tablaAsignacionesBody = document.querySelector("#tablaAsignaciones tbody");
    const buscarAsignacionInput = document.getElementById("buscarAsignacion");
    const btnNuevaAsignacion = document.getElementById("btnNuevaAsignacion");

    // Modal
    const modalOverlay = document.getElementById("modalAsignacionOverlay");
    const btnCerrarX = document.getElementById("btnCerrarAsignacionX");
    const btnCancelar = document.getElementById("btnCancelarAsignacion");
    const formAsignacion = document.getElementById("formAsignacion");

    // Selects del Formulario
    const asigCasoSelect = document.getElementById("asig_id_caso");
    const asigStockSelect = document.getElementById("asig_id_stock");
    const asigTecnicoSelect = document.getElementById("asig_id_tecnico");
    const asigCostoInput = document.getElementById("asig_costo_aplicado");

    let listaAsignacionesCache = [];
    let mapaStockCosto = {};

    function abrirModal() { if (modalOverlay) modalOverlay.style.display = "flex"; }
    function cerrarModal() { if (modalOverlay) modalOverlay.style.display = "none"; if (formAsignacion) formAsignacion.reset(); }

    if (btnCerrarX) btnCerrarX.addEventListener("click", cerrarModal);
    if (btnCancelar) btnCancelar.addEventListener("click", cerrarModal);

    // ==========================================
    // 📚 1. CARGAR SELECTS AUXILIARES
    // ==========================================
    async function cargarAuxiliares() {
        try {
            const res = await fetch(`${API_ASIGNACIONES}?action=auxiliares`);
            const data = await res.json();

            if (data.status === "success") {
                // Casos Activos
                if (asigCasoSelect) {
                    asigCasoSelect.innerHTML = '<option value="">[SELECCIONE CASO RMA]</option>' +
                        data.casos.map(c => `<option value="${c.id}">${c.numero_caso} - ${c.equipo.toUpperCase()} (${c.cliente_nombre.toUpperCase()})</option>`).join('');
                }

                // Insumos con Stock Disponibles
                mapaStockCosto = {};
                if (asigStockSelect) {
                    asigStockSelect.innerHTML = '<option value="">[SELECCIONE INSUMO DEL STOCK]</option>' +
                        data.stock.map(s => {
                            mapaStockCosto[s.id] = s.costo_unitario;
                            return `<option value="${s.id}" data-costo="${s.costo_unitario}">${s.nombre.toUpperCase()} [DISP: ${s.cantidad} u.] - ₲ ${parseFloat(s.costo_unitario).toLocaleString('es-PY')}</option>`;
                        }).join('');
                }

                // Técnicos
                if (asigTecnicoSelect) {
                    asigTecnicoSelect.innerHTML = '<option value="">[SELECCIONE TÉCNICO]</option>' +
                        data.tecnicos.map(t => `<option value="${t.id}">${t.nombre.toUpperCase()}</option>`).join('');
                }
            }
        } catch (err) {
            console.error("🔴 Error cargando auxiliares de asignaciones:", err);
        }
    }

    // Auto-completar costo aplicado al seleccionar un insumo
    if (asigStockSelect) {
        asigStockSelect.addEventListener("change", function () {
            const idStock = this.value;
            if (idStock && mapaStockCosto[idStock] !== undefined) {
                if (asigCostoInput) asigCostoInput.value = mapaStockCosto[idStock];
            }
        });
    }

    // ==========================================
    // 📡 2. CONSULTAR Y RENDERIZAR TABLA
    // ==========================================
    async function cargarAsignaciones() {
        if (!tablaAsignacionesBody) return;

        try {
            const res = await fetch(`${API_ASIGNACIONES}?action=listar`);
            const data = await res.json();

            if (data.status === "success") {
                listaAsignacionesCache = data.asignaciones || [];
                renderizarTabla(listaAsignacionesCache);
            } else {
                tablaAsignacionesBody.innerHTML = `<tr><td colspan="10" class="font-mono text-center">[NO_WORKSHOP_CONSUMPTIONS_FOUND]</td></tr>`;
            }
        } catch (err) {
            console.error("🔴 Error al listar consumos de insumos:", err);
            tablaAsignacionesBody.innerHTML = `<tr><td colspan="10" class="font-mono text-center text-danger">[ERROR_DE_CONEXION_SERVIDOR]</td></tr>`;
        }
    }

    function renderizarTabla(items) {
        if (!tablaAsignacionesBody) return;
        tablaAsignacionesBody.innerHTML = "";

        if (items.length === 0) {
            tablaAsignacionesBody.innerHTML = `<tr><td colspan="10" class="font-mono text-center">[NO_WORKSHOP_CONSUMPTIONS_FOUND]</td></tr>`;
            return;
        }

        items.forEach(asig => {
            const fila = document.createElement("tr");

            const costoUnitario = parseFloat(asig.costo_aplicado || 0);
            const cantidad = parseInt(asig.cantidad || 1);
            const costoTotal = costoUnitario * cantidad;

            fila.innerHTML = `
                <td class="t-orange font-mono">${asig.id}</td>
                <td class="font-mono font-weight-bold">${asig.numero_caso || 'N/A'}</td>
                <td class="font-weight-bold">${asig.insumo_nombre ? asig.insumo_nombre.toUpperCase() : ''}</td>
                <td class="font-mono">${asig.codigo_sku || 'S/N'}</td>
                <td class="font-mono font-weight-bold">${cantidad} u.</td>
                <td class="font-mono">₲ ${costoUnitario.toLocaleString('es-PY')}</td>
                <td class="font-mono text-neon-orange font-weight-bold">₲ ${costoTotal.toLocaleString('es-PY')}</td>
                <td class="font-mono">${asig.tecnico_nombre ? asig.tecnico_nombre.toUpperCase() : 'NO DEFINIDO'}</td>
                <td class="font-mono">${asig.fecha_aplicacion || ''}</td>
                <td class="font-mono text-left">${asig.observacion || '-'}</td>
            `;

            tablaAsignacionesBody.appendChild(fila);
        });
    }

    // ==========================================
    // 🔍 3. BUSCADOR
    // ==========================================
    if (buscarAsignacionInput) {
        buscarAsignacionInput.addEventListener("input", function () {
            const termino = this.value.toLowerCase().trim();
            const filtrados = listaAsignacionesCache.filter(a => {
                return (a.numero_caso && a.numero_caso.toLowerCase().includes(termino)) ||
                    (a.insumo_nombre && a.insumo_nombre.toLowerCase().includes(termino)) ||
                    (a.codigo_sku && a.codigo_sku.toLowerCase().includes(termino)) ||
                    (a.tecnico_nombre && a.tecnico_nombre.toLowerCase().includes(termino));
            });
            renderizarTabla(filtrados);
        });
    }

    // ==========================================
    // 📥 4. POST REGISTRAR ASIGNACIÓN
    // ==========================================
    if (btnNuevaAsignacion) {
        btnNuevaAsignacion.addEventListener("click", () => {
            cargarAuxiliares();
            abrirModal();
        });
    }

    if (formAsignacion) {
        formAsignacion.addEventListener("submit", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const datos = Object.fromEntries(new FormData(formAsignacion));

            try {
                Swal.fire({
                    title: "APLICANDO INSUMO A RMA...",
                    text: "Descontando inventario y registrando consumo...",
                    background: '#ffffff',
                    color: '#0f172a',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const res = await fetch(`${API_ASIGNACIONES}?action=guardar`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });

                const resultado = await res.json();

                if (resultado.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'INSUMO APLICADO EXITOSAMENTE',
                        text: resultado.message,
                        background: '#ffffff',
                        color: '#0f172a',
                        confirmButtonColor: '#f97316'
                    });
                    cerrarModal();
                    cargarAsignaciones();
                } else {
                    throw new Error(resultado.message);
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'ASSIGNMENT_FAILED',
                    text: err.message,
                    background: '#ffffff',
                    color: '#dc2626',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    // Inicialización al cargar el DOM
    const verificarDom = setInterval(() => {
        if (document.getElementById("tablaAsignaciones")) {
            clearInterval(verificarDom);
            cargarAsignaciones();
        }
    }, 100);
})();