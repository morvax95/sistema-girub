<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 21/10/2017
 * Time: 09:12 AM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-user fa-2x"></i> <b> LISTA DE CUMPLEAÑEROS DEL MES</b></h3>
                    <div style="float:right">
                        <a class="btn btn-primary" href="<?= site_url('cliente/imprimir') ?>" target="_blank"><i class="fa fa-print"></i> Imprimir listado</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php
                    $this->load->view('cliente/lista');
                    ?>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>