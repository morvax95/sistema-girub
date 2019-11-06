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
                    <center><h2 class="box-title">EDITA LOS DATOS DE LA EMPRESA</h2></center>
                </div>
                <form id="frm_editar_sucursal" action="<?= site_url('sucursal/modificar_sucursal') ?>" method="post"
                      class="form-horizontal">
                    <?php $this->view('sucursal/formulario') ?>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>
<script src="<?= base_url('js-sistema/sucursal.js') ?>"></script>

