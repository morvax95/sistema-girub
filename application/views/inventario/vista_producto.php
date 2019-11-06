<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 04/10/2017
 * Time: 06:09 PM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ol fa-2x"></i> <b>LISTA DE INVENTARIO COMPLETO</b></h3>
                    <div style="float:right">
                        <a href="<?= site_url('inicio') ?>" class="btn btn-danger"><i class="fa fa-sign-out"></i>&nbsp; Volver</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="lista_inventario_general" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-center">Codigo</th>
                            <th class="text-center">Producto</th>
                            <th class="text-center">Color</th>
                            <th class="text-center">Talla</th>
                            <th class="text-center">Precio</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Tipo Producto</th>
                            <th class="text-center">Almacen</th>
                            <th class="text-center">Sucursal</th>
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
<script src="<?= base_url('js-sistema/inventario.js') ?>"></script>
<script>
    $(document).ready(function () {
        listar_inventario_productos();
    });
</script>