<?php

$data_user = $this->session->userdata('usuario_sesion');
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-files-o fa-2x"></i> <b>REPORTE DE VENTAS DIARIAS</b>
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
                                            <?php
                                            if ($data_user['cargo'] == 1) {
                                                ?>
                                                <div class="col-md-3">
                                                    <label class="control-label"><b>Sucursal</b></label>
                                                    <select id="sucursales" name="sucursales" class="form-control">
                                                        <?php
                                                        foreach ($sucursales as $row) {
                                                            ?>
                                                            <option value="<?= $row->id ?>"><?= $row->sucursal ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div class="col-md-2">
                                                <label class="control-label"><b>Fecha </b></label>
                                                <input type="date"  class="form-control" id="fecha_inicio" name="fecha_inicio"
                                                       >
                                            </div>

                                        </form>
                                        <div class="col-md-1" style="width: 8%">
                                            <button class="btn btn-danger btn-sm" onclick="buscarVentas_diaria();"
                                                    title="Busqueda de los datos"><i
                                                        class="fa fa-search"></i> Buscar
                                            </button>
                                        </div>
                                        <div class="col-md-1" style="width: 7%">
                                            <a class="btn btn-success btn-sm" onclick="exportar_excel_ventas_diarias()"
                                               title="Exportacion a archivo excel">
                                                <i class="fa fa-file-excel-o"></i> Excel
                                            </a class="btn">
                                        </div>
                                        <div class="col-md-1">
                                            <a class="btn btn-warning btn-sm" onclick="imprimir_ventas_diarias()"
                                               href="" target="_blank"
                                               title="Exportacion a archivo PDF">
                                                <i class="fa fa-file-pdf-o"></i> PDF
                                            </a class="btn">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table table-bordered" id="lista_reporte_ventas">
                                <thead>
                                <th class="text-center" style="width: 9%">NRO </th>
                                <th class="text-center" style="width: 25%">CLIENTE</th>
                                <th class="text-center" style="width: 28%">PRODUCTO</th>
                                <th class="text-center" style="width: 10%">PRECIO V.</th>
                                <th class="text-center" style="width: 10%">CANTIDAD</th>
                                <th class="text-center" style="width: 15%">MONTO TOTAL</th>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?= base_url('js-sistema/reporte.js') ?>"></script>
