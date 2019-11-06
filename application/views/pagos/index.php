<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 05/03/2018
 * Time: 07:43 PM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ol fa-2x"></i> <b>LISTA DE DEUDAS POR COBRAR</b></h3>
                    <div style="float:right">
                        <a href="<?= site_url('inicio') ?>" class="btn btn-danger"><i class="fa fa-sign-out"></i>&nbsp;
                            Volver</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="lista_deudas" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-center">Fecha venta</th>
                            <th class="text-center">Cliente</th>
                            <th class="text-center">Total venta</th>
                            <th class="text-center">Total Pagado</th>
                            <th class="text-center">Saldo</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Opciones</th>
                            <th class="text-center">Imprimir</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>

<div id="modal_pago_deuda" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!--<h5 class="modal-title" style="color: black"><b>REALIZAR PAGO DE DEUDA</b></h5>-->
                <center><h5 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> REALIZAR COBRO DE LA
                            DEUDA</b></center>
            </div>
            <form id="frm_pago_deuda" class="form-horizontal"
                  action="<?= site_url('pago/registrar_pago') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>CLIENTE</b></label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" value="" id="cliente_deuda" readonly/>
                            <input id="venta_id" name="venta_id" value="0" hidden>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>FECHA VENTA</b></label>
                        <div class="col-md-7">
                            <input type="date" id="fecha_venta" class="form-control" value="" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>FECHA PAGO</b></label>
                        <div class="col-md-7">
                            <input type="date" id="fecha_pago" name="fecha_pago" class="form-control"
                                   value="<?= date('Y-m-d') ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>SALDO</b></label>
                        <div class="col-md-7">
                            <input type="number" step="any" class="form-control" value="" id="saldo" name="saldo"
                                   readonly/>
                            <input type="number" step="any" value="" id="total" name="total" hidden/>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                         <label class="col-md-3 control-label text-right"><b>FORMA PAGO</b></label>
                         <div class="col-md-7">
                             <select class="form-control" id="forma_pago"
                                     name="forma_pago">
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
                     </div>-->
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>MONTO A PAGAR</b></label>
                        <div class="col-md-7">
                            <input type="number" step="any" class="form-control" value="0.00" id="monto_pagar"
                                   name="monto_pagar"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label text-right"><b>NOTA</b></label>
                        <div class="col-md-7">
                            <textarea type="text" class="form-control" id="nota"
                                      name="nota"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn btn-primary" onclick="imprimir_pagos_deudas();"
                            type="submit"><i class="fa fa-save"></i> Guardar
                    </button>

                    <!--  <a class="btn btn-warning " onclick="imprimir_pagos_deudas();" href target="_blank"
                         type="submit"><i
                                  class="fa fa-print"></i> Imprimir
                      </a>-->
                    <a id="btn_cerrar_modal_pagos" class="btn btn-danger" data-dismiss="modal"><i
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

<script src="<?= base_url('js-sistema/pago.js') ?>"></script>
<script>
    $(document).ready(function () {
        listar_deudas();
    });
</script>
