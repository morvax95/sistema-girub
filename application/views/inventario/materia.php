<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 205/03/2018
 * Time: 05:52 PM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h2 class="box-title">INGRESO DE MATERIAS PRIMAS </h2>
                </div>
                <form id="frm_registrar_inventario_materia" class="form-horizontal"
                      action="javascript:agregar_fila_materia()" method="post">
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group" style="margin-bottom: 0%">
                                <div class="col-md-6">
                                    <label class="control-label"><b>Fecha Ingreso</b></label>
                                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso"
                                           value="<?= date('Y-m-d') ?>"/>
                                    <input id="forma_ingreso" name="forma_ingreso" value="MATERIA PRIMA" hidden>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label"><b>Observacion</b></label>
                                    <textarea id="observacion" name="observacion" class="form-control"
                                              placeholder="Escriba alguna observacion en caso de que hubiera"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="control-label"><b>Almacen</b></label>
                                    <select id="seleccion_almacen" name="seleccion_almacen" class="form-control">
                                        <option value="0">:: Seleccione una opcion</option>
                                        <?php
                                        foreach ($almacenes as $row) {
                                            if ($row->tipo_almacen == 0) {
                                                ?>
                                                <option value="<?= $row->id ?>"><?= $row->descripcion ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label"><b>Sucursal</b></label>
                                    <select class="form-control" id="sucursal_seleccionada"
                                            name="sucursal_seleccionada">
                                        <?php
                                        foreach ($sucursales as $row) {
                                            ?>
                                            <option value="<?= $row->id ?>"><?= $row->sucursal ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-2">
                                    <a style="color: red;" href="#modal_registro_unidad" data-toggle="modal"><i
                                                class="fa fa-plus-square"></i> Agregar unidad</a>
                                </div>
                                <div class="col-md-6">
                                    <!-- @ todo Agregar el evento de guardar el ingreso de inventario -->
                                    <a type="submit" class="btn btn-success" id="btn_registrar_ingreso_materia"
                                       name="btn_registrar_ingreso_materia"><i class="fa fa-save"></i> Guardar</a>
                                    <a type="submit" class="btn btn-danger"
                                       href="<?= site_url('inventario/index') ?>"><i class="fa fa-times"></i> Cerrar y
                                        Salir</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table table-responsive">
                                    <table id="lista_inventario_materia" class="table table-bordered">
                                        <thead>
                                        <th style="width: 30%" class="text-center">Producto</th>
                                        <th style="width: 10%" class="text-center">Unidad</th>
                                        <th style="width: 10%" class="text-center">Cantidad</th>
                                        <th style="width: 10%" class="text-center">Opciones</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td>
                                                <input id="nombre_producto" name="nombre_producto" type="text"
                                                       class="form-control input-sm"
                                                       placeholder="Escriba el nombre del producto"/>
                                                <!--CONTADOR DE FILAS DE LA TABLA -->
                                                <input type="text" id="contador_inventario" name="contador_inventario"
                                                       hidden/>
                                                <!--CODIGO DE PRODUCTO-->
                                                <input type="text" id="producto_id" name="producto_id" hidden>
                                            </td>
                                            <td>
                                                <select id="seleccion_unidad" name="seleccion_unidad"
                                                        class="form-control input-sm">
                                                    <?php
                                                    foreach ($unidades as $unidad) {
                                                        ?>
                                                        <option value="<?= $unidad->id ?>"><?= $unidad->descripcion ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input id="cantidad_inventario" name="cantidad_inventario"
                                                       class="form-control input-sm text-right" value="0">
                                            </td>
                                            <td class="text-center">
                                                <button type="submit" id="agregar_inventario" name="agregar_inventario"
                                                        class="btn btn-primary">
                                                    <i class="fa fa-plus-circle white"></i>
                                                    Agregar
                                                </button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


<!-- REGISTRO DE UNIDAD -->
<div id="modal_registro_unidad" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: black"><b>Registra una nueva unidad de medida</b></h5>
            </div>
            <form id="frm_registro_unidad" class="form-horizontal"
                  action="<?= site_url('unidad/registrar') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>Unidad</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="descripcion_unidad" name="descripcion_unidad"
                                   value="" autofocus
                                   placeholder="Campo requerido"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>Abreviatura</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="descripcion_abreviatura"
                                   name="descripcion_abreviatura"
                                   value=""
                                   placeholder="Campo requerido"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    <a id="btn_cerrar_modal_unidad" class="btn btn-danger" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </a>
                </div>
            </form>
        </div>
    </div>
    <style>
        label {
            color: black;
        }
    </style>
</div>

<script src="<?= base_url('js-sistema/inventario.js') ?>"></script>
<script>
    var count = 0;
    function agregar_fila_materia() {
        if ($('#cantidad_inventario').val() === '0' || $('#cantidad_inventario').val() === '') {
            swal('No puede agregar item con cantidad cero y en blanco.');
            return;
        }
        count = count + 1;
        $('#contador_inventario').val(count);
        var frm = $("#frm_registrar_inventario_materia").serialize();

        $.ajax({
            url: site_url + 'inventario/agregar_materia',
            data: frm,
            type: 'post',
            success: function (registro) {
                var datos = eval(registro);
                $('#cantidad_inventario').val('0');
                $('#lista_inventario_materia tbody').append(datos[0]); //dataTable > tbody:first
                $('#nombre_producto').focus();
                $('#nombre_producto').val('');
                eliminar_fila_materia();
                return false;
            }
        });
        return false;
    }

    function eliminar_fila_materia() { //Elimina las filas de la tabla de nota de venta y resta el subtotal
        $("a.eliminar").click(function () {
            $(this).parents("tr").fadeOut("normal", function () {
                $(this).remove();
                count = count - 1;
                $('#contador_inventario').val(count);
            });
        });
    }
</script>
