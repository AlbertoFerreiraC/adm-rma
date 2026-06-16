$(document).ready(function () {

  $("#btnActualizar").click(function () {

    let idUsuario = $("#idUsuario").val();
    let passActual = $("#datoPassUsuarioActual").val();
    let passNueva = $("#datoPassUsuario").val();
    let passRepe = $("#datoPassUsuarioRepe").val();

    if (passActual === "" || passNueva === "" || passRepe === "") {
      swal("Atención", "Complete todos los campos", "warning");
      return;
    }

    if (passNueva !== passRepe) {
      swal("Error", "Las nuevas contraseñas no coinciden", "error");
      return;
    }

    let datos = {
      idusuario: idUsuario,
      passActual: btoa(passActual),
      passNueva: btoa(passNueva)
    };

    $.ajax({
      url: "../api-mensajeria/sesiones/funActualizarPassword.php",
      method: "POST",
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify(datos),
      success: function (response) {

        if (response.mensaje === "ok") {
          swal("Correcto", "Contraseña actualizada correctamente", "success")
            .then(() => location.reload());
        }

        if (response.mensaje === "pass_incorrecta") {
          swal("Error", "La contraseña actual es incorrecta", "error");
        }

      },
      error: function () {
        swal("Error", "Error en el servidor", "error");
      }

    });

  });

});