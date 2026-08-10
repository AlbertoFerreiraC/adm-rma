(function () {
    console.log("⚡ [PUBLIC_HUB] Inicializando portal de rastreo público...");

    const API_PUBLIC = "/rma-app/api-rma/publico/consulta.php";

    const formConsulta = document.getElementById("formConsultaPublica");
    const inputTracking = document.getElementById("inputTracking");
    const panelResultado = document.getElementById("panelResultadoCaso");

    // Elementos de la ficha
    const txtNumeroCaso = document.getElementById("txtNumeroCaso");
    const badgeEstadoActual = document.getElementById("badgeEstadoActual");
    const txtClienteNombre = document.getElementById("txtClienteNombre");
    const txtEquipo = document.getElementById("txtEquipo");
    const txtMarcaModelo = document.getElementById("txtMarcaModelo");
    const txtNumeroSerie = document.getElementById("txtNumeroSerie");
    const txtTipoCaso = document.getElementById("txtTipoCaso");
    const txtFechaIngreso = document.getElementById("txtFechaIngreso");
    const txtFechaCierre = document.getElementById("txtFechaCierre");
    const txtDescripcionProblema = document.getElementById("txtDescripcionProblema");
    const txtDiagnosticoFinal = document.getElementById("txtDiagnosticoFinal");
    const timelineContenedor = document.getElementById("timelineContenedor");
    const btnImprimir = document.getElementById("btnImprimirComprobante");

    let tokenImpresionActual = null;

    // ==========================================
    // 🔍 1. EJECUTAR CONSULTA PÚBLICA
    // ==========================================
    async function realizarConsulta(busqueda) {
        if (!busqueda || busqueda.trim() === "") return;

        try {
            Swal.fire({
                title: 'CONSULTANDO BASE DE DATOS...',
                text: 'Espere mientras localizamos el activo',
                background: '#060b19',
                color: '#00f2ff',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            const respuesta = await fetch(`${API_PUBLIC}?busqueda=${encodeURIComponent(busqueda.trim())}`);
            const data = await respuesta.json();

            Swal.close();

            if (data.status === "success" && data.caso) {
                const c = data.caso;
                tokenImpresionActual = c.token_impresion;

                // Llenar Ficha
                txtNumeroCaso.textContent = c.numero_caso;
                badgeEstadoActual.textContent = c.estado_actual.toUpperCase();
                txtClienteNombre.textContent = c.cliente_nombre;
                txtEquipo.textContent = c.equipo;
                txtMarcaModelo.textContent = `${c.marca} ${c.modelo}`;
                txtNumeroSerie.textContent = c.numero_serie || 'NO ESPECIFICADO';
                txtTipoCaso.textContent = c.tipo_caso;
                txtFechaIngreso.textContent = c.fecha_ingreso;
                txtFechaCierre.textContent = c.fecha_cierre ? c.fecha_cierre : 'EN PROCESO TÉCNICO';
                txtDescripcionProblema.textContent = c.descripcion_problema || 'Sin descripción guardada';
                txtDiagnosticoFinal.textContent = c.diagnostico_final || 'Pendiente de evaluación en laboratorio';

                // Llenar Timeline
                renderizarTimeline(data.historial || []);

                // Mostrar Panel
                panelResultado.classList.remove("hidden");
                panelResultado.scrollIntoView({ behavior: 'smooth' });

            } else {
                panelResultado.classList.add("hidden");
                Swal.fire({
                    icon: 'error',
                    title: 'REGISTRO NO LOCALIZADO',
                    text: data.message || 'No se encontró ningún caso que coincida con el término ingresado.',
                    background: '#060b19',
                    color: '#ff3333',
                    confirmButtonColor: '#ff3333'
                });
            }
        } catch (error) {
            Swal.close();
            console.error("🔴 Error en consulta pública:", error);
            Swal.fire({
                icon: 'error',
                title: 'SYSTEM_OFFLINE',
                text: 'No se pudo comunicar con el servidor central de rastreo.',
                background: '#060b19',
                color: '#ff3333',
                confirmButtonColor: '#ff3333'
            });
        }
    }

    function renderizarTimeline(historial) {
        timelineContenedor.innerHTML = "";

        if (historial.length === 0) {
            timelineContenedor.innerHTML = `<div class="time-obs">Sin eventos registrados aún.</div>`;
            return;
        }

        historial.forEach((item, index) => {
            const esUltimo = index === 0;
            const elem = document.createElement("div");
            elem.className = `timeline-item ${esUltimo ? 'active' : ''}`;
            elem.innerHTML = `
                <div class="time-date">${item.fecha}</div>
                <div class="time-status">${item.estado}</div>
                <div class="time-obs">${item.observacion ? item.observacion : 'Cambio de estado registrado por el operador.'}</div>
            `;
            timelineContenedor.appendChild(elem);
        });
    }

    // Evento manual de formulario de búsqueda
    if (formConsulta) {
        formConsulta.addEventListener("submit", function (e) {
            e.preventDefault();
            realizarConsulta(inputTracking.value);
        });
    }

    // 🖨️ Abrir Comprobante de Impresión Oficial con Token Seguro
    if (btnImprimir) {
        btnImprimir.addEventListener("click", function () {
            if (tokenImpresionActual) {
                const urlVentana = `comprobante.php?token=${encodeURIComponent(tokenImpresionActual)}`;
                window.open(urlVentana, '_blank', 'width=900,height=800,scrollbars=yes');
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'TOKEN_MISSING',
                    text: 'No se generó un token seguro para este comprobante.',
                    background: '#060b19',
                    color: '#ffca28'
                });
            }
        });
    }

    // ==========================================
    // ⚡ 2. AUTO-EJECUCIÓN SI VIENE PARÁMETRO POR URL (?busqueda=...)
    // ==========================================
    function autodetectUrlParam() {
        const urlParams = new URLSearchParams(window.location.search);
        const term = urlParams.get('busqueda');

        if (term && term.trim() !== "") {
            inputTracking.value = term.trim();
            realizarConsulta(term.trim());
        }
    }

    // Arranque con verificación de DOM y detección URL
    const verificarDom = setInterval(() => {
        if (document.getElementById("inputTracking")) {
            clearInterval(verificarDom);
            autodetectUrlParam();
        }
    }, 100);
})();