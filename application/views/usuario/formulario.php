<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 16/03/2018
 * Time: 08:19 PM
 */

$index = 0;
$array_seleccionados = array();
$array_lista_sucursales = array();

if (isset($usuario)) {
    foreach ($funciones_seleccionadas['seleccionados'] as $fila) {
        $array_seleccionados[$index] = $fila->id;
        $index++;
    }
    $index = 0;
    foreach ($sucursales_seleccionadas as $fila_sucursales) {
        $array_lista_sucursales[$index] = $fila_sucursales->id;
        $index++;
    }

}
?>
<script language="javascript" type="text/javascript">
    function valida(tx) {
        var nMay = 0, nMin = 0, nNum = 0,nCaracter=0;
        var t1 = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ"
        var t2 = "abcdefghijklmnñopqrstuvwxyz"
        var t3 = "0123456789"
        var t4="*?¡!|-:_;"

        for (i = 0; i < tx.length; i++) {
            if (t1.indexOf(tx.charAt(i)) != -1) {
                nMay++
            }
            if (t2.indexOf(tx.charAt(i)) != -1) {
                nMin++
            }
            if (t3.indexOf(tx.charAt(i)) != -1) {
                nNum++
            }
            if (t4.indexOf(tx.charAt(i)) != -1) {
                nCaracter++
            }
        }
        if (nMay > 0 && nMin > 0 && nNum > 0 && nCaracter>0) {
            funcion_exitosa()
        }
        else {
            funcion_erronea()
        }
    }

    function funcion_exitosa() {
        //swal('Las Contraseñas segura ');
    }

    function funcion_erronea() {
        swal('La Contraseña es muy débil ');
        // alert('Las Contraseñas es muy débil ')
        document.getElementById('clave').value = "";
        document.getElementById('confirmar').value = "";
        return true;
    }
</script>

<div class="col-md-6">
    <div class="box box-success">
        <div class="box-header with-border">
            <center><h2 class="box-title">GESTIÓN DE USUARIO</h2></center>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="col-md-3 control-label text-right"><b>Cédula *</b></label>
                <div class="col-md-7">
                    <input type="hidden" id="id_usuario" name="id_usuario"
                           value="<?= isset($usuario) ? $usuario->id : '' ?>">
                    <input type="text" class="form-control" id="ci" name="ci"
                           value="<?= isset($usuario) ? $usuario->ci : '' ?>"
                           placeholder="Campo requerido" required/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label text-right"><b>Nombre *</b></label>
                <div class="col-md-7">
                    <input type="text" class="form-control" id="nombre" name="nombre"
                           value="<?= isset($usuario) ? $usuario->nombre_usuario : '' ?>"
                           placeholder="Campo requerido" required/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label text-right"><b>Teléfono</b></label>
                <div class="col-md-7">
                    <input type="text" id="telefono" name="telefono" class="form-control"
                           value="<?= isset($usuario) ? $usuario->telefono : '' ?>"
                           placeholder="Campo no requerido"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label text-right"><b>Cargo</b></label>
                <div class="col-md-7">
                    <select id="cargo" name="cargo" class="form-control">
                        <option value="0">::Elija una opción</option>
                        <?php foreach ($cargos as $row) { ?>
                            <option value="<?= $row->id ?>" <?= is_selected($row->id, isset($usuario) ? $usuario->cargo : null) ?>><?= $row->descripcion ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label text-right"><b>Sucursales</b></label>
                <div class="col-lg-9">
                    <div class="checkbox">
                        <?php
                        foreach ($sucursales as $row) {
                            if (in_array($row->id, $array_lista_sucursales)) {
                                ?>
                                <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <input id="seleccion_sucursal" name="seleccion_sucursal[]" type="checkbox"
                                           checked="checked"
                                           value="<?= $row->id ?>"> <?= $row->sucursal ?>
                                </label>
                                <?php
                            } else {
                                ?>
                                <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <input id="seleccion_sucursal" name="seleccion_sucursal[]" type="checkbox"
                                           value="<?= $row->id ?>"> <?= $row->sucursal ?>
                                </label>
                                <?php
                            }
                            ?>
                            <?php
                        }
                        ?>
                    </div>
                    <br>
                    <br>
                    <span style="color: red"><em><b>Seleccione las sucursales donde estará.</b></em></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label text-right"><b>Usuario *</b></label>
                <div class="col-md-7">
                    <input type="text" id="usuario" name="usuario" class="form-control"
                           value="<?= isset($usuario) ? $usuario->usuario : '' ?>"
                           placeholder="Campo no requerido" required/>
                </div>
            </div>
            <?php if (!isset($usuario)) { ?>
                <div class="form-group">
                    <label class="col-md-3 control-label text-right"><b>Clave *</b></label>
                    <div class="col-md-7">
                        <input type="password" id="clave" name="clave" class="form-control"
                               placeholder="Campo no requerido" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label text-right"><b>Confirmar Clave *</b></label>
                    <div class="col-md-7">
                        <input type="password" id="confirmar" name="confirmar" class="form-control"
                               placeholder="Repita la contraseña" required/>
                        <span id="msj_pass" style="font-weight: bold; font-size: 12pt; color: red;"
                              hidden><em>Las claves no coinciden.</em></span>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="alert alert-info" role="alert" style="margin-bottom: 0%">
                        <strong>Informacion:</strong> Los campos con (*) son requeridos.
                    </div>
                </div>
            </div>
            <div class="box-footer text-center">
                <div class="col-sm-offset-2 col-sm-10">
                   <!-- <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar
                    </button>-->
                    <button type="submit" id="btn_registrar_usuario" class="btn btn-primary"
                            onClick="valida(this.form.clave.value)"><i class="fa fa-save"></i> Guardar
                    </button>
                    <a href="<?= site_url('usuario') ?>" class="btn btn-danger"><i class="fa fa-times"></i>
                        Salir</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="privilegios" class="col-md-6">
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <center><h3 class="box-title">PRIVILEGIOS DEL SISTEMA</h3></center>
        </div>
        <div class="box-body">
            <div class="form-group" id="div_seleccionar_todo">
                <label style="margin-left: 4%" class="checkbox-inline">
                    <input type="checkbox" onclick="seleccionar_todo(this);" id="check_seleccionar"
                           name="check_seleccionar">&nbsp; Seleccionar todos</label>
            </div>
            <div class="box-body">
                <div class="form-group" id="div_mensaje_adm">
                    <center><p style="margin-left: 5%">Para los administradores no es necesaria la seleccion de
                            funciones.</p></center>
                    <!-- <div id="funciones" class="col-md-12" style="margin-left: 6%">
                     </div>-->

                </div>
                <div class="form-group" id="div_mensaje_vacio">
                    <center><p style="margin-left: 5%">Seleccione un cargo.</p></center>
                    <!-- <div id="funciones" class="col-md-12" style="margin-left: 6%">
                     </div>-->

                </div>
            </div>
            <!---->
            <table cellspacing="0" cellpadding="0" id="lista_funciones" class="table table-bordered">
                <tr>
                    <td style="text-align: center">
                        <b> Módulos </b>
                    </td>
                    <td style="text-align: center"><b> Funciones </b></td>
                </tr>
                <?php
                foreach ($menu as $row) {
                    ?>
                    <tr>
                        <td style="text-align: center;vertical-align: inherit"><?= $row['modulos']->name ?></td>
                        <td>
                            <table cellpadding="0" cellspacing="0" class="table table-bordered">
                                <?php
                                $lista_funciones['funciones'] = $row['funciones'];
                                foreach ($row['funciones'] as $row1) {
                                    ?>
                                    <tr>
                                        <td>
                                            <label style="margin-left: 2%" class="checkbox-inline">
                                                <?php
                                                if (in_array($row1->id, $array_seleccionados)) {
                                                    ?>
                                                    <input type="checkbox" id="menu" name="menu[]" checked="checked"
                                                           class="funciones"
                                                           value="<?= $row1->id ?>">&nbsp; <?= $row1->name ?>

                                                    <?php
                                                } else {
                                                    ?>
                                                    <input type="checkbox" id="menu" name="menu[]"
                                                           class="funciones"
                                                           value="<?= $row1->id ?>">&nbsp; <?= $row1->name ?>
                                                <?php } ?>
                                            </label>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <!---->
        </div>
    </div>
</div>
