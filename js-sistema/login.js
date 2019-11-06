/**
 * Created by Juan on 23/03/2018.
 */
$(document).ready(function () {

    $('input[type=text]').focus(function () {
        $(this).select();
    });



    $('#frm_login_sistema').submit(function (event) {
        event.preventDefault();
        $('#mjs').hide();
        var formData = $('#frm_login_sistema').serialize();
        $('#mensajes_login').empty();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success == true) {
                    swal({
                        type: 'success',
                        title: 'Datos Correctos',
                        text: 'Su sesion se ha iniciado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    setTimeout(function () {
                        location.href = site_login + 'inicio'
                    }, 2000);
                } else {
                    if (respuesta.messages != null) { // Datos incorrectos
                        $('#mensajes_login').append('<p id="mjs1" style="color: red; font-size: 12pt; font-weight: bold">' + respuesta.messages + '</p>');
                        $('#usuario').focus();
                        swal('Error !!');
                        return true;
                    }
                }
            }
        });
    });

    $('#frm_set_impresora').submit(function (event) {
        event.preventDefault();

        var marca = $('#impresora_sel option:selected').html();
        var formData = $('#frm_set_impresora').serialize();
        var frm = formData + '&marca=' + marca;
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: frm,
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta == true) {
                    swal('Sesion registrada', '', 'success');
                    setTimeout(function () {
                        location.href = site_login + 'inicio'
                    }, 2000);
                } else {
                    swal('Error', 'Eror al registrar la sesion.', 'error');
                }
            }
        });
    });

});

// function get_impresoras(){
//     $.post(site_login + "impresora/getImpresorasLogin",
//         function (data) {
//         console.log(data)
//             var datos = JSON.parse(data);
//             $.each(datos, function (i, producto) {
//                 $('#impresora_sel').append('<option value="' + producto.id + '">' + producto.marca + '|' + producto.autorizacion+ '</option>');
//             });
//         });
// }