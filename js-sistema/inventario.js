/**
 * Created by Juan Carlos on 06/03/2018.
 */
$(document).ready(function () {
    lista_ingresos();

    $('#seleccion_almacen').change(function () {
        var value = $(this).val();
        if (value === '3') {
            $('#inf_adicional').hide();
            $('#inf_adicionalt').hide();
        } else {
            $('#inf_adicional').show();
            $('#inf_adicionalt').show();
        }
    });

    // registro de almacenes
    $('#frm_registro_almacen').submit(function (event) {
        event.preventDefault();
        $('.error').remove();
        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        formulario[0].reset();
        $('#btn_cerrar_modal_almacen').click();
        cargar_almacenes();
    });

    // registro de unidades
    $('#frm_registro_unidad').submit(function (event) {
        event.preventDefault();
        $('.error').remove();
        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        formulario[0].reset();
        $('#btn_cerrar_modal_unidad').click();
        cargar_unidades();
    });

    // busqueda
    $('#nombre_producto').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: site_url + 'producto/buscar_item',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'inventario',
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        var data = nombre.split('|');
                        if (data.length === 2) { // contiene datos de producto
                            return {
                                label: nombre,
                                value: data[0],
                                id: item
                            };
                        } else { // contiene datos de inventario
                            return {
                                label: nombre,
                                value: data[0],
                                id: item
                            };
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            var data = (ui.item.id);
            console.log(data)
            var array = (ui.item.label);
            var elem = data.split('|');
            var element = array.split('|');
            console.log(element)
            var precio = element[1].split(' ');
            $('#precio_venta').val(precio[0]);
            $('#seleccion_talla').val(element[2]);
            $('#seleccion_color').val(element[3]);
            $('#producto_id').val(elem[0]);
            $('#color_id').val(elem[1]);
            $('#talla_id').val(elem[2]);
        }
    });

    // registro de ingreso a inventario de productos
    $('#btn_registrar_inventario').click(function (event) {
        event.preventDefault();

        if ($('#seleccion_almacen').val() === '0') {
            swal('Seleccione al menos un almacen');
            return true;
        }
        if ($('#lista_inventario tbody tr').length === 0) {
            swal('Debe ingresar al menos un detalle en la tabla');
            return true;
        }
        var formData = $('#frm_registrar_inventario').serialize();
        ajaxStart('Guardando  registro, por favor espere...');
        $.ajax({
            url: site_url + 'inventario/registrar',
            type: 'post',
            data: formData,
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success) {
                    swal('Datos correcto', 'Registro realizado correctamente', 'success');
                    setTimeout(function () {
                        location.href = site_url + 'inventario/nuevo'
                    }, 2000);
                } else {
                    swal('Ocurrio algun error', 'Contactese con el administrador del sistema.', 'Error');
                }
                ajaxStop();
            }
        });
    });

    // registro de ingreso a inventario de materia prima
    $('#btn_registrar_ingreso_materia').click(function (event) {
        event.preventDefault();

        if ($('#seleccion_almacen').val() === '0') {
            swal('Seleccione al menos un almacen');
            return true;
        }
        if ($('#lista_inventario_materia tbody tr').length === 0) {
            swal('Debe ingresar al menos un detalle en la tabla');
            return true;
        }
        var formData = $('#frm_registrar_inventario_materia').serialize();
        var frm = $('#frm_registrar_inventario_materia');
        $.ajax({
            url: site_url + 'inventario/registrar_materia',
            type: 'post',
            data: formData,
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success) {
                    $('#frm_registrar_inventario_materia')[0].reset();
                    $('#lista_inventario_materia tbody').clear;
                    swal('Datos Guardador', 'Datos ingresados correctamente.', 'success');
                }
            }
        });
    });
});

function listar_inventario_productos() {
    $('#lista_inventario_general').DataTable({
        'lengthMenu': [[20, 60, 150, 250, 300], [20, 60, 150, 250, 300]],
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,

        'ajax': {
            "url": site_url + 'inventario/get_all',
            "type": 'post',
            data: {tipo_producto: 'Producto'}
        },
        'columns': [
            {data: 'producto_id'},
            {data: 'codigo_barra', 'class': 'text-center'},
            {data: 'nombre_item'},
            {data: 'color', 'class': 'text-center'},
            {data: 'talla', 'class': 'text-center'},
            {data: 'precio_venta', 'class': 'text-right'},
            {data: 'estado_inventario', 'class': 'text-center'},
            {data: 'cantidad', 'class': 'text-center'},
            {data: 'tipo_producto', 'class': 'text-center'},
            {data: 'almacen', 'class': 'text-center'},
            {data: 'sucursal', 'class': 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 1,
                searchable: false,
                orderable: false
            },
            {
                // Precio venta
                targets: 5,
                searchable: false,
                orderable: false
            },
            {
                // Estado inventario
                targets: 6,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    switch (data) {
                        case data = 'STOCK MINIMO':
                            return "<label style='font-size: 9pt' class='label label-warning'><i class='fa fa-download'></i> " + data + " (" + row.stock_minimo + ")</label>";
                            break;
                        case data = 'AGOTADO':
                            return "<span style='font-size: 9pt' class='label label-danger'><i class='fa fa-times'></i> " + data + " (" + row.stock_minimo + ")</span>";
                            break;
                        case data = 'DISPONIBLE':
                            return "<span style='font-size: 9pt' class='label label-success'><i class='fa fa-check-circle'></i> " + data + " (" + row.stock_minimo + ")</span>";
                            break;
                    }

                }
            },
            {
                // Cantidad
                targets: 7,
                searchable: false,
                orderable: false
            },
            {
                // Tipo producto
                targets: 8,
                searchable: false,
                orderable: false
            }
        ],
        "order": [[0, "asc"]],
    });
}


function listar_inventario_materia() {
    $('#lista_inventario_materia_prima').DataTable({
        'lengthMenu': [[20, 60, 150, 250, 300], [20, 60, 150, 250, 300]],
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,

        'ajax': {
            "url": site_url + 'inventario/get_all',
            "type": 'post',
            data: {tipo_producto: 'Materia Prima'}
        },
        'columns': [
            {data: 'producto_id'},
            {data: 'codigo_barra', 'class': 'text-center'},
            {data: 'nombre_item'},
            {data: 'unidad', 'class': 'text-center'},
            {data: 'estado_inventario', 'class': 'text-center'},
            {data: 'cantidad', 'class': 'text-center'},
            {data: 'tipo_producto', 'class': 'text-center'},
            {data: 'almacen', 'class': 'text-center'},
            {data: 'sucursal', 'class': 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                targets: 1,
                searchable: false,
                orderable: false
            },
            {
                targets: 3,
                searchable: false,
                orderable: false
            },
            {
                // Estado inventario
                targets: 4,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    switch (data) {
                        case data = 'STOCK MINIMO':
                            return "<label style='' class='label label-warning'><i class='fa fa-download'></i> " + data + " (" + row.stock_minimo + ")</label>";
                            break;
                        case data = 'AGOTADO':
                            return "<span class='label label-danger'><i class='fa fa-times'></i> " + data + " (" + row.stock_minimo + ")</span>";
                            break;
                        case data = 'DISPONIBLE':
                            return "<span class='label label-success'><i class='fa fa-check-circle'></i> " + data + " (" + row.stock_minimo + ")</span>";
                            break;
                    }

                }
            },
            {
                // Cantidad
                targets: 5,
                searchable: false,
                orderable: false
            },
            {
                // Tipo producto
                targets: 6,
                searchable: false,
                orderable: false
            }
        ],
        "order": [[0, "asc"]],
    });
}


function lista_ingresos() {
    $('#lista_ingreso').DataTable({
        'lengthMenu': [[20, 60, 150, 250, 300], [20, 60, 150, 250, 300]],
        'paging': true,
        'info': true,
        'filter': false,
        'stateSave': true,
        'processing': true,
        'serverSide': true,

        'ajax': {
            "url": site_url + 'inventario/get_all_entry',
            "type": 'post',
        },
        'columns': [
            {data: 'id'},
            {data: 'id', 'class': 'text-center'},
            {data: 's_fecha', 'class': 'text-center'},
            {data: 'almacen', 'class': 'text-center'},
            {data: 'sucursal', 'class': 'text-center'},
            {data: 'opciones', 'class': 'text-center'},
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false,
            },
            {
                // Sucursal
                targets: 4,
                searchable: false,
                orderable: false
            },
            {
                // Ver
                targets: 5,
                render: function (data, type, row) {
                    return '<a onclick="ver_registro(this) " class="btn btn-warning btn-xs"><i  class="fa fa-eye"></i> Ver Datos</a>';
                }
            }
            ,
            {
                targets: 2,
                data: "s_fecha",
                render: function (data, type, row) {
                    return "<spam style='color:#0d6aad; font-weight: bold;'> " + data + "</spam>"
                }
            }
        ],
        "order": [[0, "asc"]],
    });
}

function ver_registro(seleccionado) {
    var table = $(seleccionado).closest('table').DataTable();
    var current_row = $(seleccionado).parents('tr');
    if (current_row.hasClass('child')) {
        current_row = current_row.prev();
    }
    var data = table.row(current_row).data();
    $.redirect(site_url + 'inventario/ver', {id: data['id']}, 'POST', '_self');
}


function cargar_unidades() {
    $.post(site_url + "unidad/get_all",
        function (data) {
            $('#seleccion_unidad').empty();
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                $('#seleccion_unidad').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
            });
        });
}

function cargar_almacenes() {
    $.post(site_url + "almacen/get_all",
        function (data) {
            $('#seleccion_almacen').empty();
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                if (item.id != 1) {
                    $('#seleccion_almacen').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
                }
            });
        });
}