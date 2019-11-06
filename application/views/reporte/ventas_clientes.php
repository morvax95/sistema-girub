<?php
/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 16/04/2018
 * Time: 05:48 PM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-files-o fa-2x"></i> <b>Reporte de Facturas por Usuario</b>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="panel panel-default">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <form id="datos_busqueda">
                                        <div class="col-md-2">
                                            <label class="control-label"><b>Fecha desde</b></label>
                                            <input type="date" id="fecha_inicio" name="fecha_inicio"
                                                   value="<?= date('Y-m') ?>-01" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label"><b>Fecha Hasta</b></label>
                                            <input type="date" id="fecha_fin" name="fecha_fin"
                                                   value="<?= date('Y-m-d') ?>" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label"><b>CI / NIT </b></label>
                                            <input style="font-size: 13pt; font-weight: bold" type="number"
                                                   id="nit_cliente"
                                                   name="nit_cliente" class="form-control"
                                                   placeholder="Busque por NIT"/>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label"><b>Nombre Cliente</b></label>
                                            <input style="font-size: 13pt; font-weight: bold" type="text"
                                                   id="nombre_cliente"
                                                   name="nombre_cliente" class="form-control"
                                                   value="" placeholder="Busque por nombre del cliente"/>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="control-label"><b>Nro. Factura</b></label>
                                            <input type="number" id="numero_factura"
                                                   name="numero_factura" class="form-control"
                                                   value="" placeholder="Factura?"/>
                                        </div>
                                        </form>
                                        <div class="col-md-1" style="width: 8%">
                                            <button class="btn btn-danger btn-sm" onclick="buscarVentas();" title="Busqueda de los datos"><i
                                                        class="fa fa-search"></i> Buscar
                                            </button>
                                        </div>
<!--                                        <div class="col-md-1">-->
<!--                                            <a class="btn btn-success btn-sm" onclick="exportar_excel_ventas_emitidas()" title="Exportacion a archivo excel">-->
<!--                                                <i class="fa fa-file-excel-o"></i> Exportar excel-->
<!--                                            </a class="btn">-->
<!--                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table table-bordered" id="ventas_emitidas">
                                <thead>
                                <th class="text-center" style="width: 5%">NRO.</th>
                                <th class="text-center" style="width: 10%">NRO. FACTURA</th>
                                <th class="text-center" style="width: 12%">FECHA</th>
                                <th class="text-center" style="width: 15%">NIT</th>
                                <th class="text-center" style="width: 28%">CLIENTE</th>
                                <th class="text-center" style="width: 15%">MONTO TOTAL</th>
                                <th class="text-center" style="width: 15%">USUARIO</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?= base_url('js-sistema/reporte.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js-sistema/venta.js') ?>"></script>

