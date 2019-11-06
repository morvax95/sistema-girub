/**
 * Created by Juan Carlos on 17/02/2018.
 */
$(document).ready(function () {
    listar_items();


    // registro de marcas
    $('#frm_registro_marcas').submit(function (event) {
        event.preventDefault();
        $('.error').remove();
        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        formulario[0].reset();
        $('#btn_cerrar_modal_marcas').click();
        cargar_marcas();
    });

    // registro de categoria interna
    $('#frm_registro_categoria_interna').submit(function (event) {
        event.preventDefault();
        $('.error').remove();
        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        formulario[0].reset();
        $('#btn_cerrar_modal_categoria_interna').click();
        cargar_categorias_internas();

    });
    // registro de unidad de medida
    $('#frm_registro_unidad_medida').submit(function (event) {
        event.preventDefault();
        $('.error').remove();
        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        formulario[0].reset();
        $('#btn_cerrar_modal_unidad_medida').click();
        cargar_unidad_medidas();

    });

    $('#generar').click(function (event) {
        event.preventDefault();

        var code = $('#codigo_barras').val();
        if (code == '') {
            return swal({
                title: "Ingrese un codigo",
                text: "",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000
            });
            return true;
        }
        if (!$.isNumeric(code)) {
            swal('El codigo tiene que ser numerico', '', 'error');
            return true;
        }
        $.ajax({
            url: site_url + 'producto/generar_codigo',
            type: 'POST',
            data: 'codigo=' + code,
            dataType: 'json',
            success: function (respuesta) {
                $('#bar_code').attr('src', base_url + 'barcodes/' + respuesta + '.png');
            }
        });
    });

    // Registro de productos
    $('#frm_registrar_item').submit(function (event) {
        event.preventDefault();

        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        //ajaxStop();
        $('.error').remove();
    });

    // Edita los datos del producto
    $('#frm_editar_item').submit(function (event) {
        event.preventDefault();

        var formulario = $(this);
        var data = formulario.serialize();
        registro_abm(formulario, data);
        $('.error').remove();
        setTimeout(function () {
            location.href = site_url + 'producto/index'
        }, 2000);
    });

    // registro de ingreso de inventario
    // @todo ver si esta funcion es necesaria
    $('#frm_ingreso_item').submit(function (event) {
        event.preventDefault();
        $('#mensaje').hide();
        var formulario = $(this);
        var data = formulario.serialize();
        $.ajax({
            url: $(formulario).attr('action'),
            type: $(formulario).attr('method'),
            data: data,
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta) {
                    swal('Registro Guardados', 'Los datos ingresados se guardaron correctamente', 'success');
                    $(formulario)[0].reset();
                } else {
                    $('#mensaje').show();
                }
            }
        });
    })
});

//Lista todos los productos, usa procesamiento de dt (prueba correcta)
function listar_items() {
    $('#lista_item').DataTable({
        'lengthMenu': [[20, 60, 150, 250, 300], [20, 60, 150, 250, 300]],
        'paging': true,
        'info': true,
        'filter': true,
        'stateSave': true,
        'processing': true,
        'serverSide': true,

        'ajax': {
            "url": site_url + 'producto/get_all_items',
            "type": 'post',
        },
        'columns': [
            {data: 'id'},
            {data: 'codigo_barra', 'class': 'text-center'},
            {data: 'nombre_item', 'class': 'text-center'},
            {data: 'precio_venta', 'class': 'text-center'},
            //  {data: 'stock_minimo', 'class': 'text-center'},
            {data: 'estado', 'class': 'text-center'},
            {data: 'codigo', 'class': 'text-center'}
        ],
        "columnDefs": [
            {
                targets: 0,
                visible: false,
                searchable: false
            },

            {
                targets: 1,
                searchable: false,
                orderable: false,
            },
            {
                targets: 2,
                searchable: false,
                orderable: false,
            },
            {
                targets: 3,
                searchable: false,
                orderable: false,
            },
            {
                targets: 4,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    if (row.estado === '1') {
                        return "<span style='font-size: 9pt; font-weight: normal' class='label label-success'><i class='fa fa-check'></i> Habilitado </span>"
                    } else {
                        return "<span style='font-size: 9pt;font-weight: normal' class='label label-danger'><i class='fa fa-times'></i> Inhabilitado </span>"
                    }
                }
            },
            {
                targets: 5,
                render: function (data, type, row) {

                    if (row.estado === '1') {
                        return '<a data-toggle="modal" role="button" href="#modal_ver_productos" onclick="verProducto(this);" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Ver</a>&nbsp;&nbsp;' +
                            '<a href="#modal_editar_cliente" data-toggle="modal" onclick="editar(this);" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Editar</a>&nbsp;&nbsp;' +
                            '<a onclick="inabilitarProducto(this);" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i> Eliminar</a>&nbsp;&nbsp;';
                    } else {
                        return '<a onclick="habilitarProducto(this);" class="btn btn-default btn-xs text-black"><i class="fa fa-arrow-up"></i> Habilitar</a>';
                    }
                }
            }
            ,
            {
                targets: 2,
                data: "nombre_item",
                render: function (data, type, row) {
                    return "<spam style='color:#0d6aad; font-weight: bold;'> " + data + "</spam>"
                }
            }

        ],

        "order": [[0, "asc"]],
    });
}
function inabilitarProducto(seleccionado) {
    delete_registrer(seleccionado, 'producto/eliminar_producto')
}
function habilitarProducto(seleccionado) {
    var table = $('#lista_item').DataTable();
    var celda = $(seleccionado).parent();
    var rowData = table.row(celda).data();
    var id = rowData['id'];

    swal({
            title: "HABILITAR PRODUCTO",
            text: "Este producto sera reactivado, esta seguro?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, activar producto!",
            cancelButtonText: "No deseo activar al producto",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {
                ajaxStart('Guardando datos...');
                $.ajax({
                    url: site_url + 'producto/habilitar_producto',
                    data: 'id_producto=' + id,
                    type: 'post',
                    success: function (registro) {
                        if (registro == 'error') {
                            ajaxStop();
                            swal("Error", "Problemas al habilitar", "error");
                        } else {
                            ajaxStop();
                            swal("Habilitado!", "El producto ha sido habilitado.", "success");
                            actualizarDataTable($('#lista_item'));
                        }
                    }
                });
            } else {
                swal("Cancelado", "Accion cancelada.", "error");
            }
        });
}
/*------------- Funcion para visualizar los datos del item  --------------------*/
function verProducto(seleccionado) {
    var table = $('#lista_item').DataTable();
    var celda = $(seleccionado).parent();
    var rowData = table.row(celda).data();
    var id = rowData['id'];
    var nombre = rowData['nombre_item'];
    var precioV = rowData['precio_venta'];
    var precioC = rowData['precio_compra'];
    var codigo_b = rowData['codigo_barra'];

    $('#ver_nombre').val(nombre);
    $('#ver_precio_venta').val(precioV);
    $('#ver_precio_compra').val(precioC);
    $('#ver_codigo').val(codigo_b);
}
// Edita los datos del producto llamando a la funcion generica
function editar(seleccionado) {
    edit_registrer_frm(seleccionado, 'producto/editar')
}

// Cambia el estado de un registro
function eliminar(seleccionado) {
    delete_registrer(seleccionado, 'producto/eliminar', $('#lista_item'))
}


// @todo Ver si esta funcion sirve y para que si no se borra
function cargar_item_combo(select) {
    $.post(site_url + 'producto/get_all_items',
        function (data) {
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                option = document.createElement("option");
                option.text = item.nombre_item;
                option.setAttribute('value', item.id);
                select.add(option);
            });
        });
    return select;
}

// agregar una fila al detalle
//@todo Verificar funcionalidad, hay que cambiar al nuevo proceso de ingreso inventario
function addRow(tableID) {
    var table = document.getElementById(tableID);
    var rowCount = table.rows.length;
    var row = table.insertRow(rowCount);
    var cell1 = row.insertCell(0);
    var element1 = document.createElement("input");
    element1.type = "checkbox";
    cell1.appendChild(element1);
    var cell2 = row.insertCell(1);
    var element2 = document.createElement("select");
    element2.type = "select";
    element2.className = "form-control";
    element2 = cargar_item_combo(element2);
    element2.id = "item";
    element2.name = "producto[]";
    cell2.appendChild(element2);
    var cell3 = row.insertCell(2);
    var element3 = document.createElement("input");
    element3.type = "text";
    element3.className = "form-control talla";
    element3.id = "talla";
    element3.name = "talla[]";
    cell3.appendChild(element3);
    var cell4 = row.insertCell(3);
    var element4 = document.createElement("input");
    element4.type = "text";
    element4.className = "form-control medidac";
    element4.id = "cantidades";
    element4.name = "cantidades[]";
    cell4.appendChild(element4);
    var cell5 = row.insertCell(4);
}

// Borra una fila del detalle
function deleteRow(tableID) {
    try {
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;

        for (var i = 0; i < rowCount; i++) {
            var row = table.rows[i];
            var chkbox = row.cells[0].childNodes[0];
            if (null != chkbox && true == chkbox.checked) {
                table.deleteRow(i);
                rowCount--;
                i--;
            }
        }
    } catch (e) {
        alert('Error: ' + e);
    }
}

// Solo numeros
function justNumbers(e) {
    var keynum = window.event ? window.event.keyCode : e.which;
    console.log(keynum);
    if ((keynum == 8) || (keynum == 46) || (keynum == 45))
        return true;

    return /\d/.test(String.fromCharCode(keynum));
}

function cargar_categorias_internas() {
    $.post(site_url + "producto/get_categoria_interna",
        function (data) {
            $('#talla_id').empty();
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                $('#talla_id').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
            });
        });
}
function cargar_unidad_medidas() {
    $.post(site_url + "producto/get_unidad_medidasfrm_registro_unidad_medida",
        function (data) {
            $('#talla_id').empty();
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                $('#talla_id').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
            });
        });
}

function cargar_marcas() {
    $.post(site_url + "producto/get_marcas",
        function (data) {
            $('#color_id').empty();
            var datos = JSON.parse(data);
            $.each(datos, function (i, item) {
                $('#color_id').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
            });
        });
}


function mostrar_ventana(url, title, w, h) {
    var left = 200;
    var top = 50;
    window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

