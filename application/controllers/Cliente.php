<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cliente_model');
        $this->load->model('producto_model', 'producto');
    }

    public function index()
    {
        plantilla('cliente/index', null);
    }

    public function nuevo()
    {
        plantilla('cliente/nuevo_cliente');
    }

    public function editar()
    {
        $data['cliente'] = $this->cliente_model->get_customer_by_id($this->input->post('id'));
        plantilla('cliente/editar', $data);
    }

    public function lista_cumple()
    {
        $data['clientes'] = $this->cliente_model->get_birthday();
        plantilla('cliente/cumple', $data);
    }

    public function imprimir()
    {
        $data['clientes'] = $this->cliente_model->get_birthday();
        $sesion = $this->session->userdata('usuario_sesion');
        $data['empresa'] = $this->db->get_where('sucursal', array('id' => $sesion['id_sucursal']))->row();
        $this->load->view('cliente/imprimir', $data);
    }

    public function imprimir_clientes()
    {
        $data['clientes'] = $this->cliente_model->listar_clientes();
        $sesion = $this->session->userdata('usuario_sesion');
        $data['empresa'] = $this->db->get_where('sucursal', array('id' => $sesion['id_sucursal']))->row();
        $this->load->view('cliente/lista_clientes', $data);
    }
    //region Metodos de cliente
    /* Obtenermos a todos los clientes ***/
    public function listar_clientes()
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

            echo json_encode($this->cliente_model->listar_clientes($params));
        } else {
            show_404();
        }
    }

    /* Registramos a un nuevo cliente*/
    public function registrar_cliente()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cliente_model->registrar_cliente();
           // echo $this->cliente_model->registrar_bitacora();
        } else {
            show_404();
        }
    }
    public function modificar_cliente()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cliente_model->modificar_cliente();
        } else {
            show_404();
        }
    }

    /* Eliminamos al cliente seleccionado */
    public function eliminar()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            echo $this->cliente_model->eliminar($id);
        } else {
            show_404();
        }
    }
    public function habilitar()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id_cliente');

            $res = $this->cliente_model->habilitar($id);
            if ($res !== 1) {
                echo 'true';
            } else {
                echo 'error';
            }
        } else {
            show_404();
        }
    }

    public function get_cliente()
    {
        $dato = $this->input->post_get('name_startsWith');
        $tipo = $this->input->post_get('type');
        if ($tipo === 'ci_nit') {
            $this->db->like('ci_nit', $dato);
            $this->db->where('id >0');
            $this->db->where('estado=1');
            $this->db->distinct();
            $res = $this->db->get('cliente');

            if ($res->num_rows() > 0) {
                foreach ($res->result_array() as $row) {
                    $data[$row['ci_nit'] . '|' . $row['nombre_cliente']] = $row['nombre_cliente'] . '/' . $row['id'];
                }
                echo json_encode($data); //format the array into json data
            } else {
                $data["No existe este nit"] = "No existe este nit";
                echo json_encode($data);
            }
        } else {
            $this->db->like('lower(nombre_cliente)', strtolower($dato));
            $this->db->where('id >0');
            $this->db->where('estado=1');
            $res = $this->db->get('cliente');

            if ($res->num_rows() > 0) {
                foreach ($res->result_array() as $row) {
                    $data[$row['nombre_cliente'] . '|' . $row['ci_nit']] = $row['ci_nit'] . '/' . $row['id'];
                }
                echo json_encode($data); //format the array into json data
            } else {
                $data["No existe este nombre de cliente"] = "No existe este nombre de cliente";
                echo json_encode($data);
            }
        }

    }
    //endregion


}