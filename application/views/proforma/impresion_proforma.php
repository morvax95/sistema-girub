<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 05/07/2018
 * Time: 05:45 PM
 */
//echo var_dump($datos_venta).'<br>';
//echo var_dump($datos_venta_detalle).'<br>';
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/estilos_factura/stilo.css') ?>" media="screen">
    <!--<link rel="stylesheet" type="text/css" href="../css/print/print.css" media="print">-->
    <link href="<?= base_url('assets/estilos_factura/printListaTransac.css') ?>" rel="stylesheet" type="text/css"
          media="print"/>
    <link href="<?= base_url('assets/estilos_factura/estiloReportes.css') ?>" rel="stylesheet" type="text/css"/>
    <style type="text/css" media="print">
        @page {
            margin: 0.5cm;
        }

        table tr td {
            font-size: 9pt;
        }
    </style>
    <script src="<?= base_url('assets/js/jquery-2.2.4.min.js') ?>"></script>
</head>
<body>
<script language="JavaScript">
    $(document).ready(function () {
        doPrint();
    });

    function doPrint() {
        //document.all.item("mostrarUser").style.visibility='visible';
        window.print()();
        //document.    {
//        all.item("mostrarUser").style.visibility='hidden';
    }
    function Salir(dato) {
        window.close();
    }
</script>
<div id="noprint">
    <table>
        <tr>
            <td>
                <button type="button" name="volver" id="volver" onclick="Salir()">Salir</button>
            </td>
            <td>&nbsp;&nbsp;</td>
            <td>
                <button type="button" onclick="doPrint()">Imprimir</button>
            </td>
        </tr>
    </table>
</div>
<div id="hoja" style="height: auto;text-align: center">
    <table align="center" style="width: 100%">
        <tr>
            <td align="center"><strong> <?= $datos_proforma->nombre_empresa ?></strong></td>
        </tr>
        <tr>
            <td align="center"><strong> <?= $datos_proforma->sucursal ?></strong></td>
        </tr>
        <tr>
            <td align="center"><?= $datos_proforma->direccion ?> </td>
        </tr>
        <tr>
            <td align="center"> Santa Cruz - Bolivia</td>
        </tr>
        <tr>
            <td>
                <div align="center"><b>P R O F O R M A<br></b></div>
            </td>
        </tr>
        <tr>
            <td align="center"><b>Nro.</b> 000<?= $datos_proforma->nro_proforma ?></td>
        </tr>
        <tr>
            <td style="border-top: 1px dashed black;"></td>
        </tr>
        <tr>
            <td><b>Fecha:</b> <?= date('d/m/Y', strtotime($datos_proforma->fecha)) ?></td>
        </tr>
        <tr>
            <td style="text-align: left"><b>Nit:</b> <?= $datos_proforma->ci_nit ?></td>
        </tr>
        <tr>
            <td style="text-align: left"><b>Nombre:</b> <?= $datos_proforma->nombre_cliente ?></td>
        </tr>
        <tr>
            <td style="border-top: 1px dashed black;"></td>
        </tr>
    </table>
    <table cellspacing="1" cellpadding="1" width="100%" style="font-size: 9pt">
        <tr>
            <td style="text-align: center; width: 70%"><b>Detalle</b></td>
            <td style="padding-left: 5%; text-align: center; width: 10%"><b>Cant.</b></td>
            <td style="padding-bottom: 0%; text-align: center; width: 10%"><b>P.Uni.</b></td>
            <td style="padding-bottom: 0%; text-align: center; width: 10%"><b>Monto</b></td>
        </tr>
        <tr>
            <td colspan="4" style="border-top: 1px dashed black;">

            </td>
        </tr>

        <?php
        $contador = 0;
        foreach ($datos_proforma_detalle as $row) {
            $contador = $contador + 1;
            $cantidad = $row->cantidad;
            $descripcion = $row->nombre_item;
            $precio = $row->precio_venta;
            $talla = $row->talla;
            $color = $row->color;
            $subtotal = $cantidad * $precio;
            ?>
            <tr>
                <td style="text-align: left; width: 70%"><?= ucwords(strtolower($descripcion)).'/'.$talla.'/'.$color ?></td>
                <td style="text-align: center; width: 10%"><?= $cantidad ?></td>
                <td style="text-align: right; padding-right: 12%; width: 10%"><?= $precio ?></td>
                <td style="text-align: right; padding-right: 2%; width: 10%"><?= number_format($subtotal, 2) ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br>
    <table width="100%" style="font-size: 9pt">
        <tr>
            <td colspan="4" style="border-top: 1px dashed black;"></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td style="width: 100%; text-align: right; "><b>SubTotal Bs.:</b></td>
            <td style="text-align: right; }"><b><?= number_format($datos_proforma->subtotal, 2) ?> </b></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td style="width: 100%;text-align: right; "><b>Descuento Bs.:</b></td>
            <td style="text-align: right"><b><?= number_format($datos_proforma->descuento, 2) ?> </b></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td style="width: 100%;text-align: right; "><b>Total Bs.:</b></td>
            <td style="text-align: right"><b><?= number_format($datos_proforma->total, 2) ?> </b></td>
        </tr>
    </table>
    <br>
    <table align="center">
        <tr>
            <td>
                <b>Son:</b> <?php
                include APPPATH . '/libraries/convertidor.php';
                $v = new EnLetras();
                $valor = $v->ValorEnLetras($datos_proforma->total, " ");
                echo $valor . " Bolivianos";
                ?>
                <br>
            </td>
        </tr>
        <tr>
            <td style="text-align: left">
                <b>Cajero(a):</b> <?= $datos_proforma->nombre_usuario ?>
                <br>
            </td>
        </tr>
        <tr>
            <td style="text-align: left">
                <b>Hora:</b> <?= $datos_proforma->hora ?>
                <br>
            </td>
        </tr>
    </table>
    <table style="width: 100%" align="center">
        <tr>
            <td colspan="4" style="border-top: 1px dashed black;">
            </td>
        </tr>
        <tr>
            <td style="width:100%; font-size: 8pt; text-align: center">
                <b>"Gracias por su compra, vuelva pronto"</b>
            </td>
        </tr>
    </table>
</div>
</body>
</html>

