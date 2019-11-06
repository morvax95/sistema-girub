<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 06/09/2017
 * Time: 02:44 PM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h2 class="box-title">INGRESO DE ITEMS AL INVENTARIO</h2>
                </div>
                <form id="frm_ingreso_item" class="form-horizontal" action="<?= site_url('producto/ingreso_item') ?>"
                      method="post">
                    <div class="box-body">
                        <div class="col-md-12">
                            <p id="mensaje" style="color: red;font-size: 12pt;font-weight: bold">Mensaje: Las tallas o cantidades no concuerdan con la cantidad de datos ingresados
                                respectivamente, es decir que existe mas tallas que cantidades ingresadas o viceversa.<br> Verifique los datos</p>
                            <div class="form-group">
                                <label class="col-md-1"><b>Observacion:</b></label>
                                <div class="col-md-10">
                                    <textarea id="observacion" name="observacion" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-1"><b>Fecha</b></label>
                                <div class="col-md-4">
                                    <input type="date" id="fecha_ingreso" name="fecha_ingreso" class="form-control"
                                           value="<?= date('Y-m-d') ?>" readonly>
                                </div>
                                <label class="col-md-2 text-right"><b>Forma Ingreso</b></label>
                                <div class="col-md-4">
                                    <select id="forma_ingreso" name="forma_ingreso" class="form-control">
                                        <option value="Primer Ingreso">Primer Ingreso</option>
                                        <option value="Reposicion">Reposicion</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Lista de Tallas y Cantidades</h4>
                                </div>
                                <div class="panel panel-body">
                                    <div class="col-md-10">
                                        <p style="font-size: 11pt;"><b>
                                                <i class="fa fa-info-circle"></i>
                                                Para agregar un item al inventario, seleccione el item a ingresar y
                                                escriba las tallas que tenga a disposicion separadas por un guion(-)
                                                y las cantidades de igual forma
                                                respectivamente.<br>
                                                Ejemplo.: <em>Pantalon plomo oscuro | 34-36-38-40 | 50-15-60-30 </em>
                                            </b>
                                        </p>
                                        <table id="lista_ingreso_items" class="table table-bordered table-striped">
                                            <thead>
                                            <th style="width: 5%" class="text-center">Op.</th>
                                            <th class="text-center">Productos</th>
                                            <th class="text-center">Tallas</th>
                                            <th class="text-center">Cantidades</th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><input type="checkbox" id="check" name="check"/></td>
                                                <td>
                                                    <select class="form-control" id="item" name="item[]">
                                                    </select>
                                                </td>
                                                <td><input class="form-control talla" type="text" id="talla" name="talla[]"/>
                                                </td>
                                                <td><input class="form-control medidac" type="text" id="cantidades"
                                                           name="cantidades[]"/></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success" type="button"
                                                onclick="addRow('lista_ingreso_items');">
                                            <i class="fa fa-plus"></i> Agregar fila
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-danger" type="button"
                                                onclick="deleteRow('lista_ingreso_items');"><i class="fa fa-trash"></i>
                                            Eliminar
                                            fila
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-center">
                        <button class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                        <a href="<?= site_url('producto/index') ?>" class="btn btn-danger"><i
                                    class="fa fa-times"></i> Cerrar / Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>
<script src="<?= base_url('js-sistema/producto.js') ?>"></script>
