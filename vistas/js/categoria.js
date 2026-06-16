$(document).ready(function () {

    cargarDatosTabla();

    $("#formAgregarCategoria").submit(function (e) {
        e.preventDefault();
        agregarDatos();
    });

    $("#formModificarCategoria").submit(function (e) {
        e.preventDefault();
        modificarDatos();
    });

    $("#filtradoDinamico").keyup(function () {

        var texto = $(this).val().toLowerCase();
        var table = $("#tabla tbody tr");

        table.each(function () {
            if ($(this).text().toLowerCase().indexOf(texto) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

    });

});


// ===========================================
// LISTAR
// ===========================================

function cargarDatosTabla() {

    $("#tabla tbody").empty();
    var fila = "";

    $.ajax({
        url: "../api-mensajeria/categoria/funListar.php",
        method: "GET",
        dataType: "json",
        success: function (response) {

            for (var i in response) {

                let estadoBadge = response[i].estado === "activo"
                    ? '<span class="label label-success">Activo</span>'
                    : '<span class="label label-danger">Inactivo</span>';

                fila += '<tr>' +
                    '<td>' + (parseInt(i) + 1) + '</td>' +
                    '<td>' + response[i].descripcion + '</td>' +
                    '<td>' + estadoBadge + '</td>' +
                    '<td>' +
                    '<div class="btn-group">' +
                    '<button class="btn btn-warning btnModificar" id="' + response[i].id + '" data-toggle="modal" data-target="#modalModificar"><i class="fa fa-pencil"></i></button>' +
                    '<button class="btn btn-danger btnEliminar" id="' + response[i].id + '"><i class="fa fa-times"></i></button>' +
                    '</div>' +
                    '</td>' +
                    '</tr>';
            }

            $("#tabla tbody").append(fila);

            $(".btnModificar").click(function () {
                obtenerDatosParaModificar(this.id);
            });

            $(".btnEliminar").click(function () {

                var id_registro = this.id;

                swal({
                    title: '¿Está seguro de eliminar la categoría?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar'
                }).then(function (result) {
                    if (result.value) {
                        eliminarDatos(id_registro);
                    }
                });

            });

        }
    }).fail(function () {
        swal("Error", "No se pudo cargar la lista", "error");
    });

}


// ===========================================
// AGREGAR
// ===========================================

function agregarDatos() {

    $.ajax({
        url: "../api-mensajeria/categoria/funAgregar.php",
        method: "POST",
        data: {
            descripcionAgregar: $("#formAgregarCategoria input[name='descripcionAgregar']").val()
        },
        dataType: "json",
        success: function (response) {

            if (response.mensaje === "ok") {
                swal("Correcto", "Categoría creada con éxito", "success")
                    .then(() => location.reload());
            }

            if (response.mensaje === "registro_existente") {
                swal("Error", "La categoría ya existe", "error");
            }

            if (response.mensaje === "nok") {
                swal("Error", "Error al guardar", "error");
            }

        }
    }).fail(function () {
        swal("Error", "Error en el servidor", "error");
    });

}


// ===========================================
// TRAER DATOS PARA MODIFICAR
// ===========================================

function obtenerDatosParaModificar(id) {

    $.ajax({
        url: "../api-mensajeria/categoria/funDatosParaModificar.php",
        method: "POST",
        data: JSON.stringify({ id: id }),
        contentType: "application/json",
        dataType: "json",
        success: function (response) {

            for (var i in response) {
                $("#descripcionModificar").val(response[i].descripcion);
                $("#idModificar").val(response[i].id);
            }

        }
    }).fail(function () {
        swal("Error", "No se pudieron cargar los datos", "error");
    });

}


// ===========================================
// MODIFICAR
// ===========================================

function modificarDatos() {

    $.ajax({
        url: "../api-mensajeria/categoria/funModificar.php",
        method: "POST",
        data: {
            descripcionModificar: $("#descripcionModificar").val(),
            idModificar: $("#idModificar").val()
        },
        dataType: "json",
        success: function (response) {

            if (response.mensaje === "ok") {
                swal("Correcto", "Categoría modificada", "success")
                    .then(() => location.reload());
            }

            if (response.mensaje === "repetido") {
                swal("Error", "Ya existe otra categoría con esa descripción", "error");
            }

            if (response.mensaje === "nok") {
                swal("Error", "Error al modificar", "error");
            }

        }
    }).fail(function () {
        swal("Error", "Error en el servidor", "error");
    });

}


// ===========================================
// ELIMINAR
// ===========================================

function eliminarDatos(id) {

    $.ajax({
        url: "../api-mensajeria/categoria/funEliminar.php",
        method: "POST",
        data: JSON.stringify({ id: id }),
        contentType: "application/json",
        dataType: "json",
        success: function (response) {

            if (response.mensaje === "ok") {
                swal("Eliminado", "Categoría eliminada", "success")
                    .then(() => location.reload());
            }

            if (response.mensaje === "nok") {
                swal("Error", "No se pudo eliminar", "error");
            }

        }
    }).fail(function () {
        swal("Error", "Error en el servidor", "error");
    });

}