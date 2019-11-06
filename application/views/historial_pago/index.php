<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 06/03/2018
 * Time: 12:17 PM
 */
?>
<section class="content">
    <div class="row">

        <div class=" col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-print fa-2x"></i> <b>HISTORIAL PAGOS</b>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="alert alert-success alert-dismissible" style="font-size: 12pt">
                        <h4><i class="icon fa fa-info"></i> Aviso!</h4>
                        Puede realizar búsquedas del historial de pagos mediante su nombre .
                    </div>

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">Historial Pagos</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1" style="padding: 1%">
                                <table class="table table-bordered table-striped" id="lista_nota">
                                    <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">NRO. </th>
                                        <th class="text-center">NOMBRE CLIENTE</th>
                                        <th class="text-center">MONTO VENTA</th>
                                        <th class="text-center">ESTADO</th>
                                        <th class="text-center">IMPRIMIR</th>
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
            </div>
        </div>

    </div>
</section>
<script type="text/javascript" src="<?= base_url('js-sistema/historial_pago.js') ?>"></script>
