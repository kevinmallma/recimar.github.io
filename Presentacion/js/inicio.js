const anio = (new Date).getFullYear(); //año actual
$(function() {
    $("#carga").show();
    buscar_anio(anio);
    $("#carga").fadeOut();
});

function buscar_anio(anio) { //buscar el año
    $.post('B_modelo_grafico.php', { modelo: 'compra', anio }, (data) => {
        mostrar_grafico(data.map(Number), anio); //convertir a numeros
    }, 'json');
    $.post('B_modelo-material.php', { modelo: 'listar', id: '%' }, (data) => {
        let sData = [];
        $.each(data.sort(sort), function(index, ma) { //mostrar padre
            const material = {
                name: ma.nombre,
                y: parseFloat(ma.peso)
            }
            sData.push(material);
        });
        sData.sort(sort);
        mostrar_grafico_pie(sData, anio);
    }, 'json');
}

function mostrar_grafico(data = [], anio = '') { //grafico
    Highcharts.chart('container_barra', {
        exporting: false,
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
            text: `<b>Gráfico compra (${anio})</b>`
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
                    fontSize: '12px'
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

function mostrar_grafico_pie(data, anio = '') {
    Highcharts.chart('container_pie', {
        exporting: false,
        credits: {
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: `<b>Los 10 materiales mas comprados (${anio})</b>`
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y} kg</b>'
        },
        accessibility: {
            point: {
                valueSuffix: 'kg'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y} kg'
                }
            }
        },
        series: [{
            name: 'Peso',
            colorByPoint: true,
            data: data
        }]
    });
}

function sort(a, b) { //ordenar
    a = a.y;
    b = b.y;
    if (a < b) {
        return 1;
    } else if (a > b) {
        return -1;
    }
    return 0;
}