(function () {
    console.log("⚡ [RMA_BENCH] Inicializando Estación de Trabajo del Técnico...");

    const API_TECNICO = "../api-rma/tecnico/perfil_tecnico.php";

    const loader = document.getElementById("cyberLoaderTecnico");
    const idTecnico = document.getElementById("id_tecnico") ? document.getElementById("id_tecnico").value : null;

    const tablaProcesoBody = document.querySelector("#tablaMaquinasProceso tbody");
    const tablaDespachosBody = document.querySelector("#tablaDespachosRecientes tbody");

    function mostrarLoader() {
        if (loader) {
            loader.style.display = "flex";
            loader.style.opacity = "1";
            loader.style.visibility = "visible";
        }
    }

    function ocultarLoader() {
        if (loader) {
            loader.style.opacity = "0";
            setTimeout(() => {
                loader.style.visibility = "hidden";
                loader.style.display = "none";
            }, 300);
        }
    }

    async function cargarEstacionTecnico() {
        mostrarLoader();
        try {
            const res = await fetch(`${API_TECNICO}?action=consultar_estacion&id_tecnico=${idTecnico || ''}`);
            if (!res.ok) throw new Error(`HTTP Error Status: ${res.status}`);

            const data = await res.json();

            if (data.status === "success") {
                renderizarKPIs(data.kpis);
                renderizarMaquinasProceso(data.casos_proceso);
                renderizarDespachosRecientes(data.casos_despachados);
                poblarSelectsModal(data.estados_caso, data.stock_insumos);
            } else {
                console.error("🔴 Error API Perfil Técnico:", data.message);
            }
        } catch (err) {
            console.error("🔴 Error de conexión en Estación de Trabajo:", err);
        } finally {
            ocultarLoader();
        }
    }

    function renderizarKPIs(k) {
        if (!k) return;
        if (document.getElementById("kpiAsignadas")) document.getElementById("kpiAsignadas").textContent = k.asignadas || 0;
        if (document.getElementById("kpiSubColaDetalle")) document.getElementById("kpiSubColaDetalle").textContent = `${k.en_diagnostico || 0} en diag. · ${k.en_reparacion || 0} en rep.`;

        if (document.getElementById("kpiReparadosHoy")) document.getElementById("kpiReparadosHoy").textContent = k.reparados_hoy || 0;

        const badgeMeta = document.getElementById("badgeMetaProd");
        if (badgeMeta) {
            if (k.reparados_hoy >= 5) {
                badgeMeta.textContent = "CUOTA ALCANZADA";
                badgeMeta.style.color = "var(--neon-green-dark)";
                badgeMeta.style.borderColor = "var(--neon-green-dark)";
            } else {
                badgeMeta.textContent = "EN PROGRESO";
                badgeMeta.style.color = "var(--neon-yellow-dark)";
                badgeMeta.style.borderColor = "var(--neon-yellow-dark)";
            }
        }

        if (document.getElementById("kpiTasaExito")) document.getElementById("kpiTasaExito").textContent = `${k.tasa_exito || 100}%`;
        if (document.getElementById("kpiReingresosMeta")) document.getElementById("kpiReingresosMeta").textContent = `${k.reingresos || 0} reingresos este mes`;

        if (document.getElementById("kpiTrabadas")) document.getElementById("kpiTrabadas").textContent = k.ordenes_trabadas || 0;
    }

    function renderizarMaquinasProceso(casos) {
        if (!tablaProcesoBody) return;
        tablaProcesoBody.innerHTML = "";

        if (!casos || casos.length === 0) {
            tablaProcesoBody.innerHTML = `<tr><td colspan="5" class="font-mono text-center">[NO_ASSIGNED_CASES_IN_BENCH]</td></tr>`;
            return;
        }

        casos.forEach(c => {
            const tr = document.createElement("tr");

            let badgeClass = "status-1";
            if (c.estado_nombre.toLowerCase().includes("reparación") || c.estado_nombre.toLowerCase().includes("taller")) badgeClass = "status-default";
            if (c.estado_nombre.toLowerCase().includes("espera") || c.estado_nombre.toLowerCase().includes("proveedor")) badgeClass = "status-external";

            tr.innerHTML = `
                <td class="t-cyan font-mono">${c.numero_caso}</td>
                <td><strong>${c.equipo} ${c.marca} ${c.modelo}</strong><br><small class="text-muted-cyan">${c.cliente_nombre}</small></td>
                <td><span class="badge-status-cyber ${badgeClass}">${c.estado_nombre}</span></td>
                <td class="text-truncate-tec" title="${c.descripcion_problema}">${c.descripcion_problema}</td>
                <td class="text-right">
                    <button type="button" class="btn-terminal-edit" onclick="abrirGestionTrabajo('${c.id}', '${c.numero_caso}', '${c.id_estado_actual}', '${escapeHtml(c.diagnostico_final || '')}')" title="Procesar Orden">⚙️</button>
                    <button type="button" class="btn-terminal-view" onclick="imprimirTicketEquipo('${c.numero_caso}')" title="Imprimir Ticket">🖨️</button>
                </td>
            `;

            tablaProcesoBody.appendChild(tr);
        });
    }

    function renderizarDespachosRecientes(casos) {
        if (!tablaDespachosBody) return;
        tablaDespachosBody.innerHTML = "";

        if (!casos || casos.length === 0) {
            tablaDespachosBody.innerHTML = `<tr><td colspan="4" class="font-mono text-center">[NO_RECENT_DISPATCHES]</td></tr>`;
            return;
        }

        casos.forEach(c => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td class="t-cyan font-mono">${c.numero_caso}</td>
                <td><strong>${c.equipo} ${c.marca} ${c.modelo}</strong><br><small class="green-accent">${c.diagnostico_final || 'Reparación / Cierre Confirmado'}</small></td>
                <td class="font-mono">${c.fecha_cierre}</td>
                <td class="text-right">
                    <button type="button" class="btn-terminal-view" onclick="reimprimirEtiquetaRma('${c.numero_caso}')" title="Reimprimir QR">📷</button>
                </td>
            `;

            tablaDespachosBody.appendChild(tr);
        });
    }

    function poblarSelectsModal(estados, stock) {
        const selectEst = document.getElementById("selectEstadoRma");
        const selectIns = document.getElementById("selectInsumoTaller");

        if (selectEst && estados) {
            selectEst.innerHTML = estados.map(e => `<option value="${e.id}">${e.nombre}</option>`).join('');
        }

        if (selectIns && stock) {
            selectIns.innerHTML = `<option value="">-- Sin insumos adicionales --</option>` +
                stock.map(s => `<option value="${s.id}">${s.nombre} (Stock: ${s.cantidad} u. | ₲ ${parseFloat(s.precio_venta).toLocaleString('es-PY')})</option>`).join('');
        }
    }

    window.abrirGestionTrabajo = function (idCaso, numCaso, idEstadoActual, diagFinal) {
        document.getElementById('modalIdRma').value = idCaso;
        document.getElementById('modalTituloRma').innerHTML = `<span class="cyan-accent">//</span> [ACTUALIZAR DIAGNÓSTICO / ORDEN: ${numCaso}]`;

        const selEst = document.getElementById('selectEstadoRma');
        if (selEst && idEstadoActual) selEst.value = idEstadoActual;

        const txtDiag = document.getElementById('txtDetalleTecnico');
        if (txtDiag) txtDiag.value = diagFinal || '';

        document.getElementById('modalGestionTrabajo').style.display = 'flex';
    };

    window.cerrarModalGestion = function () {
        document.getElementById('modalGestionTrabajo').style.display = 'none';
    };

    window.imprimirTicketEquipo = function (numCaso) {
        Swal.fire({
            icon: 'info',
            title: '[COLA DE IMPRESIÓN]',
            text: `Generando comprobante de taller para el caso: ${numCaso}`,
            confirmButtonColor: '#0284c7'
        });
    };

    window.reimprimirEtiquetaRma = function (numCaso) {
        Swal.fire({
            icon: 'info',
            title: '[IMPRESORA TÉRMICA]',
            text: `Reimprimiendo etiqueta adhesiva QR para chasis: ${numCaso}`,
            confirmButtonColor: '#7e22ce'
        });
    };

    // EVENTOS DE BOTONES GLOBAL Y GUARDADO
    document.getElementById("btnGuardarAvanceTecnico")?.addEventListener("click", async function () {
        const idCaso = document.getElementById('modalIdRma').value;
        const idEstado = document.getElementById('selectEstadoRma').value;
        const idInsumo = document.getElementById('selectInsumoTaller').value;
        const diagnostico = document.getElementById('txtDetalleTecnico').value.trim();

        if (!diagnostico) {
            Swal.fire({ icon: 'warning', title: 'Campo Requerido', text: 'Ingresa un detalle o reporte técnico del avance.' });
            return;
        }

        mostrarLoader();
        try {
            const formData = new FormData();
            formData.append("action", "guardar_avance");
            formData.append("id_caso", idCaso);
            formData.append("id_estado", idEstado);
            formData.append("id_insumo", idInsumo);
            formData.append("diagnostico", diagnostico);
            formData.append("id_tecnico", idTecnico || '');

            const res = await fetch(API_TECNICO, { method: "POST", body: formData });
            const data = await res.json();

            if (data.status === "success") {
                Swal.fire({ icon: 'success', title: 'Avance Consolidado', text: 'El caso fue actualizado en el laboratorio.', timer: 1500, showConfirmButton: false });
                cerrarModalGestion();
                cargarEstacionTecnico();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        } catch (e) {
            console.error(e);
            Swal.fire({ icon: 'error', title: 'Error de Red', text: 'No se pudo guardar el avance.' });
        } finally {
            ocultarLoader();
        }
    });

    function escapeHtml(str) {
        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    const pollDom = setInterval(() => {
        if (document.getElementById("tablaMaquinasProceso")) {
            clearInterval(pollDom);
            cargarEstacionTecnico();
        }
    }, 100);
})();