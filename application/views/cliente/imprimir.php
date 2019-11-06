<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 21/10/2017
 * Time: 09:34 AM
 */
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/estilos_factura/stilo.css') ?>" media="screen">
    <!--<link rel="stylesheet" type="text/css" href="../css/print/print.css" media="print">-->
    <link href="<?= base_url('assets/estilos_factura/printListaTransac.css') ?>" rel="stylesheet" type="text/css" media="print"/>
    <link href="<?= base_url('assets/estilos_factura/estiloReportes.css') ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <style type="text/css" media="print">
        @page{
            margin-top: 0.5cm;
        }
        /*table tr td{*/
            /*font-size: 9pt;*/
        /*}*/
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
        window.print()();
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
<div id="hoja" style="height: auto; width: auto">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-globe"></i> Lista de cumpleañeros del mes
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <div class="row invoice-info">
        <div class="col-sm-12">
            <address>
                <strong><?= $empresa->sucursal ?></strong><br>
                <?= $empresa->direccion ?><br>
                Telf.: <?= $empresa->telefono ?><br>
                <?= $empresa->email ?>
            </address>
        </div>
    </div>
    <?php
    $this->load->view('cliente/lista');
    ?>
</div>
</body>
</html>
