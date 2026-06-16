<div class="content-wrapper categoria-wrapper">

    <section class="content-header">
        <h1>Administrar Categorías</h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Categorías</li>
        </ol>
    </section>

    <section class="content">

        <div class="box box-dark">

            <div class="box-header with-border">

                <div class="form-group col-sm-3 col-xs-12">
                    <button class="btn btn-gradient btn-block" data-toggle="modal" data-target="#modalAgregar">
                        <i class="fa fa-plus"></i> Agregar Categoría
                    </button>
                </div>

                <div class="form-group col-sm-9 col-xs-12">
                    <input type="text"
                        class="form-control input-dark"
                        placeholder="Filtrado General..."
                        id="filtradoDinamico">
                </div>

            </div>

            <div class="box-body">

                <div id="div1">
                    <table class="table table-bordered table-striped dt-responsive tabla-dark" id="tabla" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Descripción</th>
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


<!-- ==============================
MODAL AGREGAR
=================================-->

<div id="modalAgregar" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content modal-dark">

            <form id="formAgregarCategoria">

                <div class="modal-header modal-dark-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar Categoría</h4>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text"
                            class="form-control input-dark"
                            name="descripcionAgregar"
                            required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fa fa-hdd-o"></i> Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


<!-- ==============================
MODAL MODIFICAR
=================================-->

<div id="modalModificar" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content modal-dark">

            <form id="formModificarCategoria">

                <div class="modal-header modal-dark-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modificar Categoría</h4>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="idModificar" id="idModificar">

                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text"
                            class="form-control input-dark"
                            name="descripcionModificar"
                            id="descripcionModificar"
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


<!-- ==============================
ESTILOS
=================================-->

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

    .tabla-dark {
        background: #ffffff;
        color: #333;
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
        color: #333;
        border-radius: 6px;
    }

    .input-dark:focus {
        border-color: #0072ff;
        box-shadow: 0 0 5px rgba(0, 114, 255, 0.4);
    }

    .btn-gradient {
        background: linear-gradient(90deg, #00c6ff, #0072ff);
        border: none;
        border-radius: 25px;
        color: white;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-gradient:hover {
        transform: scale(1.03);
        box-shadow: 0 0 15px rgba(0, 198, 255, 0.5);
    }

    .modal-dark {
        background: #ffffff;
        border-radius: 15px;
    }

    .modal-dark-header {
        background: #243b55;
        color: #fff;
    }

    #div1 {
        overflow: auto;
        width: 100%;
    }

    .content-header h1 {
        color: #ffffff;
        font-weight: 700;
        font-size: 28px;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
    }

    .content-header .breadcrumb>li>a,
    .content-header .breadcrumb>li.active {
        color: #e0f2ff;
    }

    .content-header .breadcrumb>li+li:before {
        color: #ffffff;
    }
</style>

<script src="vistas/js/categoria.js"></script>