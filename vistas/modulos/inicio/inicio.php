<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<input type="hidden" name="rol" id="rol" value="<?php echo $_SESSION['id_rol'] ?? ''; ?>">

<div class="content-wrapper dashboard-wrapper">

  <section class="content-header">
    <div class="header-text">
      <h1>Panel de Servicios Técnicos</h1>
      <p class="subtitulo">Gestión, seguimiento, QR y control de órdenes RMA</p>
    </div>
  </section>

  <section class="content">

    <div class="quick-actions">
      <button class="action-btn primary" id="btnNuevoServicio">
        ➕ Nuevo Servicio
      </button>
      <button class="action-btn" id="btnTecnicos">
        👨‍🔧 Técnicos
      </button>
      <button class="action-btn" id="btnDerivar">
        📦 Derivar Externo
      </button>
      <button class="action-btn" id="btnQR">
        📷 Escanear QR
      </button>
      <button class="action-btn" id="btnHistorial">
        📄 Historial
      </button>
    </div>

    <div class="row kpi-container">
      <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="kpi-card kpi-blue">
          <div class="kpi-icon">📋</div>
          <h3 id="kpiTotal">0</h3>
          <p>Total Servicios</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="kpi-card kpi-yellow">
          <div class="kpi-icon">⏳</div>
          <h3 id="kpiPendiente">0</h3>
          <p>En Proceso</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="kpi-card kpi-green">
          <div class="kpi-icon">✅</div>
          <h3 id="kpiConcretado">0</h3>
          <p>Finalizados</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="kpi-card kpi-red">
          <div class="kpi-icon">❌</div>
          <h3 id="kpiNoConcretado">0</h3>
          <p>Rechazados</p>
        </div>
      </div>
    </div>

    <div class="glass-panel filters-bar">
      <div class="filter-group">
        <label>Estado</label>
        <select id="filtroEstado" class="custom-input">
          <option value="">Todos</option>
          <option value="pendiente">Pendiente</option>
          <option value="proceso">En proceso</option>
          <option value="finalizado">Finalizado</option>
          <option value="externo">Externo</option>
        </select>
      </div>

      <div class="filter-group">
        <label>Desde</label>
        <input type="date" id="fechaDesde" class="custom-input">
      </div>

      <div class="filter-group">
        <label>Hasta</label>
        <input type="date" id="fechaHasta" class="custom-input">
      </div>

      <div class="filter-group btn-group-align">
        <button id="btnFiltrar" class="btn-filtrar">
          🔍 Filtrar
        </button>
      </div>
    </div>

    <div class="glass-panel tabla-container">
      <div class="tabla-header">
        <h4>Listado de Servicios</h4>
      </div>

      <div class="table-responsive">
        <table id="tablaPedidos" class="tabla-modern">
          <thead>
            <tr>
              <th>ID</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Producto</th>
              <th>Estado</th>
              <th>Técnico</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>

  </section>
</div>

<div id="modalEstado" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content glass-modal">
      <div class="modal-header border-0">
        <h4 class="modal-title">Gestión de Servicio</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idServicio">

        <div class="form-group">
          <label>Estado</label>
          <select id="nuevoEstado" class="custom-input w-100">
            <option value="pendiente">Pendiente</option>
            <option value="proceso">En proceso</option>
            <option value="finalizado">Finalizado</option>
            <option value="externo">Derivado externo</option>
          </select>
        </div>

        <div class="form-group mt-3">
          <label>Observación</label>
          <textarea id="observacion" class="custom-input w-100" rows="3"
            placeholder="Escribe los detalles aquí..."></textarea>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button class="btn btn-secondary action-btn" data-dismiss="modal">Cancelar</button>
        <button class="btn action-btn primary" id="btnGuardarEstado">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

  .dashboard-wrapper {
    background: radial-gradient(circle at top right, #0b1220, #090e17, #030508);
    min-height: 100vh;
    padding: 30px;
    color: #e2e8f0;
    font-family: 'Poppins', sans-serif;
  }

  /* HEADER */
  .content-header {
    margin-bottom: 30px;
  }

  .content-header h1 {
    font-weight: 700;
    font-size: 2.2rem;
    background: linear-gradient(135deg, #ffffff, #8ba1c5);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 5px;
  }

  .subtitulo {
    color: #94a3b8;
    font-size: 1.1rem;
    font-weight: 300;
  }

  /* GLASSMORPHISM SHARED CLASS */
  .glass-panel {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
  }

  /* QUICK ACTIONS */
  .quick-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 30px;
  }

  .action-btn {
    padding: 12px 20px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.05);
    color: #e2e8f0;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(5px);
  }

  .action-btn:hover {
    transform: translateY(-3px);
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  }

  .action-btn.primary {
    background: linear-gradient(135deg, #008cff, #00d2ff);
    color: #000;
    font-weight: 700;
    border: none;
    box-shadow: 0 4px 15px rgba(0, 140, 255, 0.4);
  }

  .action-btn.primary:hover {
    box-shadow: 0 8px 25px rgba(0, 140, 255, 0.6);
  }

  /* KPI CARDS */
  .kpi-container {
    margin-bottom: 20px;
  }

  .kpi-card {
    padding: 25px 20px;
    border-radius: 16px;
    text-align: center;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
  }

  .kpi-card:hover {
    transform: translateY(-5px);
  }

  .kpi-icon {
    font-size: 1.5rem;
    margin-bottom: 10px;
    opacity: 0.8;
  }

  .kpi-blue {
    background: linear-gradient(135deg, rgba(0, 114, 255, 0.2), rgba(0, 198, 255, 0.05));
    box-shadow: 0 8px 32px rgba(0, 114, 255, 0.15);
  }

  .kpi-blue:hover {
    box-shadow: 0 8px 32px rgba(0, 114, 255, 0.3);
  }

  .kpi-yellow {
    background: linear-gradient(135deg, rgba(247, 151, 30, 0.2), rgba(255, 210, 0, 0.05));
    box-shadow: 0 8px 32px rgba(247, 151, 30, 0.15);
  }

  .kpi-yellow:hover {
    box-shadow: 0 8px 32px rgba(247, 151, 30, 0.3);
  }

  .kpi-green {
    background: linear-gradient(135deg, rgba(0, 176, 155, 0.2), rgba(150, 201, 61, 0.05));
    box-shadow: 0 8px 32px rgba(0, 176, 155, 0.15);
  }

  .kpi-green:hover {
    box-shadow: 0 8px 32px rgba(0, 176, 155, 0.3);
  }

  .kpi-red {
    background: linear-gradient(135deg, rgba(255, 65, 108, 0.2), rgba(255, 75, 43, 0.05));
    box-shadow: 0 8px 32px rgba(255, 65, 108, 0.15);
  }

  .kpi-red:hover {
    box-shadow: 0 8px 32px rgba(255, 65, 108, 0.3);
  }

  .kpi-card h3 {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0;
    color: #fff;
  }

  .kpi-card p {
    margin: 5px 0 0;
    font-size: 0.9rem;
    color: #cbd5e1;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  /* FILTROS */
  .filters-bar {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
    align-items: flex-end;
  }

  .filter-group label {
    display: block;
    font-size: 0.85rem;
    color: #94a3b8;
    margin-bottom: 8px;
    font-weight: 500;
  }

  /* ESTILOS DE INPUTS Y SELECTS (Dark Mode) */
  .custom-input {
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    padding: 10px 15px;
    border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    transition: 0.3s;
    outline: none;
  }

  .custom-input:focus {
    border-color: #008cff;
    box-shadow: 0 0 0 3px rgba(0, 140, 255, 0.2);
  }

  .custom-input option {
    background: #0f172a;
    color: #fff;
  }

  .btn-filtrar {
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    background: rgba(0, 140, 255, 0.1);
    color: #00d2ff;
    font-weight: 600;
    border: 1px solid rgba(0, 140, 255, 0.3);
    cursor: pointer;
    transition: 0.3s;
  }

  .btn-filtrar:hover {
    background: rgba(0, 140, 255, 0.2);
    box-shadow: 0 0 15px rgba(0, 140, 255, 0.3);
  }

  /* TABLA */
  .tabla-header h4 {
    margin-bottom: 20px;
    font-weight: 600;
    color: #fff;
  }

  .tabla-modern {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.95rem;
  }

  .tabla-modern thead th {
    background: rgba(255, 255, 255, 0.05);
    color: #94a3b8;
    font-weight: 500;
    padding: 15px;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 1px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
  }

  .tabla-modern thead th:first-child {
    border-top-left-radius: 10px;
  }

  .tabla-modern thead th:last-child {
    border-top-right-radius: 10px;
  }

  .tabla-modern tbody td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    color: #e2e8f0;
    transition: background 0.2s;
  }

  .tabla-modern tbody tr:hover td {
    background: rgba(0, 140, 255, 0.08);
  }

  /* MODAL GLASSMORPHISM */
  .glass-modal {
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
  }

  .modal-title {
    font-weight: 600;
  }

  /* Ajuste de utilidades */
  .w-100 {
    width: 100%;
  }

  .mt-3 {
    margin-top: 1rem;
  }

  .border-0 {
    border: none !important;
  }
</style>

<script src="vistas/js/inicio.js"></script>