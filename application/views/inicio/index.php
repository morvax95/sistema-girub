<?php
/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 01/06/2018
 * Time: 11:06 AM
 */
$user_data = $this->session->userdata('usuario_sesion');
?>
<style>
    .sombra2 {
        text-shadow: 2px 4px 3px rgba(0, 0, 0, 0.3);
        font-size: 35px;
        font-weight: bold;
        font-family: 'Arial Black';
        text-align: center;
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="sombra2">BIENVENIDO</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!--Contenido-->
                            <div class="card">

                                <div class="row clearfix">
                                    <!--<center><h4>Tomar en cuenta los mensajes del sistema</h4></center>-->
                                </div>

                            </div>
                            <!--para que sea responsiva la imagen-->
                            <style>
                                img {
                                    width: 25%;
                                    height: auto;
                                }
                            </style>
                            <div class="row clearfix" align="center">
                                <div class="image">
                                    <!--   <img src="<?= base_url('assets/img/logo_empresa.png') ?>" width="600" height="435"/>-->
                                </div>
                            </div>
                            <!--Fin Contenido-->
                        </div>
                    </div>
                    <!--inicio-->
                    <?php if ($user_data['cargo'] === '1') { ?>
                        <?php
                        if (isset($ventas)) {
                            $venta_proceso = count($ventas);
                            ?>
                            <!--  <div class="col-lg-4 col-md-6 col-xs-6">-->
                            <!--<div class="small-box  box-success box-solid">-->

                            <!--  <div class="small-box  box-success bg-aqua">
                                    <div class="inner">
                                        <h4> <?= $venta_proceso ?> <?= 'Ventas en Proceso' ?></h4>

                                        <p><?= 'Dicarp Diseños y Complementos' ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cart-arrow-down"></i>
                                    </div>
                                    <a href="<?= site_url() ?>venta_proceso" class="small-box-footer">
                                        <marquee>Ver ventas en proceso <i class="fa fa-arrow-circle-right"></i>
                                        </marquee>
                                    </a>
                                </div>
                            </div>-->
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                    <!--fin-->
                    <!--inicio-->
                    <?php if ($user_data['cargo'] === '1') { ?>
                        <?php
                        if (isset($productos)) {
                            $cantidad = count($productos);
                            ?>
                            <!-- <div class="col-lg-4 col-md-6 col-xs-6">-->
                            <!--<div class="small-box  box-success box-solid">-->
                            <!--<div class="small-box  box-success bg-yellow">
                                    <div class="inner">
                                        <h4> <?= $cantidad ?> <?= 'Productos en Producción' ?></h4>

                                        <p><?= 'Dicarp Diseños y Complementos' ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-bell-o"></i>
                                    </div>
                                    <a href="<?= site_url() ?>producto_produccion" class="small-box-footer">
                                        <marquee>Ver productos en producción <i class="fa fa-arrow-circle-right"></i>
                                        </marquee>
                                    </a>
                                </div>
                            </div>-->
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                    <!--fin-->

                    <!--inicio-->
                    <?php if ($user_data['cargo'] === '1') { ?>
                        <?php
                        if (isset($ventas_mes)) {
                            $cantidad = count($ventas_mes);
                            ?>
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <!--   <div class="small-box  box-success box-solid">-->
                                <div class="small-box  box-success bg-yellow">
                                    <div class="inner">
                                        <h4> <?= $cantidad ?> <?= 'Ventas del Mes' ?></h4>

                                        <p><?= 'Tecnologia e innovación' ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-bell"></i>
                                    </div>
                                    <a href="<?= site_url() ?>reporte/reporte_venta" class="small-box-footer">
                                        <marquee>Ver Deudas por Cobrar <i class="fa fa-arrow-circle-right"></i>
                                        </marquee>
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                    <!--fin-->
                    <!--inicio-->
                    <?php if ($user_data['cargo'] === '1') { ?>
                        <?php
                        if (isset($pagos)) {
                            $cantidad = count($pagos);
                            ?>
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <!--   <div class="small-box  box-success box-solid">-->
                                <div class="small-box  box-success bg-aqua">
                                    <div class="inner">
                                        <h4> <?= $cantidad ?> <?= 'Deudas por Cobrar' ?></h4>

                                        <p><?= 'Tecnologia e innovación' ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <a href="<?= site_url() ?>pago/listar" class="small-box-footer">
                                        <marquee>Ver Deudas por Cobrar <i class="fa fa-arrow-circle-right"></i>
                                        </marquee>
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                    <!--fin-->

                </div>
            </div>
        </div>
    </div>



