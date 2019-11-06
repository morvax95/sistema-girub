<?php

?>
<section class="content">
    <div class="row">
        <div class=" col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-print fa-2x"></i> <b>CONSULTAR VENTA</b></h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">

                    <div class="col-lg-12">
                        <div class="alert alert-success alert-dismissible">
                            <h4><i class="icon fa fa-info"></i> Aviso!</h4>
                            <i class="fa fa-info"></i>&nbsp; Los datos que se listan a continuación son registros de
                            la sucursal con la que inicio sesión.
                        </div>
                    </div>

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">VENTAS</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1" style="padding: 1%">
                                <table class="table table-bordered table-striped" id="lista_nota">
                                    <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">NRO. NOTA</th>
                                        <th class="text-center">CLIENTE</th>
                                        <th class="text-center">FECHA</th>
                                        <th class="text-center">MONTO TOTAL</th>
                                        <th class="text-center">FORMA PAGO</th>
                                        <th class="text-center">IMPRIMIR</th>
                                        <!-- <th class="text-center">ANULAR</th>-->
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
<script type="text/javascript" src="<?= base_url('js-sistema/consultar_venta.js') ?>"></script>