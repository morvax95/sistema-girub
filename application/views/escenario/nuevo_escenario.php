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
                    <center><h2 class="box-title"><i class="fa fa-address-book-o fa-2x"></i><b> REGISTRA
                                ESCENARIO DEPORTIVO</b></h2></center>
                </div>
                <form id="frm_registrar_cliente" action="<?= site_url('escenario/registrar_escenario') ?>" method="post"
                      class="form-horizontal">
                    <?php $this->view('escenario/formulario') ?>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>

<script src="<?= base_url('js-sistema/escenario.js') ?>"></script>

