<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escenario extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('escenario_model');

    }

    public function index()
    {
        plantilla('escenario/index', null);
    }

    public function nuevo()
    {
        $data['tipo'] = $this->escenario_model->get_all();
        plantilla('escenario/nuevo_escenario',$data);
    }

    public function editar()
    {
        $data['escenario'] = $this->escenario_model->get_customer_by_id($this->input->post('id'));
        plantilla('escenario/editar', $data);
    }
    //region Metodos de cliente

    /* Obtenermos a todos los escenarios ***/
    public function listar_escenario()
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

            echo json_encode($this->escenario_model->listar_clientes($params));
        } else {
            show_404();
        }
    }

    /* Registramos a un nuevo cliente*/
    public function registrar_escenario()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->escenario_model->registrar_cliente();
        } else {
            show_404();
        }
    }


    public function modificar_cliente()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->escenario_model->modificar_cliente();
        } else {
            show_404();
        }
    }

    /* Eliminamos al cliente seleccionado */
    public function eliminar()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            echo $this->escenario_model->eliminar($id);
        } else {
            show_404();
        }
    }

    public function habilitar()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id_cliente');

            $res = $this->escenario_model->habilitar($id);
            if ($res !== 1) {
                echo 'true';
            } else {
                echo 'error';
            }
        } else {
            show_404();
        }
    }

}