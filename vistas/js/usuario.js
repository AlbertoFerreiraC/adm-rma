$(document).ready(function () {

  cargarUsuarios();

  // ==========================
  // FILTRO DINÁMICO
  // ==========================
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

  // ==========================
  // AGREGAR
  // ==========================
  $("#formAgregarUsuario").submit(function (e) {
    e.preventDefault();
    agregarUsuario();
  });

  // ==========================
  // MODIFICAR
  // ==========================
  $("#formModificarUsuario").submit(function (e) {
    e.preventDefault();
    modificarUsuario();
  });

});

/* ==========================================
   LISTAR USUARIOS
========================================== */
function cargarUsuarios() {

  $("#tabla tbody").empty();
  let fila = "";

  $.ajax({
    url: "../api-mensajeria/usuario/funListar.php",
    method: "GET",
    dataType: "json",
    success: function (response) {

      for (let i in response) {

        let estadoBadge = response[i].estado === "activo"
          ? '<span class="label label-success">Activo</span>'
          : '<span class="label label-danger">Inactivo</span>';

        fila += '<tr>' +
          '<td>' + (parseInt(i) + 1) + '</td>' +
          '<td>' + response[i].nombre + '</td>' +
          '<td>' + estadoBadge + '</td>' +
          '<td>' +
          '<div class="btn-group">' +
          '<button class="btn btn-warning btnModificar" data-id="' + response[i].idusuario + '" data-toggle="modal" data-target="#modalModificar"><i class="fa fa-pencil"></i></button>' +
          '<button class="btn btn-danger btnEliminar" data-id="' + response[i].idusuario + '"><i class="fa fa-times"></i></button>' +
          '</div>' +
          '</td>' +
          '</tr>';
      }

      $("#tabla tbody").append(fila);

      // BOTÓN MODIFICAR
      $(".btnModificar").click(function () {
        let id = $(this).data("id");
        obtenerUsuario(id);
      });

      // BOTÓN ELIMINAR (INACTIVAR)
      $(".btnEliminar").click(function () {

        let id = $(this).data("id");

        swal({
          title: '¿Está seguro de eliminar el usuario?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar'
        }).then(function (result) {
          if (result.value) {
            eliminarUsuario(id);
          }
        });

      });

    }
  }).fail(function () {
    swal("Error", "No se pudo cargar la lista", "error");
  });

}

/* ==========================================
   AGREGAR USUARIO
========================================== */
function agregarUsuario() {

  $.ajax({
    url: "../api-mensajeria/usuario/funAgregar.php",
    method: "POST",
    data: {
      nombreAgregar: $("input[name='nombreAgregar']").val()
    },
    dataType: "json",
    success: function (response) {

      if (response.mensaje === "ok") {
        swal("Correcto", "Usuario creado con éxito", "success")
          .then(() => location.reload());
      }

      if (response.mensaje === "repetido") {
        swal("Error", "El usuario ya existe", "error");
      }

      if (response.mensaje === "nok") {
        swal("Error", "Error al guardar", "error");
      }

    }
  }).fail(function () {
    swal("Error", "Error en el servidor", "error");
  });

}

/* ==========================================
   OBTENER DATOS PARA MODIFICAR
========================================== */
function obtenerUsuario(id) {

  $.ajax({
    url: "../api-mensajeria/usuario/funDatosParaModificar.php",
    method: "POST",
    data: JSON.stringify({ id: id }),
    contentType: "application/json",
    dataType: "json",
    success: function (response) {

      let usuario = response[0];

      $("#idUsuarioModificar").val(usuario.idusuario);
      $("#nombreModificar").val(usuario.nombre);

    }
  }).fail(function () {
    swal("Error", "No se pudieron cargar los datos", "error");
  });

}

/* ==========================================
   MODIFICAR USUARIO
========================================== */
function modificarUsuario() {

  $.ajax({
    url: "../api-mensajeria/usuario/funModificar.php",
    method: "POST",
    data: {
      idModificar: $("#idUsuarioModificar").val(),
      nombreModificar: $("#nombreModificar").val()
    },
    dataType: "json",
    success: function (response) {

      if (response.mensaje === "ok") {
        swal("Correcto", "Usuario modificado", "success")
          .then(() => location.reload());
      }

      if (response.mensaje === "repetido") {
        swal("Error", "Ya existe otro usuario con ese nombre", "error");
      }

      if (response.mensaje === "nok") {
        swal("Error", "Error al modificar", "error");
      }

    }
  }).fail(function () {
    swal("Error", "Error en el servidor", "error");
  });

}

/* ==========================================
   ELIMINAR (INACTIVAR)
========================================== */
function eliminarUsuario(id) {

  $.ajax({
    url: "../api-mensajeria/usuario/funEliminar.php",
    method: "POST",
    data: JSON.stringify({ id: id }),
    contentType: "application/json",
    dataType: "json",
    success: function (response) {

      if (response.mensaje === "ok") {
        swal("Eliminado", "Usuario eliminado correctamente", "success")
          .then(() => location.reload());
      }

      if (response.mensaje === "nok") {
        swal("Error", "No se pudo eliminar el usuario", "error");
      }

    }
  }).fail(function () {
    swal("Error", "Error en el servidor", "error");
  });

}