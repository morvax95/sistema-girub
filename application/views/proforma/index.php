<?php

$usuario = $this->session->userdata('usuario_sesion');
?>
<!-- Main content -->
<style>
    hr {
        margin-top: 2%;
        margin-bottom: 1%;
    }
</style>
<section id="seccion" class="content">
    <div class="row">
        <form id="frm_registro_venta" class="form-horizontal" role="form" action="javascript: addRow1();">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-file-archive-o fa-2x"></i> <b>PROFORMA</b></h3>
                    </div>
                    <div class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label"><b>NOMBRE</b></label>
                                    <input style="font-size: 13pt; font-weight: bold" type="text" id="nombre_cliente"
                                           name="nombre_cliente" class="form-control"
                                           value="" placeholder="Nombre del cliente"/>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <label class="control-label"><b>CI/NIT</b></label>
                                    <input style="font-size: 13pt; font-weight: bold" type="number" id="nit_cliente"
                                           name="nit_cliente" class="form-control" value=""
                                           placeholder="Nit del cliente" autofocus/>
                                    <input type="text" id="idCliente" name="idCliente" value="" hidden/>
                                </div>
                                <div style="padding-top: 3%" class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <a href="#modal_registro_cliente" data-toggle="modal"
                                       class="btn btn-success btn-block"><i class="fa fa-user-plus"></i> Nuevo
                                        cliente</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                <span style="color:red">
                                    <i class="fa fa-info-circle"></i>
                                    <b>
                                        <em>
                                            Los clientes que no esten registrados, se agregarán en la agenda automáticamente al registrar la proforma.
                                        </em>
                                    </b>
                                </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">Deposito</label>
                                    <select id="almacen_id" name="almacen_id" class="form-control">
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
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label"><b>Tipo Venta</b></label>
                                    <select class="form-control" id="tipo_ingreso" name="tipo_ingreso">
                                        <option value="3">VENTA TIENDA</option>
                                        <option value="4">VENTA COORPORATIVA</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="col-xs-12">
                                <table id="lista_detalle" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width: 30%"><b>CÓDIGO</b></th>
                                        <th class="text-center" style="width: 50%"><b>PRODUCTO</b></th>
                                        <th class="text-center" style="width: 0%"><b></b></th>
                                        <th class="text-center" style="width: 0%"><b></b></th>
                                        <th class="text-center" style="width: 12%"><b>PRECIO Bs.</b></th>
                                        <th class="text-center" style="width: 12%"><b>CANTIDAD Und.</b></th>
                                        <th class="text-center" style="width: 12%"><b>TOTAL Bs.</b></th>
                                        <th class="text-center"><b></b></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td>
                                            <input type="text" type="text"
                                                   class="form-control" id="codigo_barra_detalle"
                                                   name="codigo_barra_detalle"
                                                   placeholder="Codigo de barra"/>
                                        </td>
                                        <td>
                                            <input id="detalle_venta" name="detalle_venta" type="text"
                                                   class="form-control"
                                                   placeholder="Escriba su detalle"/>
                                            <input type="text" id="contador" name="contador" hidden/>
                                            <!--CONTADOR DE FILAS DE LA TABLA -->
                                            <input type="text" id="id_producto" name="id_producto"
                                                   placeholder="producto" hidden>
                                            <input type="text" id="nombre_item" name="nombre_item" hidden>
                                            <input type="text" id="id_talla" name="id_talla" placeholder="talla" hidden>
                                            <input type="text" id="id_color" name="id_color" placeholder="color" hidden>
                                            <!--CODIGO DE PRODUCTO-->
                                        </td>
                                        <td>
                                            <!--<input type="text" id="talla_venta" name="talla_venta"
                                                   class="form-control" style="text-align: right" readonly/>
                                            <select name="talla_venta" id="talla_venta" class="form-control">
                                                <option value=""  ></option>
                                            </select>-->
                                        </td>
                                        <td>
                                            <!--<input type="text" id="color_venta" name="color_venta"
                                                   class="form-control" style="text-align: right" readonly/>
                                            <select name="color_venta" id="color_venta" class="form-control">
                                                <option value="" ></option>
                                            </select>-->
                                        </td>
                                        <td>
                                            <input type="number" step="any" id="precio_venta" name="precio_venta"
                                                   class="form-control" style="text-align: right"/>
                                        </td>
                                        <td>
                                            <input type="number" id="cantidad_venta" name="cantidad_venta" min="1"
                                                   max=""
                                                   class="form-control"/>
                                        </td>
                                        <td></td>
                                        <td>
                                            <button type="submit" id="agregar" name="agregar" class="btn btn-primary"
                                                    title="Agregar fila">
                                                <i class="fa fa-plus-circle white"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SUBTOTAL Y DESCUENTO -->
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <label class="control-label" style="font-size: 14pt"><b>SUBTOTAL Bs.</b></label>
                        <input style="font-size: 18pt;" type="number" step="any" id="subtotal_as" name="subtotal_as"
                               class="form-control" value="0.00" readonly/>
                        <label class="control-label" style="font-size: 14pt"><b>DESCUENTO Bs.</b></label>
                        <input style="font-size: 18pt;" type="number" step="any" id="descuento_as"
                               name="descuento_as"
                               class="form-control" value="0.00"/>
                    </div>
                </div>
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-dollar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 14pt"><b>TOTAL</b></span>
                        <span class="info-box-number">
                        <input readonly style="border:0px; font-size: 18pt; background-color: transparent" type="number"
                               step="any" id="total_as" name="total_as"
                               class="form-control" value="0.00"/></span>
                    </div>
                </div>
            </div>
            <!-- FORMAS DE PAGO -->
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="box" hidden>
                    <div class="box-header with-border">
                        <h3 class="box-title">FORMA DE PAGO</h3>
                    </div>
                    <div class="box-body" hidden>
                        <table class="table table-bordered table-striped">
                            <tr class="danger">
                                <td>
                                    <label>
                                        <input checked="checked" type="checkbox" id="forma_pago1" name="forma_pago[]"
                                               class="forma_pago" value="efectivo">
                                        EFECTIVO
                                    </label>
                                    <form id="pago1">
                                        <div class="form-group" style="margin-bottom: 0%">
                                            <div class="col-md-12">
                                                <input type="number" step="any" id="monto_efectivo"
                                                       name="monto_efectivo"
                                                       class="monto form-control" value="0.00">
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <tr class="warning">
                                <td>
                                    <label>
                                        <input type="checkbox" id="forma_pago2" name="forma_pago[]" class="forma_pago"
                                               value="credito">
                                        CREDITO
                                    </label>
                                    <form hidden id="pago2">
                                        <div class="form-group" style="margin-bottom: 0%">
                                            <div class="col-md-6">
                                                <label class="control-label text-right"><b>Monto</b></label>
                                                <input type="number" step="any" id="monto_cuenta_credito"
                                                       name="monto_cuenta_credito" class="monto form-control"
                                                       value="0.00">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label text-right"><b>Saldo</b></label>
                                                <input type="number" step="any" id="saldo_credito" name="saldo_credito"
                                                       class="form-control" value="0.00">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div style="margin-top: 0%" class="col-md-12">
                                                <label class="control-label text-right"><b>Fecha de pago</b></label>
                                                <input type="date" id="fecha_pago_credito" name="fecha_pago_credito"
                                                       class="form-control" value="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <tr class="active">
                                <td>
                                    <label>
                                        <input type="checkbox" id="forma_pago3" name="forma_pago[]" class="forma_pago"
                                               value="deposito">
                                        DEPOSITO BANCARIO
                                    </label>
                                    <form hidden id="pago3">
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" id="banco" name="banco" class="form-control"
                                                       placeholder="Ingrese el nombre del banco">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input type="number" id="cuenta" name="cuenta" class="form-control"
                                                       placeholder="Numero de cuenta">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-7">
                                                <label class="control-label text-right"><b>Monto</b></label>
                                                <input type="number" step="any" id="monto_deposito"
                                                       name="monto_deposito"
                                                       class="monto form-control" value="0.00">
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <tr class="success">
                                <td>
                                    <label>
                                        <input type="checkbox" id="forma_pago4" name="forma_pago[]" class="forma_pago"
                                               value="cheque">
                                        CHEQUE
                                    </label>
                                    <form hidden id="pago4">
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input id="banco_cheque" name="banco_cheque" class="form-control"
                                                       placeholder="Ingrese el nombre del banco">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input type="number" id="nro_cheque" name="nro_cheque"
                                                       class="form-control"
                                                       placeholder="Ingrese el numero del cheque">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <label class="control-label">Monto</label>
                                                <input type="number" step="any" id="monto_cheque" name="monto_cheque"
                                                       class="monto form-control" value="0.00">
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <tr class="info">
                                <td>
                                    <label>
                                        <input type="checkbox" id="forma_pago5" name="forma_pago[]" class="forma_pago"
                                               value="tarjeta">
                                        TARJETA
                                    </label>
                                    <form hidden id="pago5">
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input id="banco_tarjeta" name="banco_tarjeta" class="form-control"
                                                       placeholder="Ingrese el nombre del banco">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input type="number" id="nro_tarjeta" name="nro_tarjeta"
                                                       class="form-control"
                                                       placeholder="Ingrese el numero del cheque">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <label class="control-label">Monto</label>
                                                <input type="number" step="any" id="monto_tarjeta" name="monto_tarjeta"
                                                       class="monto form-control" value="0.00">
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!--                <button id="btn_registrar_factura" class="btn btn-block btn-primary"><i class="fa fa-qrcode"></i> Facturar</button>-->
                <button id="btn_registrar_nota" class="btn btn-block btn-primary"><i class="fa fa-save"></i> Registrar
                    Proforma
                </button>
            </div>
        </form>
</section>

<!-- REGISTRO DE CLIENTE -->
<div id="modal_registro_cliente" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" style="color: black"><b>Registro de cliente</b></h5>-->
                <center><h5 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> REGISTRO DE UN
                            NUEVO CLIENTE</b></center>
            </div>
            <form id="modal_registrar_cliente" action="<?= site_url('cliente/registrar_cliente') ?>" method="post"
                  class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">CÉDULA / NIT </label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="ci_nit" name="ci_nit" value=""
                                   placeholder="Escriba en nro de carnet o NIT">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">NOMBRE *</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" value=""
                                   placeholder="Escriba el nombre completo">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">TELÉFONO *</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="telefono" name="telefono" value=""
                                   placeholder="Escriba el nro de telefono o celular">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                         <div class="col-lg-offset-1 col-lg-10">
                             <div class="alert alert-success alert-dismissible">
                                 <h4><i class="icon fa fa-info"></i> Aviso!</h4>
                                 Los campos con (*) son requidos.
                             </div>
                         </div>
                     </div>-->

                </div>
                <div class="modal-footer text-center">
                    <button id="modal_cliente" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    <a id="btn_cerrar_modal_cliente" class="btn btn-danger" data-dismiss="modal"><i
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

<script type="text/javascript" src="<?= base_url('js-sistema/proforma.js') ?>"></script>
<script>
    var f = 0;
    var subtotal = 0;
    function addRow1() {
        if ($('#cantidad_venta').val() == '') {
            $('#detalle_venta').focus();
            return true;
        }
        //agrega filas a la tabla de notas de venta y suma los precios de cada producto
        f = f + 1;
        $('#contador').val(f);
        var frm = $("#frm_registro_venta").serialize();
        ajaxStart('Verificando datos para agregar al detalle, por favor espere');
        $.ajax({
            url: site_url + 'proforma/agregar',
            data: frm,
            type: 'post',
            success: function (registro) {
                ajaxStop();
                var datos = eval(registro);
                if (datos[0] == 'error') {
                    swal('Stock Insuficiente', 'La cantidad pedida es mayor al stock en inventario.', 'error');
                } else {
                    $('#precio_venta').val("0.00");
                    $('#cantidad_venta').val('');

                    $('#lista_detalle tbody').append(datos[0]); //dataTable > tbody:first
                    subtotal = parseFloat(subtotal) + parseFloat(datos[2]);
                    $('#subtotal_as').val(subtotal.toFixed(2));
                    $('#detalle_venta').focus();
                    $('#detalle_venta').val('');
                    $('#talla_venta').val('');
                    $('#color_venta').val('');
                    $('#codigo_barra_detalle').val('');
                    cuadradar_saldos();
                    fn_dar_eliminar(parseFloat(datos[2]));
                    return false;
                }
            }
        });
        return false;
    }

    function fn_dar_eliminar(dato) { //Elimina las filas de la tabla de nota de venta y resta el subtotal

        $("a.elimina").click(function () {
            $(this).parents("tr").fadeOut("normal", function () {
                $(this).remove();
                subtotal = parseFloat($('#subtotal_as').val()) - dato;
                saldo = parseFloat($('#subtotal_as').val()) - dato;
                $('#subtotal_as').val(saldo.toFixed(2));
                $('#total_as').val($('#subtotal_as').val());
                f = f - 1;
                $('#contador').val(f);
            });
        });
    }

    function modificar_detalle(contador) {
        var cant = $("#cantidad" + contador).val();
        if ($.isNumeric(cant)) {
            var precio = $('#precio' + contador).val();
            var monto = parseFloat(cant) * parseFloat(precio);
            modiSub = $("#monto" + contador).val();
            $('#monto' + contador).val(monto);
            var monto1 = parseFloat(modiSub);
            var st = $('#subtotal_as').val();
            st = parseFloat(st) - monto1;
            var subTotal = st + monto;
            $('#subtotal_as').val(subTotal.toFixed(2));
            $('#total_as').val(subTotal.toFixed(2));
            subtotal = subTotal;
        }
    };
</script>
