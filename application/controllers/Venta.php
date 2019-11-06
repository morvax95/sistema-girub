<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('venta_model', 'venta');
        $this->load->model('producto_model', 'producto');
        $this->load->model('almacen_model', 'almacen');
        $this->load->model('sucursal_model', 'sucursal');
        $this->load->model('caja_model', 'caja');
        $this->load->model('reporte_model', 'reportes');

        //verificando si al insertar un dato el valor del campo de >0
        /*$this->load->library('unit_test');
        $valor = $this->input->post('cantidad');
        $resultado = 0;
        $nombre = 'validacion';
        $datos['validacion_valor1'] = $this->unit->run($valor, $resultado, $nombre);
        $datos['titulo'] = 'Libreria Unit Test';
        $datos['contenido'] = 'pruebas';
        echo $this->unit->run($datos);*/
    }

    public function index()
    {

        $data['cajas'] = $this->caja->get_available_boxes();
        $data['almacenes'] = $this->almacen->get_all();
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('venta/index', $data);
    }

    public function ver_datos_ventas()
    {

        if ($this->input->post()) {
            $id = $this->input->post('id');
            $data = $this->ventas_proceso->get_data_by_id($id);
            plantilla('venta/ver', $data);
        } else {
            show_404();
        }
    }

    public function agregar()
    {
        if ($this->input->is_ajax_request()) {

            if ($this->venta->verificar_stock($this->input->post('id_producto'), $this->input->post('cantidad_venta'))) {
                // Recibimos los parametros del formulario
                $contador = $this->input->post('contador');
                $id_producto = $this->input->post('id_producto');
                $id_talla = $this->input->post('id_talla');
                $id_color = $this->input->post('id_color');
                $descripcion = $this->input->post('detalle_venta');
                $precio = $this->input->post('precio_venta');
                $cantidad = $this->input->post('cantidad_venta');
                $codigo_barra_detalle = $this->input->post('codigo_barra_detalle');
                $stock_disponible = $this->input->post('stock_disponible');
                //$stock_produccion = $this->input->post('stock_produccion');
                $estado_entrega = $this->input->post('estado_entrega');

                $estado_ent = array();
                if (isset($_SESSION["estado_entrega"])) {
                    $estado_ent = $_SESSION["estado_entrega"];
                    $estado_ent[] = $estado_entrega;
                    $_SESSION["estado_entrega"] = $estado_ent;
                } else {
                    $estado_ent[] = $estado_entrega;
                    $_SESSION["estado_entrega"] = $estado_ent;
                }

                $sub_total = round($cantidad * $precio, 2);

                // Creamos las filas del detalle
                $fila = '<tr>';
                $fila .= '<td>' . $codigo_barra_detalle . '</td>';
                $fila .= '<td><input type="text" value="' . $id_producto . '" id="producto_id" name="producto_id[]" hidden/>
            <input type="text" value="' . $id_talla . '" id="talla_id" name="talla_id[]" hidden/>
            <input type="text" value="' . $id_color . '" id="color_id" name="color_id[]" hidden/>
            <input type="text" value="' . $descripcion . '" id="descripcion" name="descripcion[]" hidden/>' . $descripcion . '</td>';

                $fila .= '<td>' . $stock_disponible . '</td>';
                // $fila .= '<td><input type="number" step="any" value="' . $stock_produccion . '" id="stock_produccion' . $contador . '" name="stock_produccion[]" hidden/>' . $stock_produccion . '</td>';
                $fila .= '<td><input type="number" step="any" value="' . $precio . '" id="precio' . $contador . '" name="precio[]" hidden/>' . $precio . '</td>';
                $fila .= '<td><input type="text" class="form-control"  value="' . $cantidad . '" id="cantidad' . $contador . '" name="cantidad[]" onkeyup="modificar_detalle(' . $contador . ')" onclick="modificar_detalle(' . $contador . ')" style="text-align: right" min="1" max=""/></td>';


                if ($estado_entrega == 5) {
                    $val1 = '<option  value="5">Entregado</option>
                    <option  value="4">Pendiente</option>';
                } else {
                    $val1 = '<option  value="4">Pendiente</option>
                    <option  value="5">Entregado</option>';
                }

                /* $fila .= '<td><select class="form-control" id="estado_entrega' . $contador . '" name="estado_entrega[]">
                                                        ' . $val1 . '
                                                        </select></td>';*/
                $fila .= '<td><input type="text" class="form-control" value="' . $sub_total . '" id="monto' . $contador . '" name="monto[]" size="4" style="text-align: right" readonly/></td>';

                $fila .= '<td class="text-center"><a class="elimina"><i class="fa fa-trash-o"></i></a></td></tr>';

                $datos = array(
                    0 => $fila,
                    1 => $contador,
                    2 => $sub_total,
                    //3 => $estado_entrega,
                );

                echo json_encode($datos);
            } else {
                $datos = array(
                    0 => 'error',
                );

                echo json_encode($datos);
            }
        } else {
            show_404();
        }
    }

    //actualizar el estado de la venta
    public function modificar_estado_venta()
    {
        if ($this->input->is_ajax_request()) {
            $nro_venta = $this->input->post('nroVenta');

            $res = $this->venta->cambiar_estado_venta($nro_venta);
            if ($res !== 1) {
                echo 'true';
            } else {
                echo 'error';
            }
        } else {
            show_404();
        }
    }

    public function consultar_producto()
    {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post('product_code');
            $this->db->select('nombre_item, SUM(cantidad) as cantidad, sucursal,cantidad_produccion');
            $this->db->from('inventario_producto');
            // $this->db->where('lower(nombre_item)', strtolower($data));
            $this->db->like('lower(nombre_item)', strtolower($data));
            $this->db->group_by('nombre_item, sucursal');
            $res = $this->db->get();

            $fila = '';
            if ($res->num_rows() > 0) {
                $fila = '';
                foreach ($res->result() as $row) {
                    $fila = $fila . '<tr>';
                    $fila .= '<td>' . $row->nombre_item . '</td>';
                    $fila .= '<td>' . $row->cantidad . '</td>';
                    //  $fila .= '<td>' . $row->cantidad_produccion . '</td>';
                    $fila .= '<td>' . $row->sucursal . '</td>';
                    $fila .= '</tr>';
                }
            } else {
                $fila = $fila . '<tr>';
                $fila .= '<td colspan="5"> No existe datos para el codigo ingresado</td>';
                $fila .= '</tr>';
            }
            echo($fila);
        } else {
            show_404();
        }
    }

    public function pruebaa()
    {
        $monto = "0";
        $monto1 = 0.00;

        if ($monto == $monto1) {
            echo 'bien';
        } else {
            echo 'mal';
        }
    }

    public function registro_venta()
    {
        if ($this->input->is_ajax_request()) {

            echo json_encode($this->venta->registro_venta());

        } else {
            show_404();
        }
    }

    public function registroDetalleVenta()
    {
        if ($this->input->is_ajax_request()) {
            $detalle['venta_id'] = $this->input->post('venta_id');
            $detalle['detalle'] = $this->input->post('detalle');
            $detalle['cantidad'] = $this->input->post('cantidad');
            $detalle['precio_venta'] = $this->input->post('precio');

            $this->venta->registroDetalleVenta($detalle);
        } else {
            show_404();
        }
    }

    public function registroDetalleVentaMateria()
    {
        if ($this->input->is_ajax_request()) {
            $detalle['venta_id'] = $this->input->post('venta_id');
            $detalle['especie_id'] = $this->input->post('especie');
            $detalle['espesor'] = $this->input->post('espesor');
            $detalle['ancho'] = $this->input->post('ancho');
            $detalle['largo'] = $this->input->post('largo');
            $detalle['detalle'] = $this->input->post('detalle');
            $detalle['cantidad'] = $this->input->post('cantidad');
            $detalle['precio_venta'] = $this->input->post('precio');
            $detalle['total_pie'] = $this->input->post('total_pie');

            $this->venta->registroDetalleVentaMateria($detalle);
        } else {
            show_404();
        }
    }

    function obtener_mes($valor)
    {
        $result = '';
        switch ($valor) {
            case '01':
                $result = 'Enero';
                break;
            case '02':
                $result = 'Febrero';
                break;
            case '03':
                $result = 'Marzo';
                break;
            case '04':
                $result = 'Abril';
                break;
            case '05':
                $result = 'Mayo';
                break;
            case '06':
                $result = 'Junio';
                break;
            case '07':
                $result = 'Julio';
                break;
            case '08':
                $result = 'Agosto';
                break;
            case '09':
                $result = 'Septiembre';
                break;
            case '10':
                $result = 'Octubre';
                break;
            case '11':
                $result = 'Noviembre';
                break;
            case '12':
                $result = 'Diciembre';
                break;
        }
        return $result;
    }

    public function imprimir_nota_venta()
    {
        $venta_id = $this->uri->segment(3);
        $respuesta = $this->venta->imprimir_nota_venta($venta_id);

        $this->load->view('venta/impresion_nota_venta', $respuesta);
    }

    /*** Imprimir nota de la solicitud ****/
    public function imprimir_nota_ventas_contado()
    {
        $venta_id = $this->uri->segment(3);
        $datos = $this->venta->comprobante_ventas($venta_id);
        $lista_compras = $datos['datos'];
        $lista_detalle_ventas = $datos['datos_venta_detalle'];

        foreach ($lista_compras as $row_detalle) {
            $nombre_cliente = $row_detalle->nombre_cliente;
            $subTotal = $row_detalle->subtotal;
            $descuent = $row_detalle->descuento;
            $fecha_venta = $row_detalle->fecha;
            $nro_comprobante = $row_detalle->nro_nota;
            $usuario = $row_detalle->nombre_usuario;
            $nota = $row_detalle->nota;
            $forma_pagos = $row_detalle->forma_pago;
            $nombre_empresa = $row_detalle->nombre_empresa;
            $sucursal = $row_detalle->sucursal;
            $direccion = $row_detalle->direccion;
            $telefono = $row_detalle->telefono;

        }
        foreach ($lista_detalle_ventas as $row_detalle_venta) {

            $monto_a_plazos = $row_detalle_venta->monto_plazos;


        }
        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        $this->pdf->SetTitle("COMPROBANTE DE VENTA");
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 45, 25);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(60, 5, $nombre_empresa, 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        /**/
        $this->pdf->Ln(5);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(60, 5, $sucursal, 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Cell(75, 5, ' ', 0, 0, 'C');

        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(40, 5, 'COMPROBANTE DE VENTA', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(100, 4, $direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);

        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(40, 5, 'Nro. 00' . $nro_comprobante, 0, 0, 'C');

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(100, 4, 'Telf. ' . $telefono, 0, 0, 'C');

        $this->pdf->Ln(4);
        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(50, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->Ln(6);
        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(8);

        /*COLOCANDO FECHA EN LITERAL*/
        $anio = substr($fecha_venta, 0, 4);
        $mes = substr($fecha_venta, 5, 2);
        $dia = substr($fecha_venta, 8, 2);
        $fechaTransaccion = $dia . ' de ' . $this->obtener_mes($mes) . ' del ' . $anio;

        /*  NOMBRE DEL CLIENTE*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('NOMBRE CLIENTE     : ' . $nombre_cliente), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA DE LA VENTA*/
        $this->pdf->Cell(27, 5, utf8_decode('FECHA DE VENTA      : ' . $fechaTransaccion . '                                                                                   FORMA PAGO  : CONTADO - ' . $forma_pagos), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /* Encabezado de la columna*/
        $this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "CODIGO", 1, 0, 'C');
        $this->pdf->Cell(92, 5, "NOMBRE PRODUCTO", 1, 0, 'C');
        $this->pdf->Cell(30, 5, "P/U", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "TOTAL VENTA", 1, 0, 'C');


        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);

        $cantidad_filas = 0;

        foreach ($lista_detalle_ventas as $row_detalle) {
            if ($row_detalle->estado_entrega == 4) {
                $estado_entrega = "PENDIENTE";
            }
            if ($row_detalle->estado_entrega == 5) {
                $estado_entrega = "ENTREGADO";

            }

            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas == count($lista_detalle_ventas)) {
                $estilo = 'LRB';
            }


            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->cantidad), $estilo, 0, 'C');
            $this->pdf->Cell(25, 4, utf8_decode($row_detalle->codigo_barra), $estilo, 0, 'C');
            $this->pdf->Cell(92, 4, utf8_decode($row_detalle->nombre_item), $estilo, 0, 'C');
            $this->pdf->Cell(30, 4, utf8_decode($row_detalle->precio_venta), $estilo, 0, 'C');
            $this->pdf->Cell(25, 4, utf8_decode($row_detalle->precio_venta * $row_detalle->cantidad), $estilo, 0, 'C');


            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }
        $this->pdf->Ln(3);
        /*  SUBTOTAL*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                   SUBTOTAL     Bs  : ' . $subTotal), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  DESCUENTO  */
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                   DESCUENTO  Bs   : ' . $descuent), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(5);


        // Convertimos el monto en literal
        include APPPATH . '/libraries/convertidor.php';
        $v = new EnLetras();
        $valor = $v->ValorEnLetras($monto_a_plazos, " ");
        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'MONTO TOTAL Bs  :  ', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '                           ' . $valor, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, $monto_a_plazos, 1, 0, 'R');
        $this->pdf->Ln(8);
        /*  NOTA DE LA VENTA*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('USUARIO    : ' . $usuario), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  LINEA DE SEPARACION */
        $this->pdf->Cell(27, 5, utf8_decode('NOTA          :' . $nota), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(30);
        /*  ENTREGADO Y RECIBIDO POR*/
        $this->pdf->Cell(27, 5, utf8_decode('                                   -------------------------------------------------------------   ' . '                            -------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(3);
        $this->pdf->Cell(27, 5, utf8_decode('                                                       ENTREGADO                                ' . '                                                       RECIBIDO    '), ' ');
        $this->pdf->Ln(17);
        $this->pdf->Cell(27, 5, utf8_decode('   *******************************************************      GRACIAS POR SU COMPRA VUELVA PRONTO   *************************************************  '), ' ');
        $this->pdf->Ln(8);
        $this->pdf->Cell(27, 5, utf8_decode('   ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  '), ' ');
        /*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
        $this->pdf->Ln(6);
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);    //color rojo
        $this->pdf->Cell(200, 5, 'GIRUB ', 0, 0, 'C');
        $this->pdf->Ln(5);
        $this->pdf->Cell(200, 5, utf8_decode('COMPROBANTE DE VENTA  00' . $nro_comprobante), 0, 0, 'C');
        $this->pdf->SetTextColor(0, 0, 0);    //color negro
        $this->pdf->Ln(8);

        /*COLOCANDO FECHA EN LITERAL*/
        $anio = substr($fecha_venta, 0, 4);
        $mes = substr($fecha_venta, 5, 2);
        $dia = substr($fecha_venta, 8, 2);
        $fechaTransaccion = $dia . ' de ' . $this->obtener_mes($mes) . ' del ' . $anio;

        /*  NOMBRE DEL CLIENTE*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('NOMBRE CLIENTE     : ' . $nombre_cliente), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA DE LA VENTA*/
        $this->pdf->Cell(27, 5, utf8_decode('FECHA DE VENTA      : ' . $fechaTransaccion . '                                                                                   FORMA PAGO  : CONTADO - ' . $forma_pagos), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /* Encabezado de la columna*/
        $this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "CODIGO", 1, 0, 'C');
        $this->pdf->Cell(92, 5, "NOMBRE PRODUCTO", 1, 0, 'C');
        $this->pdf->Cell(30, 5, "P/U", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "TOTAL VENTA", 1, 0, 'C');

        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);

        $cantidad_filas = 0;

        foreach ($lista_detalle_ventas as $row_detalle) {
            if ($row_detalle->estado_entrega == 4) {
                $estado_entrega = "PENDIENTE";
            }
            if ($row_detalle->estado_entrega == 5) {
                $estado_entrega = "ENTREGADO";

            }
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas == count($lista_detalle_ventas)) {
                $estilo = 'LRB';
            }
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->cantidad), $estilo, 0, 'C');
            $this->pdf->Cell(25, 4, utf8_decode($row_detalle->codigo_barra), $estilo, 0, 'C');
            $this->pdf->Cell(92, 4, utf8_decode($row_detalle->nombre_item), $estilo, 0, 'C');
            $this->pdf->Cell(30, 4, utf8_decode($row_detalle->precio_venta), $estilo, 0, 'C');
            $this->pdf->Cell(25, 4, utf8_decode($row_detalle->precio_venta * $row_detalle->cantidad), $estilo, 0, 'C');

            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }
        $this->pdf->Ln(3);
        /*  SUBTOTAL*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                   SUBTOTAL     Bs  : ' . $subTotal), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  DESCUENTO  */
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                   DESCUENTO  Bs   : ' . $descuent), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(5);


        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'MONTO TOTAL Bs  :  ', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '                           ' . $valor, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, $monto_a_plazos, 1, 0, 'R');
        $this->pdf->Ln(8);
        /*  NOTA DE LA VENTA*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('USUARIO    : ' . $usuario), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  LINEA DE SEPARACION */
        $this->pdf->Cell(27, 5, utf8_decode('NOTA          :' . $nota), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(30);
        /*  ENTREGADO Y RECIBIDO POR*/
        $this->pdf->Cell(27, 5, utf8_decode('                                   -------------------------------------------------------------   ' . '                            -------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(3);
        $this->pdf->Cell(27, 5, utf8_decode('                                                       ENTREGADO                                ' . '                                                       RECIBIDO    '), ' ');

        $this->pdf->Output("ComprobanteVenta.pdf", 'I');
    }

    /*** Imprimir nota de la solicitud a plazos****/
    public function imprimir_nota_ventas_plazos()
    {
        $venta_id = $this->uri->segment(3);
        $datos = $this->venta->comprobante_ventas($venta_id);
        $lista_compras = $datos['datos'];
        $lista_detalle_ventas = $datos['datos_venta_detalle'];

        $sucursal_id = 1;// $this->input->post('sucursal');
        $datos_empresa = $this->sucursal->get_datos_empresa($sucursal_id);

        foreach ($lista_compras as $row_detalle) {
            $nombre_cliente = $row_detalle->nombre_cliente;
            $subTotal = $row_detalle->subtotal;
            $fecha_venta = $row_detalle->fecha;
            $nro_comprobante = $row_detalle->nro_nota;
            $usuario = $row_detalle->nombre_usuario;
            $nota = $row_detalle->nota;
            $forma_pago_plazo = $row_detalle->descripcion;
            $nombre_empresa = $row_detalle->nombre_empresa;
            $sucursal = $row_detalle->sucursal;
            $direccion = $row_detalle->direccion;
            $telefono = $row_detalle->telefono;
            $monto_total = $row_detalle->monto;
            $saldo = $row_detalle->saldo;
            $descuento = $row_detalle->descuento;

        }
        foreach ($lista_detalle_ventas as $row_detalle_venta) {

            $codigo_producto = $row_detalle_venta->codigo_barra;
        }
        if ($forma_pago_plazo == 'forma_pago_efectivo') {
            $modo_pago = 'Efectivo';
        } else if ($forma_pago_plazo == 'forma_pago_tarjeta') {
            $modo_pago = 'Tarjeta';
        } else if ($forma_pago_plazo == 'forma_pago_cheque') {
            $modo_pago = 'Cheque';
        } else if ($forma_pago_plazo = 'forma_pago_deposito') {
            $modo_pago = 'Deposito';
        } else {
            $modo_pago = 'NADA';
        }

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        $this->pdf->SetTitle("COMPROBANTE DE VENTA");
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 45, 25);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(60, 5, $nombre_empresa, 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        /**/
        $this->pdf->Ln(5);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(60, 5, $sucursal, 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Cell(75, 5, ' ', 0, 0, 'C');

        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(40, 5, 'COMPROBANTE DE VENTA', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(100, 4, $direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);

        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(40, 5, 'Nro. 00' . $nro_comprobante, 0, 0, 'C');

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(100, 4, 'Telf. ' . $telefono, 0, 0, 'C');

        $this->pdf->Ln(4);
        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(50, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(3);
        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(10);
        $anio = substr($fecha_venta, 0, 4);
        $mes = substr($fecha_venta, 5, 2);
        $dia = substr($fecha_venta, 8, 2);
        $fechaTransaccion = $dia . ' de ' . $this->obtener_mes($mes) . ' del ' . $anio;


        /*  NOMBRE DEL CLIENTE*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('NOMBRE CLIENTE     : ' . $nombre_cliente), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA DE LA VENTA*/
        $this->pdf->Cell(27, 5, utf8_decode('FECHA DE VENTA      : ' . $fechaTransaccion . '                                                                           FORMA DE PAGO  : PLAZO - ' . $modo_pago), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /* Encabezado de la columna*/
        $this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "CODIGO", 1, 0, 'C');
        $this->pdf->Cell(92, 5, "NOMBRE PRODUCTO", 1, 0, 'C');
        $this->pdf->Cell(30, 5, "P/U", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "TOTAL VENTA", 1, 0, 'C');


        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);

        $cantidad_filas = 0;

        foreach ($lista_detalle_ventas as $row_detalle) {
            if ($row_detalle->estado_entrega == 4) {
                $estado_entrega = "PENDIENTE";
            }
            if ($row_detalle->estado_entrega == 5) {
                $estado_entrega = "ENTREGADO";

            }

            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas == count($lista_detalle_ventas)) {
                $estilo = 'LRB';
            }

            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->cantidad), $estilo, 0, 'C');
            $this->pdf->Cell(25, 4, utf8_decode($row_detalle->codigo_barra), $estilo, 0, 'C');
            $this->pdf->Cell(92, 4, utf8_decode($row_detalle->nombre_item), $estilo, 0, 'C');
            $this->pdf->Cell(30, 4, utf8_decode($row_detalle->precio_venta), $estilo, 0, 'C');
            $this->pdf->Cell(25, 4, utf8_decode($row_detalle->cantidad * $row_detalle->precio_venta), $estilo, 0, 'C');


            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }
        $this->pdf->Ln(3);
        /*********/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                    SUBTOTAL  Bs  : ' . $subTotal), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                             DESCUENTO     Bs   : ' . $descuento), 'LB');

        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(5);
        /*******/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                        PAGADO  Bs  : ' . $monto_total), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                       SALDO     Bs   : ' . $saldo), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(5);

        // Convertimos el monto en literal
        include APPPATH . '/libraries/convertidor.php';
        $v = new EnLetras();
        $valor = $v->ValorEnLetras($monto_total, " ");
        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'MONTO TOTAL Bs  :  ', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '                           ' . $valor, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, $monto_total, 1, 0, 'R');
        $this->pdf->Ln(8);
        /*  NOTA DE LA VENTA*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('USUARIO    : ' . $usuario), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  LINEA DE SEPARACION */
        $this->pdf->Cell(27, 5, utf8_decode('NOTA          : ' . $nota), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(30);
        /*  ENTREGADO Y RECIBIDO POR*/
        $this->pdf->Cell(27, 5, utf8_decode('                                   -------------------------------------------------------------   ' . '                            -------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(3);
        $this->pdf->Cell(27, 5, utf8_decode('                                                       ENTREGADO                                ' . '                                                       RECIBIDO    '), ' ');
        $this->pdf->Ln(17);
        $this->pdf->Cell(27, 5, utf8_decode('   *******************************************************      GRACIAS POR SU COMPRA VUELVA PRONTO   *************************************************  '), ' ');
        $this->pdf->Ln(8);
        $this->pdf->Cell(27, 5, utf8_decode('   ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  '), ' ');
        /*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

        $this->pdf->Ln(6);
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);    //color rojo
        $this->pdf->Cell(200, 5, $datos_empresa->nombre_empresa, 0, 0, 'C');
        $this->pdf->Ln(5);
        $this->pdf->Cell(200, 5, utf8_decode('COMPROBANTE DE VENTA  00' . $nro_comprobante), 0, 0, 'C');
        $this->pdf->SetTextColor(0, 0, 0);    //color negro
        $this->pdf->Ln(8);

        $anio = substr($fecha_venta, 0, 4);
        $mes = substr($fecha_venta, 5, 2);
        $dia = substr($fecha_venta, 8, 2);
        $fechaTransaccion = $dia . ' de ' . $this->obtener_mes($mes) . ' del ' . $anio;


        /*  NOMBRE DEL CLIENTE*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('NOMBRE CLIENTE     : ' . $nombre_cliente), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA DE LA VENTA*/
        $this->pdf->Cell(27, 5, utf8_decode('FECHA DE VENTA      : ' . $fechaTransaccion . '                                                                           FORMA DE PAGO  : PLAZO - ' . $modo_pago), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /* Encabezado de la columna*/
        $this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "PRODUCTO", 1, 0, 'C');
        $this->pdf->Cell(92, 5, "NOMBRE PRODUCTO", 1, 0, 'C');
        $this->pdf->Cell(30, 5, "P/U", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "TOTAL VENTA", 1, 0, 'C');

        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);

        $cantidad_filas = 0;

        foreach ($lista_detalle_ventas as $row_detalle) {
            if ($row_detalle->estado_entrega == 4) {
                $estado_entrega = "PENDIENTE";
            }
            if ($row_detalle->estado_entrega == 5) {
                $estado_entrega = "ENTREGADO";

            }
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas == count($lista_detalle_ventas)) {
                $estilo = 'LRB';
            }
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->cantidad), $estilo, 0, 'C');
            $this->pdf->Cell(25, 4, utf8_decode($row_detalle->codigo_barra), $estilo, 0, 'C');
            $this->pdf->Cell(92, 4, utf8_decode($row_detalle->nombre_item), $estilo, 0, 'C');
            $this->pdf->Cell(30, 4, utf8_decode($row_detalle->precio_venta), $estilo, 0, 'C');
            $this->pdf->Cell(25, 4, utf8_decode($row_detalle->cantidad * $row_detalle->precio_venta), $estilo, 0, 'C');

            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }
        $this->pdf->Ln(3);
        /*********/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                    SUBTOTAL  Bs  : ' . $subTotal), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                             DESCUENTO     Bs   : ' . $descuento), 'LB');

        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(5);
        /*******/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                        PAGADO  Bs  : ' . $monto_total), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                       SALDO     Bs   : ' . $saldo), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(5);

        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'MONTO TOTAL Bs  :  ', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '                           ' . $valor, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, $monto_total, 1, 0, 'R');
        $this->pdf->Ln(8);
        /*  NOTA DE LA VENTA*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('USUARIO    : ' . $usuario), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  LINEA DE SEPARACION */
        $this->pdf->Cell(27, 5, utf8_decode('NOTA          : ' . $nota), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(30);
        /*  ENTREGADO Y RECIBIDO POR*/
        $this->pdf->Cell(27, 5, utf8_decode('                                   -------------------------------------------------------------   ' . '                            -------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(3);
        $this->pdf->Cell(27, 5, utf8_decode('                                                       ENTREGADO                                ' . '                                                       RECIBIDO    '), ' ');

        $this->pdf->Output("ComprobanteVenta.pdf", 'I');
    }


    /*** Imprimir  en pdf ventas ****/
    public function nota_entrega_productos()
    {
        $codigo = $this->input->post('codigo');

        $datos = $this->venta->nota_entrega($codigo);
        $datos_empresa = $this->sucursal->get_datos_empresa(2);

        $lista_compras = $datos['datos'];
        $monto_total = 0;

        foreach ($lista_compras as $row_detalle) {
            $monto_total = $monto_total + $row_detalle->cantidad_total;
            $forma_pago = $row_detalle->forma_pago;
            $nombre_cliente = $row_detalle->nombre_cliente;
        }

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        $this->pdf->SetTitle("NOTA DE ENTREGA");
        $var_img = base_url() . 'assets/img/logo_empresa.png';
        $this->pdf->Image($var_img, 10, 10, 60, 28);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(65, 5, 'DICARP ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        /**/
        $this->pdf->Ln(5);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(60, 5, '      Colchones y Complementos', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Cell(75, 5, ' ', 0, 0, 'C');

        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(60, 5, 'NOTA DE ENTREGA', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(60, 4, 'Av 26 de Febrero 2do anillo casi centenario', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);

        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(61, 5, 'DICARP', 0, 0, 'C');

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(60, 4, 'Telf. 9302099 - 70838701
', 0, 0, 'C');

        $this->pdf->Ln(10);
        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(10);

        /*  FECHA INICIO*/
        $this->pdf->SetFont('Arial', 'B', 8);
        //$this->pdf->Cell(27, 5, utf8_decode('Nombre Cliente    :   ' . $nombre_cliente . '                            Fecha de Impresión   :  ' . date('d/m/Y') . '                           Sucursal : ' . $datos_empresa->sucursal), 'TL');
        $this->pdf->Cell(27, 5, utf8_decode('Nombre Cliente    :   ' . $nombre_cliente . '                            Fecha de Impresión   :  ' . date('d/m/Y')), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA FIN  */
        $this->pdf->Cell(27, 5, utf8_decode('Cantidad Total   : ' . $monto_total) . '                                                                 Tipo Venta  : ' . $forma_pago, 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /* Encabezado de la columna*/
        $this->pdf->Cell(30, 5, "NRO", 1, 0, 'C');
        $this->pdf->Cell(125, 5, "PRODUCTO", 1, 0, 'C');
        $this->pdf->Cell(37, 5, "CANTIDAD", 1, 0, 'C');


        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);

        $cantidad_filas = 0;

        foreach ($lista_compras as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas == count($lista_compras)) {
                $estilo = 'LRB';
            }

            $this->pdf->Cell(30, 4, utf8_decode($cantidad_filas), $estilo, 0, 'C');
            $this->pdf->Cell(125, 4, utf8_decode($row_detalle->nombre_item), $estilo, 0, 'C');
            $this->pdf->Cell(37, 4, utf8_decode($row_detalle->cantidad_total), $estilo, 0, 'C');

            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }
        $this->pdf->Output("NotaEntrega.pdf", 'I');

    }

    //hace referencia al metodo obtener todos los articulos con el estado_venta==5
    public function get_all_items()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order
            );

            echo json_encode($this->venta->get_all_items($params));
        } else {
            show_404();
        }
    }

}