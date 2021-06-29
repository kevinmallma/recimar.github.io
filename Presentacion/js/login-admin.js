$(function() {
    $("#email").focus();
    //login submit
    $('#form-login').on('submit', function(e) {
        e.preventDefault(); //no actualiza la pagina
        const datos = $(this).serialize();
        const url = $(this).attr('action');
        $.post(url, datos, (data) => { //retornando validacion
            if (data.respuesta === 'exito') {
                Swal.fire(
                    'Login Exitoso',
                    `${data.email} se logueo correctamente`,
                    'success'
                );
                setTimeout(function() {
                    $(location).attr('href', 'Inicio.php');
                }, 2000);
            } else {
                Swal.fire(
                    'Error!',
                    'Password Incorrecto o Usuario No existente!',
                    'error'
                );
            }
        }, "json");
    });
});