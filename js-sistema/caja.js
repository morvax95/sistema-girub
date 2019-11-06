/**
 * Created by Juan on 23/03/2018.
 */
$(document).ready(function () {

    listado_caja();

    $('#frm_registrar_caja').submit(function (event) {
        event.preventDefault();
        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        formulario[0].reset();
        $('#btn_cerrar_modal_caja').click();
        setTimeout(function () {
            location.href = site_url + 'caja/index'
        }, 1000);
    });

    $('#frm_modificar_caja').submit(function (event) {
        event.preventDefault();
        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        formulario[0].reset();
        $('#btn_cerrar_modal_caja_modifica').click();
        setTimeout(function () {
            location.href = site_url + 'caja/index'
        }, 1000);
    })
});

function listado_caja() {
    $('#lista_caja').DataTable({
        'lengthMenu': [[20, 60, 150, 250, 300], [20, 60, 150, 250, 300]],
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,

        'ajax': {
            "url": site_url + "caja/get_all",
            "type": "post",
            dataSrc: ''
        },
        'columns': [
            {data: 'id'},
            {data: 'descripcion', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
            {data: 'opciones', class: 'text-center'}
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 2,
                visible: true,
                searchable: false,
                orderable: false,
                data: 'estado',
                render: function (data, type, row) {
                    // estado = 0
                    if (data == 0) {
                        return "<span class='label label-danger'><i class='fa fa-times'></i> Deshabilitado</span>"
                    } else {
                        //estado = 1
                        return "<span class='label label-success'><i class='fa fa-check'></i> Habilitado</span>"
                    }
                }
            },
            {
                targets: 3,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado == '1') {
                        return '<a class="btn btn-warning btn-sm" href="#modal_modifica_caja" data-toggle="modal" onclick="editar(this)"><i class="fa fa-edit"></i> Editar</a> ' +
                            '<a class="btn btn-danger btn-sm" onclick="eliminar(this)"><i class="fa fa-times-circle"></i> Desactivar</a>';
                    } else {
                        return '';
                    }
                }
            }
        ],
        "order": [[1, "asc"]],
    });
}

function editar(seleccionado) {
    var table = $('#lista_caja').DataTable();
    var celda = $(seleccionado).parent();
    var rowData = table.row(celda).data();
    var id = rowData['id'];
    $.ajax({
        url: site_url + 'caja/get_box_by_id',
        data: 'id=' + id,
        type: 'post',
        success: function (response) {
            var data = JSON.parse(response);
            $('#id_caja').val(data['id']);
            $('#descripcion_edita').val(data['descripcion']);
        }
    });
}

function eliminar(seleccionado) {
    delete_registrer(seleccionado, 'caja/eliminar', $('#lista_caja'));
}