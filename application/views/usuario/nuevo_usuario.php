<?php
$nombre = $this->session->userdata('usuario_sesion');

?>
<?php if ($nombre['cargo'] === '1') { ?>
    <section class="content">
        <div class="row">
            <form id="frm_registrar_usuario" class="form-horizontal"
                  action="<?= site_url('usuario/registrar_usuario') ?>" method="post">
                <?php $this->load->view('usuario/formulario') ?>
            </form>
        </div>
    </section>
    <script src="<?= base_url('js-sistema/usuario.js') ?>"></script>
<?php } else { ?>
    <br>
    <br>
    <br>
    <div class="form-group">
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible">
                <h4><i class="icon fa fa-info"></i> AVISO!</h4>
                <i class="fa fa-info"></i>&nbsp; NO TIENE ACCESO A ESTA PARTE DEL SISTEMA
            </div>
        </div>
    </div>

    <?php

}
?>