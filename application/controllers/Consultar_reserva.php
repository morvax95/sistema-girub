<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Consultar_reserva extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Consultar_reserva_model', 'consulta');
        $this->load->model('venta_model', 'venta');
    }

    public function index()
    {
        plantilla('consultar_reserva/index', null);
    }

    public function get_all()
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

            echo json_encode($this->consulta->get_all($params));
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


    /*** Imprimir nota de las reservas ****/
    public function imprimir_nota_reservas()
    {
        $id = $this->uri->segment(3);
        $datos = $this->consulta->comprobante_reserva($id);
        $lista_compras = $datos['datos'];

        foreach ($lista_compras as $row_detalle) {
            $nombre_cliente = $row_detalle->cliente;
            $fecha_venta = $row_detalle->fecha_reserva;
            $nombre_empresa = $row_detalle->nombre_empresa;
            $direccion = $row_detalle->direccion;
            $telefono = $row_detalle->telefono;
            $sucursal = $row_detalle->sucursal;


            /*  $subTotal = $row_detalle->subtotal;
              $descuent = $row_detalle->descuento;
              $nro_comprobante = $row_detalle->nro_nota;
              $usuario = $row_detalle->nombre_usuario;
              $nota = $row_detalle->nota;
              $forma_pagos = $row_detalle->forma_pago;
             */

        }

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        $this->pdf->SetTitle("NOTA DE RESERVA");
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
        $this->pdf->Cell(40, 5, 'NOTA DE RESERVA', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(100, 4, $direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);

        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        //  $this->pdf->Cell(40, 5, 'Nro. 00' . $nro_comprobante, 0, 0, 'C');

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(180, 4, 'Telf. ' . $telefono, 0, 0, 'C');

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
        $this->pdf->Cell(27, 5, utf8_decode('NOMBRE CLIENTE        : ' . $nombre_cliente), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA DE LA VENTA*/
        $this->pdf->Cell(27, 5, utf8_decode('FECHA DE RESERVA    : ' . $fechaTransaccion . '                                                                                   FORMA PAGO  : CONTADO - '), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /* Encabezado de la columna*/
        $this->pdf->Cell(45, 5, "TIPO ESCENARIO", 1, 0, 'C');
        $this->pdf->Cell(92, 5, "ESCENARIO", 1, 0, 'C');
        $this->pdf->Cell(30, 5, "HORAS", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "SUBTOTAL", 1, 0, 'C');


        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);

        $cantidad_filas = 0;

        /*  foreach ($lista_detalle_ventas as $row_detalle) {
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
          }*/
        $this->pdf->Ln(3);
        /*  SUBTOTAL*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                              SUBTOTAL Bs  : ' . 0), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  DESCUENTO  */
        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                           DESCUENTO Bs  : ' . 200), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(5);


        // Convertimos el monto en literal
        $monto_a_plazos = 75;
        include APPPATH . '/libraries/convertidor.php';
        $v = new EnLetras();
        $valor = $v->ValorEnLetras($monto_a_plazos, " ");
        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'MONTO TOTAL Bs  :  ', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '                           ' . 5000, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, 20000, 1, 0, 'R');
        $this->pdf->Ln(8);
        /*  NOTA DE LA VENTA*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('USUARIO    : '), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  LINEA DE SEPARACION */
        $this->pdf->Cell(27, 5, utf8_decode('NOTA          :'), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(30);
        /*  ENTREGADO Y RECIBIDO POR*/
        $this->pdf->Cell(27, 5, utf8_decode('                                   -------------------------------------------------------------   ' . '                            -------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(3);
        $this->pdf->Cell(27, 5, utf8_decode('                                                       ENTREGADO                                ' . '                                                       RECIBIDO    '), ' ');
        $this->pdf->Ln(17);
        $this->pdf->Cell(27, 5, utf8_decode('   *******************************************************      GRACIAS POR SU RESERVA VUELVA PRONTO   *************************************************  '), ' ');
        $this->pdf->Ln(8);
        $this->pdf->Cell(27, 5, utf8_decode('   ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  '), ' ');
        /*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
        $this->pdf->Ln(6);
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);    //color rojo
        $this->pdf->Cell(200, 5, 'GIRUB ', 0, 0, 'C');
        $this->pdf->Ln(5);
        ///*  $this->pdf->Cell(200, 5, utf8_decode('COMPROBANTE DE VENTA  00' . $nro_comprobante), 0, 0, 'C');
        $this->pdf->SetTextColor(0, 0, 0);    //color negro
        $this->pdf->Ln(8);

        /*COLOCANDO FECHA EN LITERAL*/
        $anio = substr($fecha_venta, 0, 4);
        $mes = substr($fecha_venta, 5, 2);
        $dia = substr($fecha_venta, 8, 2);
        $fechaTransaccion = $dia . ' de ' . $this->obtener_mes($mes) . ' del ' . $anio;


        /*  NOMBRE DEL CLIENTE*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('NOMBRE CLIENTE       : ' . $nombre_cliente), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA DE LA VENTA*/
        $this->pdf->Cell(27, 5, utf8_decode('FECHA DE RESERVA   : ' . $fechaTransaccion . '                                                                                   FORMA PAGO  : CONTADO - '), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /* Encabezado de la columna*/
        $this->pdf->Cell(45, 5, "TIPO ESCENARIO", 1, 0, 'C');
        $this->pdf->Cell(92, 5, "ESCENARIO", 1, 0, 'C');
        $this->pdf->Cell(30, 5, "HORAS", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "SUBTOTAL", 1, 0, 'C');

        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);

        $cantidad_filas = 0;

        /*   foreach ($lista_detalle_ventas as $row_detalle) {
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
           }*/
        $this->pdf->Ln(3);
        /*  SUBTOTAL*/
        $this->pdf->SetFont('Arial', 'B', 8);
//        $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                   SUBTOTAL     Bs  : ' . $subTotal), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  DESCUENTO  */
        //    $this->pdf->Cell(27, 5, utf8_decode('                                                                                                                                                                                   DESCUENTO  Bs   : ' . $descuent), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(5);


        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'MONTO TOTAL Bs  :  ', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        // $this->pdf->Cell(125, 5, '                           ' . $valor, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        //  $this->pdf->Cell(35, 5, $monto_a_plazos, 1, 0, 'R');
        $this->pdf->Ln(8);
        /*  NOTA DE LA VENTA*/
        $this->pdf->SetFont('Arial', 'B', 8);
        // $this->pdf->Cell(27, 5, utf8_decode('USUARIO    : ' . $usuario), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  LINEA DE SEPARACION */
        //  $this->pdf->Cell(27, 5, utf8_decode('NOTA          :' . $nota), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(30);
        /*  ENTREGADO Y RECIBIDO POR*/
        $this->pdf->Cell(27, 5, utf8_decode('                                   -------------------------------------------------------------   ' . '                            -------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(3);
        $this->pdf->Cell(27, 5, utf8_decode('                                                       ENTREGADO                                ' . '                                                       RECIBIDO    '), ' ');

        $this->pdf->Output("nota_reserva.pdf", 'I');
    }

}