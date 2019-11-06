<?php
/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 10/04/2018
 * Time: 04:23 PM
 */
?>
<script language="javascript" type="text/javascript">
    function valida1(tx) {
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
        document.getElementById('clave-nueva').value = "";
        document.getElementById('clave-nueva-r').value = "";
        return true;
    }
</script>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-refresh fa-2x"></i> <b>CAMBIO DE CONTRASEÑA</b></h3>
                </div>
                <div class="box-body">
                    <div class="content-box-large box-with-header">
                        <form id="frm-cambiar-clave" action="<?= site_url('inicio/confirmar_cambio') ?>"
                              class="form-horizontal" role="form" method="post">
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <div class="alert alert-danger" role="alert" style="margin-bottom: 0%">
                                        Pasos para cambiar su contraseña:
                                        <ul>
                                            <li>Primero verifique que es uds. y no otra persona que quiere cambiar la
                                                clave.
                                            </li>
                                            <li>Ingrese su contraseña y pulse el boton <em>verificar</em></li>
                                            <li>Si los datos son correctos apareceran las casillas para la nueva
                                                contraseña.
                                            </li>
                                            <li>Caso contrario o se olvido su clave o uds no es este usuario.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-4" for="exampleInputEmail2">CONTRASEÑA
                                    ACTUAL:</label>
                                <div class="col-lg-5">
                                    <input type="password" class="form-control" id="actual" name="actual"
                                           placeholder="Ingrese su clave actual" autofocus>
                                    <span id="aviso" style="font-size: 9pt; color: red; font-weight: bold" hidden><em>La
                                            contraseña es incorrecta, intente nuevamente.</em></span>
                                    <input id="usuario-id" name="usuario-id" value="1" hidden>
                                </div>
                                <a type="button" class="btn btn-danger" id="verificar"><i
                                        class="glyphicon glyphicon-check"></i> Verificar</a>
                                        
                            </div>
                            <div id="nuevo" hidden>
                                <div class="form-group">
                                    <label class="control-label col-lg-4">CONTRASEÑA
                                        NUEVA:</label>
                                    <div class="col-lg-5">
                                        <input type="password" class="form-control" id="clave-nueva" name="clave-nueva"
                                               placeholder="Ingrese su clave actual">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-4">REPETIR CONTRASEÑA
                                        NUEVA:</label>
                                    <div class="col-lg-5">
                                        <input type="password" class="form-control" id="clave-nueva-r"
                                               name="clave-nueva-r"
                                               placeholder="Ingrese su clave actual">
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary" onClick="valida1(this.form.clave-nueva.value)">
                                        <i class="glyphicon glyphicon-refresh"></i> <b>Cambiar</b>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url('js-sistema/inicio.js') ?>"></script>

