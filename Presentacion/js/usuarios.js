const validar = { //objeto
    persona: false,
    email: false,
    password: false
};
$(function() {
    listar();

    //select 2
    $('#persona').each(function() {
        $(this).select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple')
        });
    });

    //abrir modal con el boton
    $('.btn_modal').on('click', function(e) {
        e.preventDefault(); //no actualiza pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        $('#modal_h5').html('Agregar');
        $('#modelo').val('agregar');
        $('#modal_usuario').modal('show'); //abrir modal
        mostrar_clave();
    });

    //cerrar modal
    $('#modal_usuario').on('hidden.bs.modal', function(e) {
        e.preventDefault(); //no actualiza pagina
        $('#persona').select2("val", " ");
        $('#form-data').trigger("reset");
        $('#errorpersona').html('');
        $('#erroremail').html('');
        $('#errorpassword').html('');
        $('#dni_editar').val('');
        input_validar('error', '#email');
        input_validar('error', '#password');
        $('#password').attr("required", true);
        $('#persona').prop("disabled", false);
        validar.persona = false;
        validar.email = false;
        validar.password = false;
    });

    //validar dni que no tenga usuario
    //evento al seleccionar cliente
    $('#persona').on('change', function(e) {
        e.preventDefault(); //no actualiza pagina
        const persona = this.value;
        if (persona === '') { //no hay seleccion
            $('#errorpersona').html('* Selección Obligatorio');
            validar.persona = false;
        } else { //seleccionado
            if ($('#dni_editar').val() === '') { //no es agregar
                $.post('C_validar_usuario.php', { tipo: 'dni', valor: persona }, (data) => { //obtener datos
                    if (data.respuesta === 'existe') {
                        validar.persona = false;
                        $('#errorpersona').html('* Persona existente');
                    } else {
                        validar.persona = true;
                        $('#errorpersona').html('');
                    }
                }, "json");
            }
        }
    });

    //validar usuario que no exista
    $('#email').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        const email = $(this).val().replace(/ /g, ""); //eliminar espacios en blanco
        const caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
        $(this).val(email);
        if (caract.test(email) == false) {
            input_validar('error', this);
            $('#erroremail').html('');
            validar.email = false;
        } else {
            $.post('C_validar_usuario.php', { tipo: 'email', valor: $('#dni_editar').val(), email: email }, (data) => {
                if (data.respuesta === 'existe') {
                    input_validar('error', this);
                    $('#erroremail').html('* Email existente');
                    validar.email = false;
                } else {
                    input_validar('correcto', this);
                    $('#erroremail').html('');
                    validar.email = true;
                }
            }, "json");
        }
    });

    //validar password
    $('#password').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        const password = $(this).val().replace(/ /g, "");
        this.value = password //editar espacios
        if (password === '') {
            if ($('#dni_editar').val().length === 8) { //editar contraseña si no hay datos
                input_validar('error', this);
                $('#errorpassword').html('');
                validar.password = true;
            } else {
                input_validar('error', this);
                $('#errorpassword').html('');
                validar.password = false;
            }
        } else if (!validar_clave(password)) {
            input_validar('error', this);
            $('#errorpassword').html(`* Mayor a 5 caracteres incluir: numeros <br> letras(mayusculas y minusculas)`);
            validar.password = false;
        } else {
            input_validar('correcto', this);
            $('#errorpassword').html('');
            validar.password = true;
        }
    });

    //guardar datos
    $('#form-data').on('submit', function(e) {
        e.preventDefault(); //no actualiza pagina
        if (validar.persona === true && validar.email === true && validar.password === true) {
            $.post($(this).attr('action'), $(this).serialize(), (data) => { //retornando validacion
                Swal.fire({ //agregado
                    icon: 'success',
                    title: `Usuario ${data.tipo}`,
                    showConfirmButton: false,
                    timer: 1000
                }).then(function() {
                    $('#modal_usuario').modal('hide');
                    listar(); //listar
                });
            }, "json");
        } else {
            Swal.fire(
                'Error!',
                'Corregir Errores!',
                'error'
            );
        }
    });

    //llenar los datos para editar 
    $(document).on('click', '.btn_editar', function(e) {
        e.preventDefault(); //no actualiza pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        $("#carga").show();
        $.post('B_modelo-usuario.php', { modelo: 'listar', dni: $(this).data("dni") }, (data) => { //retornando validacion
            $('#modal_h5').html('Editar');
            $('#modelo').val('editar');
            mostrar_clave();
            input_validar('correcto', '#email');
            $('#dni_editar').val(data[0].dni);
            $('#persona').val(data[0].dni).trigger('change');
            $('#persona').prop("disabled", true);
            $('#email').val(data[0].email); //establecer valor
            $('#password').attr("required", false);
            $("#carga").fadeOut(500, function() {
                $('#modal_usuario').modal('show'); //abrir modal
            });
            validar.persona = true;
            validar.email = true;
            validar.password = true;
        }, "json");
    });

    //eliminar usuario
    $(document).on('click', '.btn_eliminar', function(e) {
        e.preventDefault(); //no actualiza pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        Swal.fire({
            title: '¿Estas seguro(a)?',
            text: "Esta acción no se puede revertir!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si! Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const modelo = 'eliminar';
                const dni = $(this).data("dni");
                $.post('B_modelo-usuario.php', { modelo, dni }, (data) => { //respuesta
                    Swal.fire({ //agregado
                        icon: 'success',
                        title: `Usuario Eliminado`,
                        showConfirmButton: false,
                        timer: 1000
                    }).then(function() {
                        listar(); //listar
                    });
                });
            }
        });
    });
});

//funciones
function listar() {
    $.post('B_modelo-usuario.php', { modelo: 'listar', dni: '%' }, (data) => {
        $('#tabla_datos').dataTable({ //lenamos datos
            'paging': false,
            'lengthChange': false,
            'searching': false,
            'destroy': true,
            'processing': true,
            language: { //español
                "sProcessing": "Procesando...",
                "sInfo": "Total de ususarios: _TOTAL_ ",
                "sInfoEmpty": "0 Registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sLoadingRecords": "Cargando...",
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "drawCallback": function() { //paginador
                $('[data-toggle="tooltip"]').tooltip(); //mensaje
            },
            data: data, //datos
            columns: [
                { data: "dni", className: "align-middle" },
                { data: "nombres", className: "align-middle" },
                {
                    data: null,
                    render: function(data) {
                        return `${data.paterno} ${data.materno}`;
                    },
                    className: "align-middle"
                },
                { data: "email", className: "align-middle" },
                {
                    data: null,
                    render: function(data) {
                        return `<button class="btn btn-success btn-sm btn_editar" data-toggle="tooltip" 
                                data-placement="left" title="Editar" data-dni="${data.dni}">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn btn-danger btn-sm btn_eliminar" data-toggle="tooltip" 
                                data-placement="left" title="Eliminar" data-dni="${data.dni}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>`;
                    }
                }
            ]
        });
    }, "json");
}

//mostrar clave
function mostrar_clave() {
    var x = document.getElementById("password"); //obtener valor de input(password)
    if ($('#check').prop('checked')) {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

//validar input
function input_validar(tipo, id) {
    if (tipo === 'correcto') {
        $(id).removeClass('is-invalid');
        $(id).addClass('is-valid');
    } else {
        $(id).removeClass('is-valid');
        $(id).addClass('is-invalid');
    }
}

//crear contraseña
function validar_clave(contrasenna) { //CONTRASEÑA
    if (contrasenna.length >= 5) {
        var mayuscula = false;
        var minuscula = false;
        var numero = false;

        for (var i = 0; i < contrasenna.length; i++) {
            if (contrasenna.charCodeAt(i) >= 65 && contrasenna.charCodeAt(i) <= 90) //CODIGO ASCII MAYUCULAS
            {
                mayuscula = true;
            } else if (contrasenna.charCodeAt(i) >= 97 && contrasenna.charCodeAt(i) <= 122) //minusculas
            {
                minuscula = true;
            } else if (contrasenna.charCodeAt(i) >= 48 && contrasenna.charCodeAt(i) <= 57) //letras
            {
                numero = true;
            }
        }
        if (mayuscula === true && minuscula === true && numero === true) {
            return true;
        }
    }
    return false;
}