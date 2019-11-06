<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 08/11/2017
 * Time: 11:28 PM
 */
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/estilos_factura/stilo.css') ?>" media="screen">
    <!--<link rel="stylesheet" type="text/css" href="../css/print/print.css" media="print">-->
    <link href="<?= base_url('assets/estilos_factura/printListaTransac.css') ?>" rel="stylesheet" type="text/css" media="print"/>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?= base_url('assets/estilos_factura/estiloReportes.css') ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="<?= base_url('assets/sweetalert/sweetalert.css') ?>">
    <style type="text/css" media="print">
        @page{
            margin: 0.5cm;
        }
        table tr td{
            font-size: 9pt;
        }
    </style>
    <script src="<?= base_url('assets/js/jquery-2.2.4.min.js') ?>"></script>
    <script src="<?= base_url('assets/sweetalert/sweetalert.min.js') ?>"></script>
</head>
<body>
<script language="JavaScript">
    $(document).ready(function ()
    {
        doPrint();

        $('#cerrar_salir').click(function (e) {
            e.preventDefault();

            var total = $('#total_caja').val();
            var id_caja = $('#id_caja').val();
            $.ajax({
                url: '<?= site_url('caja/registrar_cierre') ?>',
                type: 'post',
                data: '&total='+total+'&caja='+id_caja,
                success: function (result) {
                    console.log(result)
                    if (result == 'true'){
//                        swal({
//                            type: 'success',
//                            title: 'Registro Guardado',
//                            text: 'Los datos ingresados se guardaron correctamente',
//                            showConfirmButton: false,
//                            timer: 1000
//                        });
                        alert('Cerrando Caja y Sesion')
                        setTimeout(function () { location.href = '<?= site_url('login/cerrar_sesion') ?>'},1000);
                    }else{
                        swal({
                            type: 'danger',
                            title: 'Error al guardar',
                            text: 'Notifique al administrador del sistema para mayor informacion.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                }
            })
        });
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

    }
</script>
<div id="noprint">
    <table>
        <tr>
            <td>
                <button type="button" class="btn btn-primary" name="cerrar_salir" id="cerrar_salir" onclick="Salir()">Cerrar Caja y Salir</button>
            </td>
            <td>&nbsp;&nbsp;</td>
            <td>
                <button type="button" class="btn btn-danger" onclick="doPrint()">Imprimir</button>
                <input type="hidden" id="id_caja" value="<?= $caja->id ?>"/>
                <input type="hidden" id="total_caja" value="<?= $total_caja->total ?>"/>
            </td>
        </tr>
    </table>
</div>
<div id="hoja" style="text-align: center">
    <table align="center">
        <tr>
            <td style="padding: 1%;" colspan="2" align="center"><strong> <?= $sucursal->nombre_empresa ?></strong> </td>
        </tr>
        <tr>
            <td style="padding: 1%;" colspan="2" align="center"><strong> <?= $sucursal->sucursal ?></strong> </td>
        </tr>
        <tr>
            <td style="padding: 1%;" colspan="2" align="center"><?= $sucursal->direccion ?> </td>
        </tr>
        <tr>
            <td style="padding: 1%;" colspan="2" align="center"> Santa Cruz - Bolivia </td>
        </tr>
        <tr>
            <td style="padding: 1%;" colspan="2"><div align="center"><b>C I E R R E &nbsp;&nbsp;&nbsp;D E &nbsp;&nbsp;&nbsp;C A J A</b></div></td>
        </tr>
        <tr>
            <td colspan="2">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </td>
        </tr>
        <tr>
            <td style="padding: 1%;"><b>Fecha:</b> <?= date('d/m/Y') ?></td>
            <td style="padding: 1%; text-align: left"><b>Nit:</b> <?= $sucursal->nit ?></td>
        </tr>
        <tr>
            <td style="padding: 1%; text-align: left"><b>Caja:</b> <?= $caja->descripcion ?></td>
            <td style="padding: 1%; text-align: left"><b>Fecha Apertura:</b> <?= date('d/m/Y',strtotime($caja->fecha_apertura)) ?></td>
        </tr>
        <tr>
            <td colspan="2">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </td>
        </tr>
    </table>
    <table border="1" cellspacing="1" cellpadding="4" style="font-size: 9pt">
        <tr>
            <td style="padding: 1%;width: 7%; text-align: center"><b>Nro</b></td>
            <td style="padding: 1%;width: 18%; text-align: center"><b>CI/NIT</b></td>
            <td colspan="4" style="padding: 1%; text-align: center"><b>Cliente</b></td>
        </tr>
        <tr>
            <td></td>
            <td style="padding: 1%; width: 15%; text-align: center"><b>Nro. Nota</b></td>
            <td style="padding: 1%; width: 20%; text-align: center"><b>SubTotal</b></td>
            <td style="padding: 1%; width: 20%; text-align: center"><b>Descuento</b></td>
            <td style="padding: 1%; width: 20%; text-align: center"><b>Total</b><b></b></td>
            <td style="padding: 1%; width: 25%; text-align: center"><b>Usuario</b></td>
        </tr>
        <?php
        $contador = 0;
        foreach ($datos as $row) {
            $contador   = $contador+1;
            $nit        = $row->ci_nit;
            $nombre     = $row->nombre_cliente;
            $nota       = $row->nro_nota;
            $subtotal   = $row->subtotal;
            $descuento  = $row->descuento;
            $total      = $row->total;
            $usuario    = $row->nombre_usuario;
            ?>
            <tr>
                <td style="padding: 2%; text-align: center"><?= $contador ?></td>
                <td style="padding: 2%; text-align: left; font-size: 8pt"><?= $nit ?></td>
                <td colspan="4" style="padding: 2%; padding-left: 3%"><?= $nombre ?></td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td style="text-align: center"><?= $nota ?></td>
                <td style="text-align: right; padding-right: 2%"><?= $subtotal ?></td>
                <td style="text-align: right; padding-right: 2%"><?= $descuento ?></td>
                <td style="text-align: right; padding-right: 2%"><?= $total ?></td>
                <td ><?= $usuario ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="4" style="text-align: right; padding-right: 2%"><b>Total Cerrado Bs.:</b></td>
            <td style="text-align: right"><b><?= number_format($total_caja->total, 2) ?> </b></td>
        </tr>
    </table>
</div>
</body>
</html>
