/**
 * Created by Juan Carlos on 27/02/2018.
 */
$(document).ready(function () {
    obtener_sucursal();

    $('#frm_registrar_cliente').submit(function (event) {
        event.preventDefault();
        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
    });

    $('#frm_editar_sucursal').submit(function (event) {
        event.preventDefault();
        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        setTimeout(function () {
            location.href = site_url + 'sucursal/index'
        }, 2000);
    })
});
function obtener_sucursal() {
    $('#lista_sucursal').DataTable({
        'lengthMenu': [[20, 60, 150, 250, 300], [20, 60, 150, 250, 300]],
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,

        'ajax': {
            "url": site_url + "sucursal/listar_sucursal",
            "type": "post",
        },
        'columns': [

            {data: 'id'},
            {data: 'nombre_empresa', class: 'text-center'},
            {data: 'direccion', class: 'text-center'},
            {data: 'telefono', class: 'text-center'},
            {data: 'email', class: 'text-center'},
            {data: 'sucursal', class: 'text-center'},
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
                target: 1,
                visible: true,
                searchable: false,
                orderable: false,
            },
            {
                target: 2,
                visible: true,
                searchable: false,
                orderable: false,
            },
            {
                target: 3,
                visible: true,
                searchable: false,
                orderable: false,
            },
            {
                target: 4,
                visible: true,
                searchable: false,
                orderable: false,
            },
            {
                targets: 6,
                visible: true,
                searchable: false,
                orderable: false,
                data: 'estado',
                render: function (data, type, row) {
                    // estado = 0
                    if (data == 0) {
                        return "<span class='label label-danger'><i class='fa fa-times'></i> Deshabilitado</span>"
                    } else if (data == 3) {
                        // estado = 3
                        return "<span class='label label-success'><i class='fa fa-check'></i> Habilitado</span>"
                    } else {
                        //estado = 1
                        return "<span class='label label-success'><i class='fa fa-check'></i> Habilitado</span>"
                    }
                }
            },
            {
                targets: 7,
                render: function (data, type, row) {

                    if (row.estado === '1') {
                        return '<a data-toggle="modal" role="button" href="#modal_ver_cliente" onclick="verSucursal(this);" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Ver</a>&nbsp;&nbsp;'
                    } else {
                        return '<a onclick="habilitarCliente(this);" class="btn btn-default btn-xs text-black"><i class="fa fa-arrow-up"></i> Habilitar</a>';
                    }
                }
            },
            {
                targets: 3,
                data: "telefono_cliente",
                render: function (data, type, row) {
                    return "<spam style='font-size: 12pt;'><i class='fa fa-phone'></i> &nbsp;" + data + "</spam>"
                }
            },
            {
                targets: 2,
                data: "nombre_cliente",
                render: function (data, type, row) {
                    return "<spam style='color:#0d6aad; font-weight: bold;'> " + data + "</spam>"
                }
            }
        ],
        "order": [[1, "asc"]],
    });
}

function editar(seleccionado) {
    edit_registrer_frm(seleccionado, 'sucursal/editar')
}

function verSucursal(seleccionado) {
    var table = $('#lista_sucursal').DataTable();
    var celda = $(seleccionado).parent();
    var rowData = table.row(celda).data();
    var id = rowData['id'];
    var nombre = rowData['nombre_empresa'];
    var nit = rowData['nit'];
    var telefono = rowData['telefono'];
    var direccion = rowData['direccion'];
    var email = rowData['email'];
    var fecha_nacimiento = rowData['fecha_nacimiento'];
    var nombre_contacto = rowData['nombre_contacto'];
    var numero_contacto = rowData['numero_contacto'];

    $('#ver_ci').val(nit);
    $('#ver_telefono').val(telefono);
    $('#ver_nombre').val(nombre);
    $('#ver_direccion').val(direccion);
    $('#ver_email').val(email);
    $('#ver_fecha_nacimiento').val(fecha_nacimiento);
    $('#ver_nombre_contacto').val(nombre_contacto);
    $('#ver_numero_contacto').val(numero_contacto);
}


