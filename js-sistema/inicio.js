/**
 * Created by Juan on 23/03/2018.
 */
$(document).ready(function () {

    $('#frm-cambiar-clave').submit(function (event) {
        event.preventDefault();

        ajaxStart('Guardando datos espere...');
        $.ajax({
            url: site_url + 'inicio/confirmar_cambio',
            type: 'post',
            data: $('#frm-cambiar-clave').serialize(),
            dataType: 'json',
            success: function (respuesta) {
                ajaxStop();
                if (respuesta.success === true) {
                    $('.abm-error').remove();
                    //   swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    if (respuesta.status === 'error') {
                        swal('Las contraseña no coinciden');
                    } else {
                        swal('Contraseña actualizada, cerrando sesion espere por favor.');
                        setTimeout(location.href = site_url + 'login/cerrar_sesion', 3000);
                    }
                } else {
                    $('.abm-error').remove();
                    if (respuesta.messages !== null) {
                        $.each(respuesta.messages, function (key, value) {
                            var element = $('#' + key);
                            var parent = element.parent();
                            parent.removeClass('form-line');
                            parent.addClass('form-line error');
                            parent.after(value);
                        });
                    } else {
                        swal('Error', 'Eror al registrar los datos.', 'error');
                    }
                }
                /*if(respuesta === 'error'){
                 swal('Las contraseña no coinciden');
                 }else{
                 swal('Contraseña actualizada, cerrando sesion espere por favor.');
                 setTimeout(location.href = site_url+'login/cerrar_sesion',3000);
                 }*/
            }
        });
    });

    $('#verificar').click(function () {
        var usuario = $('#usuario-id').val();
        var clave = $('#actual').val();
        $.ajax({
            url: site_url + 'inicio/verificar',
            data: 'clave=' + clave,
            type: 'post',
            success: function (data) {
                console.log(data)
                if (data === 'error') {
                    $('#aviso').show();
                } else {
                    $('#aviso').hide();
                    $('#verificar').attr('disabled', true);
                    $('#actual').attr('readonly', true);
                    $('#nuevo').show();
                }
            }
        })
    });
    //$('#frm-cambiar-gestion').submit(function(event){
    //    event.preventDefault();
    //    var frm = $('#frm-cambiar-gestion').serialize();
    //    $.ajax({
    //        url: baseurl+'gestion/confirmarCambio',
    //        type: 'post',
    //        data: frm,
    //        success: function (respuesta) {
    //            if(respuesta){
    //                swal('la gestion ha sido cambiada correctamente.');
    //                location.href = baseurl+'inicio';
    //            }
    //        }
    //    });
    //});

});