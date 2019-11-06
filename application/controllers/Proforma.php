<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proforma extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('proforma_model');
        $this->load->model('almacen_model', 'almacen');
        $this->load->model('producto_model', 'producto');
        $this->load->model('sucursal_model', 'sucursal');
        $this->load->model('caja_model', 'caja');
    }


    public function index()
    {
        $data['cajas'] = $this->caja->get_available_boxes();
        $data['almacenes'] = $this->almacen->get_all();
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('proforma/index', $data);
    }

    public function agregar()
    {
        if ($this->input->is_ajax_request()) {

            // Recibimos los parametros del formulario
            $contador = $this->input->post('contador');
            $id_producto = $this->input->post('id_producto');
            $id_talla = $this->input->post('id_talla');
            $id_color = $this->input->post('id_color');
            $descripcion = $this->input->post('detalle_venta');
            $talla = $this->input->post('talla_venta');
            $color = $this->input->post('color_venta');
            $precio = $this->input->post('precio_venta');
            $cantidad = $this->input->post('cantidad_venta');
            $codigo_barra_detalle = $this->input->post('codigo_barra_detalle');
            $sub_total = round($cantidad * $precio, 2);
            // Creamos las filas del detalle

            /*obtenemos el color y la talla con los ids*/
            $this->db->where('id', $talla);
            $data_talla = $this->db->get('talla')->row();
            $this->db->where('id', $color);
            $data_color = $this->db->get('color')->row();

            $fila = '<tr>';
            $fila .= '<td>' . $codigo_barra_detalle . '</td>';
            $fila .= '<td><input type="text" value="' . $id_producto . '" id="producto_id" name="producto_id[]" hidden/>
            <input type="text" value="' . $id_talla . '" id="talla_id" name="talla_id[]" hidden/>
            <input type="text" value="' . $id_color . '" id="color_id" name="color_id[]" hidden/>
            <input type="text" value="' . $descripcion . '" id="descripcion" name="descripcion[]" hidden/>' . $descripcion . '</td>';
            /* $fila .= '<td>' . $data_talla->descripcion . '</td>';
             $fila .= '<td>' . $data_color->descripcion . '</td>';*/
            $fila .= '<td >' . ' ' . '</td>';
            $fila .= '<td >' . ' ' . '</td>';
            $fila .= '<td><input type="number" step="any" value="' . $precio . '" id="precio' . $contador . '" name="precio[]" hidden/>' . $precio . '</td>';
            $fila .= '<td><input type="text" class="form-control" value="' . $cantidad . '" id="cantidad' . $contador . '" name="cantidad[]" readonly style="text-align: right" min="1" max=""/></td>';
            $fila .= '<td><input type="text" class="form-control" value="' . $sub_total . '" id="monto' . $contador . '" name="monto[]" size="4" style="text-align: right" readonly/></td>';
            $fila .= '<td class="text-center"><a class="elimina"><i class="fa fa-trash-o"></i></a></td></tr>';

            $datos = array(
                0 => $fila,
                1 => $contador,
                2 => $sub_total,
            );

            echo json_encode($datos);
        } else {
            show_404();
        }
    }

    /*-------------------------------------------------
     * Funcion para el registro de venta de otros productos
     *------------------------------------------------- */
    public function registro_proforma()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->proforma_model->registro_proforma());
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

            $this->proforma_model->registroDetalleVenta($detalle);
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

            $this->proforma_model->registroDetalleVentaMateria($detalle);
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

    public function consolidate_sale()
    {
        $data['cajas'] = $this->caja->get_available_boxes();
        $data['almacenes'] = $this->almacen->get_all();
        $data['sucursales'] = $this->sucursal->get_all();
        $data['tallas'] = $this->producto->get_sizes();
        $data['detail_items'] = $this->proforma_model->imprimir_proforma($this->input->post('id'));
        plantilla('proforma/consolidar_proforma', $data);
    }

    /**** Imprime las facturas que son de materia de madera *******/
    public function imprimirFactura()
    {
        $venta_id = $this->uri->segment(3);
        $respuesta = $this->proforma_model->imprimirFactura($venta_id);
        $datosImpresion['datos_factura'] = $respuesta['datos_factura'];
        $datosImpresion['datos_venta_detalle'] = $respuesta['datos_venta_detalle'];
        $datosImpresion['qr_image'] = $respuesta['qr_image'];

        $this->load->view('venta/impresion_factura', $datosImpresion);
    }

    public function imprimir_proforma()
    {
        $venta_id = $this->uri->segment(3);
        $respuesta = $this->proforma_model->imprimir_proforma($venta_id);

        $this->load->view('proforma/impresion_proforma', $respuesta);
    }

    /*  Imprimir Proforma  */
    public function print_proforma()
    {
        $venta_id = $this->uri->segment(3);
        $datos_proforma = $this->proforma_model->imprimir_proforma($venta_id);
        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Letter');
        // $ingresos  = $this->flujo_caja_model->obtener_ingresos($fecha_inicio,$fecha_fin); array(216, 279)
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
        //echo json_encode($datos_proforma);
        /**/
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("PROFORMA");
        /* La variable $x se utiliza para mostrar un número consecutivo */

        /* titulo de ingreso*/
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 48, 26);
        /*  NIT Y NRO FACTURA   */

        /*  intenando poner multicell   */
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(53, 5, $datos_proforma["datos_proforma"]->nombre_empresa, 0, 0, 'C');
        $this->pdf->Cell(55, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        /**/
        $this->pdf->Ln(5);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(52, 5, $datos_proforma["datos_proforma"]->sucursal, 0, 0, 'C');
        $this->pdf->Cell(60, 5, '', 0);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(70, 5, ' ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(50, 5, 'P R O F O R M A', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(80, 4, $datos_proforma["datos_proforma"]->direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(45, 5, utf8_decode('Nº ' . $datos_proforma["datos_proforma"]->nro_proforma), 0, 0, 'C');
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(80, 4, 'Telf. ' . $datos_proforma["datos_proforma"]->telefono, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(4);
        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(40, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);
        $this->pdf->Ln(15);


        /*  LUGAR Y FECHA ///// NIT CI*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, 'Lugar y fecha :', 'TL');
        $this->pdf->SetFont('Arial', '', 8);
        $fecha_literal = date('d', strtotime($datos_proforma["datos_proforma"]->fecha)) . ' de ';
        $fecha_literal = $fecha_literal . $this->obtener_mes(date('m', strtotime($datos_proforma["datos_proforma"]->fecha))) . ' del ';
        $fecha_literal = $fecha_literal . date('Y', strtotime($datos_proforma["datos_proforma"]->fecha));
        $this->pdf->Cell(165, 5, 'Santa Cruz, ' . $fecha_literal, 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);


        /*  CLIENTE */
        $this->pdf->Cell(27, 5, utf8_decode('Señor(es)       :'), 'LB');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(165, 5, utf8_decode($datos_proforma["datos_proforma"]->nombre_cliente), 'RB');
        $this->pdf->Ln(7);

        /*  DETALLE DE ITEMS */
        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->SetFont('Arial', 'B', 8);
        //un arreglo con alineacion de cada celda
        /* Encabezado de la columna*/
        $this->pdf->Cell(13, 5, "NRO.", 1, 0, 'C');
        $this->pdf->Cell(104, 5, "DETALLE", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "CANTIDAD", 'TB', 0, 'C');
        $this->pdf->Cell(30, 5, "PRECIO U.", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "MONTO", 1, 0, 'C');
        $this->pdf->Ln(5);
        /*  detalle*/
        $nro = 1;
        $detalle_proforma = $datos_proforma["datos_proforma_detalle"];
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetAligns(array('C', 'L', 'R'));
        $cantidad_filas = 0;
        $numero_items = 15;
        foreach ($detalle_proforma as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($numero_items == $cantidad_filas) {
                $estilo = 'LRB';
            }
            $monto_sub = $row_detalle->cantidad * $row_detalle->precio_venta;
            $this->pdf->Cell(13, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(104, 4, utf8_decode($row_detalle->nombre_item), $estilo, 0, 'C');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->cantidad), $estilo, 0, 'C');
            $this->pdf->Cell(30, 4, utf8_decode(number_format($row_detalle->precio_venta, 2)), $estilo, 0, 'C');
            $this->pdf->Cell(25, 4, utf8_decode(number_format($monto_sub, 2)), $estilo, 0, 'L');
            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }

        while ($numero_items > $cantidad_filas) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($numero_items == $cantidad_filas) {
                $estilo = 'LRB';
            }

            $this->pdf->Cell(13, 4, '', $estilo, 0, 'C');
            $this->pdf->Cell(104, 4, '', $estilo, 0, 'C');
            $this->pdf->Cell(20, 4, '', $estilo, 0, 'C');
            $this->pdf->Cell(30, 4, '', $estilo, 0, 'C');
            $this->pdf->Cell(25, 4, '', $estilo, 0, 'L');
            $this->pdf->Ln(4);
        }
        /*DESCUENTO*/
        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(7, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '', '', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(30, 5, 'DESCUENTO Bs. :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(25, 5, number_format($datos_proforma["datos_proforma"]->descuento, 2), 1, 0, 'R');
        $this->pdf->Ln(4);
        /*TOTAL*/

        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(7, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '', '', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(30, 5, 'TOTAL BS :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(25, 5, number_format($datos_proforma["datos_proforma"]->total, 2), 1, 0, 'R');
        $this->pdf->Ln(8);


        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetWidths(array(20, 100, 60));
        //un arreglo con alineacion de cada celda
        $this->pdf->SetAligns(array('C', 'L', 'R'));
        /*  quitamos bolf, y empezamos a dibujar las grillas*/
        $this->pdf->SetFont('Arial', '', 9);


        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->Output("Proforma.pdf", 'I');
    }
}