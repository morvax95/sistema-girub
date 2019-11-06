<?php

/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 23/03/2018
 * Time: 02:49 AM
 */
class Ingreso extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ingreso_model');
    }


    //region Vistas de ingreso

    public function index()
    {
        plantilla('ingreso/index');
    }

    public function nuevo()
    {
        $data['tipo_ingreso'] = $this->ingreso_model->get_type_of_income();
        plantilla('ingreso/nuevo', $data);
    }

    public function editar()
    {
        $data['tipo_ingreso'] = $this->ingreso_model->get_type_of_income();
        $data['ingreso'] = $this->ingreso_model->get_income_by_id($this->input->post('id'));
        plantilla('ingreso/editar', $data);
    }

    //endregion de tipo


    //region Metodos para el ingreso de caja

    public function registro_ingreso()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->ingreso_model->registro_ingreso();
        } else {
            show_404();
        }
    }

    public function modificar()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->ingreso_model->modificar();
        } else {
            show_404();
        }
    }

    public function eliminar()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->ingreso_model->eliminar($this->input->post('id'));
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

            echo json_encode($this->ingreso_model->get_all($params));
        } else {
            show_404();
        }
    }

    //endregion


    //region Metodos de Tipo ingreso de caja

    public function registrar_tipo_ingreso()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->ingreso_model->registrar_tipo_ingreso();
        } else {
            show_404();
        }
    }


    public function get_type_of_income()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->ingreso_model->get_type_of_income());
        } else {
            show_404();
        }
    }
    //endregion

    /*** Imprimir  en pdf nota de ingreso ****/
    public function imprimir_ingreso_seleccionado()
    {
        // $codigo = $this->input->post('codigo');
        $codigo = $this->uri->segment(3);
        $datos = $this->ingreso_model->nota_ingresos($codigo);

        $lista_compras = $datos['datos'];

        foreach ($lista_compras as $row_detalle) {

            $monto = $row_detalle->monto;
            $tipo_ingreso = $row_detalle->tipo_ingreso;
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

        $this->pdf->SetTitle("INGRESOS GIRUB");
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 50, 28);

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
        $this->pdf->Cell(40, 5, 'NOTA INGRESOS', 0, 0, 'C');
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
        $this->pdf->Cell(100, 4, 'Telf. 9302099 - 70838701
', 0, 0, 'C');

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
        $this->pdf->Cell(27, 5, utf8_decode('TIPO INGRESO              :   ' . $tipo_ingreso . '                                                                                 USUARIO : ' . $usuario), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA INGRESO  */
        $this->pdf->Cell(27, 5, utf8_decode(' FECHA DE INGRESO   :  ' . date('d/m/Y') . '                                                                                                    MONTO INGRESO  :  ' . $monto), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);


        /*  TIPO INGRESO*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('DETALLE INGRESO      :   ' . $descripcion), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA INGRESO  */
        $this->pdf->Cell(27, 5, utf8_decode(''), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(35);
        /*  ENTREGADO Y RECIBIDO POR*/
        $this->pdf->Cell(27, 5, utf8_decode('                                   -------------------------------------------------------------   ' . '                            -------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(3);
        $this->pdf->Cell(27, 5, utf8_decode('                                                       ENTREGADO                                ' . '                                                       RECIBIDO    '), ' ');
        $this->pdf->Ln(25);
        $this->pdf->Cell(27, 5, utf8_decode('   ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  '), ' ');

        $this->pdf->Ln(6);

        $this->pdf->SetTitle("INGRESOS " . $nombre_empresa);
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 50, 28);

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
        $this->pdf->Cell(40, 5, 'NOTA INGRESOS', 0, 0, 'C');
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
        $this->pdf->Cell(100, 4, 'Telf. ' . $telefono, 0, 0, 'C');
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
        $this->pdf->Cell(27, 5, utf8_decode('TIPO INGRESO              :   ' . $tipo_ingreso . '                                                                                 USUARIO : ' . $usuario), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA INGRESO  */
        $this->pdf->Cell(27, 5, utf8_decode(' FECHA DE INGRESO   :  ' . date('d/m/Y') . '                                                                                                    MONTO INGRESO  :  ' . $monto), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);


        /*  TIPO INGRESO*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('DETALLE INGRESO      :   ' . $descripcion), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA INGRESO  */
        $this->pdf->Cell(27, 5, utf8_decode(''), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(35);
        /*  ENTREGADO Y RECIBIDO POR*/
        $this->pdf->Cell(27, 5, utf8_decode('                                   -------------------------------------------------------------   ' . '                            -------------------------------------------------------------  '), ' ');
        $this->pdf->Ln(3);
        $this->pdf->Cell(27, 5, utf8_decode('                                                       ENTREGADO                                ' . '                                                       RECIBIDO    '), ' ');


        $this->pdf->Output("Ingresos.pdf", 'I');

    }
}