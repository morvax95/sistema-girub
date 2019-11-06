<?php
/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 12/04/2018
 * Time: 06:30 PM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h2 class="box-title"><i class="fa fa-database"></i> DATOS CORRESPONDIENTES AL INGRESO SELECCIONADO
                    </h2>
                    <div style="float:right">
                        <a href="<?= site_url('inventario/index') ?>" class="btn btn-danger"><i
                                    class="fa fa-arrow-left"></i>&nbsp; Volver</a>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group" style="margin-bottom: 0%">
                                <div class="col-md-3">
                                    <label class="control-label"><b>Nro. Ingreso</b></label>
                                    <input type="text" class="form-control"
                                           value="<?= $ingreso->id ?>" readonly/>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label"><b>Fecha Ingreso</b></label>
                                    <input type="date" class="form-control"
                                           value="<?= $ingreso->fecha ?>" readonly/>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label"><b>Observación</b></label>
                                    <textarea class="form-control" readonly
                                              placeholder="Escriba alguna observacion en caso de que hubiera"><?= $ingreso->observacion ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="control-label"><b>Almacen</b></label>
                                    <input class="form-control" value="<?= $ingreso->almacen ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label"><b>Sucursal</b></label>
                                    <input class="form-control" value="<?= $ingreso->sucursal ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <th style="width: 30%" class="text-center">NOMBRE PRODUCTO</th>
                                        <th style="width: 8%" class="text-center">PRECIO VENTA</th>
                                        <th style="width: 10%" class="text-center">CANTIDAD</th>
                                        <!--  <th style="width: 10%" class="text-center">Estado</th>-->
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($detalle as $row) {
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?= $row->nombre_item ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $row->precio_compra ?>
                                                </td>

                                                <td class="text-center">
                                                    <?= $row->cantidad_ingresada ?>
                                                </td>
                                                <!--  <td class="text-center">
                                                    <?php

                                                $val1 = "";
                                                if ($row->estado_producto == 1) {
                                                    $val1 = 'Existente';
                                                } else {
                                                    $val1 = 'Producción';

                                                }
                                                ?>
                                                    <?= $val1 ?>
                                                </td>-->
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>
