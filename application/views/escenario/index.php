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
                    <h3 class="box-title"><i class="fa fa-address-book-o fa-2x"></i> <b>GESTIÓN DE ESCENARIOS</b>
                    </h3>
                    <div style="float:right">
                        <a href="<?= site_url('escenario/nuevo') ?>" class="btn btn-success "><i
                                    class="fa fa-plus"></i>
                            Nuevo Escenario</a>

                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="alert alert-success alert-dismissible boton turquesa" style="font-size: 12pt">
                        <!-- <div class="alert boton turquesa alert-dismissible boton turquesa" style="font-size: 12pt">-->
                        <h4><i class="icon fa fa-info"></i> Aviso!</h4>
                        Puede realizar búsquedas de un escenario mediante su nombre .
                    </div>
                    <table id="lista_cliente" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-center">CÓDIGO</th>
                            <th class="text-center">NOMBRE ESCENARIO</th>
                            <th class="text-center">DESCRIPCION</th>
                            <th class="text-center">TIPO ESCENARIO</th>
                            <th class="text-center">ESTADO</th>
                            <th class="text-center">OPCIONES</th>
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
                <center><h4 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> DATOS DEL ESCENARIO
                            DEPORTIVO</b>
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
                        <label class="col-md-3 control-label text-right"><b>TIPO ESCENARIO</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="ver_tipo" name="ver_tipo" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>JUGADORES</b></label>
                        <div class="col-md-7">
                            <input type="number" id="ver_cantidad" name="ver_cantidad" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>DESCRIPCION</b></label>
                        <div class="col-md-7">
                            <input type="text" id="ver_descripcion" name="ver_descripcion" class="form-control" readonly/>
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

<script src="<?= base_url('js-sistema/escenario.js') ?>"></script>
