<?php

/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 23/03/2018
 * Time: 03:20 PM
 */
class Proveedor_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
//        $this->load->library('form_validation');
    }

    public function get_all()
    {
        if ($this->input->post('draw')) {
            $this->db->start_cache();
            $this->db->select('*')
                ->from('cliente c')
                ->where('c.id > 2');
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
                $this->db->like('c.ci_nit', $params['search']);
                $this->db->or_like('lower(c.nombre_cliente)', $params['search']);
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
        } else {
            $json_data = $this->db->get_where('proveedor', array('estado' => get_state('ACTIVO')))->result();
        }
        return $json_data;
    }

    // Obtiene todas los proveedores
    public function get_provider()
    {
        return $this->db->get('proveedor')->result();
    }
}