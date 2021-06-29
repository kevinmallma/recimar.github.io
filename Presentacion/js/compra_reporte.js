const validarImprimir = {
    imprimir: false
};
$(function() {
    listar();
    $("#fecha_ini").datepicker({ dateFormat: "dd/mm/yy" }).datepicker("setDate", new Date());
    $("#fecha_fin").datepicker({ dateFormat: "dd/mm/yy", minDate: new Date() }).datepicker("setDate", new Date());
    //select 2
    $('#usuario').each(function() {
        $(this).select2({
            theme: 'bootstrap4',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple')
        });
    });

    //marcar la ficha fin
    $('#fecha_ini').change(function(e) {
        e.preventDefault(); //no actualiza pagina
        $('#fecha_fin').datepicker("destroy").datepicker({ minDate: this.value }).val(this.value);
    });

    //reporte compra
    $('#form-data').submit(function(e) {
        e.preventDefault(); //no actualiza la pagina
        $('#btn_buscar').blur(); //quitar focus
        $("#carga").show();
        $.post($(this).attr('action'), $(this).serialize(), (data) => {
            if (data.length != 0) {
                listar(data)
                resultado = Math.round(data.reduce((total, reporte) => total + parseFloat(reporte.total), 0) * 100) / 100;
                $('#total').val(cero(resultado));
                validarImprimir.imprimir = true;
                $("#carga").fadeOut();
            } else {
                $("#carga").fadeOut(300, function() {
                    Swal.fire(
                        'Error!',
                        'No se encuentra reportes!',
                        'error'
                    );
                    listar();
                    validarImprimir.imprimir = false;
                });
            }
        }, 'json');
    });

    //cancelar
    $('#btn_cancelar').on('click', function(e) {
        e.preventDefault(); //no actualiza la pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        if (validarImprimir.imprimir === true) {
            $("#carga").show();
            $("#carga").fadeOut(500, function() {
                $("#fecha_ini").datepicker({ dateFormat: "dd/mm/yy" }).datepicker("setDate", new Date());
                $("#fecha_fin").datepicker({ dateFormat: "dd/mm/yy", minDate: new Date() }).datepicker("setDate", new Date());
                listar();
                $('#total').val('0.00');
                validarImprimir.imprimir = false;
            });
        } else {
            Swal.fire(
                'Error!',
                'Realizar busqueda!',
                'error'
            );
        }
    });

    $('#btn_imprimir').on('click', e => {
        e.preventDefault(); //no actualiza la pagina
        $(this).blur(); //quitar focus
        $('[data-toggle="tooltip"]').tooltip('hide'); //toggleiciar titulos ocultar
        if (validarImprimir.imprimir === true) {
            const ini = $('#fecha_ini').val(),
                fin = $('#fecha_fin').val(),
                usu = $('#usuario').val();
            window.open(`D_pdf_compra.php?ini=${ini}&fin=${fin}&usu=${usu}`);
        } else {
            Swal.fire(
                'Error!',
                'Realizar busqueda!',
                'error'
            );
        }
    });

});

function listar(data = []) {
    $('#tabla_datos').dataTable({ //lenamos datos
        'processing': true,
        'destroy': true,
        "ordering": false,
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
        data: data, //datos
        columns: [
            { data: "codigo" },
            { data: "empleado" },
            { data: "cliente" },
            { data: "fecha_hora" },
            { data: "mensaje" },
            { data: "descuento" },
            { data: "total" }
        ]
    });
}

function cero(num) {
    if (num.toString().indexOf('.') !== -1) {
        return num + "0";
    } else {
        return num + ".00";
    }
}