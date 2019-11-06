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


        <label class="col-sm-3 control-label">CÉDULA DE IDENTIDAD/NIT </label>
        <div class="col-sm-8">
            <input type="text" id="id_cliente" name="id_cliente" value="<?= isset($cliente) ? $cliente->id : '' ?>"
                   hidden>
            <input type="text" class="form-control" id="ci_nit" name="ci_nit"
                   value="<?= isset($cliente) ? $cliente->ci_nit : '' ?>"
                   placeholder="Escriba en nro de carnet o NIT">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">NOMBRE COMPLETO *</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente"
                   value="<?= isset($cliente) ? $cliente->nombre_cliente : '' ?>"
                   placeholder="Escriba el nombre completo">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">APELLIDO PATERNO</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                   value="<?= isset($cliente) ? $cliente->nombre_cliente : '' ?>"
                   placeholder="Escriba el apellido paterno">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">APELLIDO MATERNO</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno"
                   value="<?= isset($cliente) ? $cliente->nombre_cliente : '' ?>"
                   placeholder="Escriba el apellido materno">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">NÚMERO CELULAR *</label>
        <div class="col-sm-8">
            <input type="number" class="form-control" id="telefono" name="telefono"
                   value="<?= isset($cliente) ? $cliente->telefono : '' ?>"
                   placeholder="Escriba el nro de telefono o celular">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">CORREO ELECTRÓNICO</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="correo" name="correo"
                   value="<?= isset($cliente) ? $cliente->correo : '' ?>"
                   placeholder="Ingrese un correo electronico">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">DIRECCIÓN DOMICILIO</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="direccion" name="direccion"
                   value="<?= isset($cliente) ? $cliente->direccion : '' ?>"
                   placeholder="Ingrese la direccion actual de su domicilio">
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
    <a href="<?= site_url('cliente/index') ?>" class="btn btn-danger"><i class="fa fa-times"></i>
        Salir</a>
</div>
