$(document).ready(function () {

    cargarDatosTabla();
    cargarCategoriasSelect();

    // =============================
    // SUBMIT AGREGAR
    // =============================
    $("#formAgregarProducto").submit(function (e) {
        e.preventDefault();
        agregarProducto();
    });

    // =============================
    // SUBMIT MODIFICAR
    // =============================
    $("#formModificarProducto").submit(function (e) {
        e.preventDefault();
        modificarProducto();
    });

    // =============================
    // FILTRO DINÁMICO
    // =============================
    $("#filtradoDinamico").keyup(function () {

        let texto = $(this).val().toLowerCase();

        $("#tabla tbody tr").each(function () {

            if ($(this).text().toLowerCase().indexOf(texto) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }

        });

    });

});


// ===========================================
// LISTAR PRODUCTOS
// ===========================================

function cargarDatosTabla() {

    $("#tabla tbody").empty();
    let fila = "";

    $.ajax({
        url: "../api-mensajeria/producto/funListar.php",
        method: "GET",
        dataType: "json",
        success: function (response) {

            for (let i in response) {

                let estadoBadge = response[i].estado === "activo"
                    ? '<span class="label label-success">Activo</span>'
                    : '<span class="label label-danger">Inactivo</span>';

                fila += '<tr>' +
                    '<td>' + (parseInt(i) + 1) + '</td>' +
                    '<td><img src="' + response[i].url_imagen + '" style="width:60px;border-radius:8px;"></td>' +
                    '<td>' + response[i].descripcion + '</td>' +
                    '<td>Gs. ' + parseFloat(response[i].precio_lista).toLocaleString() + '</td>' +
                    '<td>' + estadoBadge + '</td>' +
                    '<td>' +
                    '<div class="btn-group">' +
                    '<button class="btn btn-warning btnModificar" data-id="' + response[i].id + '" data-toggle="modal" data-target="#modalModificar"><i class="fa fa-pencil"></i></button>' +
                    '<button class="btn btn-danger btnEliminar" data-id="' + response[i].id + '"><i class="fa fa-times"></i></button>' +
                    '</div>' +
                    '</td>' +
                    '</tr>';
            }

            $("#tabla tbody").append(fila);

            // BOTÓN EDITAR
            $(".btnModificar").click(function () {
                let id = $(this).data("id");
                obtenerDatosParaModificar(id);
            });

            // BOTÓN ELIMINAR (SOFT DELETE)
            $(".btnEliminar").click(function () {

                let id = $(this).data("id");

                swal({
                    title: '¿Está seguro de desactivar el producto?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, desactivar'
                }).then(function (result) {
                    if (result.value) {
                        eliminarProducto(id);
                    }
                });

            });

        }
    }).fail(function () {
        swal("Error", "No se pudo cargar la lista", "error");
    });

}

// ===========================================
// CARGAR CATEGORÍAS EN SELECT
// ===========================================
function cargarCategoriasSelect() {

    $.ajax({
        url: "../api-mensajeria/producto/funListarCategoria.php",
        method: "GET",
        dataType: "json",
        success: function (response) {

            let options = '<option value="">Seleccione categoría</option>';

            response.forEach(function (cat) {
                options += '<option value="' + cat.id + '">' + cat.descripcion + '</option>';
            });

            $("#categoriaAgregar").html(options);
            $("#categoriaModificar").html(options);
        },
        error: function (xhr) {
            console.log("Error:", xhr.responseText);
        }
    });

}

// ===========================================
// AGREGAR PRODUCTO
// ===========================================
function agregarProducto() {

    let formData = new FormData($("#formAgregarProducto")[0]);

    $.ajax({
        url: "../api-mensajeria/producto/funAgregar.php",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {

            if (response.mensaje === "ok") {
                swal("Correcto", "Producto creado con éxito", "success")
                    .then(() => location.reload());
            }

            if (response.mensaje === "registro_existente") {
                swal("Error", "El producto ya existe", "error");
            }

            if (response.mensaje === "formato_invalido") {
                swal("Error", "Formato de imagen no permitido", "error");
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
// OBTENER DATOS PARA MODIFICAR
// ===========================================
function obtenerDatosParaModificar(id) {

    $.ajax({
        url: "../api-mensajeria/producto/funDatosParaModificar.php",
        method: "POST",
        data: JSON.stringify({ id: id }),
        contentType: "application/json",
        dataType: "json",
        success: function (response) {

            if (response.length > 0) {

                let producto = response[0];

                $("#idModificar").val(producto.id);
                $("#categoriaModificar").val(producto.categoria);
                $("#descripcionModificar").val(producto.descripcion);
                $("#precio_listaModificar").val(producto.precio_lista);

                $("#previewModificar").attr("src", producto.url_imagen);
            }

        },
        error: function () {
            swal("Error", "No se pudieron cargar los datos", "error");
        }
    });

}

// ===========================================
// MODIFICAR PRODUCTO
// ===========================================
function modificarProducto() {

    let formData = new FormData($("#formModificarProducto")[0]);

    $.ajax({
        url: "../api-mensajeria/producto/funModificar.php",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {

            if (response.mensaje === "ok") {
                swal("Correcto", "Producto modificado correctamente", "success")
                    .then(() => location.reload());
            }

            if (response.mensaje === "repetido") {
                swal("Error", "Ya existe otro producto con ese código", "error");
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
// ELIMINAR (SOFT DELETE)
// ===========================================
function eliminarProducto(id) {

    $.ajax({
        url: "../api-mensajeria/producto/funEliminar.php",
        method: "POST",
        data: JSON.stringify({ id: id }),
        contentType: "application/json",
        dataType: "json",
        success: function (response) {

            if (response.mensaje === "ok") {
                swal("Desactivado", "Producto desactivado correctamente", "success")
                    .then(() => location.reload());
            }

            if (response.mensaje === "nok") {
                swal("Error", "No se pudo desactivar", "error");
            }

        }
    }).fail(function () {
        swal("Error", "Error en el servidor", "error");
    });

}