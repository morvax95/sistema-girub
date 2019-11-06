<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 15/02/2018
 * Time: 11:00 AM
 */
class Sucursal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sucursal_model', 'sucursal');
    }

    public function index()
    {
        plantilla('sucursal/index', null);
    }



    public function editar()
    {
        $data['cliente'] = $this->sucursal->get_by_id($this->input->post('id'));
        plantilla('sucursal/editar', $data);
    }

    public function listar_sucursal()
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

            echo json_encode($this->sucursal->listar_sucursal($params));
        } else {
            show_404();
        }
    }


    public function modificar_sucursal()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->sucursal->modificar_sucursal();
        } else {
            show_404();
        }
    }


}