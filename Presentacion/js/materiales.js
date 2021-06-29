const validar = { //objeto
    nombre: false,
    und: false,
    // precio_v1: true,
    precio_c1: true,
    precio_c2: true,
    precio_c3: true
};
$(function() {
    listar();
    //abrir modal
    $('#btn-modal-material').on('click', function(e) {
        e.preventDefault(); //no actualiza pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        $('#modal_h5').html('Agregar');
        $('#modelo').val('agregar');
        $('#modal_material').modal('show'); //abrir modal
    });

    //cerrar modal
    $('#modal_material').on('hidden.bs.modal', function(e) {
        e.preventDefault(); //evitar actualiza pagina
        $('#form-data').trigger("reset");
        $('#errornombre').html('');
        $('#errorprecio_v1').html('');
        $('#errorprecio_c1').html('');
        $('#errorprecio_c2').html('');
        $('#errorprecio_c3').html('');
        $('#id').val('');
        input_validar('error', '#nombre');
        input_validar('error', '#precio_v1');
        input_validar('error', '#precio_c1');
        input_validar('error', '#precio_c2');
        input_validar('error', '#precio_c3');
        validar.nombre = false;
        validar.und = false;
        validar.precio_v1 = true;
        validar.precio_c1 = true;
        validar.precio_c2 = true;
        validar.precio_c3 = true;
    });

    //inicio del modal material
    $('#modal_material').on('shown.bs.modal', function(e) {
        e.preventDefault(); //no cargar pagina
        $('#nombre').trigger('focus');
    });

    //validar nombre
    $('#nombre').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        let nombre = (this.value + '').replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ´. ]/g, '');
        this.value = nombre.toUpperCase();
        if (nombre.replace(/ /g, "").length >= 3 && nombre.replace(/ /g, "").length <= 20) {
            $.post('C_validar_material.php', { material: nombre.toUpperCase(), id: $('#id').val() }, (data) => { //obtener datos
                if (data.respuesta === 'existe') {
                    input_validar('error', this);
                    $('#errornombre').html('* Material existente');
                    validar.nombre = false;
                } else {
                    input_validar('correcto', this);
                    $('#errornombre').html('');
                    validar.nombre = true;
                }
            }, "json");
        } else {
            input_validar('error', this);
            $('#errornombre').html('* Nombre incorrecto');
            validar.nombre = false;
        }
    });

    //validar select
    $("#und").change(function(e) {
        e.preventDefault(); //no actualiza pagina
        if (this.value.length > 0) {
            validar.und = true;
        }
    });

    //validar precio venta
    // $('#precio_v1').keyup(function(e) {
    //     e.preventDefault(); //no actualiza pagina
    //     const estado = validar_precio(this, '#errorprecio_v1');
    //     if (estado) {
    //         validar.precio_v1 = true;
    //     } else {
    //         validar.precio_v1 = false;
    //     }
    // });

    // $('#precio_v1').change(function(e) {
    //     e.preventDefault(); //no actualiza pagina
    //     const estado = validar_precio(this, '#errorprecio_v1');
    //     if (estado) {
    //         validar.precio_v1 = true;
    //     } else {
    //         validar.precio_v1 = false;
    //     }
    // });

    //calidar precio compra 1
    $('#precio_c1').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        const estado = validar_precio(this, '#errorprecio_c1');
        if (estado) {
            validar.precio_c1 = true;
        } else {
            validar.precio_c1 = false;
        }
    });
    $('#precio_c1').change(function(e) {
        e.preventDefault(); //no actualiza pagina
        const estado = validar_precio(this, '#errorprecio_c1');
        if (estado) {
            validar.precio_c1 = true;
        } else {
            validar.precio_c1 = false;
        }
    });

    //calidar precio compra 2
    $('#precio_c2').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        const estado = validar_precio(this, '#errorprecio_c2');
        if (estado) {
            validar.precio_c2 = true;
        } else {
            validar.precio_c2 = false;
        }
    });
    $('#precio_c2').change(function(e) {
        e.preventDefault(); //no actualiza pagina
        const estado = validar_precio(this, '#errorprecio_c2');
        if (estado) {
            validar.precio_c2 = true;
        } else {
            validar.precio_c2 = false;
        }
    });

    //calidar precio compra 3
    $('#precio_c3').keyup(function(e) {
        e.preventDefault(); //no actualiza pagina
        const estado = validar_precio(this, '#errorprecio_c3');
        if (estado) {
            validar.precio_c3 = true;
        } else {
            validar.precio_c3 = false;
        }
    });
    $('#precio_c3').change(function(e) {
        e.preventDefault(); //no actualiza pagina
        const estado = validar_precio(this, '#errorprecio_c3');
        if (estado) {
            validar.precio_c3 = true;
        } else {
            validar.precio_c3 = false;
        }
    });

    //guardar datos
    $('#form-data').submit(function(e) {
        e.preventDefault(); //no actualiza la pagina
        if (validar.nombre === true && validar.und === true && validar.precio_c1 === true && validar.precio_c2 === true && validar.precio_c3 === true) {
            $.post($(this).attr('action'), $(this).serialize(), (data) => { //retornando validacion
                Swal.fire({ //agregado
                    icon: 'success',
                    title: `Material ${data.tipo}`,
                    showConfirmButton: false,
                    timer: 1000
                }).then(function() {
                    $('#modal_material').modal('hide');
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

    //editar cliente
    $(document).on('click', '.btn_editar', function(e) {
        e.preventDefault(); //no actualiza pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        $("#carga").show();
        $.post('B_modelo-material.php', { modelo: 'listar', id: $(this).data("id") }, (data) => { //retornando validacion
            $('#modal_h5').html('Editar');
            $('#modelo').val('editar');
            input_validar('correcto', '#nombre');
            // input_validar('correcto', '#precio_v1');
            input_validar('correcto', '#precio_c1');
            input_validar('correcto', '#precio_c2');
            input_validar('correcto', '#precio_c3');
            $('#id').val(data[0].id); //establecer valor
            $('#nombre').val(data[0].nombre);
            $('#und').val(data[0].und);
            // $('#precio_v1').val(data[0].precio_v1);
            $('#precio_c1').val(data[0].precio_c1);
            $('#precio_c2').val(data[0].precio_c2);
            $('#precio_c3').val(data[0].precio_c3);
            $("#carga").fadeOut(500, function() {
                $('#modal_material').modal('show'); //abrir modal
            });
            validar.nombre = true;
            validar.und = true;
            // validar.precio_v1 = true;
            validar.precio_c1 = true;
            validar.precio_c2 = true;
            validar.precio_c3 = true;
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
                $.post('B_modelo-material.php', { modelo: 'eliminar', id: $(this).data("id") }, (data) => { //respuesta
                    Swal.fire({ //agregado
                        icon: 'success',
                        title: `Material Eliminado`,
                        showConfirmButton: false,
                        timer: 1000
                    }).then(function() {
                        listar(); //listar
                    });
                });
            }
        });
    });

    //eliminar cliente
    $(document).on('click', '.btn_reporte', function(e) {
        $(this).blur(); //quitar focus
        window.open('D_pdf_material.php');
    });

});

function listar() {
    $.post('B_modelo-material.php', { modelo: 'listar', id: '%' }, (data) => { //retornando validacion
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
                { data: "nombre", className: "align-middle" },
                {
                    data: null,
                    render: function(data) {
                        return `<span class="badge badge-info">${data.peso}</span>`;
                    },
                    className: "align-middle"
                },
                { data: "und", className: "align-middle" },
                // { data: "precio_v1", className: "align-middle" },
                { data: "precio_c1", className: "align-middle" },
                { data: "precio_c2", className: "align-middle" },
                { data: "precio_c3", className: "align-middle" },
                {
                    data: null,
                    render: function(data) {
                        return `<button class="btn btn-success btn-sm btn_editar" data-toggle="tooltip" 
                                data-placement="left" title="Editar" data-id="${data.id}">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn btn-danger btn-sm btn_eliminar" data-toggle="tooltip" 
                                data-placement="left" title="Eliminar" data-id="${data.id}">
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

// validar precio venta funcion
function validar_precio(input, error) {
    let estado = true;
    const precio = input.value;
    if (precio === '') {
        input_validar('error', input);
        $(error).html('');
        estado = true;
    } else if (precio >= 0 && precio < 91) {
        input_validar('correcto', input);
        $(error).html('');
        estado = true;
    } else {
        input_validar('error', input);
        $(error).html('* Precio Incorrecto');
        estado = false;
    }
    return estado;
}