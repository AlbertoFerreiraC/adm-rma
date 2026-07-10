(function () {
    console.log("⚡ [RMA_CORE] Protocolo de Registro Inicial Activo...");

    const API_URL = "../api-rma/rma-core/casos/casos.php";

    let bloqueandoSubmit = false;
    const form = document.getElementById("formNuevoCaso");

    if (form) {
        form.onsubmit = (e) => e.preventDefault();
    }

    async function inicializarComponentes() {
        try {
            const resClientes = await fetch(`${API_URL}?action=aux_clientes`);
            const dataClientes = await resClientes.json();
            if (dataClientes.status === "success") {
                const selectClie = document.getElementById("selectCliente");

                selectClie.innerHTML = '<option value="">[SELECCIONE UN NODO CLIENTE]</option>';

                dataClientes.clientes.forEach(c => {
                    const opt = document.createElement("option");
                    opt.value = c.id;
                    opt.textContent = `[ID: ${c.id}] - ${c.nombre.toUpperCase()} (${c.cedula})`;
                    selectClie.appendChild(opt);
                });
            }

            const resTipos = await fetch(`${API_URL}?action=aux_tipos`);
            const dataTipos = await resTipos.json();
            if (dataTipos.status === "success") {
                const selectTipos = document.getElementById("selectTipoCaso");

                selectTipos.innerHTML = '<option value="">[SELECCIONE CLASIFICACIÓN]</option>';

                dataTipos.tipos.forEach(t => {
                    const opt = document.createElement("option");
                    opt.value = t.id;
                    opt.textContent = `// PROTOCOLO: ${t.nombre.toUpperCase()}`;
                    selectTipos.appendChild(opt);
                });
            }

            const resCorrelativo = await fetch(`${API_URL}?action=obtener_proximo_numero`);
            const dataCorr = await resCorrelativo.json();
            if (dataCorr.status === "success") {
                document.getElementById("numeroCasoAuto").value = dataCorr.proximo_numero;
            }

        } catch (err) {
            console.error("❌ Error cargando la telemetría inicial de componentes:", err);
        }
    }

    form?.addEventListener("submit", async (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (bloqueandoSubmit) return;
        bloqueandoSubmit = true;

        const datosFormulario = new FormData(form);

        try {
            Swal.fire({
                title: "[INJECTING_RMA_DATA...]",
                text: "Estableciendo enlace con el bloque de persistencia de datos.",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const res = await fetch(`${API_URL}?action=guardar`, {
                method: "POST",
                body: datosFormulario
            });

            const r = await res.json();

            if (r.status === "success") {
                const barcodeUrl = `https://barcode.tec-it.com/barcode.ashx?data=${encodeURIComponent(r.numero_caso)}&code=Code128&translate-esc=true`;

                Swal.fire({
                    title: `[NODO DESPLEGADO: ${r.numero_caso}]`,
                    html: `
                        <div style="text-align: center; font-family: 'Share Tech Mono', monospace; color: #cbd5e1;">
                            <p style="color: #00ff66; margin: 0;">// TRANSMISIÓN EXITOSA //</p>
                            
                            <div id="ticket-impresion" class="ticket-etiqueta">
                                <h3 class="ticket-title">${r.numero_caso}</h3>
                                <img src="${r.qr_url}" class="ticket-qr" alt="QR" />
                                <br>
                                <img src="${barcodeUrl}" class="ticket-barcode" alt="BARCODE" />
                            </div>

                            <p style="font-size: 0.85rem; color: #506690; margin: 10px 0;">Despliega el comando físico para adherir el identificador al hardware o abre la visualización digital.</p>
                            
                            <div style="display: flex; justify-content: center; gap: 10px;">
                                <button id="btnImprimirEtiqueta" style="padding: 8px 15px; background: rgba(255, 202, 40, 0.1); border: 1px solid #ffca28; color: #ffca28; font-weight: bold; border-radius: 4px; cursor: pointer;">
                                    ⚡ [PRINT_LABEL]
                                </button>
                                <button id="btnVerComprobantePop" style="padding: 8px 15px; background: rgba(0, 242, 255, 0.1); border: 1px solid #00f2ff; color: #00f2ff; font-weight: bold; border-radius: 4px; cursor: pointer;">
                                    [VIEW_RECEIPT]
                                </button>
                            </div>
                        </div>
                    `,
                    background: '#0a1020',
                    confirmButtonColor: '#ffca28',
                    confirmButtonText: "[DISMISS_HUD]",
                    didOpen: () => {
                        document.getElementById("btnImprimirEtiqueta").onclick = () => {
                            const contenido = document.getElementById("ticket-impresion").innerHTML;
                            const ventanaImpresion = window.open('', '_blank', 'height=500,width=400');
                            ventanaImpresion.document.write(`
                                <html>
                                <head>
                                    <title>Imprimir Etiqueta RMA</title>
                                    <style>
                                        body { margin: 0; padding: 10px; font-family: 'Courier New', monospace; text-align: center; background: #fff; color: #000; }
                                        .ticket-title { font-size: 14px; font-weight: bold; margin: 0 0 5px 0; letter-spacing: 1px; }
                                        .ticket-qr { width: 130px; height: 130px; margin-bottom: 5px; }
                                        .ticket-barcode { width: 160px; height: 45px; }
                                        @page { size: auto; margin: 0mm; }
                                    </style>
                                </head>
                                <body>
                                    ${contenido}
                                    <script>
                                        window.onload = function() { window.print(); setTimeout(function() { window.close(); }, 500); }
                                    <\/script>
                                </body>
                                </html>
                            `);
                            ventanaImpresion.document.close();
                        };

                        document.getElementById("btnVerComprobantePop").onclick = () => {
                            const posicionAncho = (screen.width - 850) / 2;
                            const posicionAlto = (screen.height - 700) / 2;

                            window.open(
                                r.link_pdf,
                                'ComprobanteRMA',
                                `width=850,height=700,left=${posicionAncho},top=${posicionAlto},resizable=yes,scrollbars=yes,status=no,toolbar=no,menubar=no`
                            );
                        };
                    }
                });

                form.reset();
                const displayFile = document.getElementById('fileNameDisplay');
                if (displayFile) displayFile.textContent = '';

                inicializarComponentes();
            } else {
                Swal.fire("FALLO EN INYECCIÓN", r.message, "error");
            }

        } catch (err) {
            Swal.fire("CRITICAL CORE ERROR", "La conexión con el núcleo RMA fue interrumpida de golpe.", "error");
            console.error(err);
        }

        bloqueandoSubmit = false;
    });

    const verificarExistencia = setInterval(() => {
        if (document.getElementById("formNuevoCaso")) {
            clearInterval(verificarExistencia);
            inicializarComponentes();
        }
    }, 100);

})();