/**
 * Created by Juan on 23/03/2018.
 */

$(document).ready(function () {

});

// Funcion generica para el registro de formularios
function registro_abm(frm, data) {
    ajaxStart('Guardando el registro, por favor espere');
    $.ajax({
        url: $(frm).attr('action'),
        type: $(frm).attr('method'),
        data: data,
        dataType: 'json',
        success: function (respuesta) {
            ajaxStop();
            if (respuesta.success == true) {
                get_message('guardar');
                frm[0].reset();
                if ( $(this).hasClass('frm-datatable') ) {
                    $('table').DataTable.ajax.reload();
                }
            } else {
                $('.error').remove();
                if (respuesta.messages != null) {
                    $.each(respuesta.messages, function (key, value) {
                        // Usá la versión 2.x de jquery
                        var element = $('#' + key);
                        element.closest('input .form-control').find('.error').remove();
                        element.after(value);
                    });
                } else {
                    get_message('error');
                }
            }

        }
    });
}

function edit_registrer_frm(seleccionado, metodo) {
    var table = $(seleccionado).closest('table').DataTable();
    var current_row = $(seleccionado).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();

    $.redirect(site_url + metodo, { id: data['id'] }, 'POST', '_self');
}

function delete_registrer(seleccionado, metodo, tabla) {
    var table = $(seleccionado).closest('table').DataTable();
    var current_row = $(seleccionado).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    var id  = data['id'];

    swal({
            title: "Está seguro que desea eliminar este registro?",
            text: "El estado del registro cambiará",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, eliminar registro!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                ajaxStart('Anulando o Desactivando el registro, por favor espere');
                $.post(site_url + metodo, { id: id }).done(function( response ) {

                    if (response == true) {
                        ajaxStop();
                        table.ajax.reload();

                        swal("Eliminado!", "El registro ha sido eliminado.", "success");
                    } else {
                        ajaxStop();
                        swal("Error", "Problemas al eliminar", "error");
                    }
                });
            } /*else {
             swal("Cancelado", "Accion cancelada.", "error");
             }*/
        });
}

// Devuelve el valor del tipo de impresion seleccionada
function get_type_of_impresion(value) {
    var impresion = '';
    switch (parseInt(value)) {
        case 1:
            impresion = 'ROLLO';
            break;
        case 2:
            impresion = 'CARTA';
            break;
        case 3:
            impresion = 'MEDIA CARTA';
            break;
        case 4:
            impresion = 'OFICIO';
            break;
    }
    return impresion;
}

//
function get_state_inventory(value) {
    var label = '';
    switch (parseInt(value)) {
        case 0:
            label = '<span style="font-size: 9pt; font-weight: normal" class="label label-warning">No ingresado</span>';
            break;
        case 1:
            label = '<span style="font-size: 9pt; font-weight: normal" class="label label-primary">Ingresado</span>';
            break;
        case 2:
            label = '<span style="font-size: 9pt; font-weight: normal" class="label label-danger">Agotado</span>';
            break;
    }
    return label;
}

//
function get_buttons_frm(value) {
    var bottons = '<div class="btn-group"> ' +
        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> ' +
        'Opciones &nbsp; <span class="caret"></span> ' +
        '</button> ' +
        '<ul class="dropdown-menu" role="menu">';
    switch (value){
        case 'ver':
            bottons = bottons+'<li><a onclick="ver_registro(this)"><i class="fa fa-eye"></i> Ver</a></li>';
            break;
        case 'editar':
            bottons = bottons+'<li><a onclick="editar(this)"><i class="fa fa-edit"></i> Editar</a></li>';
            break;
        case 'eliminar':
            bottons = bottons+'<li><a onclick="eliminar(this)"><i class="fa fa-times-circle"></i> Desactivar</a></li></ul></div>';
            break;
        case 'ee':
            bottons = bottons+'<li><a onclick="editar(this)"><i class="fa fa-edit"></i> Editar</a></li>' +
                '<li><a onclick="eliminar(this)"><i class="fa fa-times-circle"></i> Desactivar</a></li></ul></div>';
            break;
        case 'vee':
            bottons = bottons+'<li><a onclick="ver_registro(this)"><i class="fa fa-eye"></i> Ver</a></li>' +
                '<li><a onclick="editar(this)"><i class="fa fa-edit"></i> Editar</a></li>' +
                '<li><a onclick="eliminar(this)"><i class="fa fa-times-circle"></i> Desactivar</a></li></ul></div>';
            break;
        case 've':
            bottons = bottons+'<li><a onclick="ver_registro(this)" class="btn btn-default btn-xs"><i class="fa fa-eye"></i> Ver</a></li>' +
                '<li><a onclick="eliminar(this)"><i class="fa fa-times-circle"></i> Desactivar</a></li></ul></div>';
            break;

        case 'edit-delete':
            bottons = bottons +'<li><a onclick="editar(this)"><i class="fa fa-edit"></i> Ver datos</a></li>' +
                '<li><a onclick="eliminar(this)"><i class="fa fa-times-circle"></i> Desactivar</a></li></ul></div>';
            break;
    }
    return bottons;
}


//<editor-fold desc="Devuelve el mensaje segun el tipo de evento solicitado. guardar, validar, error, tabla">
function get_message(type) {
    switch (type){
        case 'guardar':
            swal({
                type: 'success',
                title: 'Registro Guardado',
                text: 'Los datos ingresados se guardaron correctamente',
                showConfirmButton: false,
                timer: 1000
            });
            break;
        case 'validar':
            swal({
                type: 'warning',
                title: 'Datos Incorrectos',
                text: 'Verifique los datos ingresados, puede que hayan campos requeridos o de seleccion',
                showConfirmButton: false,
                timer: 2000
            });
            break;
        case 'error':
            swal({
                type: 'error',
                title: 'Error',
                text: 'Error al registrar los datos',
                showConfirmButton: false,
                timer: 2000
            });
            break;
        case 'tabla':
            swal({
                type: 'error',
                title: 'Error',
                text: 'No Existe detalle en la tabla',
                showConfirmButton: false,
                timer: 2000
            });
            break;
    }
}
//</editor-fold>
//
function ajaxStart(text) {
    if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
        jQuery('body').append('<div id="resultLoading" style="display:none"><div><i class="fa fa-cog fa-spin fa-4x fa-fw"></i><div>' + text + '</div></div><div class="bg"></div></div>');
    }

    jQuery('#resultLoading').css({
        'width':'100%',
        'height':'100%',
        'position':'fixed',
        'z-index':'10000000',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto'
    });

    jQuery('#resultLoading .bg').css({
        'background':'#000000',
        'opacity':'0.7',
        'width':'100%',
        'height':'100%',
        'position':'absolute',
        'top':'0'
    });

    jQuery('#resultLoading>div:first').css({
        'width': '250px',
        'height':'75px',
        'text-align': 'center',
        'position': 'fixed',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto',
        'font-size':'16px',
        'z-index':'10',
        'color':'#ffffff'

    });

    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeIn(300);
    jQuery('body').css('cursor', 'wait');
}

//
function ajaxStop() {
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeOut(300);
    jQuery('body').css('cursor', 'default');
}