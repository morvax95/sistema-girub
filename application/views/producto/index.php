<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 17/02/2018
 * Time: 09:56 AM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-address-book-o fa-2x"></i> <b>
                            REGISTRA TUS PRODUCTOS Y SERVICIOS
                        </b></h3>
                    <div style="float:right">
                        <a href="<?= site_url('producto/nuevo') ?>" class="btn btn-success"><i class="fa fa-plus"></i>
                            Nuevo producto</a>
                        <!--<a href="<?= site_url('producto/imprimir_codigos') ?>" target="_blank" class="btn btn-danger" ><i class="fa fa-print"></i> Imprimir Codigos</a>-->
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="lista_item" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th style="width: 10%" class="text-center">CÓDIGO</th>
                            <th style="width: 20%" class="text-center">NOMBRE PRODUCTO</th>
                            <th style="width: 8%" class="text-center">PRECIO VENTA</th>
                            <!--<th style="width: 5%" class="text-center">STOCK M.</th>-->
                            <th style="width: 5%" class="text-center"
                                title="Indica el estado del registro si esta habilitado o no">ESTADO
                            </th>
                            <th style="width: 13%" class="text-center">OPCIONES</th>
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
<script src="<?= base_url('js-sistema/item.js') ?>"></script>
<!--modal ver productos-->
<div id="modal_ver_productos" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h4 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> DATOS DEL
                            PRODUCTO</b></h4></center>
            </div>
            <form id="frm_ver_cliente" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>CÓDIGO </b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="ver_codigo" name="ver_codigo" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>PRODUCTO</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="ver_nombre" name="ver_nombre" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>PRECIO VENTA</b></label>
                        <div class="col-md-7">
                            <input type="number" id="ver_precio_venta" name="ver_precio_venta" class="form-control"
                                   readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>PRECIO COMPRA</b></label>
                        <div class="col-md-7">
                            <input type="text" id="ver_precio_compra" name="ver_precio_compra" class="form-control"
                                   readonly/>
                        </div>
                    </div>




                </div>
                <div class="modal-footer">
                    <a id="btn_cerrar-_modal_ver" class="btn btn-danger" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </a>
                </div>
            </form>
        </div>
    </div>
    <style>
        label {
            color: black;
        }
    </style>
</div>