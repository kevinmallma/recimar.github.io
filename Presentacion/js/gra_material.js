let nombre = '';
$(function() {
    mostrar_grafico([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
    $('#material').each(function() {
        $(this).select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple')
        });
    });

    $('#anio').each(function() {
        $(this).select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
            closeOnSelect: !$(this).attr('multiple')
        });
    });

    $('#material').on('change', function(e) {
        e.preventDefault(); //no actualiza pagina
        const id = this.value;
        $.post('B_modelo-material.php', { modelo: 'listar', id }, (data) => {
            nombre = data[0].nombre;
        }, 'json');
    });

    //guardar datos
    $('#form-data').on('submit', function(e) {
        e.preventDefault(); //no actualiza pagina
        $("#carga").show();
        $.post($(this).attr('action'), $(this).serialize(), (data) => {
            mostrar_grafico(data.map(Number));
            $('.highcharts-data-table').remove();
            $("#carga").fadeOut();
        }, 'json');
    });
});

function mostrar_grafico(data = []) { //grafico
    Highcharts.chart('container', {
        credits: {
            enabled: false
        },
        chart: { //3d
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 0,
                beta: 0,
                depth: 50
            }
        },
        title: { //titulo
            text: `Gráfico Material (${nombre})`
        },
        subtitle: { //texto
            text: 'Suma total de cada material por mes realizadas de las compras'
        },
        plotOptions: { //grosor de la columna
            column: {
                depth: 25
            }
        },
        xAxis: { //tamaño de letra
            categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            labels: {
                skew3d: true,
                style: {
                    fontSize: '16px'
                }
            }
        },
        yAxis: {
            title: { //titulo
                text: null
            }
        },
        series: [{ //datos
            name: 'Cantidad',
            data: data,
            color: 'rgba(40, 167, 69, 0.5)'
        }]
    });
}