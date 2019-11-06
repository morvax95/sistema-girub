<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 16/02/2018
 * Time: 12:15 PM
 */
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <center><h2 class="box-title"><i class="fa fa-address-book-o fa-2x"></i><b> REGISTRA LOS DATOS DEL
                                CLIENTE</b></h2></center>
                </div>
                <form id="frm_registrar_cliente" action="<?= site_url('cliente/registrar_cliente') ?>" method="post"
                      class="form-horizontal">
                    <?php $this->view('cliente/formulario') ?>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>

<!-- REGISTRO DE TALLA -->
<div id="modal_registro_talla" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: black"><b>Registra una nueva talla de prenda</b></h5>
            </div>
            <form id="frm_registro_talla" class="form-horizontal"
                  action="<?= site_url('producto/registrar_talla') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>Talla</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="descripcion_talla" name="descripcion_talla"
                                   value="" autofocus
                                   placeholder="Campo requerido"/>
                            <input id="type_data" name="type_data" value="1" hidden>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    <a id="btn_cerrar_modal_talla" class="btn btn-danger" data-dismiss="modal"><i
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


<script src="<?= base_url('js-sistema/cliente.js') ?>"></script>

