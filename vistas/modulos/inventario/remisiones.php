<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$esTecnico = (int)($_SESSION['id_rol'] ?? 0) === 1;
if (!in_array((int)($_SESSION['id_rol'] ?? 0), [1,2,5], true)) { http_response_code(403); exit('Acceso no autorizado.'); }
$crear = $esTecnico && $ruta === 'nuevaRemision';
?>
<div class="content-wrapper remisiones-page" data-crear="<?= $crear ? '1':'0' ?>" data-tecnico="<?= $esTecnico ? '1':'0' ?>">
    <section class="content-header">
        <h1><?= $crear ? 'Agregar remisión' : ($esTecnico ? 'Mis remisiones' : 'Notas de remisión') ?></h1>
        <p><?= $crear ? 'Solicitá al depósito los repuestos para un caso RMA abierto.' : ($esTecnico ? 'Consultá el estado y el PDF de las notas que creaste.' : 'Revisá las solicitudes de los técnicos y gestioná sus estados.') ?></p>
    </section>
    <section class="content">
        <div id="remMensaje" role="alert" class="alert" hidden></div>
        <?php if ($crear): ?>
        <div class="box box-primary rem-form-box"><div class="box-body">
            <form id="formNuevaRemision">
                <div class="form-group"><label for="remCaso">Caso RMA</label><select id="remCaso" class="form-control" required><option value="">Cargando casos...</option></select></div>
                <div class="form-group"><label for="remInsumo">Repuesto o insumo solicitado</label><input id="remInsumo" class="form-control" maxlength="255" required placeholder="Ej.: memoria RAM DDR4 de 8 GB"></div>
                <div class="form-group"><label for="remCantidad">Cantidad</label><input id="remCantidad" class="form-control" type="number" min="1" max="100000" step="1" value="1" required></div>
                <div class="form-group"><label for="remObservacion">Observación</label><textarea id="remObservacion" class="form-control" rows="4" maxlength="2000" placeholder="Indicá las especificaciones o el motivo del pedido"></textarea></div>
                <p class="text-muted">La solicitud queda pendiente para su revisión en depósito.</p>
                <button class="btn btn-primary" id="remEnviar" disabled>Enviar solicitud</button>
                <a href="remisiones" class="btn btn-default">Ver mis remisiones</a>
            </form>
        </div></div>
        <?php else: ?>
        <div class="rem-toolbar">
            <div><label for="remEstado">Estado</label><select id="remEstado" class="form-control">
                <option value="TODAS">Todos los estados</option><option value="ACTIVAS">Activas (pendientes y aceptadas)</option>
                <option value="PENDIENTE">Pendientes</option><option value="ACTIVA">Aceptadas</option>
                <option value="CERRADA">Cerradas</option><option value="CANCELADA">Canceladas</option><option value="RECHAZADA">Rechazadas</option>
            </select></div>
            <div><label for="remBuscar">Buscar</label><input id="remBuscar" class="form-control" placeholder="Nota, caso, repuesto o técnico"></div>
            <button class="btn btn-default" id="remRecargar">Actualizar</button>
            <?php if ($esTecnico): ?><a href="nuevaRemision" class="btn btn-primary">Agregar remisión</a><?php endif; ?>
        </div>
        <div class="box box-primary"><div class="box-body table-responsive">
            <table class="table table-hover" id="tablaRemisiones"><thead><tr><th>Nota</th><th>Caso</th><th>Repuesto solicitado</th><th>Creada por</th><th>Cantidad</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead><tbody></tbody></table>
        </div></div>
        <?php endif; ?>
    </section>
</div>
<dialog id="remDetalle" class="rem-dialog" aria-labelledby="remDetalleTitulo"><h3 id="remDetalleTitulo">Detalle de remisión</h3><div id="remDetalleContenido"></div><div class="rem-dialog-actions"><a id="remDetallePdf" target="_blank" rel="noopener" class="btn btn-primary">Ver PDF</a><button class="btn btn-default" id="remCerrarDetalle">Cerrar</button></div></dialog>
<dialog id="remCambio" class="rem-dialog" aria-labelledby="remCambioTitulo"><form id="remCambioForm"><h3 id="remCambioTitulo">Cambiar estado</h3><label for="remMotivo">Observación / motivo</label><textarea id="remMotivo" class="form-control" rows="4" maxlength="2000"></textarea><div class="rem-dialog-actions"><button id="remConfirmarCambio" class="btn btn-primary">Confirmar</button><button type="button" class="btn btn-default" id="remCancelarCambio">Volver</button></div></form></dialog>
<style>
.remisiones-page {padding:12px 20px; color:#0f172a; min-height:80vh}
.remisiones-page h1 {font-size:27px; margin-bottom:12px}
.remisiones-page .content-header p {margin-top:10px;color:#475569}
.rem-form-box {max-width:760px}
.remisiones-page .box {border-radius:8px;box-shadow:0 4px 18px #0f172a0c}
.remisiones-page .box-body {padding:22px}
.remisiones-page .form-control {border-radius:5px}
.rem-toolbar {display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;margin-bottom:18px}
.rem-toolbar>div {min-width:230px}
.remisiones-page td {vertical-align:middle!important}
.remisiones-page td:last-child {min-width:190px}
.remisiones-page td .btn {margin:3px}
.rem-dialog {border:1px solid #cbd5e1;border-radius:10px;padding:25px;width:min(650px,95vw);max-height:85vh;overflow:auto;color:#0f172a}
.rem-dialog::backdrop {background:#0f172a99}
.rem-dialog dt {margin-top:14px}
.rem-dialog dd {white-space:pre-wrap;overflow-wrap:anywhere}
.rem-dialog-actions {display:flex;gap:10px;justify-content:flex-end;margin-top:24px}
.remisiones-page .content {padding:15px}
.remisiones-page label,.rem-dialog label {display:block;font-weight:600;margin-bottom:7px}
.remisiones-page .form-group {margin-bottom:20px}
.remisiones-page .form-control,.rem-dialog .form-control {display:block;width:100%;min-height:40px;padding:9px 12px;border:1px solid #cbd5e1;background:#fff;color:#0f172a;font:14px Arial,sans-serif;box-sizing:border-box;border-radius:5px}
.remisiones-page .form-control:focus,.rem-dialog .form-control:focus {outline:2px solid #bae6fd;border-color:#0284c7}
.remisiones-page .btn,.rem-dialog .btn {display:inline-flex;align-items:center;justify-content:center;padding:9px 14px;border:1px solid #cbd5e1;border-radius:5px;color:#334155;background:#fff;text-decoration:none;cursor:pointer;font:600 13px Arial,sans-serif;line-height:1.3}
.remisiones-page .btn:disabled,.rem-dialog .btn:disabled {opacity:.5;cursor:wait}
.remisiones-page .btn-primary,.rem-dialog .btn-primary {color:#fff;background:#0369a1;border-color:#0369a1}
.remisiones-page .btn-success {color:#fff;background:#15803d;border-color:#15803d}
.remisiones-page .btn-danger {color:#fff;background:#b91c1c;border-color:#b91c1c}
.remisiones-page .btn-info {color:#0369a1;background:#e0f2fe;border-color:#7dd3fc}
.remisiones-page .btn-warning {color:#92400e;background:#fef3c7;border-color:#fcd34d}
.remisiones-page .btn-xs {padding:6px 9px;font-size:12px}
.remisiones-page .table-responsive {overflow-x:auto}
.remisiones-page table {width:100%;border-collapse:collapse;font-size:14px}
.remisiones-page th {color:#475569;font-size:12px;text-align:left;background:#f8fafc;padding:12px 10px;border-bottom:2px solid #e2e8f0}
.remisiones-page td {padding:12px 10px;border-bottom:1px solid #e2e8f0}
.remisiones-page td:first-child,.remisiones-page td:nth-child(2) {white-space:nowrap}
.remisiones-page .label {display:inline-block;padding:5px 7px;border-radius:4px;font-size:11px;font-weight:700}
.remisiones-page .label-warning {background:#fef3c7;color:#92400e}
.remisiones-page .label-danger {background:#fee2e2;color:#991b1b}
.remisiones-page .label-success {background:#dcfce7;color:#166534}
.remisiones-page .alert {padding:14px;border-radius:6px;margin-bottom:16px}
.remisiones-page .alert-danger {background:#fee2e2;color:#991b1b}
.remisiones-page .alert-success {background:#dcfce7;color:#166534}
.remisiones-page .alert a {color:inherit;text-decoration:underline}
</style>
<script src="vistas/js/inventario/remisiones.js?v=<?= filemtime(__DIR__.'/../../js/inventario/remisiones.js') ?>"></script>
