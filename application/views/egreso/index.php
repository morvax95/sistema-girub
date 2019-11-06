<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 08/12/2016
 * Time: 12:28 AM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-address-book-o fa-2x"></i><b> LISTA DE EGRESOS</b></h3>
                    <div style="float:right">
                        <a href="<?= site_url('egreso/nuevo') ?>" data-toggle="modal" class="btn btn-success"
                           style="margin: 0%"><i
                                    class="fa fa-plus"></i> Nuevo egreso</a>
                    </div>
                </div>
                <div class="box-body">
                    <table id="lista_egreso" class="table table-bordered">
                        <thead>
                        <tr>
                            <th><b>ID</b></th>
                            <th class="text-center"><b>FECHA</b></th>
                            <th class="text-center"><b>TIPO EGRESO</b></th>
                            <th class="text-center"><b>MONTO EGRESO</b></th>
                            <!-- <th class="text-center"><b>SUCURSAL</b></th>-->
                            <th class="text-center"><b>ESTADO</b></th>
                            <th class="text-center"><b>IMPRIMIR</b></th>
                            <th class="text-center"><b>OPCIONES</b></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url('js-sistema/egreso.js') ?>"></script>
