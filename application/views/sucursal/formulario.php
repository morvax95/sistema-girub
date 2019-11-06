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


        <label class="col-sm-3 control-label">NIT </label>
        <div class="col-sm-8">
            <input type="text" id="id_cliente" name="id_cliente" value="<?= isset($cliente) ? $cliente->id : '' ?>"
                   hidden>
            <input type="text" class="form-control" id="nit_sucursal" name="nit_sucursal"
                   value="<?= isset($cliente) ? $cliente->nit : '' ?>"
                   placeholder="Escriba en nro de carnet o NIT">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">NOMBRE *</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa"
                   value="<?= isset($cliente) ? $cliente->nombre_empresa : '' ?>"
                   placeholder="Escriba el nombre completo">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">TELÉFONO *</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="telefono_sucursal" name="telefono_sucursal"
                   value="<?= isset($cliente) ? $cliente->telefono : '' ?>"
                   placeholder="Escriba el nro de telefono o celular">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">EMAIL</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="correo_sucursal" name="correo_sucursal"
                   value="<?= isset($cliente) ? $cliente->email : '' ?>"
                   placeholder="Ingrese un correo electronico">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">DIRECCIÓN</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="direccion_sucursal" name="direccion_sucursal"
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
    <a href="<?= site_url('sucursal/index') ?>" class="btn btn-danger"><i class="fa fa-times"></i>
        Salir</a>
</div>
