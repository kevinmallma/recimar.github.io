const validar = { //objeto
    dni: false,
    nombre: false,
    paterno: false,
    materno: false,
    celular: true,
    id_material: false,
    precio: false,
    peso: false
};
$(function() {
    codigo();
    listar_compra();

    //abrir modal cliente
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
            $('#errornombre').html('* Nombre incorrecto');
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
                    //llenar el select
                    var newOption = new Option($('#nombre').val() + ' ' + $('#paterno').val() + ' ' + $('#materno').val(), $('#dni').val(), false, false);
                    $('#dni_cliente').append(newOption).trigger('change'); //agregado al select
                    $('#dni_cliente').val($('#dni').val()).trigger('change'); //seleccionado el nuevo
                    $('#asistencia').val("0");
                    $('#celular_cliente').val($('#celular').val());
                    codigo($('#dni').val().substr(-20, 2)); //
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

    //select 2
    $('#dni_cliente').each(function() {
        $(this).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple')
        });
    });

    $('#id_material').each(function() {
        $(this).select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple')
        });
    });

    //obtener datos del clientes con select2
    //evento al seleccionar cliente
    $('#dni_cliente').on('change', function(e) {
        e.preventDefault(); //no actualiza pagina
        const dni = $(this).val();
        if (dni === '') {
            $('#asistencia').val('');
            $('#cliente').val('');
            $('#celular_cliente').val('');
            codigo();
        } else {
            $.post('B_modelo-cliente.php', { modelo: 'listar', dni: dni }, (data) => {
                $('#asistencia').val(data[0].nro_asistencia);
                $('#cliente').val(data[0].nombres + " " + data[0].paterno + " " + data[0].materno);
                $('#celular_cliente').val(data[0].celular);
                codigo(data[0].dni.substr(-20, 2));
            }, 'json');
        }
    });

    //abrir modal marerial para agregar
    $('#btn_modal_material').on('click', function(e) {
        e.preventDefault(); //no actualiza pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        $('#modal_material').modal('show'); //abrir modal
    });

    //inicio del modal material
    $('#modal_material').on('shown.bs.modal', e => {
        e.preventDefault(); //no cargar pagina
        $('#id_material').select2('open');
    });

    //cerrar modal material
    $('#modal_material').on('hidden.bs.modal', e => {
        e.preventDefault(); //evitar actualiza pagina
        $('#id_material').select2("val", " ");
        $('#form-data-material').trigger("reset");
        reset_modal_material();
        input_validar('error', '#peso');
        $('#errorid_material').html('');
        $('#errorprecio').html('');
        $('#errorpeso').html('');
        validar.id_material = false;
        validar.precio = false;
        validar.peso = false;
    });

    //evento al seleccionar material
    $('#id_material').on('change', function(e) {
        e.preventDefault(); //no actualiza pagina
        if ($(this).val() === '') { // si o selecciona nada
            reset_modal_material();
            $('#errorid_material').html('* Selección abligatorio');
            validar.id_material = false;
        } else {
            if ($(this).val() > 0) { ///mayor 1
                $.post('B_modelo-material.php', { modelo: 'listar', id: $(this).val() }, (data) => {
                    reset_modal_material(data[0].nombre, data[0].und, data[0].precio_c1, data[0].precio_c2, data[0].precio_c3);
                }, 'json');
                $('#errorid_material').html('');
                validar.id_material = true;
            }
        }
    });

    //marcar el precio
    $('.form-check-input').change(function(e) {
        e.preventDefault(); //no actualiza pagina
        if (parseFloat(this.value) == 0.00) {
            $('#errorprecio').html('* Precio incorrecto');
            validar.precio = false;
        } else {
            $('#errorprecio').html('');
            validar.precio = true;
            $('#peso').trigger('focus');
        }
    });

    //validar peso
    $('#peso').change(e => {
        e.preventDefault(); //no actualiza pagina
        const estado = validar_peso(e, '#errorpeso');
        if (estado) {
            validar.peso = true;
        } else {
            validar.peso = false;
        }
    });

    $('#peso').keyup(e => {
        e.preventDefault(); //no actualiza pagina
        const estado = validar_peso(e, '#errorpeso');
        if (estado) {
            validar.peso = true;
        } else {
            validar.peso = false;
        }
    });

    //agregar a tabla
    $('#form-data-material').submit(function(e) {
        e.preventDefault(); //no actualiza la pagina
        if (validar.id_material == true && validar.precio == true && validar.peso == true) {
            $.post($(this).attr('action'), $(this).serialize(), (data) => {
                $('#peso').val('');
                input_validar('error', '#peso');
                $('#peso').trigger('focus');
                listar_compra(); //listar
            });
        } else { //error
            Swal.fire(
                'Error!',
                'Corregir Errores!',
                'error'
            );
        }
    });

    $(document).on('keydown', '.peso', function(e) {
        if (e.which === 13) {
            e.preventDefault(); //no actualiza la pagina
            editar_peso(this.value, $(this).data("id"));
        }
    });

    $(document).on('keydown', '.precio', function(e) {
        if (e.which === 13) {
            e.preventDefault(); //no actualiza la pagina
            editar_precio(this.value, $(this).data("id"));
        }
    });

    $(document).on('keydown', '.descuento', e => {
        if (e.which === 13) {
            e.preventDefault(); //no actualiza la pagina
            listar_compra();
        }
    });

    //eliminar compra
    $(document).on('click', '.btn_eliminar', function(e) {
        e.preventDefault(); //no actualiza la pagina
        $.post('B_modelo-Ncompra.php', { modelo_compra: 'eliminar', id: $(this).data("id") }, (data) => { //respuesta
            listar_compra();
        });
    });

    $('#btn_cancelar').on('click', e => {
        e.preventDefault(); //no actualiza la pagina
        $(e.target).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        $.post('B_modelo-Ncompra.php', { modelo_compra: 'listar' }, (data) => { //respuesta
            //const datos = JSON.parse(data);
            if (data.length > 0) { // verefivar que tenga datos la tabla
                Swal.fire({
                    title: '¿Estas seguro(a) en cancelar la compra?',
                    text: "Esta acción no se puede revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) { // cancelar
                        $("#carga").show();
                        $.post('B_modelo-Ncompra.php', { modelo_compra: 'cancelar' }, (data) => { //cancelar toda la compra
                            $("#carga").fadeOut(500, function() {
                                $('#dni_cliente').val('').trigger('change'); //iniciar combox
                                $('#form-data-compra').trigger("reset");
                                codigo();
                                listar_compra();
                            });
                        });
                    }
                });
            }
        }, 'json');
    });

    //guardar datos
    $('#form-data-compra').submit(e => {
        e.preventDefault(); //no actualiza la pagina
        $('#btn_guardar').blur(); //quitar focus
        $.post('B_modelo-Ncompra.php', { modelo_compra: 'listar' }, (data) => { //respuesta
            if (data.length > 0) { // verefivar que tenga datos la tabla
                Swal.fire({
                    title: '¿Estas seguro(a) en guardar la compra?',
                    text: "Esta acción no se puede revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si! Guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) { // cancelar
                        $.post($(e.target).attr('action'), $(e.target).serialize(), (data) => { //comprobar si hay datos en la tabla
                            $.post('B_modelo-Ncompra.php', { modelo_compra: 'imprimir', codigo: $('#codigo').val() }, (data) => {
                                $('#dni_cliente').val('').trigger('change'); //iniciar combox
                                $('#form-data-compra').trigger("reset");
                                codigo();
                                listar_compra();
                            });
                        });
                    }
                });
            } else {
                Swal.fire(
                    'Error!',
                    'Insertar material a la tabla',
                    'error'
                );
            }
        }, 'json');
    });

});

function codigo(num = '00') {
    $.post('B_modelo-Ncompra.php', { modelo_compra: 'codigo' }, (data) => { //obtener datos
        $('#codigo').val('RECO' + num + '-' + (parseInt(data.id) + 1));
    }, 'json');
}

function listar_compra() {
    $.post('B_modelo-Ncompra.php', { modelo_compra: 'listar' }, (data) => { //retornando validacion
        $('#tabla_datos').dataTable({ //lenamos datos
            'paging': false,
            'lengthChange': false,
            'searching': false,
            "info": false,
            'destroy': true,
            'processing': true,
            "ordering": false,
            language: { //español
                "sProcessing": "Procesando...",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sLoadingRecords": "Cargando..."
            },
            data: data, //datos
            columns: [{
                    data: "material",
                    className: "align-middle"
                },
                {
                    data: null,
                    render: function(data) {
                        // Combinar campos
                        return `<input type="number" value="${data.peso}" class="form-control form-control-sm text-center peso" onchange="editar_peso(this.value,${data.id_material})" data-id="${data.id_material}" step="any" min="0" title="Solo números">`;
                    }
                },
                {
                    data: "und",
                    className: "align-middle"
                },
                {
                    data: null,
                    render: function(data) {
                        // Combinar campos
                        return `<input type="number" value="${data.precio}" class="form-control form-control-sm text-center precio" onchange="editar_precio(this.value,${data.id_material})" data-id="${data.id_material}" step="any" min="0" title="Solo números">`;
                    }
                },
                {
                    data: "sub_total",
                    className: "align-middle"
                },
                {
                    data: null,
                    render: function(data) {
                        // Combinar campos
                        return `<button class="btn btn-danger btn-sm btn_eliminar" title="Eliminar"
                                        data-id="${data.id_material}">
                                        <i class="fas fa-trash-alt"></i>
                                </button>`;
                    },
                    className: "align-middle"
                }
            ],
            columnDefs: [{
                    width: "30%",
                    targets: 0
                },
                {
                    width: "15%",
                    targets: 1
                },
                {
                    width: "15%",
                    targets: 2
                },
                {
                    width: "15%",
                    targets: 3
                },
                {
                    width: "15%",
                    targets: 4
                },
                {
                    width: "10%",
                    targets: 5
                }
            ]
        });
        resultado = Math.round(data.reduce((total, producto) => total + parseFloat(producto.sub_total), 0) * 100) / 100;
        total_pagar(resultado);
    }, 'json');
}

function total_pagar(resultado) {
    const descuento = cero(parseFloat($('#descuento').val() === '' ? 0.00 : $('#descuento').val()).toFixed(1));
    $('#descuento').val('');
    if (descuento > resultado || descuento < 0) {
        $('#descuento').attr("placeholder", "0.00");
        $('#total').val(cero(resultado));
    } else {
        descuento == 0.00 ? $('#descuento').attr("placeholder", "0.00") : $('#descuento').val(descuento);
        $('#total').val(cero((resultado - descuento).toFixed(1)));
    }
}

function cero(num) {
    if (num.toString().indexOf('.') !== -1) {
        return num + "0";
    } else {
        return num + ".00";
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

// resetera material
function reset_modal_material(material = '', und = 'UND', precio1 = '0.00', precio2 = '0.00', precio3 = '0.00') {
    $('input[name=check_precio]').prop('checked', false);
    $('#material').val(material);
    $('#und').val(und);
    $('#und_span').html(und);
    $('#check_precio1').val(precio1);
    $('#label_precio1').text(precio1);
    $('#check_precio2').val(precio2);
    $('#label_precio2').text(precio2);
    $('#check_precio3').val(precio3);
    $('#label_precio3').text(precio3);
}

//validar peso
function validar_peso(e, error) {
    let estado = true;
    const peso = e.target.value;
    if (peso === '') {
        input_validar('error', e.target);
        $(error).html('');
        estado = true;
    } else if (peso > 0) {
        input_validar('correcto', e.target);
        $(error).html('');
        estado = true;
    } else {
        input_validar('error', e.target);
        $(error).html('* Peso Incorrecto');
        estado = false;
    }
    return estado;
}

function editar_peso(valor, id) {
    if (parseFloat(valor) > 0) {
        let numero = valor.replace(/\,/g, '.');
        $.post('B_modelo-Ncompra.php', { modelo_compra: 'editar_peso', numero, id }, (data) => { //obtener datos
            listar_compra();
        });
    } else {
        listar_compra();
    }
}

function editar_precio(valor, id) {
    if (parseFloat(valor) > 0) {
        let numero = valor.replace(/\,/g, '.');
        $.post('B_modelo-Ncompra.php', { modelo_compra: 'editar_precio', numero, id }, (data) => { //obtener datos
            listar_compra();
        });
    } else {
        listar_compra();
    }
}