const anio = (new Date).getFullYear(); //año actual
$(function() {
    buscar_anio(anio);
    //click en seleccionar el año
    $('.btn_anio').on('click', function(e) {
        buscar_anio($(this).data("anio"));
        $('.btn_anio').removeClass('active');
        $(this).addClass('active');
        $('.highcharts-data-table').remove();
    });
});

function buscar_anio(anio) { //buscar el año
    $("#carga").show();
    $.post('B_modelo_grafico.php', { modelo: 'compra', anio }, (data) => {
        mostrar_grafico(data.map(Number), anio); //convertir a numeros
        $("#carga").fadeOut();
    }, 'json');
}

function mostrar_grafico(data = [], anio = '') { //grafico
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
            text: `Gráfico compra (${anio})`
        },
        subtitle: { //texto
            text: 'Suma total de compras por cada mes'
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
            name: 's/',
            data: data,
            color: 'rgba(40, 167, 69, 0.5)'
        }]
    });
}