<?php

class Historial_pago_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function cambiar_estado_produccion($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('solicitud', array('estado_producto' => 1));
    }

    public function get_all_items($params = array())
    {
        // Si se envia como parametro "draw", la peticion se esta realizando desde datatables
        if (!empty($this->input->post('draw'))) {

            $this->db->start_cache();
            $this->db->select("vp.id as codigo_pago,vp.forma_pago,vp.fecha_pago,c.nombre_cliente,vp.estado,v.id,v.total");
            $this->db->from('venta_pago vp,venta v,cliente c');
            $this->db->where('v.id=vp.venta_id');
            $this->db->where('c.id=v.cliente_id');
            $this->db->where('v.sucursal_id', get_branch_id_in_session());
            $this->db->group_by('v.id');
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
                $this->db->order_by("{$params['column']}", $params['order']);
            } else {
                $this->db->order_by('vp.id', 'DESC');
            }

            if (array_key_exists('search', $params) && !empty($params['search'])) {
                $this->db->group_start();
                $this->db->like('lower(c.nombre_cliente)', strtolower($params['search']));
//                $this->db->or_like('fecha', $params['search']);
                $this->db->or_like('c.ci_nit', $params['search']);
                $this->db->group_end();
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
    }
    //obtiene los datos del  detalle
    public function get_data_by_id($id)
    {
        $this->db->select("vp.id,vp.forma_pago,vp.monto,vp.saldo,vp.estado,vp.fecha_pago,c.nombre_cliente,c.ci_nit,v.id as codigo_venta,v.total")
            ->from("venta_pago vp,venta v,cliente c")
            ->where("v.id=vp.venta_id")
            ->where("c.id=v.cliente_id")
            ->where("v.id", $id);

        $data['datos_cliente'] = $this->db->get()->row();
        // Detalle
        $this->db->select("vp.id,vp.forma_pago,vp.monto,vp.saldo,vp.estado,vp.fecha_pago,c.nombre_cliente,c.ci_nit,v.id as codigo_venta,v.total")
            ->from("venta_pago vp,venta v,cliente c")
            ->where("v.id=vp.venta_id")
            ->where("c.id=v.cliente_id")
            ->where("vp.venta_id", $id);
        $data['detalle'] = $this->db->get()->result();
        return $data;
    }

    // Cambia el estado solcitud a entregado
    public function cambiar_estado_solicitud($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('solicitud', array('estado_solicitud' => 2));
    }

    //para imprimir el pdf de la solicitud entregada
    public function nota_entrega($codigo)
    {
        $this->db->select("vp.id,vp.forma_pago,vp.monto,vp.saldo,vp.estado,vp.fecha_pago,c.nombre_cliente,c.ci_nit,v.id as codigo_venta,v.total");
        $this->db->from("venta_pago vp,venta v,cliente c");
        $this->db->where("v.id=vp.venta_id");
        $this->db->where("c.id=v.cliente_id");
        if (!empty($codigo)) {
            $this->db->where("vp.venta_id ='" . $codigo . "'");

        }

        $resultado['datos'] = $this->db->get()->result();

        return $resultado;
    }

    //consulta para la notificacinon
    public function solicitud_en_proceso()
    {
        $this->db->select("count(id) as codigo")
            ->from("solicitud")
            ->where("estado_solicitud=1")
            ->group_by("id");
        $result = $this->db->get()->result();
        return $result;
    }


}