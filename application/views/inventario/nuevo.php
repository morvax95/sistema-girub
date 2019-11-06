<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 01/03/2018
 * Time: 10:55 AM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-files-o fa-2x"></i> <b>GESTIÓN DE PRODUCTOS</b>
                    </h3>
                </div>
                <form id="frm_registrar_inventario" class="form-horizontal"
                      action="javascript:agregar_fila()" method="post">
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group" style="margin-bottom: 0%">
                                <div class="col-md-6">
                                    <label class="control-label"><b>Fecha Ingreso</b></label>
                                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso"
                                           value="<?= date('Y-m-d') ?>"/>
                                </div>
                                <div class="col-md-3" hidden>
                                    <label class="control-label"><b>Estado Producto</b></label>
                                    <select name="id_estado_producto" id="id_estado_producto" class="form-control">
                                        <!--<option value="0">Seleccione Estado del producto</option>-->
                                        <option value="1">Existente</option>
                                        <option value="2">En Producción</option>

                                    </select>

                                </div>
                                <div class="col-md-6">
                                    <label class="control-label"><b>Observación</b></label>
                                    <textarea id="observacion" name="observacion" class="form-control"
                                              placeholder="observaciones"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="control-label"><b>Deposito</b></label>
                                    <select id="seleccion_almacen" name="seleccion_almacen" class="form-control">
                                        <!--<option value="0">:: Seleccione una opcion</option>-->
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
                                <div class="col-md-3" hidden>
                                    <label class="control-label"><b>Tipo de ingreso</b></label>
                                    <select class="form-control" id="forma_ingreso" name="forma_ingreso">
                                        <option value="Ingreso de producto">Ingreso de producto</option>
                                        <option value="Reposicion de producto">Reposicion de producto</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-3">
                                    <a style="color: red;" href="#modal_registro_almacen" data-toggle="modal"><i
                                                class="fa fa-plus-square"></i> Agregar mas almacén</a>
                                </div>
                                <div class="col-md-6">
                                    <!-- @ todo Agregar el evento de guardar el ingreso de inventario -->
                                    <a type="submit" class="btn btn-success" id="btn_registrar_inventario"
                                       name="btn_registrar_inventario"><i class="fa fa-save"></i> Guardar</a>
                                    <a type="submit" class="btn btn-danger"
                                       href="<?= site_url('inventario/index') ?>"><i class="fa fa-times"></i> Cerrar y
                                        Salir</a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table table-responsive">
                                    <table id="lista_inventario" class="table table-bordered">
                                        <thead>
                                        <th style="width: 30%" class="text-center">NOMBRE PRODUCTO</th>
                                        <th style="width: 10%" class="text-center">CANTIDAD</th>
                                        <th style="width: 8%" class="text-center">PRECIO VENTA</th>
                                        <!-- <th style="width: 10%" class="text-center">Marca</th>
                                         <th style="width: 10%" class="text-center">.</th>-->
                                        <th style="width: 10%" class="text-center">OPCIONES</th>
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
                                                <input type="text" id="producto_id" name="producto_id"
                                                       placeholder="producto" hidden>
                                                <input type="text" id="color_id" name="color_id" placeholder="color"
                                                       hidden>
                                                <input type="text" id="talla_id" name="talla_id" placeholder="talla"
                                                       hidden>
                                            </td>
                                            <td>
                                                <input type="number" id="cantidad_inventario" name="cantidad_inventario"
                                                       class="form-control input-sm " value="0">
                                            </td>
                                            <td>
                                                <input type="number" step="any" class="form-control input-sm"
                                                       id="precio_venta" name="precio_venta" value="0.00" readonly>
                                            </td>
                                            <!-- <td>
                                                 <input type="text" id="seleccion_color" name="seleccion_color" readonly
                                                        class="form-control input-sm"/>
                                             </td>
                                              <td>
                                                  <input type="text" id="seleccion_talla" name="seleccion_talla" readonly
                                                         class="form-control input-sm">
                                              </td>-->
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
    <!-- /.row -->
</section>

<!-- REGISTRO DE UN NUEVO ALMACEN -->
<div id="modal_registro_almacen" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="panel-heading cabecera_frm bg-primary" style="color: white"><b>REGISTRO DE
                            ALMACÉN</b></h5></center>
            </div>
            <form id="frm_registro_almacen" class="form-horizontal"
                  action="<?= site_url('almacen/registrar') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>Nombre Almacén</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="descripcion_almacen" name="descripcion_almacen"
                                   value="" autofocus
                                   placeholder="Campo requerido"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>Tipo Almacén</b></label>
                        <div class="col-md-7">
                            <select class="form-control" id="tipo_almacen" name="tipo_almacen">
                                <option value="0">PRODUCTOS</option>
                                <option value="1">MATERIA PRIMA</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    <a id="btn_cerrar_modal_almacen" class="btn btn-danger" data-dismiss="modal"><i
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
    function agregar_fila() {
        if ($('#cantidad_inventario').val() === '0' || $('#cantidad_inventario').val() === '') {
            swal('No puede agregar item con cantidad cero y en blanco.');
            return;
        }
        count = count + 1;
        $('#contador_inventario').val(count);
        var frm = $("#frm_registrar_inventario").serialize();
        console.log(frm);
        $.ajax({
            url: site_url + 'inventario/agregar',
            data: frm,
            type: 'post',
            success: function (registro) {
                var datos = eval(registro);
                $('#cantidad_inventario').val('0');
                $('#lista_inventario tbody').append(datos[0]); //dataTable > tbody:first
                $('#nombre_producto').focus();
                $('#nombre_producto').val('');
                $('#precio_venta').val('0.00');
                //$('#seleccion_color').val('');
                // $('#seleccion_talla').val('');
                eliminar_fila();
                return false;
            }
        });
        return false;
    }

    function eliminar_fila() { //Elimina las filas de la tabla de nota de venta y resta el subtotal
        $("a.eliminar").click(function () {
            $(this).parents("tr").fadeOut("normal", function () {
                $(this).remove();
                count = count - 1;
                $('#contador_inventario').val(count);
            });
        });
    }
</script>