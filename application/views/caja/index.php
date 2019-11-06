<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 06/07/2018
 * Time: 09:37 AM
 */
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-credit-card fa-2x"></i> <b>REGISTRO DE CAJAS</b></h3>
                    <div style="float:right">
                        <a href="#modal_registro_caja" data-toggle="modal" class="btn btn-success"><i
                                    class="fa fa-plus"></i> Crear Caja</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="lista_caja" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-center">DESCRIPCIÓN</th>
                            <th class="text-center">ESTADO</th>
                            <th class="text-center">OPCIONES</th>
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

<div id="modal_registro_caja" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!--<h5 class="modal-title" style="color: black"><b>Registre las cajas que tendra su negocio</b></h5>-->
                <center><h5 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> Registre las cajas
                            que tendrá su negocio</b></center>
            </div>
            <form id="frm_registrar_caja" action="<?= site_url('caja/registrar_caja') ?>" method="post"
                  class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">DESCRIPCIÓN *</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="descripcion" name="descripcion" value=""
                                   placeholder="Ingrese el nombre de su caja">
                        </div>
                    </div>
                    <!--                    <div class="form-group">-->
                    <!--                        <label class="col-sm-3 control-label">Sucursales</label>-->
                    <!--                        <div class="col-sm-8">-->
                    <!--                            <select id="sucursales" name="sucursales" class="form-control">-->
                    <!--                                --><?php
                    //                                foreach ($sucursales as $row){
                    //                                    ?>
                    <!--                                <option value="<? //= $row->id ?><? //= $row->sucursal ?><!--</option>-->
                    <!--                                --><?php
                    //                                }
                    //                                ?>
                    <!--                            </select>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                </div>
                <div class="modal-footer text-center">
                    <button id="modal_cliente" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    <a id="btn_cerrar_modal_caja" class="btn btn-danger" data-dismiss="modal"><i
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

<div id="modal_modifica_caja" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="panel-heading cabecera_frm bg-primary" style="color: white"><b>MODIFICAR LOS DATOS
                            REGISTRADOS</b></h5>
                </center>
            </div>
            <form id="frm_modificar_caja" action="<?= site_url('caja/modificar_caja') ?>" method="post"
                  class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">DESCRIPCIÓN *</label>
                        <div class="col-sm-8">
                            <input id="id_caja" name="id_caja" value="" hidden>
                            <input type="text" class="form-control" id="descripcion_edita" name="descripcion_edita"
                                   value=""
                                   placeholder="Ingrese el nombre de su caja">
                        </div>
                    </div>
                </div>
                <!--                <div class="form-group">-->
                <!--                    <label class="col-sm-3 control-label">Sucursales</label>-->
                <!--                    <div class="col-sm-8">-->
                <!--                        <select id="sucursales_edita" name="sucursales_edita" class="form-control">-->
                <!--                            --><?php
                //                            foreach ($sucursales as $row){
                //                                ?>
                <!--                                <option value="<? //= $row->id ?><? //= $row->sucursal ?><!--</option>-->
                <!--                                --><?php
                //                            }
                //                            ?>
                <!--                        </select>-->
                <!--                    </div>-->
                <!--                </div>-->
                <div class="modal-footer text-center">
                    <button id="modal_caja" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    <a id="btn_cerrar_modal_caja_modifica" class="btn btn-danger" data-dismiss="modal"><i
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

<script src="<?= base_url('js-sistema/caja.js') ?>"></script>
