/**
 * Created by Juan on 23/03/2018.
 */
$(document).ready(function () {

    lista_egresos();

    /** METODO PARA NUEVO TIPO EGRESO **/
    $('#frm_tipo_egreso').submit(function (event) {
        event.preventDefault();

        var form = $(this);
        var data = form.serialize();
        registro_abm(form, data);
        form[0].reset();
        cargar_tipos_egresos();
        $('#btn_cerrar_modal_tipo').click();
    });

    /** METODO PARA NUEVO EGRESO **/
    $('#frm_registrar_egreso').submit(function (event) {
        event.preventDefault();

        if ($('#monto').val() === '0.00') {
            swal('El monto egresado no puede ser cero');
            return true;
        }
        var form = $(this);
        var data = form.serialize();
        registro_abm(form, data);
        form[0].reset();
    });

    $('#frm_editar_egreso').submit(function (event) {
        event.preventDefault();

        if ($('#monto').val() === '0.00') {
            swal('El monto egresado no puede ser cero');
            return true;
        }
        var form = $(this);
        var data = form.serialize();
        registro_abm(form, data);
        setTimeout(function () {
            location.href = site_url + 'egreso/index'
        }, 2000);
    });
});

// Recarga el combo de tipos de egreso
function cargar_tipos_egresos() {
    $.post(site_url + "egreso/get_all_type_of_egress",
        function (data) {
            $('#tipo_egreso').empty();
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                $('#tipo_egreso').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
            });
        });
}

function lista_egresos() {
    $('#lista_egreso').DataTable({
        'lengthMenu': [[20, 60, 150, 250, 300], [20, 60, 150, 250, 300]],
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,

        'ajax': {
            "url": site_url + "egreso/get_all",
            "type": "post",
        },
        'columns': [
            {data: 'id'},
            {data: 'fecha_egreso', class: 'text-center'},
            {data: 'descripcion', class: 'text-center'},
            {data: 'monto', class: 'text-center'},
            // {data: 'sucursal', class: 'text-center'},
            {data: 'estado', class: 'text-center'},
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
                targets: 5,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    if (row.nombre_cliente != 'ANULADO') {
                        if (row.estado != '2') {
                            return '<a role="button"  onclick="reimprimir_ingreso(this)"  ><img width="30" height="30" src="' + base_url + '/assets/img/imprimir.png" title="Buscar"></a>&nbsp;&nbsp;';
                        } else {
                            return '<label style="color: red;"><b>Esta anulado</b></label>';
                        }
                    }
                }
            },
            {
                targets: 4,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado === '1') {
                        return "<span style='font-size: 9pt; font-weight: normal' class='label label-success'><i class='fa fa-clock-o'></i> Habilitado</span>"
                    } else {
                        return "<span style='font-size: 9pt;font-weight: normal' class='label label-danger'><i class='fa fa-times'></i> Inhabilitado</span>"
                    }
                }
            },
            {
                targets: 6,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado === '1') {
                        return get_buttons_frm('ee');
                    } else {
                        return '';
                    }
                }
            },
            {
                targets: 2,
                data: "descripcion",
                render: function (data, type, row) {
                    return "<spam style='color:#0d6aad; font-weight: bold;'> " + data + "</spam>"
                }
            }
        ],


        "order": [[0, "asc"]],
    });
}

function editar(seleccionado) {
    edit_registrer_frm(seleccionado, 'egreso/editar')
}

function eliminar(seleccionado) {
    delete_registrer(seleccionado, 'egreso/eliminar')
}
function reimprimir_ingreso(seleccionado) {
    var table = $('#lista_egreso').DataTable();
    var celda = $(seleccionado).parent();
    var rowData = table.row(celda).data();
    var id = rowData['id']
    mostrar_ventana_consulta(site_url + 'egreso/imprimir_egreso_seleccionado/' + id, 'Nota de Ingreso', '1000', '700');//para imprimir en tamaño carta el comprobante de venta
    //mostrar_ventana_consulta(site_url + 'consultar_venta/imprimir_nota_venta/' + id, 'Nota de Venta','1000', '700'); //para imprimir el comprobante en tamaño TM-U220
}
function mostrar_ventana_consulta(url, title, w, h) {
    var left = 200;
    var top = 50;

    window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}


