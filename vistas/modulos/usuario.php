<input type="hidden" id="idModificar">

<div class="content-wrapper categoria-wrapper">

  <section class="content-header">
    <h1>Administrar Usuarios</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Usuarios</li>
    </ol>
  </section>

  <section class="content">

    <div class="box box-dark">

      <div class="box-header with-border">

        <div class="form-group col-sm-3 col-xs-12">
          <button class="btn btn-gradient btn-block"
            data-toggle="modal"
            data-target="#modalAgregar">
            <i class="fa fa-plus"></i> Agregar Usuario
          </button>
        </div>

        <div class="form-group col-sm-9 col-xs-12">
          <input type="text"
            class="form-control input-dark"
            id="filtradoDinamico"
            placeholder="Filtrado General...">
        </div>

      </div>

      <div class="box-body">

        <div id="div1">
          <table class="table table-bordered table-striped dt-responsive tabla-dark"
            id="tabla"
            width="100%">
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

      </div>

    </div>

  </section>

</div>



<!-- ======================================
MODAL AGREGAR
====================================== -->

<div id="modalAgregar" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content modal-dark">

      <form id="formAgregarUsuario">

        <div class="modal-header modal-dark-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar Usuario</h4>
        </div>

        <div class="modal-body">

          <div class="form-group">
            <label>Nombre de Usuario</label>
            <input type="text"
              class="form-control input-dark"
              name="nombreAgregar"
              required>
          </div>
          <div class="text-center">
            <small style="color:#DC3139;">
              Contraseña por defecto: 12345
            </small>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-gradient">
            <i class="fa fa-save"></i> Guardar
          </button>
        </div>

      </form>

    </div>
  </div>
</div>



<!-- ======================================
MODAL MODIFICAR
====================================== -->

<div id="modalModificar" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content modal-dark">

      <form id="formModificarUsuario">

        <div class="modal-header modal-dark-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modificar Usuario</h4>
        </div>

        <div class="modal-body">

          <input type="hidden" id="idUsuarioModificar" name="idUsuarioModificar">

          <div class="form-group">
            <label>Nombre de Usuario</label>
            <input type="text"
              class="form-control input-dark"
              id="nombreModificar"
              name="nombreModificar"
              required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-gradient">
            <i class="fa fa-save"></i> Modificar
          </button>
        </div>

      </form>

    </div>
  </div>
</div>



<!-- ======================================
ESTILOS
====================================== -->

<style>
  .categoria-wrapper {
    background: linear-gradient(135deg, #141e30, #243b55);
    min-height: 100vh;
    padding-bottom: 40px;
  }

  .box-dark {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
  }

  .tabla-dark thead {
    background: #243b55;
    color: #ffffff;
  }

  .tabla-dark tbody tr:hover {
    background-color: #f4f9ff;
  }

  .input-dark {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 6px;
  }

  .btn-gradient {
    background: linear-gradient(90deg, #00c6ff, #0072ff);
    border: none;
    border-radius: 25px;
    color: white;
    font-weight: bold;
  }

  .modal-dark-header {
    background: #243b55;
    color: white;
  }

  .content-header h1 {
    color: #ffffff;
    font-weight: 700;
    font-size: 28px;
    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
  }

  #div1 {
    overflow: auto;
  }
</style>

<script src="vistas/js/usuario.js"></script>