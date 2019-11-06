<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 30/05/2017
 * Time: 04:10 PM
 */
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FACTURA FACIL</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome-4.7.0/css/font-awesome.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/sweetalert/sweetalert.css') ?>">

</head>
<body class="hold-transition login-page">
<div class="register-box">
    <div class="login-logo">
        <a href="#"><b>FACTURACION </b> FACIL</a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg" style="text-align: left; font-size: 12pt">
            Bienvenido al sistema de emision de facturas "Factura Facil".<br>
            Como primer paso registremos los datos de su empresa.
        <li>Nit</li>
        <li>Nombre de empresa</li>
        <li>Sucursal matriz</li>
        <li>marca - modelo de impresora</li>
        <li>serial de la impresora</li>
        </p>

        <form action="<?= site_url() ?>login/registrar_sucursal" method="post">
            <div class="form-group has-feedback">
                <input type="text" id="nit_empresa" name="nit_empresa" class="form-control" placeholder="Nit empresa"
                       required>
                <span class="glyphicon glyphicon-list form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" id="nombre_empresa" name="nombre_empresa" class="form-control"
                       placeholder="Nombre empresa" required>
                <span class="fa fa-home form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" id="nombre_sucursal" name="nombre_sucursal" class="form-control"
                       placeholder="Nombre sucursal Ejm. Casa Matriz, El Cristo" required>
                <span class="fa fa-edit form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" id="marca_impresora" name="marca_impresora" class="form-control"
                       placeholder="Marca - modelo de la impresora" required>
                <span class="fa fa-print form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" id="serial_impresora" name="serial_impresora" class="form-control"
                       placeholder="Numero de serie de la impresora" required>
                <span class="fa fa-print form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>
                </div>
            </div>
        </form>

        <a target="_blank" href="http://workcorp.online/" class="text-center">WORKCORP</a>
    </div>
    <!-- /.form-box -->
</div>
<script>
    var site_login = '<?= site_url() ?>';
</script>
<!-- jQuery 2.2.3 -->
<script src="<?= base_url('assets/js/jquery-2.2.4.min.js') ?>"></script>
<script src="<?= base_url('js-sistema/login.js') ?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/sweetalert/sweetalert.min.js') ?>"></script>
</body>
</html>
