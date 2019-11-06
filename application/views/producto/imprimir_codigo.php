<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 21/10/2017
 * Time: 03:52 AM
 */
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/estilos_factura/stilo.css') ?>" media="screen">
    <!--<link rel="stylesheet" type="text/css" href="../css/print/print.css" media="print">-->
    <link href="<?= base_url('assets/estilos_factura/printListaTransac.css') ?>" rel="stylesheet" type="text/css" media="print"/>
    <link href="<?= base_url('assets/estilos_factura/estiloReportes.css') ?>" rel="stylesheet" type="text/css"/>
    <style type="text/css" media="print">
        @page{
            margin: 0.5cm;
        }
        table tr td{
            font-size: 9pt;
        }
    </style>
    <script src="<?= base_url('assets/js/jquery-2.2.4.min.js') ?>"></script>
</head>
<body>
<script language="JavaScript">
    $(document).ready(function ()
    {
        doPrint();
    });

    function doPrint()
    {
        //document.all.item("mostrarUser").style.visibility='visible';
        window.print();
        //document.    {
//        all.item("mostrarUser").style.visibility='hidden';
    }
    function Salir(dato)
    {
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
<div id="hoja">
    <?php
    if ($tipo == 1){
        ?>
        <img src="<?= base_url('barcodes/'.$codigo->codigo_barra.'.png') ?>" />
    <?php
    }else{
        foreach ($codigos as $row){
            ?>
            <div style="text-align: center">
                <span><?= ucwords(strtolower($row->nombre_item)).' '.$row->talla." ".$row->color. " ".$row->precio_venta ?></span>
                <img width="auto" height="auto" src="<?= base_url('barcodes/'.$row->codigo_barra.'.png') ?>" />
                <br>
                <br>
            </div>
            <?php
        }
        ?>
    <?php
    }
    ?>
</div>
</body>
</html>
