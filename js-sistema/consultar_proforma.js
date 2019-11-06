/**
 * Created by Juan on 23/03/2018.
 */

$(document).ready(function () {
    get_note_sales();
});

function get_note_sales() {
    $('#lista_nota').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'responsive': true,
        'processing': true,
        'serverSide': true,

        'ajax': {
            "url": site_url + "consultar_proforma/get_all",
            "type": "post",
            // dataSrc: ''
        },
        'columns': [
            {data: 'id'},
            {data: 'nro_proforma', class: 'text-center'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'fecha', class: 'text-center'},
            {data: 'total', class: 'text-center'},
            {data: 'descuento', class: 'text-center'},
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
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    return get_buttons_frm_proforma('ver');
                }
            }
        ],
        "order": [[0, "desc"]],
    });

}

function get_buttons_frm_proforma() {
    var buttons = '';
    buttons = '<div class="btn-group">' +
        '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">     ' +
        'OPCIONES <span class="caret"></span> ' +
        '</button><ul class="dropdown-menu">';
    buttons = buttons + '<li><a onclick="print_proforma(this);"><i class="fa fa-print"></i> Imprimir</a></li>' +
        /*'<li><a onclick="consolidate_sale(this);"><i class="fa fa-wpforms"></i> Consolidar</a></li>'+*/
        '</ul> ' +
        '</div>';
    return buttons;
}

function print_proforma(element) {
    var table = $(element).closest('table').DataTable();
    var fila = $(element).parents('tr');
    if (fila.hasClass('child')) {
        fila = fila.prev();
    }
    var rowData = table.row(fila).data();
    var id = rowData['id'];
    var url = site_url + 'proforma/print_proforma/' + id;
    $.redirect(url, {id: id}, 'POST', '_blank');
}


function mostrar_ventana_consulta(url, title, w, h) {
    var left = 200;
    var top = 50;

    window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

function ver_registro(seleccionado) {
    var table = $(seleccionado).closest('table').DataTable();
    var current_row = $(seleccionado).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();

    $.redirect(site_url + 'proforma/consolidar_proforma', {id: data['id']}, 'POST', '_self');
}