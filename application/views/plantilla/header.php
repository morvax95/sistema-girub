<?php

$nombre = ucwords(strtolower($dato_sesion['nombre']));
$cargo = ucwords(strtolower($dato_sesion['cargo']));

?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.ico') ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">


    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/img/log-black.png') ?>"/>
    <!-- Favicon-->
    <link rel="icon" type="image/ico" href="<?= base_url('assets/img/logo.ico') ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>GIRUB</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <!-- DataTables -->
    <link href="<?= base_url('assets/datatables/dataTables.bootstrap.css') ?>" rel="stylesheet" media="screen">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome-4.7.0/css/font-awesome.min.css') ?>">
    <!-- Sweet alert -->
    <link rel="stylesheet" href="<?= base_url('assets/sweetalert/sweetalert.css') ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?= base_url('assets/css/_all-skins.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/js/jquery-ui.css') ?>">
    <!--    <link rel="stylesheet" href="-->
    <? //= base_url('assets/font-awesome-4.7.0/css/font-awesome.min.css') ?><!--">-->
    <!-- jQuery 2.2.3 -->
    <script src="<?= base_url('assets/js/jquery-2.2.4.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery-ui-1.10.3.min.js') ?>"></script>

    <script src="<?= base_url('assets/datatables/jquery.dataTables.min.js" rel="stylesheet') ?>"></script>
    <script src="<?= base_url('assets/datatables/dataTables.bootstrap.min.js') ?>" rel="stylesheet"></script>
    <style>
        .error {
            color: red;
            font-weight: bold;
            font-style: italic;
            font-size: 10pt;
        }

        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .boton.turquesa {
            background-color: #00c69f; /* Código de un color Turquesa */
            color: white !important;
        }
    </style>
</head>
<!--oncontextmenu="return false" onkeydown="return false"-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="<?= site_url('inicio') ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>GB</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>GIRUB</b></span>
        </a>
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user"></i>
                            <span class="hidden-xs"><?= $nombre ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <p style="font-size: 13pt">
                                    USUARIO : <?= $nombre ?><br>
                                    EMPRESA : GIRUB

                                </p>
                            </li>
                            <li class="user-footer ">
                                <div class="pull-left " style="width: 100%">
                                    <!--<a href="#modal_cerrar_sesion" data-toggle="modal" style="width: 100%"
                                       class="btn form-control btn-primary btn-flat boton turquesa">CERRAR CAJA</a>-->
                                    <a href="<?= site_url('inicio/cambio') ?>" style="width: 100%"
                                       class="btn form-control btn-primary btn-flat boton turquesa">CAMBIAR
                                        CONTRASEÑA</a>
                                    <a href="<?= site_url('login/cerrar_sesion') ?>" style="width: 100%"
                                       class="btn form-control btn-primary btn-flat boton turquesa">SALIR
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- <li class="dropdown user user-menu">
                         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user"></i>
                            <span style="font-size: 14pt" class="hidden-xs">SUCURSAL: <?= $nombre_sucursal ?></span>
                        </a>
                    </li>-->
                </ul>
            </div>

        </nav>
    </header>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li class="header">MENÚ DEL SISTEMA</li>
                <li class="treeview">
                    <a href="<?= site_url('inicio') ?>">
                        <i class="fa fa-home"></i> <span>INICIO</span>
                    </a>
                </li>
                <?= $this->multi_menu->render(); ?>
                <!-- <li class="treeview">
                     <a href="#modal_cerrar_sesion" data-toggle="modal">
                         <i class="fa fa-sign-out"></i> <span>SALIR</span>
                     </a>
                 </li>-->
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
    <div class="content-wrapper">

        <div id="modal_cerrar_sesion" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <center><h5 class="modal-title" style="color: black"><b>MENÚ DE OPCIONES</b></h5></center>
                    </div>
                    <form id="frm_opciones_sesion" class="form-horizontal">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <a href="<?= site_url('caja/cerrar_caja') ?>">
                                        <div class="info-box bg-green">
                                            <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-number">CIERRE DE CAJA</span>
                                                <span class="info-box-text">cerrar la caja activa en ventas de esta sucursal.</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!--  <div class="col-md-12 col-sm-12 col-xs-12">
                                    <a href="<?= site_url('login/cerrar_sesion') ?>">
                                        <div class="info-box bg-red">
                                            <span class="info-box-icon"><i class="fa fa-sign-out"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-number">Cerrar Sesión</span>
                                                <span class="info-box-text">Cierra la sesion pero la caja de ventas sigue permaneciendo activa.</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>-->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <style>
                label {
                    color: black;
                }
            </style>
        </div>
