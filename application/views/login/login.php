<?php

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Favicon-->
    <link rel="icon" type="image/ico" href="<?= base_url('assets/img/favicon.ico') ?>"/>
    <title>GIRUB</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome-4.7.0/css/font-awesome.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/sweetalert/sweetalert.css') ?>">
    <style>
        body {
            background-image: url(<?=base_url('assets/img/fondo1.jpg')?>) !important;
            background-size: 100% 100% !important;
            background-attachment: fixed !important;
            background-repeat: no-repeat !important;
            overflow: hidden !important;
        }

        .login-box-body {
            background: rgba(255, 255, 255, 0.5);
        }

        .login-box-msg {
            color: white;
            size: 40px !important;
        }

        .enter {
            background: #6d0000;
            color: white;
            /*font-size: 14px;*/
        }

        .enter:hover {
            background: #6d0000;
            color: white;
            font-size: 16px;
        }

        .boton.turquesa {
            background-color: #00c69f; /* Código de un color Turquesa */
            color: white !important;
        }

    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!--<div class="login-logo">
        <a href="#"><b>WorkCorp </b></a><br>
    </div>-->
    <!-- /.login-logo -->
    <div class="login-box-body">
        <h3><p class="login-box-msg">INICIAR SESIÓN</p></h3>

        <form id="frm_login_sistema" method="post" action="<?= site_url('login/verificar') ?>">
            <div class="form-group has-feedback">
                <select id="sucursal" name="sucursal" class="form-control" autofocus>
                    <?php
                    foreach ($sucursal as $row) {
                        ?>
                        <option value="<?= $row->id ?>"><?= $row->sucursal ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="fa fa-building form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuario" autofocus
                       required>
                <span class="fa fa-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" id="clave" name="clave" placeholder="Contraseña" required>
                <span class="fa fa-lock form-control-feedback"></span>
                <p id="mjs1" style="color: red; font-size: 12pt; font-weight: bold" hidden>Usuario y/ contraseña
                    incorrectos, por favor verifique.</p>
                <p id="mjs2" style="color: red; font-size: 12pt; font-weight: bold" hidden>Su usuario no cuenta con la
                    asignacion a esta sucursal. Acceso denegado</p>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat boton turquesa">Ingresar</button>
                </div>
                <div class="col-xs-12">
                    <div class="" style="text-align: center; color: #bdbdbd;">
                        <strong>| © 2019 GIRUB |</strong>
                    </div>
                    <!--<div class="" style="text-align: center">
                        <a target="_blank" href="http://workcorp.net" style="color: #100bf5">http://workcorp.net</a>
                    </div>-->
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
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

