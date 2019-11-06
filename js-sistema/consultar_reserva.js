$(document).ready(function () {

    get_reservas();
});

function get_reservas() {
    $('#lista_reserva').DataTable({
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'responsive': true,
        'processing': true,
        'serverSide': true,

        'ajax': {
            "url": site_url + "Consultar_reserva/get_all",
            "type": "post",
        },
        'columns': [
            {data: 'id'},
            {data: 'nro_nota', class: 'text-center'},
            {data: 'nombre_cliente', class: 'text-center'},
            {data: 'fecha', class: 'text-center'},
            {data: 'subtotal', class: 'text-center'},
            {data: 'forma_pago', class: 'text-center'},
            {data: 'imprimir', class: 'text-center'},

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
                    if (row.nombre_cliente != 'ANULADO') {
                        if (row.estado != '2') {
                            return '<a role="button"  onclick="reimprimir_nota_venta(this)"  ><img width="30" height="30" src="' + base_url + '/assets/img/imprimir.png" title="Buscar"></a>&nbsp;&nbsp;';
                        } else {
                            return '<label style="color: red;"><b>Esta anulado</b></label>';
                        }
                    }
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
        "order": [[0, "desc"]],
    });

}

function reimprimir_nota_venta(seleccionado) {
    var table = $('#lista_nota').DataTable();
    var celda = $(seleccionado).parent();
    var rowData = table.row(celda).data();
    var id = rowData['id']
    var forma_pago = rowData['forma_pago']
    if (forma_pago == 'Plazo') {
        mostrar_ventana_consulta(site_url + 'venta/imprimir_nota_ventas_plazos/' + id, 'Nota de Venta', '1000', '700');//para imprimir en tamaño carta el comprobante de venta
    } else if (forma_pago == 'Deposito' || 'Efectivo' || 'Tarjeta' || 'Cheque') {
        mostrar_ventana_consulta(site_url + 'venta/imprimir_nota_ventas_contado/' + id, 'Nota de Venta', '1000', '700');//para imprimir en tamaño carta el comprobante de venta
    }


}


function mostrar_ventana_consulta(url, title, w, h) {
    var left = 200;
    var top = 50;

    window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}