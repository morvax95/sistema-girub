<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 16/02/2018
 * Time: 11:02 AM
 */
?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-address-book-o fa-2x"></i> <b>GESTIÓN DE EMPRESA</b>
                    </h3>
                    <div style="float:right">
                        <!-- <a href="<?= site_url('sucursal/nuevo') ?>" class="btn btn-success "><i
                                    class="fa fa-plus"></i>
                            Nuevo cliente</a>-->

                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <table class="table table-bordered table-striped" id="lista_sucursal">
                        <thead>
                        <tr>
                            <th class="">ID</th>
                            <th class="">NOMBRE</th>
                            <th class="">DIRECCIÓN</th>
                            <th class="">TELÉFONO</th>
                            <th class="">EMAIL</th>
                            <th class="">SUCURSAL</th>
                            <th class="">ESTADO</th>
                            <th class="">OPCIONES</th>
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
<div id="modal_ver_cliente" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h4 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> DATOS DE LA
                            EMPRESA</b>
                    </h4></center>
            </div>
            <form id="frm_ver_cliente" class="form-horizontal">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>NOMBRE</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="ver_nombre" name="ver_nombre" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>TELÉFONO</b></label>
                        <div class="col-md-7">
                            <input type="text" id="ver_telefono" name="ver_telefono" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>DIRECCIÓN</b></label>
                        <div class="col-md-7">
                            <input type="text" id="ver_direccion" name="ver_direccion" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direc_cliente" class="col-sm-3 control-label">EMAIL</label>

                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="ver_email" name="ver_email" readonly>
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

<script src="<?= base_url('js-sistema/sucursal.js') ?>"></script>
