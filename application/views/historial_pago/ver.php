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
                    <h2 class="box-title"><i class="fa fa-database"></i> DATOS CORRESPONDIENTES AL CLIENTE
                    </h2>
                    <div style="float:right">
                        <a href="<?= site_url('historial_pago/index') ?>" class="btn btn-danger"><i
                                    class="fa fa-arrow-left"></i>&nbsp; Volver</a>
                    </div>

                </div>
                <div class="form-horizontal">
                    <div class="box-body">


                        <div class="col-md-12">
                            <div class="form-group" style="margin-bottom: 0%">
                                <div class="col-md-5">
                                    <label class="control-label"><b>NOMBRE CLIENTE</b></label>
                                    <input type="text" class="form-control"
                                           value="<?= $datos_cliente->nombre_cliente ?>" readonly/>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label"><b>CÉDULA IDENTIDAD</b></label>
                                    <input class="form-control" readonly
                                           value="<?= $datos_cliente->ci_nit ?>">
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label"><b>MONTO VENTA</b></label>
                                    <input class="form-control" readonly
                                           value="<?= $datos_cliente->total ?>">
                                </div>

                                <div class="col-md-2">
                                    <label class="control-label"><b>NRO. VENTA</b></label>
                                    <input class="form-control" readonly
                                           value="<?= $datos_cliente->codigo_venta ?>">
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-md-6">
                                    <label class="control-label"><b> </b></label>

                                </div>

                            </div>
                        </div>
                        <!--DATOS DE LOS PRODUCTOS PENDIENTES-->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table table-responsive">
                                    <table id="lista_datos" class="table table-bordered">
                                        <thead>
                                        <th style="width: 10%" class="text-center">NRO</th>
                                        <th style="width: 20%" class="text-center">FORMA PAGO</th>
                                        <th style="width: 17%" class="text-center">FECHA PAGO</th>
                                        <th style="width: 15%" class="text-center">MONTO</th>
                                        <th style="width: 12%" class="text-center">SALDO</th>
                                        <th style="width: 20%" class="text-center">ESTADO</th>

                                        </thead>
                                        <tbody>
                                        <?php
                                        $contador = 0;
                                        foreach ($detalle as $row) {
                                            ?>
                                            <tr>
                                                <td hidden><input type="text" value=" <?= $row->id ?> "
                                                                  id="n_venta" name="n_venta"/>
                                                </td>
                                                <td class="text-center">
                                                    <?= $row->id ?>

                                                </td>
                                                <td class="text-center">
                                                    <?= $row->forma_pago ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $row->fecha_pago ?>
                                                </td>

                                                <td class="text-center">
                                                    <?= $row->monto ?>
                                                </td>

                                                <td class="text-center">
                                                    <?= $row->saldo ?>
                                                </td>

                                                <td class="text-center">
                                                    <?= $row->estado ?>
                                                </td>


                                            </tr>

                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <!-- <a onclick="imprimir_historial_pago(<?= $row->id ?>);"
                                       class="btn btn-success " type="submit"
                                    > <i class="fa fa-print"></i> IMPRIMIR HISTORIAL</a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?= base_url('js-sistema/reporte.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js-sistema/historial_pago.js') ?>"></script>