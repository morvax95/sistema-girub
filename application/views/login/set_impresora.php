<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 27/04/2017
 * Time: 02:35 PM
 */
$user = $this->session->userdata('usuario_sesion');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema</title>
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
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>FACTURA </b> FACIL</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">SELECCIONE LA IMPRESORA CON LA QUE TRABAJARA</p>

        <form id="frm_set_impresora" method="post" action="<?= site_url('login/guardarSesion') ?>">
            <div class="form-group has-feedback">
                <input id="user_id" name="user_id" value="<?= $user['id'] ?>" hidden/>
                <input type="text" class="form-control" value="<?= $user['nombre'] ?>" readonly/>
            </div>
            <div class="form-group has-feedback">
                <label>Impresora</label>
                <select id="impresora_sel" name="impresora_sel" class="form-control">

                </select>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in"></i>
                        Seleccionar
                    </button>
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
<script>
    $(document).ready(function () {
        get_impresoras();

    });
    function get_impresoras() {
        $.post(site_login + "impresora/getImpresorasLogin",
            function (data) {
                var datos = JSON.parse(data);
                $.each(datos, function (i, item) {
                    $('#impresora_sel').append('<option value="' + item.id + '">' + item.marca + ' | '+ item.serial+'</option>');
                });
            });
    }
</script>
</body>
</html>
