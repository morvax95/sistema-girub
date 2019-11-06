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
                    <h2 class="box-title"><i class="fa fa-database"></i> DATOS CORRESPONDIENTES A LA VENTA
                    </h2>
                    <div style="float:right">
                        <a href="<?= site_url('Venta_proceso/index') ?>" class="btn btn-danger"><i
                                    class="fa fa-arrow-left"></i>&nbsp; Volver</a>
                    </div>

                </div>
                <div class="form-horizontal">
                    <div class="box-body">
                        <div class="col-md-6">
                            <a href="<?= site_url('producto_produccion/index') ?>" class="btn btn-succes"><i
                                        class="fa fa-search"></i>&nbsp; Ver Inventario</a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= site_url('pago/listar') ?>" class="btn btn-succes"><i
                                        class="fa fa-search"></i>&nbsp; Buscar Deudas</a>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" style="margin-bottom: 0%">



                                <div class="col-md-3">
                                    <label class="control-label"><b>NRO. NOTA</b></label>
                                    <input class="form-control" readonly
                                           value="<?= $datos_cliente->nro_nota ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label"><b>CI / NIT</b></label>
                                    <input class="form-control" readonly
                                           value="<?= $datos_cliente->ci ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label"><b>NOMBRE </b></label>
                                    <input type="text" class="form-control"
                                           value="<?= $datos_cliente->nombre ?>" readonly/>
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
                                        <th style="width: 13%" class="text-center">Código Producto</th>
                                        <th style="width: 26%" class="text-center">Nombre Producto</th>
                                        <th style="width: 12%" class="text-center">Cantidad Producción</th>
                                        <th style="width: 10%" class="text-center">Estado</th>
                                      <!--<th style="width: 10%" class="text-center">Opciones</th>-->

                                        </thead>
                                        <tbody>
                                        <?php
                                        $contador = 0;
                                        foreach ($detalle as $row) {
                                            ?>
                                            <tr>
                                                <td hidden><input type="text" value=" <?= $row->codigo_venta ?> "
                                                                  id="n_venta" name="n_venta"/>
                                                </td>
                                                <td class="text-center">
                                                    <?= $row->codigo_barra ?>

                                                </td>
                                                <td class="text-center">
                                                    <?= $row->nombre_item ?>
                                                </td>

                                                <td class="text-center">
                                                    <?= $row->suma_total ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php

                                                    $contador++;
                                                    $val1 = "";
                                                    if ($row->estado_entrega == 5) {
                                                        $val1 = '<option id="estado_venta' . $contador . '" name="estado_venta[]"  value="5">Entregado</option>
                                                        <option  value="4">Pendiente</option>';
                                                    } else {
                                                        $val1 = '<option id="estado_venta' . $contador . '" name="estado_venta[]" value="4">Pendiente</option>
                                                        <option  value="5">Entregado</option>';

                                                    }
                                                    ?>
                                                    <!--<select class="form-control">
                                                       <?= $val1 ?>
                                                    </select>-->
                                                    <?= 'PENDIENTE' ?>

                                                </td>

                                             <!--  <td class="text-center">
                                                    <a onclick="modificar_estado_venta(<?= $row->codigo_venta ?>);"
                                                       class="btn btn-success  "><i
                                                                class="fa fa-upload"></i> MODIFICAR</a>
                                                </td>-->

                                            </tr>

                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <a onclick="modificar_estado_venta(<?= $row->codigo_venta ?>);"
                                       class="btn btn-success  "><i
                                                class="fa fa-upload"></i> MODIFICAR</a>
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
<script type="text/javascript" src="<?= base_url('js-sistema/venta_proceso.js') ?>"></script>