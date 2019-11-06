<?php

?>
<div class="box-body">
    <div class="form-group">
        <label class="col-md-3 control-label text-right"><b>TIPO EGRESO *</b></label>
        <div class="col-md-8">
            <input type="hidden" id="id_egreso" name="id_egreso" value="<?= isset($egreso) ? $egreso->id : '' ?>">
            <select class="form-control" id="tipo_egreso" name="tipo_egreso">
                <?php
                foreach ($tipo_egreso as $row){
                    ?>
                    <option value="<?= $row->id ?>" <?= isset($egreso) ? is_selected($egreso->tipo_egreso_id, $row->id) : '' ?>><?= $row->descripcion ?></option>
                <?php
                }
                ?>
            </select>
            <span>
                <a style="color: red;" href="#modal_registro_tipo" data-toggle="modal" title="Agregar tipo egreso"><i class="fa fa-plus-square"></i> Registrar nuevo tipo de egreso</a>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label text-right"><b>FECHA EGRESO *</b></label>
        <div class="col-md-8">
            <input type="date" id="fecha_egreso" name="fecha_egreso" class="form-control" value="<?= isset($egreso) ? $egreso->fecha_egreso : date('Y-m-d') ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label text-right"><b>MONTO EGRESADO *</b></label>
        <div class="col-md-8">
            <input type="number" class="form-control" id="monto" name="monto" value="<?= isset($egreso) ? $egreso->monto : '0.00' ?>"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label text-right"><b>DETALLE </b></label>
        <div class="col-md-8">
            <textarea class="form-control" id="detalle" name="detalle" placeholder="Escriba alguna observacion o detalle si tuviera"><?= isset($egreso) ? $egreso->detalle : '' ?></textarea>
        </div>
    </div>
</div>
<div class="box-footer text-center">
    <button class="btn btn-primary"><i class="fa fa-save"></i>  Guardar</button>
    <a href="<?= site_url('egreso/index') ?>" class="btn btn-danger"><i class="fa fa-times"></i>
        Salir</a>
</div>