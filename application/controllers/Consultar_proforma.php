<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Consultar_proforma extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('consultar_proforma_model','consulta');
        $this->load->model('venta_model','venta');
    }
    public function index()
    {
        plantilla('consultar_proforma/index', null);
    }
    public function get_all()
    {
        if ($this->input->is_ajax_request())
        {
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

    public function anular_venta()
    {
        if ($this->input->is_ajax_request()){
            $id = $this->input->post('id');
            echo $this->consulta->anular_venta($id);
        }else{
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

    /*********** Reimpresion de la factura **************/
    public function imprimirCopiaFactura()
    {
        $id = $this->uri->segment(3);
        $respuesta = $this->model_venta->imprimirFactura($id);
        $datosImpresion['datos_factura'] = $respuesta['datos_factura'];
        $datosImpresion['datos_venta_detalle'] = $respuesta['datos_venta_detalle'];
        $datosImpresion['qr_image'] = $respuesta['qr_image'];

        $this->load->view('consultar_venta/copia_factura',$datosImpresion);
    }

    public function imprimir_nota_venta()
    {
        $id = $this->uri->segment(3);
        $respuesta = $this->venta->imprimir_nota_venta($id);
        $this->load->view('consultar_venta/copia_nota',$respuesta);
    }

}