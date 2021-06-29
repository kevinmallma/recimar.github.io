<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_compra;
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
if (isset($_GET['ini']) && isset($_GET['fin']) && isset($_GET['usu'])) {
    if (validateDate(fecha_formato($_GET['ini'])) && validateDate(fecha_formato($_GET['fin']))) {
        $com = new LN_compra();
        $usuario = empty($_GET['usu']) ? '%' : $_GET['usu'];
        $compra = $com->reporte_compra(fecha_formato($_GET['ini']), fecha_formato($_GET['fin']), $usuario);
        $css = file_get_contents('css/reportes.css'); //onbeter diseño
        ob_clean();
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);

        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Reporte-Compra"); //titulo
        $mpdf->SetAuthor("kevin"); //autor
        $mpdf->SetWatermarkText("RECIMAR E.I.R.L."); //marca de agua
        $mpdf->showWatermarkText = true; //activar marca de agua
        $mpdf->watermark_font = 'DejaVuSansCondensed'; //fuente predeterminada de la marca de agua
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage'); //paginado
        $mpdf->WriteHTML($css, HTMLParserMode::HEADER_CSS); //diseño
        $plantilla = '
        <body>
            <!--CABEZA-->
                <htmlpageheader name="myheader">
                    <table class="tabla_header">
                        <tr>
                            <td class="titulo">
                                <span>REPORTE DE COMPRA</spna>
                                (' . fecha_formato($_GET['ini']) . ' - ' . fecha_formato($_GET['fin']) . ')
                            </td>
                        </tr>
                    </table>
                </htmlpageheader>
            <!--CABEZA-->
            <!--PIE DE PAGINA-->
                <htmlpagefooter name="myfooter">
                    <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
                        Pagina {PAGENO} de {nb}
                    </div>
                </htmlpagefooter>
            <!--PIE DE PAGINA-->
            <sethtmlpageheader name="myheader" value="on" show-this-page="1" /> <!--ESTABLECER VALORES-->
            <sethtmlpagefooter name="myfooter" value="on" /> <!--ESTABLECER VALORES-->
            <!--tabla-->
            <table class="tabla_cuerpo" cellpadding="8">
                <thead>
                    <tr>
                        <td width="5%">Nº</td>
                        <td>CODIGO</td>
                        <td>COMPRADOR</td>
                        <td>CIENTE</td>
                        <td>FECHA-HORA</td>
                        <td width="25%">MENSAJE</td>
                        <td>DES.</td>
                        <td>TOTAL</td>
                    </tr>
                </thead>
                <tbody>';
        $id = 1;
        foreach ($compra as $co) {
            $plantilla .= '
                     <tr>
                        <td>' . $id . '</td>
                        <td>' . $co->codigo . '</td>
                        <td>' . $co->empleado . '</td>
                        <td>' . $co->cliente . '</td>
                        <td>' . $co->fecha_hora . '</td>
                        <td>' . $co->mensaje . '</td>
                        <td>' . $co->descuento . '</td>
                        <td>' . $co->total . '</td>
                     </tr>';
            $id++;
        }
        foreach ($compra as $co) {
            $tol = $co->total + $tol;
        }
        $cer = strpos((string) $tol, '.') == true ? "0" : ".00";
        $plantilla .= '<tr>
                        <td class="blanktotal" colspan="6" rowspan="6"></td>
                        <td class="totals">TOTAL S/</td>
                        <td class="totals">' . $tol . $cer . '</td>
                   </tr>
                </tbody>
            </table>
        </body>';
        $mpdf->WriteHTML($plantilla, HTMLParserMode::HTML_BODY); //cuerpo
        $mpdf->Output('clientes.pdf', 'I');
        ob_end_flush();
    } else {
        echo "<script>window.close();</script>";
    }
} else {
    echo "<script>window.close();</script>";
}
