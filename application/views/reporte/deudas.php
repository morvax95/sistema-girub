<?php
/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 20/04/2018
 * Time: 10:04 PM
 */
$data_user = $this->session->userdata('usuario_sesion');
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-files-o fa-2x"></i> <b>REPORTE DE DEUDAS POR COBRAR</b>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="panel panel-default">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <form id="datos_busqueda_deudas">
                                            <!--<?php
                                            //if ($data_user['cargo'] == 1) {
                                            ?>-->
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
                                            <!--  <?php
                                            //  }
                                            ?>-->
                                            <div class="col-md-3">
                                                <label class="control-label"><b>Fecha desde</b></label>
                                                <input type="date" class="form-control" id="fecha_inicio"
                                                       name="fecha_inicio" name="fecha_fin" value="<?= $fecha_inicio ?>"
                                                >
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label"><b>Fecha Hasta</b></label>
                                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                                       name="fecha_fin" value="<?= $fecha_actual ?>"
                                                >
                                            </div>
                                        </form>
                                        <div class="col-md-1" style="width: 8%">
                                            <button class="btn btn-danger btn-sm" onclick="buscar_deudas();"
                                                    title="Busqueda de los datos"><i
                                                        class="fa fa-search"></i> Buscar
                                            </button>
                                        </div>

                                        <div class="col-md-1">
                                            <a class="btn btn-warning btn-sm" onclick="imprimir_deudas()"
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
                            <table id="lista_deudas_reporte" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center">Nro.</th>
                                    <th class="text-center">Fecha venta</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Total venta</th>
                                    <th class="text-center">Total Pagado</th>
                                    <th class="text-center">Saldo</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?= base_url('js-sistema/reporte.js') ?>"></script>
<script>

</script>
