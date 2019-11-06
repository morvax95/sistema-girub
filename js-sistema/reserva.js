$(document).ready(function () {


    // busqueda
    $('#nombre_producto').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: site_url + 'personaCurso/get_estudiantes',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'nombre',
                },
                success: function (data) {
                    response($.map(data, function (item, nombre) {
                        var data = nombre.split('|');
                        if (data.length === 2) { // contiene datos de los estudiantes
                            return {
                                label: nombre,
                                value: data[0],
                                id: item
                            };
                        } else { // contiene datos del estudiante
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
            $('#producto_id').val(elem[0]);

        }
    });

    // registro de ingreso al detalle personaCurso
    $('#btn_registrar_inventario').click(function (event) {
        event.preventDefault();
        if ($('#turno_id').val() === '0') {
            swal('Seleccione un turno ');
            return true;
        }
        if ($('#curso_id').val() === '0') {
            swal('Seleccione un curso ');
            return true;
        }
        if ($('#curso_id').val() === '0') {
            swal('Seleccione un aula ');
            return true;
        }

        if ($('#lista_inventario tbody tr').length === 0) {
            swal('Debe ingresar al menos un detalle en la tabla');
            return true;
        }
        var formData = $('#frm_registrar_inventario').serialize();
        ajaxStart('Guardando  registro, por favor espere...');
        $.ajax({
            url: site_url + 'personaCurso/registrar',
            type: 'post',
            data: formData,
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success) {
                    swal('Datos correcto', 'Registro realizado correctamente', 'success');
                    setTimeout(function () {
                        location.href = site_url + 'consultarCurso/index'
                        // mostrar_ventana(site_url + 'consultarCurso/imprimir_gestionCurso/' + respuesta, 'Impresion', '700', '500');
                    }, 2000);

                } else {
                    swal('Ocurrio algun error', 'Contactese con el administrador del sistema.', 'Error');
                }
                ajaxStop();
            }
        });
    });
    function mostrar_ventana(url, title, w, h) {
        var left = 200;
        var top = 50;
        window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    }

    /*DOCENTE*/
    $('#ci_docente').autocomplete({
        source: function (request, response) {
            $('#idCliente').val('');
            $.ajax({
                url: site_url + 'personaCurso/get_docente',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'ci'
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
            $('#nombre_usuario').val(elem[0]);
            $('#idCliente').val(elem[1]);
            $('#ci_docente').val(ui.item.value);
        }
    });
//obtener  la busqueda de solo personas-docente
    $('#nombre_usuario').autocomplete({
        source: function (request, response) {
            $('#idCliente').val('');
            $.ajax({
                url: site_url + 'personaCurso/get_docente',
                dataType: "json",
                data: {
                    name_startsWith: request.term,
                    type: 'nombre_usuario'
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
            $('#ci_docente').val(elem[0]);
            $('#idCliente').val(elem[1]);
            $('#nombre_usuario').val(ui.item.value);
        }
    });

    /*******************************************************************************/

    $('#sub_total').keyup(function () {
        if ($('#sub_total').val() === "") {
            rellenarCero($('#sub_total'));
            rellenarCero($('#sub_total'));
        } else {
            var subtotal = parseFloat($('#sub_total').val());
            $('#total_as').val(subtotal);
        }
    });

    $('#descuento').keyup(function () {
        var subtotal = parseFloat($('#sub_total').val());
        var descuento = parseFloat($('#descuento').val());
        if ($('#descuento').val() == "") {
            rellenarCero($('#descuento'));
            descuento = 0.00;
        }
        total = subtotal - descuento;
        $('#total_as').val(total.toFixed(2));


    });
    function rellenarCero(elemento) {
        elemento.val('0.00');
    }


});






