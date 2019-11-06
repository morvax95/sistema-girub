<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 06/09/2017
 * Time: 11:28 AM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <center><h2 class="box-title">EDITA LOS DATOS DEL ITEM QUE SELECCIONASTE</h2></center>
                </div>
                <form id="frm_editar_item" class="form-horizontal"
                      action="<?= site_url('producto/modificar_producto') ?>"
                      method="post">
                    <?php $this->view('producto/formulario') ?>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>
<script src="<?= base_url('js-sistema/item.js') ?>"></script>
