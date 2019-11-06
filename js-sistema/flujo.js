/**
 * Created by Juan on 23/03/2018.
 */
$(document).ready(function () {
    cargar_tabla_ingreso();
    cargar_tabla_egreso();
    actualizar_flujo_caja();
    $("#buscar-datos").on('click', function () {
        cargar_tabla_ingreso();
        cargar_tabla_egreso();
        actualizar_flujo_caja();
    })

    $("#buscar-datos-detalle").on('click', function () {
        cargar_tabla();
    })

});


function cargar_tabla_ingreso() {
    if ($('#table-ingreso').length) {
        $('#table-ingreso').DataTable({
            "columns": [
                {"data": "nombre"},
                {"data": "monto_total"}
            ],
            "columnDefs": [
                { "width": "80%", "targets": 0,"orderable":"false" },
                { "width": "20%",
                    "targets": 1,
                    "sClass": "rig",
                    "ordering": false
                }
            ],
            "ajax": {
                "url": site_url + "/flujo_caja/obtener_ingresos",
                "type": "POST",
                "data":{fecha_inicio : $("#fecha-inicio").val(), fecha_fin: $("#fecha-fin").val()},
                "dataSrc": null
            },
            "responsive": true,
            "pagingType": "full_numbers",
            "select": false,
            "bFilter": false,
            "bLengthChange":false,
            "bPaginate":false,
            "bInfo":false,
            "bSort":false,
            "bDestroy":true
        });
    }
}

function cargar_tabla_egreso() {
    if ($('#table-egreso').length) {
        $('#table-egreso').DataTable({
            "columns": [
                {"data": "nombre"},
                {"data": "monto_total"}
            ],
            "columnDefs": [
                { "width": "80%", "targets": 0 },
                { "width": "20%", "targets": 1, "sClass": "rig" }
            ],
            "ajax": {
                "url": site_url + "/flujo_caja/obtener_egresos",
                "type": "POST",
                "data":{fecha_inicio : $("#fecha-inicio").val(), fecha_fin: $("#fecha-fin").val()},
                "dataSrc": null
            },
            "responsive": true,
            "pagingType": "full_numbers",
            "select": false,
            "bFilter": false,
            "bLengthChange":false,
            "bPaginate":false,
            "bInfo":false,
            "bSort":false,
            "bDestroy":true
        });
    }
}

function actualizar_flujo_caja() {
    $.ajax({
        url: site_url+'/flujo_caja/obtener_totales',
        type: "POST",
        data:{fecha_inicio : $("#fecha-inicio").val(), fecha_fin: $("#fecha-fin").val()},
        dataType:'json',
        success: function (response) {
            $("#td-head-ingreso").text(response.total_ingreso+' Bs.');
            $("#td-head-egreso").text(response.total_egreso+' Bs.');
            $("#label-flujo").text(response.flujo_caja+' Bs.');
        }

    })
}

function exportar_excel() {
    var page = site_url + "/flujo_caja/exportar_excel_simple";
    var params = {
        fecha_inicio: $("#fecha-inicio").val(),
        fecha_fin: $("#fecha-fin").val()
    };
    var body = document.body;
    form = document.createElement('form');
    form.method = 'POST';
    form.action = page;
    form.name = 'jsform';
    form.target = '_blank';
    for (index in params) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = index;
        input.id = index;
        input.value = params[index];
        form.appendChild(input);
    }
    body.appendChild(form);
    form.submit();
}

function exportar_excel_detallado() {
    var page = site_url + "/flujo_caja/detalle_excel";
    var params = {
        fecha_inicio: $("#fecha-inicio").val(),
        fecha_fin: $("#fecha-fin").val(),
        monto_ingreso: $("#td-head-ingreso").text(),
        monto_egreso: $("#td-head-egreso").text(),
        flujo_caja: $("#label-flujo").text()
    };
    var body = document.body;
    form = document.createElement('form');
    form.method = 'POST';
    form.action = page;
    form.name = 'jsform';
    form.target = '_blank';
    for (index in params) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = index;
        input.id = index;
        input.value = params[index];
        form.appendChild(input);
    }
    body.appendChild(form);
    form.submit();
}

function cargar_tabla(){
    $.ajax({
        url:site_url+ '/flujo_caja/detalle_buscar',
        data:{fecha_inicio:$("#fecha-inicio-detalle").val(), fecha_fin:$("#fecha-fin-detalle").val()},
        type:'post',
        dataType:'json',
        success:function (response) {
            var ingreso = response.ingreso;
            var egreso = response.egreso;
            var cadena_tr ='';
            var cadena_tr_egreso ='';
            var string_tabla_fin ='';
            var string_tabla_fin_egreso ='';

            var string_tabla = '<table class="table table-bordered table-hover" style="margin-bottom: 3px">'+
                '<tr>' +
                '<td style="vertical-align:middle;">'+'I N G R E S O S'+'</td>' +
                '<td>';
            var lista_ingreso = ingreso.lista_ingresos;
            for (i = 0 ; i<lista_ingreso.length; i++){
                var ingreso = lista_ingreso[i];
                cadena_tr = cadena_tr + tabla_detalle(ingreso);
            }
            string_tabla_fin = '</td>' +
                '</tr>' + '<tr><td style="vertical-align:middle;">'+'E G R E S O S'+'</td><td>';
            var lista_egreso = egreso.lista_egresos;
            for (i = 0 ; i<lista_egreso.length; i++){
                var egreso = lista_egreso[i];
                cadena_tr_egreso = cadena_tr_egreso + tabla_detalle_egreso(egreso);
            }
            string_tabla_fin_egreso = '</tr>'+
                '</table>';
            $("#label-flujo").text(response.monto_ingreso_total - response.monto_egreso_total+' Bs.');
            $("#div-tabla").html(string_tabla+cadena_tr+string_tabla_fin+cadena_tr_egreso+string_tabla_fin_egreso+string_tabla_fin_egreso);
        }
    })
}


function tabla_detalle(ingreso) {
    var tabla = '';
    var cadena_row = '';
    var prueb = '';
    tabla = '<table class="table table-bordered table-hover" style="margin-bottom: 3px">' +
        '<tr>' +
        '<td style="width: 30%;vertical-align:middle;">'+ingreso.descripcion+'</td>' +
        '<td>' +
        '<table class="table table-bordered table-hover">' +
        '<tr>' +
        '<td style="background-color:#c0c0c0;color:#ffffff">Fecha</td>' +
        '<td style="background-color:#c0c0c0;color:#ffffff">Concepto</td>' +
        '<td style="background-color:#c0c0c0;color:#ffffff">Monto</td>' +
        '</tr>';
    var lista = ingreso.lista;
    for (j=0 ; j<lista.length;j++){
        var fila = lista[j];
        cadena_row = cadena_row + '<tr><td>'+fila.fecha_registro+'</td>'+'<td>'+fila.detalle+'</td>'+'<td style="text-align: right">'+fila.monto+'</td></tr>';
    }
    prueb =tabla+ cadena_row + '<tr>' +
        '<td colspan="2" style="text-align: right">Monto total</td>' +
        '<td style="text-align: right">'+ingreso.monto_total_tipo+'</td>'+
        '</tr>'+
        '</table>'+
        '</td>' +
        '</tr>' +
        '</table>';
    return prueb;
}

function tabla_detalle_egreso(egreso) {
    var tabla = '';
    var cadena_row = '';
    var prueb = '';
    tabla = '<table class="table table-bordered table-hover" style="margin-bottom: 3px">' +
        '<tr>' +
        '<td style="width: 30%;vertical-align:middle;">'+egreso.descripcion+'</td>' +
        '<td>' +
        '<table class="table table-bordered table-hover">' +
        '<tr>' +
        '<td style="background-color:#c0c0c0;color:#ffffff">Fecha</td>' +
        '<td style="background-color:#c0c0c0;color:#ffffff">Concepto</td>' +
        '<td style="background-color:#c0c0c0;color:#ffffff">Monto</td>' +
        '</tr>';
    var lista = egreso.lista;
    for (j=0 ; j<lista.length;j++){
        var fila = lista[j];
        cadena_row = cadena_row + '<tr><td>'+fila.fecha_registro+'</td>'+'<td>'+fila.detalle+'</td>'+'<td style="text-align: right">'+fila.monto+'</td></tr>';
    }
    prueb =tabla+ cadena_row + '<tr>' +
        '<td colspan="2" style="text-align: right">Monto total</td>' +
        '<td style="text-align: right">'+egreso.monto_total_tipo+'</td>'+
        '</tr>'+
        '</table>'+
        '</td>' +
        '</tr>' +
        '</table>';
    return prueb;
}