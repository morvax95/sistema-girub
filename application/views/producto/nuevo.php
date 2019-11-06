<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 22/02/2018
 * Time: 12:14 PM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <center><h2 class="box-title">REGISTRO DE PRODUCTOS O SERVICIOS </h2></center>
                </div>
                <form id="frm_registrar_item" class="form-horizontal"
                      action="<?= site_url('producto/registrar_producto') ?>"
                      method="post">
                    <?php $this->view('producto/formulario') ?>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>

<!-- REGISTRO DE MARCA -->
<div id="modal_registro_marca" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> Registra un nueva
                            Marca</b>
                    </h5></center>
            </div>
            <form id="frm_registro_marcas" class="form-horizontal"
                  action="<?= site_url('producto/registrar_marca') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>MARCA</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="descripcion_marca" name="descripcion_marca"
                                   value="" autofocus
                                   placeholder="Campo requerido"/>
                            <input id="type_data" name="type_data" value="0" hidden>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-12">
                     <div class="alert alert-success alert-dismissible">
                         <h4><i class="icon fa fa-info"></i> Aviso!</h4>
                         Los campos con (*) son requidos.
                     </div>
                 </div>-->
                <div class="modal-footer text-center">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    <a id="btn_cerrar_modal_marcas" class="btn btn-danger" data-dismiss="modal"><i
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

<!-- REGISTRO DE CATEGORIA INTERNA -->
<div id="modal_registro_categoria_interna" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <center><h5 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> Registra una nueva
                            categoria Interna</b>
                    </h5></center>
            </div>
            <form id="frm_registro_categoria_interna" class="form-horizontal"
                  action="<?= site_url('producto/registrar_categoria_interna') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>CATEGORÍA</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="descripcion_categoria_interna"
                                   name="descripcion_categoria_interna"
                                   value="" autofocus
                                   placeholder="Campo requerido"/>
                            <input id="type_data" name="type_data" value="1" hidden>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    <a id="btn_cerrar_modal_unidad_medida" class="btn btn-danger" data-dismiss="modal"><i
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
<!-- REGISTRO DE UNIDAD MEDIDA Y UNIDAD DE COMPRA -->
<div id="modal_registro_unidad_medida" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <center><h5 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> Registra una nueva
                            unidad de medida y de compra</b>
                    </h5></center>
            </div>
            <form id="frm_registro_unidad_medida" class="form-horizontal"
                  action="<?= site_url('producto/registrar_unidad_medida') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>UNIDAD</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="descripcion_unidad_medida"
                                   name="descripcion_unidad_medida"
                                   value="" autofocus
                                   placeholder="Campo requerido"/>
                            <input id="type_data" name="type_data" value="1" hidden>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>ABREVIATURA</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="abreviatura_unidad_medida"
                                   name="abreviatura_unidad_medida"
                                   value="" autofocus
                                   placeholder="Campo requerido"/>
                            <input id="type_data" name="type_data" value="1" hidden>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    <a id="btn_cerrar_modal_unidad_medida" class="btn btn-danger" data-dismiss="modal"><i
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

<script src="<?= base_url('js-sistema/item.js') ?>"></script>
