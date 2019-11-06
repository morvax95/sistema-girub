<?php

/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 23/03/2018
 * Time: 12:24 AM
 */
class Egreso extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('egreso_model');
    }

    //region Vistas de egreso

    public function index()
    {
        plantilla('egreso/index');
    }
    public function nuevo()
    {
        $data['tipo_egreso'] = $this->egreso_model->get_all_type_of_egress();
        plantilla('egreso/nuevo', $data);
    }

    public function editar()
    {
        $data['tipo_egreso'] = $this->egreso_model->get_all_type_of_egress();
        $data['egreso'] = $this->egreso_model->get_egress_by_id($this->input->post('id'));
        plantilla('egreso/editar', $data);
    }

    public function registro_egreso()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->egreso_model->registro_egreso();
        } else {
            show_404();
        }
    }
    public function modificar()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->egreso_model->modificar();
        } else {
            show_404();
        }
    }
    public function eliminar()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->egreso_model->eliminar($this->input->post('id'));
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

            echo json_encode($this->egreso_model->get_all($params));
        } else {
            show_404();
        }
    }

    public function registrar_tipo_egreso()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->egreso_model->registrar_tipo_egreso();
        } else {
            show_404();
        }
    }
    public function get_all_type_of_egress()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->egreso_model->get_all_type_of_egress());
        } else {
            show_404();
        }
    }
    /*** Imprimir  en pdf nota de ingreso ****/
    public function imprimir_egreso_seleccionado()
    {
        // $codigo = $this->input->post('codigo');
        $codigo = $this->uri->segment(3);
        $datos = $this->egreso_model->nota_egresos($codigo);

        $lista_compras = $datos['datos'];

        foreach ($lista_compras as $row_detalle) {

            $monto = $row_detalle->monto;
            $tipo_ingreso = $row_detalle->tipo_egreso;
            $descripcion = $row_detalle->descripcion;
            $nombre_empresa = $row_detalle->nombre_empresa;
            $sucursal = $row_detalle->sucursal;
            $direccion = $row_detalle->direccion;
            $telefono = $row_detalle->telefono;
            $usuario = $row_detalle->usuario;

        }

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        $this->pdf->SetTitle("EGRESOS " . $nombre_empresa);
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 50, 28);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(65, 5, '' . $nombre_empresa, 0, 0, 'C');
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
        $this->pdf->Cell(40, 5, 'NOTA EGRESOS', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(100, 4, $direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);

        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(41, 5, '' . $nombre_empresa, 0, 0, 'C');

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(100, 4, $telefono, 0, 0, 'C');
        $this->pdf->Ln(4);

        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(50, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(10);
        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(10);

        /*  TIPO INGRESO*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('TIPO EGRESO              :   ' . $tipo_ingreso . '                                                                              USUARIO : ' . $usuario), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA INGRESO  */
        $this->pdf->Cell(27, 5, utf8_decode(' FECHA DE EGRESO   :  ' . date('d/m/Y') . '                                                                                                    MONTO EGRESO  :  ' . $monto), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);


        /*  TIPO EGRESO*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('DETALLE EGRESO      :   ' . $descripcion), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA EGRESO  */
        $this->pdf->Cell(27, 5, utf8_decode(''), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(25);
        /*  ENTREGADO Y RECIBIDO POR*/
        $this->pdf->Cell(27, 5, utf8_decode('                                   -------------------------------------------------------------   ' . '                            -------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(3);
        $this->pdf->Cell(27, 5, utf8_decode('                                                       ENTREGADO                                ' . '                                                       RECIBIDO    '), ' ');
        $this->pdf->Ln(25);
        $this->pdf->Cell(27, 5, utf8_decode('   ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(6);
        $this->pdf->SetTitle("EGRESOS DICARP");
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 50, 28);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(65, 5, ' ' . $nombre_empresa, 0, 0, 'C');
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
        $this->pdf->Cell(40, 5, 'NOTA EGRESOS', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(100, 4, $direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);

        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(41, 5, ' ' . $nombre_empresa, 0, 0, 'C');

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(100, 4, $telefono, 0, 0, 'C');

        $this->pdf->Ln(4);
        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(50, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(10);
        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(10);

        /*  TIPO INGRESO*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('TIPO EGRESO              :   ' . $tipo_ingreso . '                                                                              USUARIO : ' . $usuario), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA INGRESO  */
        $this->pdf->Cell(27, 5, utf8_decode(' FECHA DE EGRESO   :  ' . date('d/m/Y') . '                                                                                                    MONTO EGRESO  :  ' . $monto), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);


        /*  TIPO EGRESO*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('DETALLE EGRESO      :   ' . $descripcion), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA EGRESO  */
        $this->pdf->Cell(27, 5, utf8_decode(''), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(25);
        /*  ENTREGADO Y RECIBIDO POR*/
        $this->pdf->Cell(27, 5, utf8_decode('                                   -------------------------------------------------------------   ' . '                            -------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(3);
        $this->pdf->Cell(27, 5, utf8_decode('                                                       ENTREGADO                                ' . '                                                       RECIBIDO    '), ' ');
        $this->pdf->Ln(6);


        $this->pdf->Output("Egresos.pdf", 'I');

    }
}