<?php


class Historial_pago extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('historial_pago_model', 'historial');
        $this->load->model('sucursal_model', 'sucursal');
    }

    //region Vistas de producto

    public function index()
    {
        plantilla('historial_pago/index');
    }


    public function cambiar_estado_produccion()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id_producto');
            echo($id);

            $res = $this->historial->cambiar_estado_produccion($id);
            if ($res !== 1) {
                echo 'true';
            } else {
                echo 'error';
            }
        } else {
            show_404();
        }
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

            echo json_encode($this->historial->get_all_items($params));
        } else {
            show_404();
        }
    }

    public function ver_datos_pago()
    {

        if ($this->input->post()) {
            $id = $this->input->post('id');
            $data = $this->historial->get_data_by_id($id);
            plantilla('historial_pago/ver', $data);
        } else {
            show_404();
        }
    }

    //actualizar el estado de la solicitud
    public function modificar_estado_solicitud()
    {
        if ($this->input->is_ajax_request()) {
            $nro_venta = $this->input->post('nroVenta');

            $res = $this->historial->cambiar_estado_solicitud($nro_venta);
            if ($res !== 1) {
                echo 'true';
            } else {
                echo 'error';
            }
        } else {
            show_404();
        }
    }

    /*** Imprimir  en pdf solicitud ****/
    public function historial_pagos_cliente()
    {
        /* $codigo = $this->input->post('codigo');
         $codigo = $this->historial->nota_entrega($codigo);*/
        $codigo = $this->uri->segment(3);
        $datos = $this->historial->nota_entrega($codigo);

        $sucursal_id = 1;// $this->input->post('sucursal');
        $datos_empresa = $this->sucursal->get_datos_empresa($sucursal_id);

        $lista_compras = $datos['datos'];

        foreach ($lista_compras as $row_detalle) {
            $nombre = $row_detalle->nombre_cliente;
            $estado_pago = $row_detalle->estado;
            $monto = $row_detalle->total;
        }

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        $this->pdf->SetTitle("HISTORIAL DE PAGOS");
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 50, 28);

        /*  intenando poner multicell   */
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(53, 5, $datos_empresa->nombre_empresa, 0, 0, 'C');
        $this->pdf->Cell(55, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        $this->pdf->Ln(4);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(52, 5, $datos_empresa->sucursal, 0, 0, 'C');
        $this->pdf->Cell(60, 5, '', 0);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(70, 5, ' ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(50, 5, 'HISTORIAL DE PAGO', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(85, 4, $datos_empresa->direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(45, 5, utf8_decode($datos_empresa->nombre_empresa), 0, 0, 'C');
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(80, 4, 'Telf. ' . $datos_empresa->telefono, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(4);
        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(45, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(5);
        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(10);

        /*  FECHA INICIO*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('NOMBRE CLIENTE           :   ' . $nombre), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA FIN  */
        $this->pdf->Cell(27, 5, utf8_decode(' FECHA DE IMPRESIÓN   :  ' . date('d/m/Y') . '                                                   ESTADO : ' . $estado_pago . '                                    MONTO VENTA :' . $monto), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /* Encabezado de la columna*/

        $this->pdf->Cell(30, 5, "NRO", 1, 0, 'C');
        $this->pdf->Cell(44, 5, "FORMA PAGO", 1, 0, 'C');
        $this->pdf->Cell(44, 5, "FECHA PAGO", 1, 0, 'C');
        $this->pdf->Cell(37, 5, "MONTO", 1, 0, 'C');
        $this->pdf->Cell(37, 5, "SALDO", 1, 0, 'C');


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
            $this->pdf->Cell(44, 4, utf8_decode($row_detalle->forma_pago), $estilo, 0, 'C');
            $this->pdf->Cell(44, 4, utf8_decode($row_detalle->fecha_pago), $estilo, 0, 'C');
            $this->pdf->Cell(37, 4, utf8_decode($row_detalle->monto), $estilo, 0, 'C');
            $this->pdf->Cell(37, 4, utf8_decode($row_detalle->saldo), $estilo, 0, 'C');

            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }


        $this->pdf->Output("Historial_Pagos.pdf", 'I');

    }


}