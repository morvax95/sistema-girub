$(document).ready(function () {
    obtener_solicitudes();

    function obtener_solicitudes() {
        $('#lista_solicitud').DataTable({
            'lengthMenu': [[20, 60, 150, 250, 300], [20, 60, 150, 250, 300]],
            'paging': true,
            'info': true,
            'filter': true,
            'stateSave': true,
            'processing': true,
            'serverSide': true,

            'ajax': {
                "url": site_url + "traspaso/listar_salidas",
                "type": "post",
            },
            'columns': [
                {data: 'id'},
                {data: 'id', class: 'text-center'},
                {data: 'almacen_origen',class: 'text-center'},
                {data: 'almacen_destino', class: 'text-center'},
                {data: 'fecha_salida', class: 'text-center'},
                {data: 'nombre_usuario', class: 'text-center'},
                {data: 'opciones', class: 'text-center'}
            ],
            "columnDefs": [
                {
                    targets: 0,
                    visible: false,
                    searchable: false,
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
                    target: 5,
                    visible: true,
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: 6,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, row) {

                        if (row.estado === '1') {
                            return get_buttons_frm('ee');
                        } else {
                            return '<a onclick="habilitarSolicitud(this);" class="btn btn-warning btn-xs text-warning"><i class="fa fa-arrow-up"></i> VER SOLICITUD</a>';
                        }

                    }
                },

            ],
            "order": [[0, "asc"]],
        });
    }

    function pagos_seleccionados() {
        var pagos = [];
        $('input:checked').each(function () {
            pagos.push($(this).val());
        });
        return pagos;
    }

    $('#btn_registrar_solicitud').click(function (event) {
        event.preventDefault();

        var cantidad_detalle = $('#lista_detalle tbody tr').length;
        if (cantidad_detalle == 0) {
            get_message('tabla');
            return true;
        }
        var form_data = $('#frm_registro_venta').serialize();
        var array = {};

        array['tipo_venta'] = 'nota';
        array['pagos_seleccionados'] = pagos_seleccionados();

        // Verificar que tipo de pago esta seleccionado para enviar
        if ($('#forma_pago1').is(':checked')) {
            var frm_efectivo = $('#pago1').serialize();
        }

        if ($('#forma_pago2').is(':checked')) {
            var frm_credito = $('#pago2').serialize();
        }

        if ($('#forma_pago3').is(':checked')) {
            var frm_deposito = $('#pago3').serialize();
        }

        if ($('#forma_pago4').is(':checked')) {
            var frm_cheque = $('#pago4').serialize();
        }

        if ($('#forma_pago5').is(':checked')) {
            var frm_tarjeta = $('#pago5').serialize();
        }
        ajaxStart('Guardando la venta, por favor espere');
        $.ajax({
            url: site_url + 'ingreso_mercaderia/registro_solicitud',
            type: 'post',
            data: form_data + '&' + frm_efectivo + '&' + frm_credito + '&' + frm_deposito + '&' + frm_cheque + '&' + frm_tarjeta + '&tipo_venta=' + 'nota',
            success: function (respuesta) {
                ajaxStop();
                if (respuesta == false) {
                    swal('Error', 'La transaccion no pudo ser completada', 'error');
                } else {

                    location.href = site_url + 'ingreso_mercaderia/index';
                    alert("Se registro exitosamente.");

                }
            }
        });
    });

    $('#detalle_venta').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: site_url + 'producto/get_producto_generico',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'detalle_inventario',
                    almacen_id: $('#almacen_id').val(),
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        var array = nombre.length;
                        if (array > 0) {
                            return {
                                label: nombre,
                                value: nombre,
                                id: item
                            };
                        } else {
                            return {
                                label: nombre,
                                value: "",
                                id: item
                            };
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            var data = (ui.item.id);
            $('#id_producto').val(data);
            $('#nombre_item').val(data);
            $('#detalle_venta').focus();

            cargar_select_talla(data);
        }
    });

    $('#codigo_barra_detalle').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: site_url + 'producto/get_producto_generico',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'codigo_barra_inventario',
                    almacen_id: $('#almacen_id').val(),
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        var array = nombre.split('|');
                        if (array.length > 1) {
                            return {
                                label: array[0] + '|' + array[1] + '|' + array[2] + '|' + array[3] + '|' + array[4] + '|' + array[5],
                                value: array[5],
                                id: item
                            };
                        } else {
                            return {
                                label: nombre,
                                value: "",
                                id: item
                            };
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            var data = (ui.item.id);
            var elem = data.split('|');
            var datos = ui.item.label;
            var data_array = datos.split('|');
            if (data_array.length > 0) {
                console.log(data_array[5]);
                if (data_array[4] == 0) {
                    swal({
                        type: 'error',
                        title: 'Producto Agotado',
                        text: 'El producto elegido tiene stock cero.',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    return true;
                } else {
                    $('#id_producto').val(elem[0]);
                    $('#id_color').val(elem[2]);
                    $('#id_talla').val(elem[1]);
                    $('#talla_venta').val(data_array[1]);
                    $('#color_venta').val(data_array[2]);
                    $('#precio_venta').val(data_array[3]);
                    $('#codigo_barra_detalle').val(data_array[5]);
                    $('#detalle_venta').val(data_array[0]);
                    $('#precio_venta').focus();
                    $('#stock_disponible').val(data_array[4]);

                    cargar_talla_codbarra(elem[1]);
                    cargar_color_codbarra(elem[2]);
                }
            } else {
                console.log('chambom');
            }
        }
    });


    var tecla = 113;
    $(document).keyup(function (e) {
        if (e.keyCode == tecla)
            $('#efectivo_as').focus();
    });
});


$(document).ready(function () {

    /* Evento de cambio de talla al cargar el item*/
    $('#talla_venta').on('change', function () {
        var item = $('#nombre_item').val();
        var talla_id = $('#talla_venta').val();
        cargar_select_color(item, talla_id);
    });

    /* Evento de seleccion de color  */
    $('#color_venta').on('change', function () {
        obtener_datos_inventario();
    })

});


function cargar_talla_codbarra(talla_id) {
    $.ajax({
        url: site_url + 'producto/get_talla_by_id',
        type: 'post',
        data: {talla_id: talla_id},
        dataType: 'json',
        success: function (response) {
            console.log(response);
            $('#talla_venta').empty();
            $.each(response, function (i, item) {
                $('#talla_venta').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
            });
        }
    });
}

function cargar_color_codbarra(color_id) {
    $.ajax({
        url: site_url + 'producto/get_color_by_id',
        type: 'post',
        data: {color_id: color_id},
        dataType: 'json',
        success: function (response) {
            console.log(response);
            $('#color_venta').empty();
            $.each(response, function (i, item) {
                $('#color_venta').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
            });
        }
    });
}

function rellenarCero(elemento) {
    elemento.val('0.00');
}

function obtener_datos_inventario() {
    var item = $('#nombre_item').val();
    var talla_id = $('#talla_venta').val();
    var color_venta = $('#color_venta').val();

    $.ajax({
        url: site_url + 'producto/get_item_inventario',
        type: 'post',
        data: {item_name: item, talla_id: talla_id, color_id: color_venta},
        dataType: 'json',
        success: function (response) {
            $("#codigo_barra_detalle").val(response.codigo_barra);
            $('#id_producto').val(response.producto_id);
            $('#id_color').val(response.id_color);
            $('#id_talla').val(response.id_talla);
            $('#stock_disponible').val(response.cantidad);
            $('#precio_venta').val(response.precio_venta);
            $('#cantidad_venta').focus();
        }
    });
}


function cargar_select_talla(item) {
    $.ajax({
        url: site_url + 'producto/get_talla_inventario_venta',
        type: 'post',
        data: {item_name: item},
        dataType: 'json',
        success: function (response) {
            $('#talla_venta').empty();
            $('#talla_venta').append('<option value=""></option>');
            $.each(response, function (i, item) {
                $('#talla_venta').append('<option value="' + item.id_talla + '">' + item.talla + '</option>');
            });
            var talla_id = $('#talla_venta').val();

        }
    });
}


function cargar_select_color(item, talla_id) {
    $.ajax({
        url: site_url + 'producto/get_color_inventario_venta',
        type: 'post',
        data: {item_name: item, talla_id: talla_id},
        dataType: 'json',
        success: function (response) {
            $('#color_venta').empty();
            $('#color_venta').append('<option value=""></option>');
            $.each(response, function (i, item) {
                $('#color_venta').append('<option value="' + item.id_color + '">' + item.color + '</option>');
            });
        }
    });
}

