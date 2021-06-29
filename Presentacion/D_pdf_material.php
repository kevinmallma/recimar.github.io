<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_material;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use Entidades\Contenedor;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear fichero
    return $logger;
});

$ma_LN = new LN_material();
$material = $ma_LN->buscar_listar();
$css = file_get_contents('css/reportes.css'); //onbeter diseño
$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 20,
    'margin_bottom' => 25,
    'margin_header' => 10,
    'margin_footer' => 10
]);

$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Reporte-Clientes"); //titulo
$mpdf->SetAuthor("kevin"); //autor
$mpdf->SetWatermarkText("RECIMAR E.I.R.L."); //marca de agua
$mpdf->showWatermarkText = true; //activar marca de agua
$mpdf->watermark_font = 'DejaVuSansCondensed'; //fuente predeterminada de la marca de agua
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage'); //paginado

$mpdf->WriteHTML($css, HTMLParserMode::HEADER_CSS); //diseño

$platilla = '
<body>
    <htmlpageheader name="myheader"><!--CABEZA-->
        <table class="tabla_header">
            <tr>
                <td class="logo">
                    <img src="imagenes/materiales.png" alt="usuario" style="width:2%; height:2%">
                </td>
                <td class="titulo">
                    <span>MATERIALES</span>
                </td>
                <td class="fecha">
                    ' . date('d/m/Y') . '
                </td>
            </tr>
        </table>
    </htmlpageheader><!--CABEZA-->
    <!--PIE DE PAGINA-->
    <htmlpagefooter name="myfooter">
        <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
            Pagina {PAGENO} de {nb}
        </div>
    </htmlpagefooter><!--PIE DE PAGINA-->
    <sethtmlpageheader name="myheader" value="on" show-this-page="1" /> <!--ESTABLECER VALORES-->
    <sethtmlpagefooter name="myfooter" value="on" /> <!--ESTABLECER VALORES-->
    <!--tabla-->
    <table class="tabla_cuerpo" cellpadding="8">
        <thead>
            <tr>
                <td width="5%">Nº</td>
                <td width="25%">NOMBRE</td>
                <td width="17%">PESO</td>
                <td>UND</td>
                <td>P. COMPRA MIN.</td>
                <td>P. COMPRA MED.</td>
                <td>P. COMPRA MAY.</td>
            </tr>
        </thead>
        <tbody>';
$id = 1;
foreach ($material as $ma) {
    $platilla .= '
            <tr>
                <td>' . $id . '</td>
                <td>' . $ma->nombre . '</td>
                <td>' . $ma->peso . '</td>
                <td>' . $ma->und . '</td>
                <td>' . $ma->precio_c1 . '</td>
                <td >' . $ma->precio_c2 . '</td>
                <td >' . $ma->precio_c3 . '</td>
            </tr>';
    $id++;
}
$platilla .= '
        </tbody>
    </table>
    <!--tabla-->
</body>
';
$mpdf->WriteHTML($platilla, HTMLParserMode::HTML_BODY); //cuerpo

$mpdf->Output('clientes.pdf', 'I');
