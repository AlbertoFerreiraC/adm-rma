<input type="hidden" id="idUsuario" value="<?php echo $_SESSION['idusuario']; ?>">

<div class="content-wrapper categoria-wrapper">

  <section class="content-header">
    <h1>Actualizar mi Contraseña</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Configuración</li>
    </ol>
  </section>

  <section class="content">

    <div class="box box-dark">

      <div class="box-header with-border">
        <h4 style="font-weight:600;">Cambiar contraseña</h4>
      </div>

      <div class="box-body row">

        <div class="form-group col-md-4">
          <label>Contraseña Actual</label>
          <input type="password"
            class="form-control input-dark"
            id="datoPassUsuarioActual"
            placeholder="Ingrese su contraseña actual">
        </div>

        <div class="form-group col-md-4">
          <label>Nueva Contraseña</label>
          <input type="password"
            class="form-control input-dark"
            id="datoPassUsuario"
            placeholder="Ingrese nueva contraseña">
        </div>

        <div class="form-group col-md-4">
          <label>Repita Nueva Contraseña</label>
          <input type="password"
            class="form-control input-dark"
            id="datoPassUsuarioRepe"
            placeholder="Repita nueva contraseña">
        </div>

        <div class="form-group col-md-12 text-center" style="margin-top:15px;">
          <button type="button"
            class="btn btn-gradient btn-lg"
            id="btnActualizar">
            <i class="fa fa-refresh"></i> Actualizar Contraseña
          </button>
        </div>

      </div>

    </div>

  </section>

</div>

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

  .input-dark {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    transition: 0.3s;
  }

  .input-dark:focus {
    border-color: #0072ff;
    box-shadow: 0 0 8px rgba(0, 114, 255, 0.4);
  }

  .btn-gradient {
    background: linear-gradient(90deg, #00c6ff, #0072ff);
    border: none;
    border-radius: 30px;
    color: white;
    font-weight: bold;
    padding: 10px 25px;
    transition: 0.3s;
  }

  .btn-gradient:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(0, 198, 255, 0.6);
  }

  .content-header h1 {
    color: #ffffff;
    font-weight: 700;
    font-size: 28px;
    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
  }

  .breadcrumb>li>a,
  .breadcrumb>li.active {
    color: #e0f2ff !important;
  }
</style>

<script src="vistas/js/actualizarMisDatos.js"></script>