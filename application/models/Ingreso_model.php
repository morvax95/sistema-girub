<?php

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 21/03/2018
 * Time: 03:06 AM
 */
class Ingreso_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }


    public function registrar_tipo_ingreso()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $this->form_validation->set_rules('descripcion', 'descripcion', 'trim|required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        if ($this->form_validation->run() === true) {
            $nombre = mb_strtoupper($this->input->post('descripcion'), "UTF-8");
            $tipo_egreso = array(
                'descripcion' => $nombre,
                'estado' => get_state('ACTIVO'),
                'tipo_dato' => 1    // egreso = 0
            );
            $response['success'] = $this->db->insert('tipo_ingreso_egreso', $tipo_egreso);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }


    public function registro_ingreso()
    {
        $cmd['operacion'] = "INSERCIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "INGRESO";
        $this->db->insert('bitacora', $cmd);

        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $this->form_validation->set_rules('fecha_ingreso', 'fecha de ingreso', 'trim|required');
        $this->form_validation->set_rules('monto', 'monto', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        if ($this->form_validation->run() === true) {
            $ingreso['fecha_registro'] = date('Y-m-d');
            $ingreso['fecha_ingreso'] = $this->input->post('fecha_ingreso');
            $ingreso['detalle'] = $this->input->post('detalle');
            $ingreso['monto'] = $this->input->post('monto');
            $ingreso['estado'] = get_state('ACTIVO');
            $ingreso['tipo_ingreso_id'] = $this->input->post('tipo_ingreso');
            $ingreso['sucursal_id'] = get_branch_id_in_session();
            $ingreso['usuario_id'] = get_user_id_in_session();


            $response['success'] = $this->db->insert('ingreso_caja', $ingreso);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }


    public function modificar()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $this->form_validation->set_rules('fecha_ingreso', 'fecha de ingreso', 'trim|required');
        $this->form_validation->set_rules('monto', 'monto', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        if ($this->form_validation->run() === true) {
            $id_egreso = $this->input->post('id_ingreso');
            $egreso['fecha_ingreso'] = $this->input->post('fecha_ingreso');
            $egreso['detalle'] = $this->input->post('detalle');
            $egreso['monto'] = $this->input->post('monto');
            $egreso['tipo_ingreso_id'] = $this->input->post('tipo_ingreso');

            $this->db->where('id', $id_egreso);
            $response['success'] = $this->db->update('ingreso_caja', $egreso);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }


    public function eliminar($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('ingreso_caja', array('estado' => get_state('INACTIVO')));
    }

    public function get_all($params = array())
    {
        $this->db->start_cache();
        $this->db->select('e.id, date (e.fecha_ingreso) as fecha_ingreso, e.monto, s.sucursal, e.estado, t.id as tipo_ingreso, t.descripcion')
            ->from('ingreso_caja e, tipo_ingreso_egreso t, sucursal s')
            ->where('e.tipo_ingreso_id = t.id')
            ->where('e.sucursal_id = s.id')
            ->where('t.tipo_dato', 1)
            ->where('e.estado', get_state('ACTIVO'));
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
            $this->db->order_by("e.{$params['column']}", $params['order']);
        } else {
            $this->db->order_by('e.id', 'ASC');
        }

        if (array_key_exists('search', $params) && !empty($params['search'])) {
            $this->db->like('lower(t.descripcion)', $params['search']);
            $this->db->or_like('lower(s.sucursal)', $params['search']);
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
    }

    public function get_income_by_id($id)
    {
        return $this->db->get_where('ingreso_caja', array('id' => $id))->row();
    }

    public function get_type_of_income()
    {
        return $this->db->get_where('tipo_ingreso_egreso', array('tipo_dato' => 1))->result();
    }

    //para imprimir el pdf de la nota de ingresos
    public function nota_ingresos($codigo)
    {
        $this->db->select("e.id, e.fecha_ingreso, e.monto, s.sucursal, e.estado, t.descripcion as tipo_ingreso, t.descripcion,s.nombre_empresa,s.sucursal,s.direccion,s.telefono,u.nombre_usuario as usuario");
        $this->db->from("ingreso_caja e, tipo_ingreso_egreso t, sucursal s,usuario u");
        $this->db->where("e.tipo_ingreso_id = t.id");
        $this->db->where("e.sucursal_id = s.id");
        $this->db->where("e.usuario_id = u.id");

        if (!empty($codigo)) {
            $this->db->where("e.id ='" . $codigo . "'");

        }

        $resultado['datos'] = $this->db->get()->result();

        return $resultado;
    }

}