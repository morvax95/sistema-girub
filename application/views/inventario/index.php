<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 01/03/2018
 * Time: 10:55 AM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-line-chart fa-2x"></i> <b>LISTA DE INVENTARIO COMPLETO</b>
                    </h3>
                    <div style="float:right">
                        <!--<a href="<?= site_url('inventario/vista_inventario_materia') ?>" class="btn btn-warning"><i class="fa fa-plus-square"></i>&nbsp; Ver Inventario de materia</a>-->
                        <!--<a href="<?= site_url('inventario/vista_inventario_producto') ?>" class="btn btn-primary"><i
                                    class="fa fa-plus-square"></i>&nbsp; Ver Inventario de productos</a>-->
                        <!-- <a href="#modal_ingreso_por_tipo" data-toggle="modal" class="btn btn-success"><i
                                     class="fa fa-plus"></i> Nuevo ingreso</a>-->

                        <a href="<?= site_url('inventario/nuevo') ?>" class="btn btn-success"><i class="fa fa-plus"></i>
                            Nuevo Ingreso</a>
                        <!-- <a href="<?= site_url('inventario/materia_prima') ?>" class="btn btn-primary" class="fa fa-plus">Nuevo
                            Materia Prima</a>-->
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="lista_ingreso" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-center">NRO</th>
                            <th class="text-center">FECHA INGRESO</th>
                            <th class="text-center">ALMACÉN</th>
                            <th class="text-center">SUCURSAL</th>
                            <th class="text-center">OPCIONES</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="modal_ingreso_por_tipo" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: black"><b>Selecciona una forma de ingreso de inventario</b></h5>
            </div>
            <form class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <a href="<?= site_url('inventario/materia_prima') ?>" class="small-box-footer">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>Materia Prima</h3>

                                        <p>Ingresa tus materiales al almacen definido.</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-bars"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <a href="<?= site_url('inventario/nuevo') ?>" class="small-box-footer">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>Producto</h3>

                                        <p>Ingresa tus productos a los almacenes disponibles.</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-bars"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <a id="btn_cerrar_modal_color" class="btn btn-danger" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('js-sistema/inventario.js') ?>"></script>
