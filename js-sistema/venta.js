/**
 * Created by Juan Carlos on 26/02/2018.
 */
$(document).ready(function () {
    var bool;
    var caja_aperturada;
    var id_caja;
    ocultar_paneles();
    $('#btn_registrar_nota').hide(); //oculta el boton "REGISTRAR VENTA"

    //validacion de ocultar un input por ajax
    $('#tipo_ventas').change(function () {
        if ($('#tipo_ventas').val() == 'forma_pago_contado') {
            formaPago_contado();
        }
        if ($('#tipo_ventas').val() == 'forma_pago_plazo') {
            formaPago_plazo();
        }
        if ($('#tipo_ventas').val() == 'vacio') {
            ocultar_paneles();
        }
    });
    function formaPago_contado() {
        $('#datos_venta').show();
        $('#forma_pago_efectivo').show();
        $('#imagen').show();
        ocultar_panel_plazo();
        $('#btn_registrar_nota').show();
    }

    function formaPago_plazo() {
        $('#datos_venta').show();
        $('#forma_pago_plazo').show();
        $('#imagen').show();
        ocultar_panel_efectivo();
        $('#btn_registrar_nota').show();
    }

    function ocultar_paneles() {
        $('#forma_pago_plazo').hide();
        $('#forma_pago_efectivo').hide();
        $('#datos_venta').hide();
        $('#imagen').hide();
    }

    function ocultar_panel_efectivo() {
        $('#forma_pago_efectivo').hide();
    }

    function ocultar_panel_plazo() {
        $('#forma_pago_plazo').hide();
    }

    verficar_caja_aperturada();
    if (bool == true) {
        state_element_sales(false);
        $('#caja_aperturada').html(caja_aperturada);
        $('#id_caja').val(id_caja);
    } else {
        state_element_sales(true);
        $('#modal_set_caja').modal({
            show: true,
            backdrop: 'static'
        });
    }

    function verficar_caja_aperturada() {
        $.ajax({
            url: site_url + 'caja/verficar_caja_aperturada',
            type: 'post',
            dataType: 'json',
            async: false,
            success: function (response) {
                var data = JSON.parse(response)
                bool = data.bool;
                caja_aperturada = data.descripcion;
                id_caja = data.id;
            }
        });
    }

    $('#btn_set_caja').click(function (event) {
        event.preventDefault();

        var frm = $('#frm_set_caja')
        var data = $(frm).serialize();

        if ($('#monto_caja').val() == '0.00' || $('#monto_caja').val == '') {
            swal('El monto en caja no puede ser cero o vacio');
            return true;
        }
        $.ajax({
            url: $(frm).attr('action'),
            type: $(frm).attr('method'),
            data: data,
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success == true) {
                    get_message('guardar');
                    location.href = site_url + 'venta';
                } else {
                    get_message('error');
                }
            }
        });
        $('#btn_cerrar_set_caja').click();
    });
    /*------------------------------
     Evento de forma de pago
     ------------------------------*/

    $('#forma_pago1').on('change', function () {
        if ($(this).is(':checked')) {
            $('#pago1').show();
        } else {
            $('#pago1').hide();
        }
    });
    $('#forma_pago2').on('change', function () {
        if ($(this).is(':checked')) {
            $('#pago2').show();
        } else {
            $('#pago2').hide();
        }
    });
    $('#forma_pago3').on('change', function () {
        if ($(this).is(':checked')) {
            $('#pago3').show();
        } else {
            $('#pago3').hide();
        }
    });
    $('#forma_pago4').on('change', function () {
        if ($(this).is(':checked')) {
            $('#pago4').show();
        } else {
            $('#pago4').hide();
        }
    });
    $('#forma_pago5').on('change', function () {
        if ($(this).is(':checked')) {
            $('#pago5').show();
        } else {
            $('#pago5').hide();
        }
    });


    $('#monto_cuenta_credito').keyup(function () {
        var value = $(this).val();
        var total = $('#total_as').val();

        var saldo = parseFloat(total) - parseFloat(value);
        $('#saldo_credito').val(saldo);
    });

    $('#modal_cliente').click(function (event) {
        event.preventDefault();

        var frm = $('#modal_registrar_cliente')
        var data = $(frm).serialize();

        ajaxStart('Guardando el registro, por favor espere');
        $.ajax({
            url: $(frm).attr('action'),
            type: $(frm).attr('method'),
            data: data,
            dataType: 'json',
            success: function (respuesta) {
                ajaxStop();
                if (respuesta.success == true) {
                    var customer_id = respuesta.customer.id;
                    var customer_name = respuesta.customer.nombre_cliente;
                    var customer_nit = respuesta.customer.ci_nit;
                    $('#nombre_cliente').val(customer_name);
                    $('#nit_cliente').val(customer_nit);
                    $('#idCliente').val(customer_id);
                    get_message('guardar');
                    frm[0].reset();
                    $('#modal_registro_cliente').click();
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


    });

    $('#btn_registrar_nota').click(function (event) {
        event.preventDefault();
        // registrar_venta();

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
            url: site_url + 'venta/registro_venta',
            type: 'post',
            data: form_data + '&' + frm_efectivo + '&' + frm_credito + '&' + frm_deposito + '&' + frm_cheque + '&' + frm_tarjeta + '&tipo_venta=' + 'nota',
            success: function (respuesta) {
                ajaxStop();
                if (respuesta == false) {
                    swal('Error', 'La transaccion no pudo ser completada', 'error');
                } else {

                    location.href = site_url + 'venta/index';
                    if ($('#tipo_ventas').val() == 'forma_pago_contado') {
                        // mostrar_ventana(site_url + 'venta/imprimir_nota_ventas_contado/' + respuesta, 'Impresion', '700', '500');//para imprimir en tamaño carta el comprobante de venta
                        location.href = site_url + 'consultar_venta/index';

                    } else if ($('#tipo_ventas').val() == 'forma_pago_plazo') {
                        //  mostrar_ventana(site_url + 'venta/imprimir_nota_ventas_plazos/' + respuesta, 'Impresion', '700', '500');//para imprimir en tamaño carta el comprobante de venta
                        location.href = site_url + 'consultar_venta/index';
                    }

                }
            }
        });
    });

    function pagos_seleccionados() {
        var pagos = [];
        $('input:checked').each(function () {
            pagos.push($(this).val());
        });
        return pagos;
    }

    function registrar_venta() {
        var formData = $('#frm_registro_venta_otros').serialize();
        $.ajax({
            url: site_url + 'venta/registro_venta',
            type: 'post',
            data: formData,
            // dataType: 'json',
            success: function (respuesta) {
                location.href = site_url + 'venta/index';
                mostrar_ventana(site_url + 'venta/imprimirFactura/' + respuesta, 'Impresion', '800', '600');
            }
        });
    }

    function mostrar_ventana(url, title, w, h) {
        var left = 200;
        var top = 50;
        window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    }

    /*******************************************************************************
     *  EVENTOS DE LA VISTA DEL FORMULARIO
     * *****************************************************************************/
    // Por escritura
    $('#subtotal_as').keyup(function () {
        if ($('#subtotal_as').val() === "") {
            rellenarCero($('#subtotal_as'));
            rellenarCero($('#subtotal_as'));
        } else {
            var subtotal = parseFloat($('#subtotal_as').val());
            $('#total_as').val(subtotal);
        }
    });

    $('#descuento_as').keyup(function () {
        var subtotal = parseFloat($('#subtotal_as').val());
        var descuento = parseFloat($('#descuento_as').val());
        if ($('#descuento_as').val() == "") {
            rellenarCero($('#descuento_as'));
            descuento = 0.00;
        }
        total = subtotal - descuento;
        $('#total_as').val(total.toFixed(2));
        $('#monto_efectivo').val(total.toFixed(2));
    });

    $('#nit_cliente').autocomplete({
        source: function (request, response) {
            $('#idCliente').val('');
            $.ajax({
                url: site_url + 'cliente/get_cliente',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'ci_nit'
                },
                success: function (data) {
                    response($.map(data, function (item, nit) {
                        var datos = nit.split('|');
                        if (datos.length > 1) {
                            return {
                                label: nit,
                                value: datos[0],
                                id: item
                            };
                        } else {
                            return {
                                label: nit,
                                value: "",
                                id: item
                            };
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            var Date = (ui.item.id);
            var elem = Date.split('/');
            $('#nombre_cliente').val(elem[0]);
            $('#idCliente').val(elem[1]);
            $('#nit_cliente').val(ui.item.value);
        }
    });

    $('#nombre_cliente').autocomplete({
        source: function (request, response) {
            $('#idCliente').val('');
            $.ajax({
                url: site_url + 'cliente/get_cliente',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'nombre_cliente'
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        var datos = nombre.split('|');
                        if (datos.length > 1) {
                            return {
                                label: nombre,
                                value: datos[0],
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
            var Date = (ui.item.id);
            var elem = Date.split('/');
            $('#nit_cliente').val(elem[0]);
            $('#idCliente').val(elem[1]);
            $('#nombre_cliente').val(ui.item.value);
        }
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
                        var array = nombre.split('|');
                        if (array.length > 1) {
                            return {
                                label: array[0] + '|' + array[1] + '|' + array[2] + '|' + array[3] + '|' + array[4] + '|' + array[5] + '|' + array[6],
                                value: array[0],//la posicion 0 indica la posicion del nombre_item
                                //value: array[6],//es el valor del codigo_barras
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
                if (data_array[4] == 0 && data_array[5] == 0) {
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
                    $('#detalle_venta').val(data_array[0]);
                    $('#id_talla').val(elem[1]);
                    $('#id_color').val(elem[2]);
                    $('#precio_venta').val(data_array[3]);
                    $('#precio_venta').focus();
                    $('#stock_disponible').val(data_array[4]);
                    $('#stock_produccion').val(data_array[5]);
                    $('#codigo_barra_detalle').val(data_array[6]);


                    cargar_talla_codbarra(elem[1]);
                    cargar_color_codbarra(elem[2]);
                }
            } else {
                console.log('chambom');
            }
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
                                label: array[0] + '|' + array[1] + '|' + array[2] + '|' + array[3] + '|' + array[4] + '|' + array[5] + '|' + array[6],
                                value: array[6],//es el valor del codigo_barras
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
                if (data_array[4] == 0 && data_array[5] == 0) {
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

                    $('#detalle_venta').val(data_array[0]);
                    $('#talla_venta').val(data_array[1]);
                    $('#color_venta').val(data_array[2]);
                    $('#precio_venta').val(data_array[3]);
                    $('#precio_venta').focus();
                    $('#stock_disponible').val(data_array[4]);
                    $('#stock_produccion').val(data_array[5]);
                    $('#codigo_barra_detalle').val(data_array[0]);


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

function abrir_modal_credito() {
    $('#modal_registro_credito').modal({
        show: true,
        backdrop: 'static'
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
            //cargar_select_color(item,talla_id);
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


function abrir_modal_deposito() {
    $('#modal_registro_deposito').modal({
        show: true,
        backdrop: 'static'
    });
}

function abrir_modal_cheque() {
    $('#modal_registro_cheque').modal({
        show: true,
        backdrop: 'static'
    });
}

function abrir_modal_tarjeta() {
    $('#modal_registro_tarjeta').modal({
        show: true,
        backdrop: 'static'
    });
}

function cuadradar_saldos() {
    if ($('#subtotal_as').val() === "") {
        rellenarCero($('#subtotal_as'));
        rellenarCero($('#subtotal_as'));
    } else {
        var subtotal = parseFloat($('#subtotal_as').val());
        $('#descuento_as').val('0.00');
        $('#total_as').val(subtotal.toFixed(2));
        $('#monto_efectivo').val(subtotal.toFixed(2));
    }
}

function state_element_sales(bool) {
    $("input").prop('disabled', bool);
    $('button').prop('disabled', bool);
    $('a').attr('disabled', bool);
    $('#btn_set_caja').prop('disabled', !bool)
    $('#btn_cerrar_set_caja').prop('disabled', !bool)
    $('#monto_caja').prop('disabled', !bool)
}
