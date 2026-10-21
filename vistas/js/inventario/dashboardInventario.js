(function () {
    const API = '../api-rma/inventario/inventario.php';
    const ALERTAS = '../api-rma/inventario/alertas.php';
    const tabla = document.querySelector('#tablaStockInventario tbody');
    const dialog = document.getElementById('invEditar');
    const selector = document.getElementById('invSeleccionar');
    const campos = document.getElementById('invCampos');
    const guardar = document.getElementById('invGuardar');
    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
    const inputs = {codigo:'invCodigo',nombre:'invNombre',categoria:'invCategoria',marca:'invMarca',stock_actual:'invActual',stock_rma:'invRma',stock_minimo:'invMinimo'};
    let productos = [], actual = null, carga = 0, guardando = false;
    function error(texto) {
        const el = document.getElementById('invError'); el.textContent = texto; el.hidden = !texto;
    }
    async function request(url, datos) {
        const r = await fetch(url, datos ? {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':window.rmaCsrf}, body:JSON.stringify(datos)} : {cache:'no-store'});
        const d = await r.json();
        if (!r.ok || d.status !== 'success') throw new Error(d.message || 'No se pudo completar la operación.');
        return d;
    }
    function render() {
        const termino = document.getElementById('buscarInventario').value.toLowerCase().trim();
        const rows = productos.filter(p=>[p.codigo,p.nombre,p.categoria,p.marca].some(v=>String(v??'').toLowerCase().includes(termino)));
        tabla.innerHTML = rows.map(p=> {
            const cls = p.estado_alerta === 'SIN STOCK' ? 'badge-status-danger' : (p.estado_alerta === 'OK' ? 'system-badge-live' : 'badge-status-warning');
            return '<tr><td class="t-cyan">'+esc(p.codigo)+'</td><td>'+esc(p.nombre)+'</td><td>'+esc(p.categoria)+'</td><td>'+esc(p.marca)+'</td><td>'+p.stock_actual+'</td><td>'+p.stock_rma+'</td><td>'+p.stock_disponible+'</td><td><span class="'+cls+'">'+esc(p.estado_alerta)+'</span></td><td><button type="button" class="btn-terminal-edit" data-id="'+p.id+'">Editar stock</button></td></tr>';
        }).join('') || '<tr><td colspan="9">No hay productos para esta búsqueda.</td></tr>';
    }
    async function cargar() {
        const loader = document.getElementById('cyberLoaderDashboard');
        if (loader) { loader.style.opacity='1'; loader.style.visibility='visible'; }
        try {
            const [d, k] = await Promise.all([request(API+'?action=listar'),request(ALERTAS+'?action=consultar_alertas')]);
            productos=d.stock; render();
            document.getElementById('kpiTotalProductos').textContent=k.kpis.total_productos;
            document.getElementById('kpiUnidadesStock').textContent=k.kpis.unidades_totales;
            document.getElementById('kpiStockRma').textContent=k.kpis.en_proceso_rma;
            document.getElementById('kpiAlertasStock').textContent=k.kpis.sin_stock+k.kpis.stock_bajo;
        } catch(e) {
            tabla.innerHTML='<tr><td colspan="9">No se pudo cargar el inventario: '+esc(e.message)+'</td></tr>';
        } finally {
            if (loader) { loader.style.opacity='0'; loader.style.visibility='hidden'; }
        }
    }
    function disponible() {
        const a=Number(document.getElementById('invActual').value), r=Number(document.getElementById('invRma').value);
        document.getElementById('invDisponible').textContent=Number.isFinite(a-r) ? String(a-r) : '—';
        document.getElementById('invRma').setCustomValidity(r>a ? 'La reserva no puede superar el stock actual.' : '');
    }
    async function detalle(id) {
        const turno=++carga; actual=null; campos.disabled=true; guardar.disabled=true; error('');
        if (!id) return;
        try {
            const d=await request(API+'?action=detalle&id='+encodeURIComponent(id));
            if (turno!==carga) return;
            actual=d.producto;
            Object.entries(inputs).forEach(([key,el])=>document.getElementById(el).value=actual[key]);
            document.getElementById('invMotivo').value='';
            campos.disabled=false; guardar.disabled=false; disponible();
        } catch(e) { if(turno===carga) error(e.message); }
    }
    function abrir(id) {
        selector.innerHTML='<option value="">Seleccioná un producto</option>'+productos.map(p=>'<option value="'+p.id+'">'+esc(p.codigo+' · '+p.nombre)+'</option>').join('');
        selector.value=id || ''; error(''); dialog.showModal(); detalle(id);
    }
    tabla.addEventListener('click', e=>{
        const btn=e.target.closest('button[data-id]');
        if(btn) abrir(btn.dataset.id);
    });
    selector.addEventListener('change',()=>detalle(selector.value));
    document.getElementById('invActual').addEventListener('input',disponible);
    document.getElementById('invRma').addEventListener('input',disponible);
    function cerrar() { if (!guardando) { carga++; dialog.close(); } }
    document.getElementById('invCerrar').onclick=cerrar;
    document.getElementById('invCancelar').onclick=cerrar;
    dialog.addEventListener('cancel',e=>{if(guardando)e.preventDefault();});
    document.getElementById('invForm').addEventListener('submit',async e=>{
        e.preventDefault();
        if (!actual || guardando) return;
        const payload={id:actual.id,version:actual.version,motivo:document.getElementById('invMotivo').value.trim()};
        Object.entries(inputs).forEach(([key,el])=>payload[key]=document.getElementById(el).value.trim());
        guardando=true; guardar.disabled=true; selector.disabled=true; error('');
        try {
            const d=await request(API+'?action=actualizar',payload);
            guardando=false; dialog.close();
            const msg=document.getElementById('invMensaje');msg.textContent=d.message;msg.hidden=false;
            await cargar();
        } catch(err) { error(err.message); }
        finally { guardando=false; guardar.disabled=false; selector.disabled=false; }
    });
    document.getElementById('buscarInventario').addEventListener('input',render);
    document.getElementById('btnNuevaRemision').onclick=()=>window.location.href='remisiones';
    document.getElementById('btnRegistrarMovimiento').onclick=()=>abrir('');
    cargar();
})();
