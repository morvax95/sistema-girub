<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-files-o fa-2x"></i> <b>REPORTE FLUJO DE CAJA</b></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">Flujo caja</a></li>
                            <li><a href="#tab_2" data-toggle="tab">Flujo caja detallado</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1" style="padding: 1%">
                                <div class="row">
                                    <form class="form-horizontal">
                                        <div class="col-lg-8 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label col-lg-4 col-md-4" for="fecha-inicio">Fecha inicio:</label>
                                                <div class="col-lg-8 col-md-8">
                                                    <input type="date" class="form-control" id="fecha-inicio" name="fecha-inicio" value="<?= $fecha_inicio?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-lg-4 col-md-4" for="fecha-fin">Fecha fin:</label>
                                                <div class="col-lg-8 col-md-8">
                                                    <input type="date" class="form-control" id="fecha-fin" name="fecha-fin" value="<?= $fecha_actual?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <a id="buscar-datos">
                                                <img src="<?= base_url('assets/img/buscar.png')?>" title="Buscar">
                                            </a>
                                            <a onclick="" target="_blank" class="hidden">
                                                <img src="<?= base_url('assets/img/pdf.png')?>" title="Exportar a PDF">
                                            </a>
                                            <a onclick="exportar_excel()" target="_blank">
                                                <img src="<?= base_url('assets/img/excel.png')?>" title="Exportar a EXCEL">
                                            </a>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <strong> Ingresos registrados en el sistema</strong>
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <table class="table" cellspacing="0" width="100%" id="table-ingreso">
                                            <thead>
                                            <th>T O T A L &nbsp;&nbsp;&nbsp;&nbsp;   I N G R E S O</th>
                                            <th class="padding-right-hegth" id="td-head-ingreso"></th>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <strong> Egresos registrados en el sistema </strong>
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <table class="table" cellspacing="0" width="100%" id="table-egreso">
                                            <thead>
                                            <th>T O T A L&nbsp;&nbsp;&nbsp;&nbsp;    E G R E S O S</th>
                                            <th class="padding-right-hegth" id="td-head-egreso"></th>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="page-header " style="margin-top: 5px"><strong>Flujo caja: &nbsp;&nbsp;</strong><label for="" id="label-flujo"> </label></h3>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2">
                                <!--FLUJO DE CAJA DETALLADO -->
                                <form class="form-horizontal">
                                    <div class="col-lg-8 col-md-6 col-sm-6 col-xs-5">
                                        <div class="form-group">
                                            <label class="control-label col-lg-4 col-md-4" for="fecha-inicio-detalle">Fecha inicio:</label>
                                            <div class="col-lg-8 col-md-8">
                                                <input type="date" class="form-control" id="fecha-inicio-detalle" name="fecha-inicio-detalle" value="<?= $fecha_inicio?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-4 col-md-4" for="fecha-fin-detalle">Fecha fin:</label>
                                            <div class="col-lg-8 col-md-8">
                                                <input type="date" class="form-control" id="fecha-fin-detalle" name="fecha-fin-detalle" value="<?= $fecha_actual?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-7">
                                        <a id="buscar-datos-detalle">
                                            <img src="<?= base_url('assets/img/buscar.png')?>" title="Buscar">
                                        </a>
                                        <a onclick="" target="_blank" class="hidden">
                                            <img src="<?= base_url('assets/img/pdf.png')?>" title="Exportar a PDF" >
                                        </a>
                                        <a onclick="exportar_excel_detallado()" target="_blank">
                                            <img src="<?= base_url('assets/img/excel.png')?>" title="Exportar a EXCEL">
                                        </a>
                                    </div>
                                </form>
                                <div id="div-tabla">
                                    <table class="table table-bordered table-hover" style="margin-bottom: 3px">
                                        <tr class="header">
                                            <td style="vertical-align:middle;">I N G R E S O S</td>
                                            <td>
                                                <?php
                                                $lista_ingreso = $ingreso['lista_ingresos'];
                                                foreach ($lista_ingreso as $row_ingreso):?>
                                                    <table class="table table-bordered table-hover" style="margin-bottom: 3px">
                                                        <tr>
                                                            <td style="width: 30%;vertical-align:middle;"><?= $row_ingreso['descripcion'] ?></td>
                                                            <td>
                                                                <table class="table table-bordered table-hover">
                                                                    <tr>
                                                                        <td style="background-color:#c0c0c0;color:#ffffff">Fecha</td>
                                                                        <td style="background-color:#c0c0c0;color:#ffffff">Concepto</td>
                                                                        <td style="background-color:#c0c0c0;color:#ffffff">Monto</td>
                                                                    </tr>
                                                                    <?php
                                                                    $lista = $row_ingreso['lista'];
                                                                    $monto = 0;
                                                                    foreach ($lista as $row):
                                                                        ?>
                                                                        <tr>
                                                                            <td><?= $row->fecha_registro ?></td>
                                                                            <td><?= $row->detalle ?></td>
                                                                            <td style="text-align: right"><?= $row->monto ?></td>
                                                                        </tr>
                                                                        <?php $monto = $monto + $row->monto; endforeach; ?>
                                                                    <tr>
                                                                        <td colspan="2" style="text-align: right"> Monto total</td>
                                                                        <td style="text-align: right"><?= number_format($monto, 2) ?></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php endforeach; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:middle;">E G R E S O S</td>
                                            <td>
                                                <?php
                                                $lista_egreso = $egreso['lista_egresos'];
                                                foreach ($lista_egreso as $row_egreso):?>
                                                    <table class="table table-bordered table-hover" style="margin-bottom: 3px">
                                                        <tr>
                                                            <td style="width: 30%;vertical-align:middle;"><?= $row_egreso['descripcion'] ?></td>
                                                            <td>
                                                                <table class="table table-bordered table-hover"
                                                                       style="margin-bottom: 3px">
                                                                    <tr>
                                                                        <td style="background-color:#c0c0c0;color:#ffffff">Fecha</td>
                                                                        <td style="background-color:#c0c0c0;color:#ffffff">Concepto</td>
                                                                        <td style="background-color:#c0c0c0;color:#ffffff">Monto</td>
                                                                    </tr>
                                                                    <?php
                                                                    $lista = $row_egreso['lista'];
                                                                    $monto_egreso = 0;
                                                                    foreach ($lista as $row):
                                                                        ?>
                                                                        <tr>
                                                                            <td><?= $row->fecha_registro ?></td>
                                                                            <td><?= $row->detalle ?></td>
                                                                            <td style="text-align: right"><?= $row->monto ?></td>
                                                                        </tr>
                                                                        <?php $monto_egreso = $monto_egreso + $row->monto; endforeach; ?>
                                                                    <tr>
                                                                        <td colspan="2" style="text-align: right"> Monto total</td>
                                                                        <td style="text-align: right"><?= number_format($monto_egreso, 2) ?></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php endforeach; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?= base_url('js-sistema/flujo.js') ?>"></script>