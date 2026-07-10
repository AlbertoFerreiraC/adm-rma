(function () {
    console.log("⚡ [RMA_CORE] Sistema de Bandeja de Casos Inicializado...");

    const API_URL = "../api-rma/rma-core/casos/bandejaCasos.php";
    const buscador = document.getElementById("cyberBuscador");
    const tbody = document.getElementById("tbodyCasos");

    async function listarCasos(busqueda = "") {
        try {
            const res = await fetch(`${API_URL}?action=listar_bandeja&buscar=${encodeURIComponent(busqueda)}`);
            const data = await res.json();

            if (data.status !== "success") {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:#ff3333;">[ERROR]: ${data.message}</td></tr>`;
                return;
            }

            tbody.innerHTML = "";
            document.getElementById("contadorCasos").textContent = `NODES: ${data.casos.length}`;

            if (data.casos.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:#506690;">[NO_ACTIVE_NODES_FOUND]</td></tr>`;
                return;
            }

            data.casos.forEach(c => {
                const tr = document.createElement("tr");

                const badgeClass = c.id_estado_actual == 1 ? "status-1" : "status-default";
                const fechaFormateada = new Date(c.fecha_ingreso).toLocaleDateString('es-ES');
                const barcodeUrl = `https://barcode.tec-it.com/barcode.ashx?data=${encodeURIComponent(c.numero_caso)}&code=Code128&translate-esc=true`;

                tr.innerHTML = `
                    <td class="text-neon-cyan font-weight-bold">${c.numero_caso}</td>
                    <td>${fechaFormateada}</td>
                    <td style="text-transform:uppercase;">${c.cliente_nombre}</td>
                    <td style="text-transform:uppercase;">${c.equipo} <span style="color:#506690;">(${c.marca})</span></td>
                    <td style="color:#ffca28;">${c.numero_serie}</td>
                    <td><span class="badge-status-cyber ${badgeClass}">${c.estado_nombre.toUpperCase()}</span></td>
                    <td style="text-align:center; display: flex; gap: 8px; justify-content: center;">
                        <button class="btn-cyber-action btn-open-pop" data-link="${c.link_secure}">
                            [VIEW_RECEIPT]
                        </button>
                        <button class="btn-cyber-action btn-cyber-reprint btn-execute-reprint" 
                                data-caso="${c.numero_caso}" 
                                data-qr="${c.qr_url}" 
                                data-barcode="${barcodeUrl}">
                            ⚡ [REPRINT_LABEL]
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            bindBandejaButtons();

        } catch (err) {
            console.error("Critical stream mapping error:", err);
        }
    }

    function bindBandejaButtons() {
        document.querySelectorAll(".btn-open-pop").forEach(btn => {
            btn.onclick = () => {
                const urlSecure = btn.dataset.link;
                const ancho = (screen.width - 850) / 2;
                const alto = (screen.height - 700) / 2;

                window.open(
                    urlSecure,
                    'ComprobanteRMA',
                    `width=850,height=700,left=${ancho},top=${alto},resizable=yes,scrollbars=yes,status=no,toolbar=no,menubar=no`
                );
            };
        });

        document.querySelectorAll(".btn-execute-reprint").forEach(btn => {
            btn.onclick = () => {
                const numeroCaso = btn.dataset.caso;
                const qrUrl = btn.dataset.qr;
                const barcodeUrl = btn.dataset.barcode;

                const ventanaImpresion = window.open('', '_blank', 'height=500,width=400');

                ventanaImpresion.document.write(`
                    <html>
                    <head>
                        <title>Reimprimir Etiqueta ${numeroCaso}</title>
                        <style>
                            body { margin: 0; padding: 10px; font-family: 'Courier New', monospace; text-align: center; background: #fff; color: #000; }
                            .ticket-title { font-size: 14px; font-weight: bold; margin: 0 0 5px 0; letter-spacing: 1px; }
                            .ticket-qr { width: 130px; height: 130px; margin-bottom: 5px; }
                            .ticket-barcode { width: 160px; height: 45px; object-fit: contain; }
                            @page { size: auto; margin: 0mm; }
                        </style>
                    </head>
                    <body>
                        <h3 class="ticket-title">${numeroCaso}</h3>
                        <img src="${qrUrl}" class="ticket-qr" alt="QR" />
                        <br>
                        <img src="${barcodeUrl}" class="ticket-barcode" alt="BARCODE" />
                        <script>
                            window.onload = function() { 
                                window.print(); 
                                setTimeout(function() { window.close(); }, 500); 
                            }
                        <\/script>
                    </body>
                    </html>
                `);
                ventanaImpresion.document.close();
            };
        });
    }

    buscador?.addEventListener("input", (e) => {
        listarCasos(e.target.value.trim());
    });

    document.getElementById("btnLimpiarFiltro").onclick = () => {
        buscador.value = "";
        listarCasos("");
    };

    const initBandeja = setInterval(() => {
        if (document.getElementById("cyberBuscador")) {
            clearInterval(initBandeja);
            listarCasos("");
        }
    }, 100);

})();