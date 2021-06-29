const validar = { //objeto
    dni: false,
    nombre: false,
    paterno: false,
    materno: false,
    celular: true,
};
$(function() {
    listar();
    //abrir modal
    $('#btn-modal-cliente').on('click', function(e) {
        e.preventDefault(); //no actualiza pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        $('#modal_h5').html('Agregar');
        $('#modelo').val('agregar');
        $('#modal_cliente').modal('show'); //abrir modal
    });

    //inicio del modal clientes
    $('#modal_cliente').on('shown.bs.modal', e => {
        e.preventDefault(); //no cargar pagina
        $('#dni').trigger('focus');
    });

    //cerrar modal clientes
    $('#modal_cliente').on('hidden.bs.modal', e => {
        e.preventDefault(); //evitar actualiza pagina
        $('#form-data-cliente').trigger("reset");
        $('#errordni').html('');
        $('#errornombre').html('');
        $('#errorpaterno').html('');
        $('#errormaterno').html('');
        $('#errorcelular').html('');
        $('#id').val('');
        input_validar('error', '#dni');
        input_validar('error', '#nombre');
        input_validar('error', '#paterno');
        input_validar('error', '#materno');
        input_validar('error', '#celular');
        validar.dni = false;
        validar.nombre = false;
        validar.paterno = false;
        validar.materno = false;
        validar.celular = true;
    });

    //validar dni
    $('#dni').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        const dni = (this.value + '').replace(/[^0-9]/g, '');
        this.value = dni;
        const id = $('#id').val();
        if (dni.length === 8) {
            $.post('C_validar_cliente.php', { dni, id }, (data) => { //obtener datos
                if (data.respuesta === 'existe') {
                    input_validar('error', this);
                    $('#errordni').html('* DNI existente');
                    validar.dni = false;
                } else {
                    input_validar('correcto', this);
                    $('#errordni').html('');
                    validar.dni = true;
                }
            }, "json");
        } else {
            input_validar('error', this);
            $('#errordni').html('* DNI incorrecto');
            validar.dni = false;
        }
    });

    //validar nombre
    $('#nombre').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        let nombre = (this.value + '').replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ´ ]/g, '');
        this.value = nombre.toUpperCase();
        if (nombre.replace(/ /g, "").length >= 3 && nombre.replace(/ /g, "").length <= 30) { //sin espacios
            input_validar('correcto', this);
            $('#errornombre').html('');
            validar.nombre = true;
        } else {
            input_validar('error', this);
            $('#errornombre').html('* Nombre(s) incorrecto');
            validar.nombre = false;
        }
    });

    //validar paterno
    $('#paterno').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        let paterno = (this.value + '').replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ´ ]/g, '');
        this.value = paterno.toUpperCase();
        if (paterno.replace(/ /g, "").length >= 3 && paterno.replace(/ /g, "").length <= 30) {
            input_validar('correcto', this);
            $('#errorpaterno').html('');
            validar.paterno = true;
        } else {
            input_validar('error', this);
            $('#errorpaterno').html('* Apellido paterno incorrecto');
            validar.paterno = false;
        }
    });

    //validar materno
    $('#materno').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        let materno = (this.value + '').replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ´ ]/g, '');
        this.value = materno.toUpperCase();
        if (materno.replace(/ /g, "").length >= 3 && materno.replace(/ /g, "").length <= 30) {
            input_validar('correcto', this);
            $('#errormaterno').html('');
            validar.materno = true;
        } else {
            input_validar('error', this);
            $('#errormaterno').html('* Apellido materno incorrecto');
            validar.materno = false;
        }
    });

    //validar celular
    $('#celular').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        const celular = (this.value + '').replace(/[^0-9]/g, '');
        this.value = celular;
        if (celular.length === 0) {
            input_validar('error', this);
            $('#errorcelular').html('');
            validar.celular = true;
        } else if (celular.length === 9) {
            input_validar('correcto', this);
            $('#errorcelular').html('');
            validar.celular = true;
        } else {
            input_validar('error', this);
            $('#errorcelular').html('* Celular incorrecto');
            validar.celular = false;
        }
    });

    //guardar datos
    $('#form-data-cliente').submit(function(e) {
        e.preventDefault(); //no actualiza la pagina
        if (validar.dni === true && validar.nombre === true && validar.paterno === true && validar.materno === true && validar.celular === true) { //todo correcto
            $.post($(this).attr('action'), $(this).serialize(), (data) => { //retornando validacion
                Swal.fire({ //alerta
                    icon: 'success',
                    title: `Cliente ${data.tipo}`,
                    showConfirmButton: false,
                    timer: 1000
                }).then(function() {
                    $('#modal_cliente').modal('hide');
                    listar(); //listar
                });
            }, "json");
        } else { //error
            Swal.fire(
                'Error!',
                'Corregir Errores!',
                'error'
            );
        }
    });

    //editar cliente
    $(document).on('click', '.btn_editar', function(e) {
        e.preventDefault(); //no actualiza pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        $("#carga").show();
        $.post('B_modelo-cliente.php', { modelo: 'listar', dni: $(this).data("dni") }, (data) => { //retornando validacion
            $('#modal_h5').html('Editar');
            $('#modelo').val('editar');
            input_validar('correcto', '#dni');
            input_validar('correcto', '#nombre');
            input_validar('correcto', '#paterno');
            input_validar('correcto', '#materno');
            $('#id').val(data[0].id);
            $('#dni').val(data[0].dni);
            $('#nombre').val(data[0].nombres);
            $('#paterno').val(data[0].paterno);
            $('#materno').val(data[0].materno);
            if (data[0].celular !== 'nulo') {
                input_validar('correcto', '#celular');
                $('#celular').val(data[0].celular);
            }
            $("#carga").fadeOut(500, function() {
                $('#modal_cliente').modal('show'); //abrir modal
            });
            validar.dni = true;
            validar.nombre = true;
            validar.paterno = true;
            validar.materno = true;
            validar.celular = true;
        }, "json");
    });

    //eliminar cliente
    $(document).on('click', '.btn_eliminar', function(e) {
        e.preventDefault(); //no actualiza pagina
        $(this).blur(); //quitar focus
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
                $.post('B_modelo-cliente.php', { modelo: 'eliminar', dni: $(this).data("dni") }, (data) => { //respuesta
                    Swal.fire({ //agregado
                        icon: 'success',
                        title: `Cliente Eliminado`,
                        showConfirmButton: false,
                        timer: 1000
                    }).then(function() {
                        listar(); //listar
                    });
                });
            }
        });
    });

    //imprimir cliente
    $(document).on('click', '.btn_reporte', function(e) {
        $(this).blur(); //quitar focus
        window.open('D_pdf_clientes.php');
    });

});

//funciones
function listar() {
    $.post('B_modelo-cliente.php', { modelo: 'listar', dni: '' }, (data) => { //retornando validacion
        $('#tabla_datos').dataTable({ //lenamos datos
            'destroy': true,
            'processing': true,
            language: { //español
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": "",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "<i class='fas fa-chevron-right'></i>",
                    "sPrevious": "<i class='fas fa-chevron-left'></i>"
                },
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
                {
                    data: null,
                    render: function(data) {
                        return data.celular === 'nulo' ? '' : data.celular;
                    },
                    className: "align-middle"
                },
                {
                    data: null,
                    render: function(data) {
                        // Combinar campos
                        return "<span class='badge badge-info'>" + data.nro_asistencia + "</span>";
                    },
                    className: "align-middle"
                },
                { data: "ulti_compra", className: "align-middle" },
                {
                    data: null,
                    render: function(data) {
                        return `<button class="btn btn-success btn-sm btn_editar" data-toggle="tooltip" 
                                data-placement="left" title="Editar" data-dni="${data.dni}">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn btn-danger btn-sm btn_eliminar" data-toggle="tooltip" 
                                data-placement="left" title="Eliminar" data-dni="${data.dni}" data-usu="${data.dni}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>`;
                    }
                }
            ]
        });
    }, "json");
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