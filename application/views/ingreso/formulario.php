<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 21/09/2017
 * Time: 10:28 PM
 */
?>
<div class="box-body">
    <div class="form-group">
        <label class="col-md-3 control-label text-right"><b>TIPO INGRESO *</b></label>
        <div class="col-md-8">
            <input type="text" id="id_ingreso" name="id_ingreso" value="<?= isset($ingreso) ? $ingreso->id : '' ?>" hidden>
            <select class="form-control" id="tipo_ingreso" name="tipo_ingreso">
                <?php
                foreach ($tipo_ingreso as $row){
                    ?>
                    <option value="<?= $row->id ?>" <?= isset($ingreso) ? is_selected($ingreso->tipo_ingreso_id, $row->id) : '' ?>><?= $row->descripcion ?></option>
                <?php
                }
                ?>
            </select>
            <span>
                <a style="color: red;" href="#modal_registro_tipo_ingreso" data-toggle="modal" title="Agregar tipo ingreso"><i class="fa fa-plus-square"></i> Registrar nuevo tipo de ingreso</a>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label text-right"><b>FECHA INGRESO *</b></label>
        <div class="col-md-8">
            <input type="date" id="fecha_ingreso" name="fecha_ingreso" class="form-control" value="<?= isset($ingreso) ? $ingreso->fecha_ingreso : date('Y-m-d') ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label text-right"><b>MONTO INGRESO *</b></label>
        <div class="col-md-8">
            <input type="number" class="form-control" id="monto" name="monto" value="<?= isset($ingreso) ? $ingreso->monto : '0.00' ?>"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label text-right"><b>DETALLE </b></label>
        <div class="col-md-8">
            <textarea class="form-control" id="detalle" name="detalle" placeholder="Escriba alguna observacion o detalle si tuviera"><?= isset($ingreso) ? $ingreso->detalle : '' ?></textarea>
        </div>
    </div>
</div>
<div class="box-footer text-center">
    <button class="btn btn-primary"><i class="fa fa-save"></i>  Guardar</button>
    <a href="<?= site_url('ingreso/index') ?>" class="btn btn-danger"><i class="fa fa-times"></i>
        Salir</a>
</div>
