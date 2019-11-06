<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 04/09/2017
 * Time: 09:51 AM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <center><h2 class="box-title">EDITA LOS DATOS DEL ESCENARIO SELECCIONADO</h2></center>
                </div>
                <form id="frm_editar_cliente" action="<?= site_url('escenario/modificar_cliente') ?>" method="post"
                      class="form-horizontal">
                    <?php $this->view('escenario/formulario') ?>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>
<script src="<?= base_url('js-sistema/escenario.js') ?>"></script>

