<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 07/09/2017
 * Time: 02:51 PM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-address-book-o fa-2x"></i> <b>LISTADO DE ITEMS EN INVENTARIO </b></h3>
                    <div style="float:right">
                        <a href="<?= site_url('producto/ingreso') ?>" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo Ingreso Inventario</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="lista_inventario" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th style="width: 20%" class="text-center">Item</th>
                            <th style="width: 15%" class="text-center">Precio Venta</th>
                            <th style="width: 15%" class="text-center">Tallas</th>
                            <th style="width: 15%" class="text-center">Stock</th>
<!--                            <th style="width: 15%" class="text-center">Estado</th>-->
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url('js-sistema/producto.js') ?>"></script>
