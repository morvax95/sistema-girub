<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 17/02/2018
 * Time: 12:14 PM
 */
?>
<div class="box-body" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <!--    1er segmento -->
    <div class="col-md-12">

        <div class="form-group">
            <label class="col-md-3 control-label"><b>CÓDIGO PRODUCTO*</b></label>
            <div class="col-md-7">
                <input type="text" id="id_item" name="id_item" value="<?= isset($producto) ? $producto->id : '' ?>"
                       hidden>
                <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" maxlength="20"
                       value="<?= isset($producto) ? $producto->codigo_barra : '' ?>"
                       placeholder="Ingrese el codigo Interno"/>
                <input type="number" id="codigo_barras_old" name="codigo_barras_old" maxlength="20"
                       value="<?= isset($producto) ? $producto->codigo_barra : '' ?>" hidden/>
                <span style="float: right; color: red;"><em><b>Maximo 20 números</b></em></span>
            </div>

        </div>

        <div class="form-group">

            <label class="col-md-3 control-label"><b>NOMBRE PRODUCTO *</b></label>
            <div class="col-md-7">
                <input type="text" class="form-control" id="nombre_item" name="nombre_item"
                       value="<?= isset($producto) ? $producto->nombre_item : '' ?>"
                       placeholder="Escriba el nombre de su producto"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label text-right"><b>PRECIO VENTA *</b></label>
            <div class="col-md-7">
                <input type="number" step="any" id="precio_venta" name="precio_venta" class="form-control"
                       value="<?= isset($producto) ? $producto->precio_venta : '0.00' ?>"/>
            </div>
        </div>
        <div class="form-group" hidden>
            <label class="col-md-3 control-label text-right"><b>STOCK *</b></label>
            <div class="col-md-7">
                <input type="number" id="stock_minimo" name="stock_minimo" class="form-control"
                       value="<?= isset($producto) ? $producto->stock_minimo : '0' ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label text-right"><b>PRECIO COMPRA </b></label>
            <div class="col-md-7">
                <input type="number" step="any" id="precio_compra" name="precio_compra" class="form-control"
                       value="<?= isset($producto) ? $producto->precio_compra : '0.00' ?>"/>
            </div>
        </div>

        <div class="form-group">

            <label class="col-md-3 control-label"><b>DESCRIPCIÓN</b></label>
            <div class="col-md-7">
                <textarea type="text" class="form-control" id="descripcion" name="descripcion"
                          value="<?= isset($producto) ? $producto->descripcion : '' ?>"
                          placeholder="Escriba el descripcion del producto"/></textarea>
            </div>
        </div>


    </div>

    <!--  2do segmento  -->
    <div class="col-md-12">
       <!-- <div class="form-group">
            <label class="col-md-3 control-label text-right"><b>Tipo de item</b></label>
            <div class="col-md-7">
                <select name="tipo_item" id="tipo_item" class="form-control">
                    <!--  <option value="">Seleccione tipo de producto</option>-->
                   <!-- <option value="Producto">Producto</option>
                    <option value="Materia prima">Materia prima</option>
                </select>
            </div>
        </div>-->
        <!-- <div class="form-group">
            <label class="col-md-3 control-label text-right"><b>Dimensión</b></label>
            <div class="col-md-7">
                <input type="text" id="dimension" name="dimension" class="form-control"
                       value="<?= isset($producto) ? $producto->dimension : '0' ?>"/>
            </div>
        </div>-->


        <div class="form-group" hidden>
            <label class="col-md-3 control-label text-right"><b>Marca</b></label>
            <div class="col-md-7">
                <select class="form-control" id="marca_id" name="marca_id">
                    <?php
                    foreach ($marcas as $row) {
                        ?>
                        <option value="<?= $row->id ?>" <?= isset($producto) ? is_selected($producto->marca_id, $row->id) : '' ?>><?= $row->descripcion ?></option>
                        <?php
                    }
                    ?>
                </select>
                <a style="color: orange;" href="#modal_registro_marca" data-toggle="modal"><i
                            class="fa fa-plus-square"></i> Agregar una nueva Marca</a>
            </div>

        </div>
        <div class="form-group" >
            <label class="col-md-3 control-label text-right"><b>NOMBRE CATEGORIA </b></label>
            <div class="col-md-7">
                <select class="form-control" id="categoria_interna_id" name="categoria_interna_id">
                    <?php
                    foreach ($categorias as $row) {
                        ?>
                        <option value="<?= $row->id ?>" <?= isset($producto) ? is_selected($producto->categoria_interna_id, $row->id) : '' ?>><?= $row->descripcion ?></option>
                        <?php
                    }
                    ?>
                </select>
                <a style="color: orange;" href="#modal_registro_categoria_interna" data-toggle="modal"><i
                            class="fa fa-plus-square"></i> Agregar un nueva Categoria Interno</a>
            </div>

        </div>


        <div class="form-group">
            <!--  <label class="col-md-3 control-label text-right"><b>Unid. Medida</b></label>
            <div class="col-md-7">
                <select class="form-control" id="unidad_medida_id" name="unidad_medida_id">
                    <?php
            foreach ($unidad_medida as $row) {
                ?>
                        <option value="<?= $row->id ?>" <?= isset($producto) ? is_selected($producto->unidad_medida_id, $row->id) : '' ?>><?= $row->abreviatura ?></option>
                        <?php
            }
            ?>
                </select>
                <a style="color: orange;" href="#modal_registro_unidad_medida" data-toggle="modal"><i
                            class="fa fa-plus-square"></i> Agregar una nueva Unidad Medida</a>
            </div>-->
        </div>
        <div class="form-group" hidden>
            <label class="col-md-3 control-label text-right"><b>Unid. Compra</b></label>
            <div class="col-md-7">
                <select class="form-control" id="unidad_compra_id" name="unidad_compra_id">
                    <?php
                    foreach ($unidad_medida as $row) {
                        ?>
                        <option value="<?= $row->id ?>" <?= isset($producto) ? is_selected($producto->unidad_medida_id, $row->id) : '' ?>><?= $row->abreviatura ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="alert alert-success alert-dismissible">
            <h4><i class="icon fa fa-info"></i> Aviso!</h4>
            Los campos con (*) son requidos, si su item no cuenta con código de interno colocar otro
            número o dejar en blanco.

        </div>
    </div>


</div>
<div class="box-footer text-center">
    <button class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
    <a href="<?= site_url('producto/index') ?>" class="btn btn-danger"><i
                class="fa fa-times"></i> Cerrar / Volver
    </a>
</div>

