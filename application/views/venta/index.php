<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 03/02/2018
 * Time: 04:33 PM
 */
$usuario = $this->session->userdata('usuario_sesion');
?>
<link rel="stylesheet" href="<?= base_url('assets/css/estilo_ventas.css') ?>"/>
<section id="seccion" class="content">
    <div class="row">
        <form id="frm_registro_venta" class="form-horizontal" role="form" action="javascript: addRow1();">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-file-archive-o fa-2x"></i> <b>VENTAS DE PRODUCTOS </b>
                        </h3>
                        <div style="float:right" hidden>
                            <b class="label_title">CAJA APERTURADA: </b><label id="caja_aperturada"
                                                                               class="label_title"></label>
                            <input type="text" id="id_caja" name="id_caja" value="" hidden>
                        </div>
                    </div>
                    <div class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label"><b>NOMBRE</b></label>
                                    <input style="font-size: 13pt; font-weight: bold" type="text" id="nombre_cliente"
                                           name="nombre_cliente" class="form-control"
                                           value="" placeholder="Nombre del cliente" required="autofocus"/>
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
                                <span class="class_span">
                                    <i class="fa fa-info-circle"></i>
                                    <b>
                                        <em>
                                            Los clientes que no esten registrados, se agregarán en la agenda automáticamente al registrar la venta.
                                        </em>
                                    </b>
                                </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">Depósito</label>
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
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" hidden>
                                    <label class="control-label"><b>Tipo Venta</b></label>
                                    <select class="form-control" id="tipo_ingreso" name="tipo_ingreso">
                                        <option value="3">VENTA TIENDA</option>
                                        <option value="4">VENTA COORPORATIVA</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label"><b> </b></label>
                                    <a href="#modal_query_product" data-toggle="modal" id="search_product_btn"
                                       class="btn btn-succes btn-block"><i class="fa fa-search"></i>Consultar
                                        producto</a>
                                </div>
                            </div>


                            <div class="form-group" hidden>
                                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                    <a href="#modal_query_product" data-toggle="modal" id="search_product_btn"
                                       class="btn btn-succes btn-block"><i class="fa fa-search"></i> Consultar
                                        producto</a>
                                </div>
                                <!--link que envia a ventas en proceso-->
                                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12" hidden>
                                    <a href="venta_proceso/index" id="id_producto_produccion"
                                       class="btn btn-succes btn-block"><i class="fa fa-eye"></i>
                                        Ventas en Proceso</a>
                                </div>
                                <!--link que envia a poductos en produccion-->
                                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12" hidden>
                                    <a href="producto_produccion/index" id="id_producto_produccion"
                                       class="btn btn-succes btn-block"><i class="fa fa-pencil"></i>
                                        Productos en Producción</a>
                                </div>
                            </div>
                            <hr>
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table id="lista_detalle" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="width: 15%"><b>CÓDIGO</b></th>
                                            <th class="text-center" style="width: 35%"><b>PRODUCTO</b></th>
                                            <th class="text-center" style="width: 10%"><b>STOCK </b></th>
                                            <!--  <th class="text-center" style="width: 10%"><b>STOCK pr.</b></th>-->
                                            <th class="text-center" style="width: 16%"><b>PRECIO bs</b></th>
                                            <th class="text-center" style="width: 10%"><b>CANTIDAD</b></th>
                                            <!-- <th class="text-center" style="width: 64%"><b>ESTADO entrega</b></th>-->
                                            <th class="text-center" style="width: 12%"><b>TOTAL bs</b></th>
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
                                                       placeholder="Código "/>
                                            </td>
                                            <td>
                                                <input id="detalle_venta" name="detalle_venta" type="text"
                                                       class="form-control"
                                                       placeholder="nombre del producto" hidden/>
                                                <input type="text" id="contador" name="contador" hidden/>
                                                <!--CONTADOR DE FILAS DE LA TABLA -->
                                                <input type="text" id="id_producto" name="id_producto"
                                                       placeholder="producto" hidden>
                                                <input type="text" id="nombre_item" name="nombre_item" hidden>
                                                <input type="text" id="id_talla" name="id_talla" placeholder="talla"
                                                       hidden>
                                                <input type="text" id="id_color" name="id_color" placeholder="color"
                                                       hidden>
                                            </td>
                                            <!--input del stock disponible-->
                                            <td>
                                                <input type="text" id="stock_disponible" name="stock_disponible"
                                                       class="form-control" style="text-align: right" readonly/>
                                            </td>
                                            <!--input del stock en produccion-->
                                            <!--  <td>
                                                  <input type="text" id="stock_produccion" name="stock_produccion"
                                                         class="form-control" style="text-align: right" readonly/>
                                              </td>-->
                                            <td>
                                                <input type="number" step="any" id="precio_venta" name="precio_venta"
                                                       class="form-control" style="text-align: right" readonly/>
                                            </td>
                                            <td>
                                                <input type="number" id="cantidad_venta" name="cantidad_venta" min="1"
                                                       max=""
                                                       class="form-control"/>
                                            </td>
                                            <td hidden>

                                                <select class="form-control" id="estado_entrega" name="estado_entrega">
                                                    <option value="5">Entregado
                                                    </option>
                                                    <option value="4">Pendiente
                                                    </option>
                                                </select>


                                            </td>
                                            <td></td>
                                            <td>
                                                <button type="submit" id="agregar" name="agregar"
                                                        class="btn btn-primary"
                                                        title="Agregar fila">
                                                    <i class="fa fa-plus-circle white"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="control-label">Nota</label>
                                    <textarea id="nota" name="nota" class="form-control">

                                     </textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button id="btn_registrar_nota" class="btn btn-block btn-primary"><i
                                                class="fa fa-save"></i> REGISTRAR
                                        VENTA
                                    </button>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!--inicio-->

                <!--fin-->
            </div>
            <!-- SUBTOTAL Y DESCUENTO -->
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="box">
                    <div class="box-body">


                        <div class="box-header with-border">
                            <center><h3 class="box-title">DATOS VENTA</h3></center>
                        </div>

                        <div class="box-body">
                            <label class="control-label" style="font-size: 14pt"><b>SUBTOTAL
                                    Bs.</b></label>
                            <input style="font-size: 18pt;" type="number" step="any"
                                   id="subtotal_as" name="subtotal_as"
                                   class="form-control" value="0.00" readonly/>
                            <label class="control-label" style="font-size: 14pt"><b>DESCUENTO
                                    Bs.</b></label>
                            <input style="font-size: 18pt;" type="number" step="any"
                                   id="descuento_as"
                                   name="descuento_as"
                                   class="form-control" value="0.00"/>
                        </div>
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-dollar"></i></span>
                            <div class="info-box-content">
                                                    <span class="info-box-text"
                                                          style="font-size: 14pt"><b>TOTAL</b></span>
                                <span class="info-box-number">
                                                     <input readonly
                                                            style="border:0px; font-size: 18pt; background-color: transparent"
                                                            type="number"
                                                            step="any" id="total_as" name="total_as"
                                                            class="form-control" value="0.00"/></span>
                            </div>
                            <br>
                            <center><h3>TIPO VENTA </h3></center>
                            <select class="form-control" id="tipo_ventas" name="tipo_ventas">
                                <option value="vacio">SELECCIÓN TIPO VENTA</option>
                                <option value="forma_pago_contado">AL CONTADO</option>
                                <option value="forma_pago_plazo">A PLAZO</option>
                            </select>
                        </div>


                    </div>
                </div>

            </div>
            <!-- FORMAS DE PAGO -->
            <div id="forma_pago_plazo" class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <center><h3 class="box-title">FORMA DE PAGO</h3></center>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped">

                            <tr class="warning">
                                <td>
                                    <label>
                                        <input type="checkbox" id="forma_pago2" name="forma_pago[]"
                                               class="forma_pago"
                                               value="plazo">
                                        A PLAZO
                                    </label>
                                    <form hidden id="pago2">
                                        <div class="form-group" style="margin-bottom: 0%">
                                            <div class="col-md-6">
                                                <label class="control-label text-right"><b>Monto</b></label>
                                                <input type="number" step="any"
                                                       id="monto_cuenta_credito"
                                                       name="monto_cuenta_credito"
                                                       class="monto form-control"
                                                       value="0.00">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label text-right"><b>Saldo</b></label>
                                                <input type="number" step="any" id="saldo_credito"
                                                       name="saldo_credito"
                                                       class="form-control" value="0.00">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div style="margin-top: 0%" class="col-md-12">
                                                <label class="control-label text-right"><b>Fecha de
                                                        pago</b></label>
                                                <input type="date" id="fecha_pago_credito"
                                                       name="fecha_pago_credito"
                                                       class="form-control"
                                                       value="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div style="margin-top: 0%" class="col-md-12">
                                                <label class="control-label text-right"><b>Cuota</b></label>

                                                <select class="form-control" id="tipo_venta_plazo"
                                                        name="tipo_venta_plazo">
                                                    <option value="forma_pago_efectivo">EFECTIVO
                                                    </option>
                                                    <option value="forma_pago_cheque">CHEQUE
                                                    </option>
                                                    <option value="forma_pago_tarjeta">TARJETA
                                                    </option>
                                                    <option value="forma_pago_deposito">DEPOSITO
                                                        BANCARIO
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>


                        </table>
                    </div>
                </div>


            </div>
            <!-- FORMAS DE PAGO SEGUNDA PARTE -->
            <div id="forma_pago_efectivo" class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">FORMA DE PAGO</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <tr class="danger">
                                <td>
                                    <label>
                                        <input type="checkbox" id="forma_pago1"
                                               name="forma_pago[]"
                                               class="forma_pago" value="efectivo">
                                        EFECTIVO
                                    </label>
                                    <!--checked="checked"  PARA QUE ESTE MARCADO AUTOMÁTICAMENTE-->
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


                            <tr class="active">
                                <td>
                                    <label>
                                        <input type="checkbox" id="forma_pago3" name="forma_pago[]"
                                               class="forma_pago"
                                               value="deposito">
                                        DEPOSITO BANCARIO
                                    </label>
                                    <form hidden id="pago3">
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" id="banco" name="banco"
                                                       class="form-control"
                                                       placeholder="Ingrese el nombre del banco">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input type="number" id="cuenta" name="cuenta"
                                                       class="form-control"
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
                                        <input type="checkbox" id="forma_pago4" name="forma_pago[]"
                                               class="forma_pago"
                                               value="cheque">
                                        CHEQUE
                                    </label>
                                    <form hidden id="pago4">
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input id="banco_cheque" name="banco_cheque"
                                                       class="form-control"
                                                       placeholder="Ingrese el nombre del banco">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input type="number" id="nro_cheque"
                                                       name="nro_cheque"
                                                       class="form-control"
                                                       placeholder="Ingrese el numero del cheque">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <label class="control-label">Monto</label>
                                                <input type="number" step="any" id="monto_cheque"
                                                       name="monto_cheque"
                                                       class="monto form-control" value="0.00">
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <tr class="info">
                                <td>
                                    <label>
                                        <input type="checkbox" id="forma_pago5" name="forma_pago[]"
                                               class="forma_pago"
                                               value="tarjeta">
                                        TARJETA
                                    </label>
                                    <form hidden id="pago5">
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input id="banco_tarjeta" name="banco_tarjeta"
                                                       class="form-control"
                                                       placeholder="Ingrese el nombre del banco">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">
                                            <div class="col-md-12">
                                                <input type="number" id="nro_tarjeta"
                                                       name="nro_tarjeta"
                                                       class="form-control"
                                                       placeholder="Ingrese el numero del cheque">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 0%" class="form-group">

                                            <div class="col-md-12">
                                                <label class="control-label">Monto</label>
                                                <input type="number" step="any" id="monto_tarjeta"
                                                       name="monto_tarjeta"
                                                       class="monto form-control" value="0.00">
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>

            </div>


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
                                   placeholder="Escriba el nombre completo" required="autofocus">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">TELÉFONO *</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="telefono" name="telefono" value=""
                                   placeholder="Escriba el nro de telefono o celular">
                        </div>
                    </div>


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


<!-- Busqueda de producto en todas las sucursales   -->
<div id="modal_query_product" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <center><h3><b> CONSULTA DE
                        PRODUCTOS</b></h3></center>
            <form id="frm_search_product" action="<?= site_url('venta/consultar_producto') ?>" method="post"
                  class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">NOMBRE PRODUCTO *</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="product_code" name="product_code" value=""
                                   placeholder="Ingrese el nombre del producto" required>
                        </div>
                        <div class="col-lg-2">
                            <button class="btn-block"> Buscar</button>
                        </div>
                    </div>
                    <div class="container full_size">
                        <div class="row">
                            <table id="lista" class="table table-bordered">
                                <thead class="black_white">
                                <tr>
                                    <th class="text-center"><b>PRODUCTO</b></th>
                                    <th class="text-center"><b>STOCK</b></th>
                                    <!--<th class="text-center"><b>STOCK PRODUCCIÓN</b></th>-->
                                    <th class="text-center"><b>SUCURSAL</b></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button id="btn_cerrar_modal_cliente" class="btn btn-danger btn" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_set_caja" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>APERTURA DE CAJA</b></h5>
            </div>
            <form id="frm_set_caja" action="<?= site_url('caja/set_caja') ?>" method="post"
                  class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Usuario</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="<?= $usuario['nombre'] ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Caja</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="caja" name="caja">
                                <?php
                                foreach ($cajas as $row) {
                                    ?>
                                    <option value="<?= $row->id ?>"><?= $row->descripcion ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" id="monto_label" name="monto_label">Monto</label>
                        <div class="col-sm-8">
                            <input type="number" step="any" class="form-control" id="monto_caja" name="monto_caja"
                                   value="0.00">
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button id="btn_set_caja" class="btn btn-primary"><i class="fa fa-save"></i> Seleccionar</button>
                    <a id="btn_cerrar_set_caja" class="btn btn-danger" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= base_url('js-sistema/venta.js') ?>"></script>
<script>
    var f = 0;
    var subtotal = 0;
    function addRow1() {
        if ($('#cantidad_venta').val() == '') {
            $('#detalle_venta').focus();
            swal('No puede agregar item con cantidad cero y en blanco.');
            return true;
        }
        //agrega filas a la tabla de notas de venta y suma los precios de cada producto
        f = f + 1;
        $('#contador').val(f);
        var frm = $("#frm_registro_venta").serialize();
        ajaxStart('Verificando datos para agregar al detalle, por favor espere');
        $.ajax({
            url: site_url + 'venta/agregar',
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
                    $('#stock_disponible').val('');

                    $('#codigo_barra_detalle').val('');
                    //  $('#estado_entrega' + parseInt(datos[1])).val(parseInt(datos[3]));
                    // $('#estado_entrega').val(5);
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

    $('#frm_search_product').submit(function (event) {
        event.preventDefault();
        var frm = $('#frm_search_product');
        var data = $(frm).serialize();
        $.ajax({
            url: $(frm).attr('action'),
            type: $(frm).attr('method'),
            data: data,
            success: function (response) {
                ajaxStop();
                console.log(response)
                $("#lista tbody").empty();
                $("#lista tbody").html(response);
            }
        });
    })

    $("#search_product_btn").click(function () {
        $("#lista tbody").empty();
    })
</script>
