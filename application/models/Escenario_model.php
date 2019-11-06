<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escenario_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function get_all()
    {
        return $this->db->get('tipo_escenario')->result();
    }
    // Obtiene los datos de un cliente seleccionado por su id
    public function get_customer_by_id($id)
    {
        return $this->db->get_where('escenario', array('id' => $id))->row();
    }
    // Obtener datos de todos los clientes
    public function listar_clientes($params = array())
    {
        if (!empty($this->input->post('draw'))) {

            $this->db->start_cache();
            $this->db->select('t.nombre as tipo,c.descripcion,c.nombre_escenario,c.id,c.estado,c.numeroJugadores')
                ->from('escenario c,tipo_escenario t')
                ->where('c.tipo_escenario_id=t.id')
                ->where('c.id > 0')
                ->group_by('c.id');
            $this->db->stop_cache();

            // Obtener la cantidad de registros NO filtrados.
            // Query builder se mantiene ya que se tiene almacenada la estructura de la consulta en memoria
            $records_total = count($this->db->get()->result_array());

            // Concatenar parametros enviados (solo si existen)
            if (array_key_exists('start', $params) && array_key_exists('limit', $params)) {
                $this->db->limit($params['limit']);
                $this->db->offset($params['start']);
            }

            if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
                $this->db->order_by("c.{$params['column']}", $params['order']);
            } else {
                $this->db->order_by('c.id', 'ASC');
            }

            if (array_key_exists('search', $params) && !empty($params['search'])) {
                $this->db->like('c.id', $params['search']);
                $this->db->or_like('lower(c.nombre_escenario)', $params['search']);
            }

            // Obtencion de resultados finales
            $draw = $this->input->post('draw');
            $data = $this->db->get()->result_array();

            $json_data = array(
                'draw' => intval($draw),
                'recordsTotal' => $records_total,
                'recordsFiltered' => $records_total,
                'data' => $data,
            );
            return $json_data;
        } else {
            return $this->db->get_where('escenario', array('estado' => get_state('ACTIVO')))->result();
        }
    }
    // Registro de cliente
    public function registrar_cliente()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        $this->form_validation->set_rules('nombre_escenario', 'nombre', 'trim|required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_cliente['nombre_escenario'] = mb_strtoupper($this->input->post('nombre_escenario'), "UTF-8");
            $obj_cliente['descripcion'] = mb_strtoupper($this->input->post('descripcion'), "UTF-8");
            $obj_cliente['numeroJugadores'] = mb_strtoupper($this->input->post('cantidad'), "UTF-8");
            $obj_cliente['tipo_escenario_id'] = mb_strtoupper($this->input->post('tipo_escenario'), "UTF-8");
            $obj_cliente['estado'] = 1;// get_state('ACTIVO');
            $obj_cliente['fecha_registro'] = date('Y-m-d H:i:s');


            // Inicio de transacción
            $this->db->trans_begin();
            $this->db->insert('escenario', $obj_cliente);
            $last_customer_id = $this->db->insert_id();

            // Obtener resultado de transacción
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $response['success'] = TRUE;
                $response['customer'] = $this->get_customer_by_id($last_customer_id);
            } else {
                $this->db->trans_rollback();
                $response['success'] = FALSE;
            }

        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }

    // Modificacion de cliente correcto
    public function modificar_cliente()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $id_cliente = $this->input->post('id_cliente');
        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        $this->form_validation->set_rules('nombre_escenario', 'nombre', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_cliente['nombre_escenario'] = mb_strtoupper($this->input->post('nombre_escenario'), "UTF-8");
            $obj_cliente['descripcion'] = mb_strtoupper($this->input->post('descripcion'), "UTF-8");
            $obj_cliente['numeroJugadores'] = mb_strtoupper($this->input->post('cantidad'), "UTF-8");
            //$obj_cliente['tipo_escenario_id'] = 1;
            $obj_cliente['estado'] = 1;
            $obj_cliente['fecha_modificacion'] = date('Y-m-d H:i:s');

            $this->db->where('id', $id_cliente);
            $response['success'] = $this->db->update('escenario', $obj_cliente);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }

    // Cambia el estado del registro (prueba correcta)
    public function eliminar($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('escenario', array('estado' => get_state('INACTIVO')));
    }

    // Cambia el estado del registro a activo (prueba correcta)
    public function habilitar($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('escenario', array('estado' => get_state('ACTIVO')));
    }


}