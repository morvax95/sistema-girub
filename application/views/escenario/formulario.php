<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 15/02/2018
 * Time: 01:05 AM
 */
?>

<div class="box-body">

    <div class="form-group">
        <div class="col-sm-8" hidden>
            <input type="text" id="id_cliente" name="id_cliente" value="<?= isset($escenario) ? $escenario->id : '' ?>"
                   hidden>

        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">NOMBRE ESCENARIO *</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="nombre_escenario" name="nombre_escenario"
                   value="<?= isset($escenario) ? $escenario->nombre_escenario : '' ?>"
                   placeholder="Escriba el nombre del escenario">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">DESCRIPCIÓN </label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="descripcion" name="descripcion"
                   value="<?= isset($escenario) ? $escenario->descripcion : '' ?>"
                   placeholder="Escriba una descripcion">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">CANTIDAD JUGADORES</label>
        <div class="col-sm-8">
            <input type="number" class="form-control" id="cantidad" name="cantidad"
                   value="<?= isset($escenario) ? $escenario->numeroJugadores : '' ?>"
                   placeholder="Ingrese la cantidad de jugadores">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">TIPO ESCENARIO</label>
        <div class="col-sm-8">
            <select id="tipo_escenario" name="tipo_escenario" class="form-control">
                <?php
                foreach ($tipo as $row) {
                    ?>
                    <option value="<?= $row->id ?>"><?= $row->nombre ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>

    <div class="col-lg-offset-1 col-lg-10">
        <div class="alert alert-success alert-dismissible">
            <h4><i class="icon fa fa-info"></i> Aviso!</h4>
            Los campos con (*) son requidos.
        </div>
    </div>
</div>
<div class="box-footer text-center">
    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
    <a href="<?= site_url('escenario/index') ?>" class="btn btn-danger"><i class="fa fa-times"></i>
        Salir</a>
</div>
