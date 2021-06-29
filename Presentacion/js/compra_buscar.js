const validarImprimir = {
    imprimir: false,
    codigo: ''
};
$(function() {
    $('#codigo').trigger('focus');

    listar_compra();

    $('#codigo').keyup(function(e) {
        e.preventDefault(); //no actualiza la pagina
        const codigo = $(this).val().replace(/ /g, ""); //evitar espacios
        $(this).val(codigo.toUpperCase());
    });

    //buscar compra
    $('#form-data').submit(function(e) {
        e.preventDefault(); //no actualiza la pagina
        $('#btn_buscar').blur(); //quitar focus
        $("#carga").show();
        $.post($(this).attr('action'), $(this).serialize(), (data) => {
            if (data != null) {
                let cliente = '';
                if (data.cliente.length > 0) { // si hay cliente
                    cliente = `${data.cliente[0].nombres} ${data.cliente[0].materno} ${data.cliente[0].paterno}`;
                }
                resetear_imput(cliente, `${data.empleado[0].nombres} ${data.empleado[0].materno} ${data.empleado[0].paterno}`, data.fecha_hora, data.dt_compra, data.mensaje, data.descuento, data.total, true, data.codigo);
            } else {
                Swal.fire(
                    'Error!',
                    'Código no existente!',
                    'error'
                );
                resetear_imput();
            }
            $("#carga").fadeOut("fast");
        }, 'json');
    });

    $('#btn_cancelar').on('click', function(e) {
        e.preventDefault(); //no actualiza la pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        if (validarImprimir.imprimir === true) {
            $("#carga").show();
            $("#carga").fadeOut(500, function() {
                $('#codigo').val('');
                $('#codigo').trigger('focus');
                resetear_imput();
            });
        } else {
            Swal.fire(
                'Error!',
                'Buscar codigo!',
                'error'
            );
        }
    });

    //buscar
    $('#btn_imprimir').on('click', function(e) {
        e.preventDefault(); //no actualiza la pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        if (validarImprimir.imprimir === true) {
            $.post('B_modelo-Ncompra.php', { modelo_compra: 'imprimir', codigo: validarImprimir.codigo }, (data) => {
                console.log(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Impresión realizada',
                    showConfirmButton: false,
                    timer: 1000
                });
            }, 'json');
        } else {
            Swal.fire(
                'Error!',
                'Buscar codigo!',
                'error'
            );
        }
    });
});

function resetear_imput(cliente = '', comprador = '', fecha_hora = '', dt_compra = [], mensaje = '', descuento = '', total = '', tipo = false, codigo = '') {
    $('#cliente').val(cliente);
    $('#comprador').val(comprador);
    $('#fecha_hora').val(fecha_hora);
    listar_compra(dt_compra);
    $('#mensaje').val(mensaje);
    $('#descuento').val(descuento);
    $('#total').val(total);
    validarImprimir.imprimir = tipo;
    validarImprimir.codigo = codigo;
}

function listar_compra(data = []) {
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
        columns: [
            { data: "material[0].nombre" },
            { data: "peso" },
            { data: "material[0].und" },
            { data: "precio" },
            { data: "sub_total" }
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
            }
        ]
    });
}