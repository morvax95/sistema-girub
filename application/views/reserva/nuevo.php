<?php
$user_data = $this->session->userdata('usuario_sesion');
?>
<!-- Main content -->
<style>
    hr {
        margin-top: 2%;
        margin-bottom: 1%;
    }
</style>
<?php if ($user_data['cargo'] == '1' or $user_data['cargo'] == '3') { ?>
    <section id="seccion" class="content">
        <div class="row">
            <form id="frm_registrar_inventario" class="form-horizontal" role="form"
                  action="javascript: agregar_fila();">
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-file-archive-o fa-2x"></i> <b>RESERVA DE CANCHA</b>
                            </h3>
                        </div>
                        <div class="form-horizontal">
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label"><b>NOMBRE</b></label>
                                        <input style="font-size: 13pt; font-weight: bold" type="text"
                                               id="nombre_usuario"
                                               name="nombre_usuario" class="form-control"
                                               value="" placeholder="Nombre del Cliente"/>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label"><b>CI</b></label>
                                        <input style="font-size: 13pt; font-weight: bold" type="number" id="ci_docente"
                                               name="ci_docente" class="form-control" value=""
                                               placeholder="ci del cliente" autofocus/>
                                        <input type="text" id="idCliente" name="idCliente" value="" hidden/>
                                    </div>
                                    <div style="padding-top: 3%" class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <a
                                                href="<?= site_url('persona/nuevo') ?>"
                                                class="btn btn-success btn-block"><i class="fa fa-user-plus"></i> Nueva
                                            Cliente</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                <span style="color:red">
                                    <i class="fa fa-info-circle"></i>
                                    <b>
                                       <em>
                                            Los Clientes que no esten registrados, se agregarán en la agenda automáticamente al registrar la Reserva.
                                        </em>
                                    </b>
                                </span>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label class="control-label">TURNO</label>
                                        <select id="turno_id" name="turno_id" class="form-control">
                                            <option value="0">::ELIJA UNA OPCIÓN</option>
                                            <option value="1">::TURNO MAÑANA</option>
                                            <option value="2">::TURNO TARDE</option>
                                            <option value="3">::TURNO NOCHE</option>

                                        </select>
                                    </div>


                                </div>
                                <hr>
                                <div class="col-xs-12">
                                    <table id="lista_inventario" class="table table-bordered">
                                        <thead>
                                        <th style="width: 30%" class="text-center">NOMBRE DEL ESCENARIO</th>
                                        <th style="width: 10%" class="text-center">TIPO DE ESCENARIO</th>
                                        <th style="width: 10%" class="text-center">OPCIONES</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td>
                                                <input id="nombre_producto" name="nombre_producto" type="text"
                                                       class="form-control input-sm"
                                                       placeholder="Escriba el nombre del escenario deportivo"/>
                                                <!--CONTADOR DE FILAS DE LA TABLA -->
                                                <input type="text" id="contador_inventario" name="contador_inventario"
                                                       hidden/>
                                                <!--CODIGO -->
                                                <input type="text" id="producto_id" name="producto_id"
                                                       placeholder="producto" hidden>
                                            </td>
                                            <td>
                                                <input type="number" id="cantidad_inventario" name="cantidad_inventario"
                                                       class="form-control input-sm "
                                                       placeholder="Escriba el tipo de escenario deportivo">
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
                                <div class="form-group">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">Nota</label>
                                        <textarea id="nota" name="nota" class="form-control">

                                    </textarea>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SUBTOTAL Y DESCUENTO -->
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <label class="control-label" style="font-size: 14pt"><b>FECHA RESERVA</b></label>
                            <input style="font-size: 18pt;" type="date" step="any" id="fecha_reserva"
                                   name="fecha_reserva"
                                   class="form-control" value="0.00"/>
                            <label class="control-label" style="font-size: 14pt"><b>HORA INICIO</b></label>
                            <input style="font-size: 18pt;" type="number" step="any" id="hora_inicio"
                                   name="hora_inicio"
                                   class="form-control" value="0.00"/>
                            <label class="control-label" style="font-size: 14pt"><b>HORA DE FIN</b></label>
                            <input style="font-size: 18pt;" type="number" step="any" id="hora_fin"
                                   name="hora_fin"
                                   class="form-control" value="0.00"/>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-body">
                            <label class="control-label" style="font-size: 14pt"><b>SUB TOTAL</b></label>
                            <input style="font-size: 18pt;" type="number" step="any" id="sub_total" name="sub_total"
                                   class="form-control" value="0.00"/>
                            <label class="control-label" style="font-size: 14pt"><b>DESCUENTO</b></label>
                            <input style="font-size: 18pt;" type="number" step="any" id="descuento"
                                   name="descuento"
                                   class="form-control" value="0.00"/>
                        </div>
                    </div>
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">

                            <span class="info-box-text" style="font-size: 14pt"><b>TOTAL</b></span>
                            <span class="info-box-number">
                          <input readonly style="border:0px; font-size: 18pt; background-color: transparent"
                                 type="number"
                                 step="any" id="total_as" name="total_as"
                                 class="form-control" value="0.00"/></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <button id="btn_registrar_inventario" name="btn_registrar_inventario"
                            class="btn btn-block btn-primary">
                        <i class="fa fa-save"></i> Registrar
                        Reserva
                    </button>
                </div>
            </form>
    </section>
<?php } else { ?>
    <br>
    <br>
    <br>
    <div class="form-group">
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissible">
                <h4><i class="icon fa fa-info"></i> AVISO!</h4>
                <i class="fa fa-info"></i>&nbsp; NO TIENE ACCESO A ESTA PARTE DEL SISTEMA
            </div>
        </div>
    </div>

    <?php

}
?>


<!-- REGISTRO DE CLIENTE -->
<div id="modal_registro_cliente" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" style="color: black"><b>Registro de cliente</b></h5>-->
                <center><h5 class="panel-heading cabecera_frm bg-primary" style="color: white"><b> REGISTRO DE UN NUEVO
                            CLIENTE</b></center>
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
                    <!--  <div class="form-group">
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

<script src="<?= base_url('js-sistema/reserva.js') ?>"></script>
<script>

    var count = 0;
    function agregar_fila() {
        if ($('#nombre_producto').val() === '') {
            swal('No puede agregar item en blanco.');
            return;
        }
        count = count + 1;
        $('#contador_inventario').val(count);
        var frm = $("#frm_registrar_inventario").serialize();
        console.log(frm);
        $.ajax({
            url: site_url + 'personaCurso/agregar',
            data: frm,
            type: 'post',
            success: function (registro) {
                var datos = eval(registro);
                $('#cantidad_inventario').val('1');
                $('#lista_inventario tbody').append(datos[0]);
                $('#nombre_producto').focus();
                $('#nombre_producto').val('');

                eliminar_fila();
                return false;
            }
        });
        return false;
    }

    function eliminar_fila() { //Elimina las filas de la tabla
        $("a.eliminar").click(function () {
            $(this).parents("tr").fadeOut("normal", function () {
                $(this).remove();
                count = count - 1;
                $('#contador_inventario').val(count);
            });
        });
    }
</script>
