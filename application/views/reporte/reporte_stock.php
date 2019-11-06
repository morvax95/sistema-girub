<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 05/11/2017
 * Time: 11:21 PM
 */
$data_user = $this->session->userdata('usuario_sesion');
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-files-o fa-2x"></i> <b>REPORTE DE STOCK MINIMO</b>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="panel panel-default">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <form id="datos_busqueda_stock">
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
                                        </form>
                                        <div class="col-md-1" style="width: 8%">
                                            <button class="btn btn-danger btn-sm" onclick="buscar_stock_minimo();"
                                                    title="Busqueda de los datos"><i
                                                        class="fa fa-search"></i> Buscar
                                            </button>
                                        </div>
                                        <div class="col-md-1" style="width:12%;">
                                            <a class="btn btn-success btn-sm" onclick="exportar_excel_stock_minimo()"
                                               title="Exportacion a archivo excel">
                                                <i class="fa fa-file-excel-o"></i> Exportar excel
                                            </a class="btn">
                                        </div>
                                        <div class="col-md-1">
                                            <a class="btn btn-warning btn-sm" onclick="imprimir_stock()"
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
                            <table class="table table-bordered" id="lista_reporte_stock">
                                <thead>
                                <th>Nro.</th>
                                <th class="text-center">Codigo</th>
                                <th class="text-center">Producto</th>
                                <th class="text-center">Color</th>
                                <th class="text-center">Talla</th>
                                <th class="text-center">Precio</th>
                                <th class="text-center">Cantidad</th>
                                <!--<th class="text-center">Deposito</th>-->
                                <th class="text-center">Estado</th>

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
