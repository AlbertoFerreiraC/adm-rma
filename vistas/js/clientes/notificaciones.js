(function () {
    console.log("⚡ [COMMS_NODE] Inicializando módulo de Notificaciones WhatsApp (Enlace Directo wa.me)...");

    const API_NOTIF = "../api-rma/notificaciones/notificaciones.php";

    const inputFiltro = document.getElementById("inputFiltroNotif");
    const selectCaso = document.getElementById("selectCasoNotif");
    const contenedorMensaje = document.getElementById("contenedorMensajeWA");
    const contenedorVacio = document.getElementById("contenedorVacioWA");

    const lblCliente = document.getElementById("lblClienteWA");
    const lblCelular = document.getElementById("lblCelularWA");
    const selectEstado = document.getElementById("selectEstadoWA");
    const txtAreaMensaje = document.getElementById("txtAreaMensajeWA");
    const btnLanzarWA = document.getElementById("btnLanzarWA");
    const badgeCasoActual = document.getElementById("badgeCasoActual");

    let casosCache = [];
    let casoSeleccionado = null;

    // ==========================================
    // 📡 1. CARGAR CASOS Y ESTADOS
    // ==========================================
    async function cargarCasosYEstados() {
        try {
            const respuesta = await fetch(`${API_NOTIF}?action=listar_casos`);
            const data = await respuesta.json();

            if (data.status === "success") {
                casosCache = data.casos || [];

                selectEstado.innerHTML = "";
                data.estados.forEach(est => {
                    const opt = document.createElement("option");
                    opt.value = est.nombre;
                    opt.textContent = est.nombre;
                    selectEstado.appendChild(opt);
                });

                renderizarListaCasos(casosCache);
            }
        } catch (error) {
            console.error("🔴 Error cargando datos de notificaciones:", error);
        }
    }

    function renderizarListaCasos(casos) {
        selectCaso.innerHTML = "";
        if (casos.length === 0) {
            selectCaso.innerHTML = `<option disabled>No hay casos registrados</option>`;
            return;
        }

        casos.forEach(c => {
            const opt = document.createElement("option");
            opt.value = c.id;
            opt.textContent = `[${c.numero_caso}] - ${c.cliente} (${c.equipo})`;
            selectCaso.appendChild(opt);
        });
    }

    // ==========================================
    // 🔍 2. FILTRAR LISTA EN TIEMPO REAL
    // ==========================================
    if (inputFiltro) {
        inputFiltro.addEventListener("input", function () {
            const term = this.value.toLowerCase().trim();
            const filtrados = casosCache.filter(c =>
                c.numero_caso.toLowerCase().includes(term) ||
                c.cliente.toLowerCase().includes(term) ||
                (c.cedula && c.cedula.toLowerCase().includes(term))
            );
            renderizarListaCasos(filtrados);
        });
    }

    // ==========================================
    // 📱 3. CONSTRUCTOR DE ENLACE DIRECTO WA.ME
    // ==========================================
    function actualizarMensajeWA() {
        if (!casoSeleccionado) return;

        const estadoSeleccionado = selectEstado.value;
        const cliente = casoSeleccionado.cliente;
        const numCaso = casoSeleccionado.numero_caso;
        const equipo = `${casoSeleccionado.equipo}`;

        // RUTA DE CONSULTA PÚBLICA
        const protocolo = window.location.protocol;
        const host = window.location.host;
        const urlSeguimiento = `${protocolo}//${host}/rma-app/adm-rma/consulta?busqueda=${encodeURIComponent(numCaso)}`;

        // Formatear texto limpio (removiendo guiones bajos del estado si existen)
        const estadoFormateado = estadoSeleccionado.toUpperCase().replace(/_/g, ' ');
        const estClean = estadoSeleccionado.toLowerCase().replace(/_/g, ' ');

        let cuerpoMensaje = "";
        if (estClean.includes('ingresado')) {
            cuerpoMensaje = `Tu equipo (${equipo}) fue RECIBIDO correctamente en nuestro laboratorio técnico. En breve nuestro equipo iniciará la revisión.`;
        } else if (estClean.includes('diagnostico')) {
            cuerpoMensaje = `Te informamos que tu equipo (${equipo}) se encuentra actualmente EN DIAGNÓSTICO por parte de nuestros técnicos.`;
        } else if (estClean.includes('reparacion')) {
            cuerpoMensaje = `Tu equipo (${equipo}) ha sido evaluado y se encuentra EN PROCESO DE REPARACIÓN / SERVICIO.`;
        } else if (estClean.includes('listo')) {
            cuerpoMensaje = `¡Buenas noticias! Tu equipo (${equipo}) ya se encuentra LISTO PARA ENTREGA. Podés pasar a retirarlo en nuestro local en horario de atención.`;
        } else if (estClean.includes('entregado')) {
            cuerpoMensaje = `Confirmamos que tu equipo (${equipo}) ha sido ENTREGADO con éxito. ¡Gracias por confiar en Microexpress!`;
        } else {
            cuerpoMensaje = `Te informamos que tu equipo (${equipo}) se encuentra en estado: *${estadoFormateado}*.`;
        }

        // CONSTRUCCIÓN DEL TEXTO BASE (Formato limpio sin caracteres problemáticos)
        const textoPrevisualizacion =
            `Hola *${cliente}*!

Novedades sobre tu caso de soporte técnico *${numCaso}*:

*Estado Actual:* ${estadoFormateado}
*Equipo:* ${equipo}

${cuerpoMensaje}

*Consulta el estado y descarga tu comprobante aquí:*
${urlSeguimiento}

_Microexpress S.R.L. - Departamento Técnico_`;

        txtAreaMensaje.value = textoPrevisualizacion;

        // FORMATEO DE CELULAR PARAGUAY: 595 + número sin el 0 inicial
        let celLimpio = (casoSeleccionado.celular || "").toString().replace(/[^0-9]/g, '');
        if (celLimpio.startsWith("0")) {
            celLimpio = celLimpio.substring(1);
        }
        const celParaguay = "595" + celLimpio;

        // ENLACE NATIVO DIRECTO
        const linkWa = `https://wa.me/${celParaguay}?text=${encodeURIComponent(textoPrevisualizacion)}`;
        btnLanzarWA.href = linkWa;
    }

    // Eventos
    selectCaso.addEventListener("change", function () {
        const idCaso = this.value;
        casoSeleccionado = casosCache.find(c => c.id == idCaso);

        if (casoSeleccionado) {
            contenedorVacio.style.display = "none";
            contenedorMensaje.style.display = "block";
            badgeCasoActual.style.display = "inline-block";
            badgeCasoActual.textContent = `CASO: ${casoSeleccionado.numero_caso}`;

            lblCliente.textContent = casoSeleccionado.cliente.toUpperCase();
            lblCelular.textContent = casoSeleccionado.celular || 'SIN TELÉFONO';

            if (casoSeleccionado.estado_actual) {
                selectEstado.value = casoSeleccionado.estado_actual;
            }

            actualizarMensajeWA();
        }
    });

    selectEstado.addEventListener("change", actualizarMensajeWA);

    const verificarDom = setInterval(() => {
        if (document.getElementById("selectCasoNotif")) {
            clearInterval(verificarDom);
            cargarCasosYEstados();
        }
    }, 100);
})();