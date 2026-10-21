(function () {
    const root = document.querySelector('.remisiones-page');
    const api = '../api-rma/inventario/remisiones.php';
    const tecnico = root.dataset.tecnico === '1';
    const esc = value => String(value ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
    const numero = id => 'NR-' + String(id).padStart(6, '0');
    let notas = [], cambio = null;
    function mensaje(texto, error = false) {
        const div = document.getElementById('remMensaje');
        div.hidden = false;
        div.className = 'alert ' + (error ? 'alert-danger' : 'alert-success');
        div.textContent = texto;
    }
    async function request(action, body) {
        const res = await fetch(api + '?action=' + action, body ? {
            method: 'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN': window.rmaCsrf},
            body: JSON.stringify(body)
        } : {cache: 'no-store'});
        const data = await res.json();
        if (!res.ok || data.status !== 'success') throw new Error(data.message || 'No se pudo completar la operación.');
        return data;
    }
    async function cargarCasos() {
        try {
            const d = await request('casos');
            const sel = document.getElementById('remCaso');
            sel.innerHTML = '<option value="">Seleccioná un caso</option>' + d.casos.map(c => '<option value="'+c.id+'">'+esc(c.numero_caso+' - '+c.equipo)+'</option>').join('');
            document.getElementById('remEnviar').disabled = !d.casos.length;
            if (!d.casos.length) mensaje('No hay casos RMA abiertos. Registrá un caso antes de solicitar una remisión.', true);
        } catch (e) { mensaje(e.message, true); }
    }
    document.getElementById('formNuevaRemision')?.addEventListener('submit', async event => {
        event.preventDefault();
        const btn = document.getElementById('remEnviar');
        btn.disabled = true;
        try {
            const d = await request('solicitar', {
                id_caso: document.getElementById('remCaso').value,
                insumo_nombre: document.getElementById('remInsumo').value.trim(),
                cantidad: document.getElementById('remCantidad').value,
                observacion: document.getElementById('remObservacion').value.trim()
            });
            mensaje(numero(d.id) + ': ' + d.message);
            const link = document.createElement('a');
            link.href = api + '?action=pdf&id=' + d.id; link.target = '_blank'; link.rel = 'noopener';
            link.textContent = ' Ver PDF'; document.getElementById('remMensaje').appendChild(link);
            event.target.reset();
        } catch(e) { mensaje(e.message, true); }
        finally { btn.disabled = false; }
    });
    function render() {
        const term = document.getElementById('remBuscar').value.trim().toLowerCase();
        const tbody = document.querySelector('#tablaRemisiones tbody');
        const filtered = notas.filter(n => [numero(n.id),n.numero_caso,n.insumo_nombre,n.creador_nombre].join(' ').toLowerCase().includes(term));
        tbody.innerHTML = filtered.map(n => {
            const abierta = ['PENDIENTE','ACTIVA'].includes(n.estado);
            const color = n.estado === 'CERRADA' ? 'success' : (['CANCELADA','RECHAZADA'].includes(n.estado) ? 'danger' : 'warning');
            const acciones = !tecnico && abierta ? (n.estado==='PENDIENTE' ? '<button class="btn btn-xs btn-info" data-estado="ACTIVA" data-id="'+n.id+'">Aceptar</button>' : '') +
                '<button class="btn btn-xs btn-success" data-estado="CERRADA" data-id="'+n.id+'">Cerrar</button>' +
                '<button class="btn btn-xs btn-warning" data-estado="CANCELADA" data-id="'+n.id+'">Cancelar</button>' +
                '<button class="btn btn-xs btn-danger" data-estado="RECHAZADA" data-id="'+n.id+'">Rechazar</button>' : '';
            return '<tr><td>'+numero(n.id)+'</td><td>'+esc(n.numero_caso)+'</td><td>'+esc(n.insumo_nombre)+'</td><td>'+esc(n.creador_nombre)+'</td><td>'+esc(n.cantidad)+'</td><td><span class="label label-'+color+'">'+esc(n.estado)+'</span></td><td>'+esc(n.fecha_creacion)+'</td><td><button class="btn btn-xs btn-default" data-detalle="'+n.id+'">Ver detalle</button><a class="btn btn-xs btn-default" target="_blank" rel="noopener" href="'+api+'?action=pdf&id='+n.id+'">PDF</a>'+acciones+'</td></tr>';
        }).join('') || '<tr><td colspan="8">No hay remisiones para esta búsqueda.</td></tr>';
    }
    async function cargar() {
        const tbody = document.querySelector('#tablaRemisiones tbody');
        tbody.innerHTML = '<tr><td colspan="8">Cargando remisiones...</td></tr>';
        try {
            const d = await request('listar&estado=' + document.getElementById('remEstado').value);
            notas = d.remisiones;
            render();
        } catch(e) { notas = []; tbody.innerHTML = '<tr><td colspan="8">No se pudo cargar el listado.</td></tr>'; mensaje(e.message, true); }
    }
    document.querySelector('#tablaRemisiones tbody')?.addEventListener('click', async event => {
        const btn = event.target.closest('button');
        if (!btn) return;
        if (btn.dataset.detalle) {
            try {
                const d = await request('detalle&id=' + btn.dataset.detalle), n = d.remision;
                const fields = {'Estado':n.estado,'Caso':n.numero_caso,'Equipo':n.equipo,'Cliente':n.cliente_nombre,'Creada por':n.creador_nombre,'Repuesto / insumo':n.insumo_nombre,'Cantidad':n.cantidad,'Observación de solicitud':n.observacion,'Fecha de creación':n.fecha_creacion,'Gestionada por':n.cierre_nombre,'Observación de depósito':n.observacion_cierre,'Fecha de cierre':n.fecha_cierre};
                document.getElementById('remDetalleTitulo').textContent = numero(n.id);
                document.getElementById('remDetalleContenido').innerHTML = '<dl>' + Object.entries(fields).map(([k,v])=>'<dt>'+esc(k)+'</dt><dd>'+esc(v || '—')+'</dd>').join('') + '</dl>';
                document.getElementById('remDetallePdf').href = api+'?action=pdf&id='+n.id;
                document.getElementById('remDetalle').showModal();
            } catch(e) { mensaje(e.message,true); }
        } else if (btn.dataset.estado && !tecnico) {
            cambio = {id:btn.dataset.id, estado:btn.dataset.estado};
            document.getElementById('remCambioTitulo').textContent = numero(cambio.id) + ' · ' + cambio.estado;
            document.getElementById('remMotivo').value = '';
            document.getElementById('remMotivo').required = ['CANCELADA','RECHAZADA'].includes(cambio.estado);
            document.getElementById('remCambio').showModal();
        }
    });
    document.getElementById('remCambioForm').addEventListener('submit', async event => {
        event.preventDefault();
        const btn = document.getElementById('remConfirmarCambio'); btn.disabled = true;
        try {
            const d = await request('cambiar_estado', {...cambio, observacion_cierre:document.getElementById('remMotivo').value.trim()});
            document.getElementById('remCambio').close(); mensaje(d.message); await cargar();
        } catch(e) { document.getElementById('remCambio').close(); mensaje(e.message,true); }
        finally { btn.disabled = false; }
    });
    document.getElementById('remCerrarDetalle').onclick = () => document.getElementById('remDetalle').close();
    document.getElementById('remCancelarCambio').onclick = () => document.getElementById('remCambio').close();
    document.getElementById('remEstado')?.addEventListener('change', cargar);
    document.getElementById('remBuscar')?.addEventListener('input', render);
    document.getElementById('remRecargar')?.addEventListener('click', cargar);
    if (root.dataset.crear === '1') cargarCasos(); else cargar();
})();
