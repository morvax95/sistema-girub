<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 08/05/2017
 * Time: 12:50 PM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-files-o fa-2x"></i> <b>REPORTE LCV (Libro de ventas I.V.A)</b></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="panel panel-default">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label style="width: 4%" class="col-md-1"><b>MES</b></label>
                                        <div style="width: 12%" class="col-md-2">
                                            <select class="form-control" id="mes" name="mes">
                                                <option value="01">ENERO</option>
                                                <option value="02">FEBRERO</option>
                                                <option value="03">MARZO</option>
                                                <option value="04">ABRIL</option>
                                                <option value="05">MAYO</option>
                                                <option value="06">JUNIO</option>
                                                <option value="07">JULIO</option>
                                                <option value="08">AGOSTO</option>
                                                <option value="09">SEPTIEMBRE</option>
                                                <option value="10">OCTUBRE</option>
                                                <option value="11">NOVIEMBRE</option>
                                                <option value="12">DICIEMBRE</option>
                                            </select>
                                        </div>
                                        <label style="width: 4%" class="col-md-1"><b>AÑO</b></label>
                                        <div class="col-md-1">
                                            <input type="text" class="form-control" id="anio" name="anio" value="<?= date('Y') ?>">
                                        </div>
                                        <label class="col-md-1"><b>SUCURSAL</b></label>
                                        <div class="col-md-2">
                                            <select class="form-control" id="sucursal_lcv" name="sucursal_lcv" value="<?= date('Y') ?>">
                                                <?php
                                                foreach ($sucursal as $row){
                                                    ?>
                                                    <option value="<?= $row->id ?>"><?= $row->sucursal ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-danger" onclick="buscar();"><i class="fa fa-search"></i> Buscar</button>
                                        </div>
                                        <div class="col-md-2" style="text-align: center"><a class="btn bg-blue-gradient" onclick="exportar_txt_lcv()"><i class="fa fa-file-text-o fa-3x"></i><br>EXPORTAR TXT</a></div>
                                        <div class="col-md-2" style="text-align: center"><a class="btn bg-green" onclick="exportar_excel_lcv()"><i class="fa fa-file-excel-o fa-3x"></i><br>EXPORTAR EXCEL</a class="btn"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table table-bordered" id="lista_lcv">
                                <thead>
                                <th class="text-center" style="width: 10%">NRO. FACTURA</th>
                                <th class="text-center" style="width: 15%">AUTORIZACION</th>
                                <th class="text-center" style="width: 12%">FECHA</th>
                                <th class="text-center" style="width: 12%">NIT</th>
                                <th class="text-center" style="width: 25%">CLIENTE</th>
                                <th class="text-center" style="width: 13%">MONTO TOTAL</th>
                                <th class="text-center" style="width: 13%">SUCURSAL</th>
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
