<?php

?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <center><h2 class="box-title">REGISTRA TUS EGRESOS</h2></center>
                </div>
                <form id="frm_registrar_egreso" action="<?= site_url('egreso/registro_egreso') ?>" method="post" class="form-horizontal">
                    <?php $this->view('egreso/formulario') ?>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>

<div id="modal_registro_tipo" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> REGISTRA UN NUEVO TIPO DE EGRESO</b></center>
            </div>
            <form id="frm_tipo_egreso" class="form-horizontal"
                  action="<?= site_url('egreso/registrar_tipo_egreso') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>DESCRIPCIÓN *</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="descripcion" name="descripcion" value=""
                                   placeholder="Campo requerido"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    <a id="btn_cerrar_modal_tipo" class="btn btn-danger" data-dismiss="modal"><i
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

<script src="<?= base_url('js-sistema/egreso.js') ?>"></script>