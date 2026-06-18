$(document).ready(function () {

    $("#ingresarLogin").click(function () {

        let usuario = $("#usuario").val().trim();
        let password = $("#contrasena").val();

        if (usuario === "" || password === "") {
            Swal.fire({
                icon: "warning",
                title: "Complete todos los campos"
            });
            return;
        }

        $.ajax({
            url: "../api-rma/sesiones/funLogin.php",
            type: "POST",
            dataType: "json",
            data: {
                usuario: usuario,
                password: password
            },
            success: function (response) {

                if (response.success) {

                    Swal.fire({
                        icon: "success",
                        title: "Bienvenido",
                        text: response.nombre
                    }).then(() => {

                        // Modificado para coincidir con el Router de plantilla.php (?ruta=XXX)
                        window.location.href = "index.php?ruta=" + response.redirect;

                    });

                } else {

                    Swal.fire({
                        icon: "error",
                        title: "Acceso inválido",
                        text: response.message
                    });

                }

            },
            error: function (xhr, status, error) {
                console.error(error);
                Swal.fire({
                    icon: "error",
                    title: "Error de conexión"
                });
            }
        });

    });

});