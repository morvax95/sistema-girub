/**
 * Created by Juan Carlos on 16/02/2018.
 */
$(document).ready(function () {
    obtenerUsuarios();
    verfificar_seleccion_funciones();

    /****** verificamos si el cargo que se esta eligiendo es el administrador *****/
    $('#cargo').change(function () {
        var valor = $('#cargo').val();
        if (valor == 1) {
            //  $('#funciones').hide();
            $('#lista_funciones').hide();
            $('#div_seleccionar_todo').hide();
            $('#div_mensaje_vacio').hide();
            $('#div_mensaje_adm').show();
        }
        else if (valor == 0) {
            $('#lista_funciones').hide();
            $('#div_seleccionar_todo').hide();
            $('#div_mensaje_adm').hide();
            $('#div_mensaje_vacio').show();
        } else {
            // $('#funciones').show();
            $('#lista_funciones').show();
            $('#div_seleccionar_todo').show();
            $('#div_mensaje_adm').hide();
            $('#div_mensaje_vacio').hide();
            cargarFunciones();
        }
    });

    function verfificar_seleccion_funciones() {
        var valor = $('#cargo').val();
        if (valor == 0) {
            $('#lista_funciones').hide();
            $('#div_seleccionar_todo').hide();
            $('#div_mensaje_adm').hide();
            $('#div_mensaje_vacio').show();
        }

    }

    // Verificador de clave correcta
    $('#confirmar').focusout(function () {
        var pass = $('#clave').val();
        if ($(this).val() != '') {
            if (pass != $(this).val()) {
                $('#msj_pass').show();
            } else {
                $('#msj_pass').hide();
            }
        }
    });

    $('#frm_registrar_usuario').submit(function (event) {
        event.preventDefault();

        if ($('#cargo').val() === '0') {
            swal('Seleccione un cargo ');
            return true;
        }

        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
    });

    $('#frm_editar_usuario').submit(function (event) {
        event.preventDefault();

        if ($('#cargo').val() === '0') {
            swal('Seleccione un cargo ');
            return true;
        }

        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        setTimeout(function () {
            location.href = site_url + 'usuario/index'
        }, 2000);
    });

});

function editar(seleccionado) {
    edit_registrer_frm(seleccionado, 'usuario/editar');
}

function eliminar(seleccionado) {
    delete_registrer(seleccionado, 'usuario/eliminar', $('#lista_usuario'));
}


function obtenerUsuarios() {
    $('#lista_usuario').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,

        'ajax': {
            "url": site_url + "usuario/obtener_usuarios",
            "type": "post",
            dataSrc: ''
        },
        'columns': [
            {data: 'id'},
            {data: 'ci', class: 'text-center'},
            {data: 'nombre_usuario', class: 'text-center'},
            {data: 'descripcion', class: 'text-center'},
            {data: 'telefono', class: 'text-center'},
            {data: 'usuario', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 6,
                visible: true,
                searchable: false,
                data: 'estado',
                "render": function (data, type, row) {
                    if (data == 0) {
                        return "<span class='label label-danger'><i class='fa fa-times'></i> Inactivo</span>"
                    } else if (data == 1) {
                        return "<span class='label label-success'><i class='fa fa-check'></i> Habilitado</span>"
                    }
                }
            },
            /*   {
             targets: 7,
             render: function (data, type, row) {
             if (row.estado != 0) {
             return get_buttons_frm('ee');
             } else {
             return '<a onclick="reactivar(this);" class="btn btn-primary btn-xs"><i class="fa fa-upload"></i> Reactivar usuario</a>&nbsp;&nbsp;';
             }
             }
             }*/
            {
                targets: 7,
                render: function (data, type, row) {
                    if (row.estado != 0) {

                        return '<a onclick="editar(this);" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Editar</a>&nbsp;&nbsp;' +
                            '<a onclick="eliminar(this);" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Eliminar</a>&nbsp;&nbsp;';
                    } else {
                        return '<a onclick="reactivar(this);" class="btn btn-primary btn-xs"><i class="fa fa-upload"></i> Reactivar usuario</a>&nbsp;&nbsp;';
                    }
                }
            },
            {
                targets: 2,
                data: "nombre_usuario",
                render: function (data, type, row) {
                    return "<spam style='color:#0d6aad; font-weight: bold;'> " + data + "</spam>"
                }
            }
        ],


        "order": [[0, "asc"]],
    });
}


function reactivar(seleccionado) {
    var table = $('#lista_usuario').DataTable();
    var celda = $(seleccionado).parent();
    var rowData = table.row(celda).data();
    var id = rowData['id'];
    swal({
            title: "Esta seguro que desea volver a activar a este usuario?",
            text: "Se cambiara el estado de usuario a activo.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, activar usuario!",
            cancelButtonText: "No deseo activar al usuario",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: site_url + 'usuario/reactivar',
                    data: 'id_usuario=' + id,
                    type: 'post',
                    success: function (registro) {
                        if (registro == 'error') {
                            swal("Error", "Problemas al activar, comuniquese con el administrador del sistema", "error");
                        } else {
                            swal("Activado!", "El usuario ha sido activado.", "success");
                            actualizarDataTable($('#lista_usuario'));
                        }
                    }
                });
            } else {
                swal("Cancelado", "Accion cancelada.", "error");
            }
        });
}

function cargarFunciones() {
    $.post(site_url + "usuario/obtener_privilegios",
        function (data) {
            var datos = JSON.parse(data);
            $('#funciones').empty();
            $.each(datos, function (i, item) {
                $('#funciones').append(
                    '<div class="checkbox">' +
                    '<label style="padding: 0%" >' +
                    '<input type="checkbox" id="menu" name="menu[]" value="' + item.id + '">&nbsp;' + item.name + '' +
                    '</label>' +
                    '</div>');
            });
        });
}


function seleccionar_todo(input) {
    var checked = input.checked;
    var elementos = document.getElementsByTagName('input');
    for (var i = 0; i < elementos.length; i++) {
        elementos[i].checked = checked;
    }
}