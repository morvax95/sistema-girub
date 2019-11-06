<?php

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 21/03/2017
 * Time: 10:10 AM
 */
class Caja_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function registrar_caja()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        $this->form_validation->set_rules('descripcion', 'descripcion', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            $caja['descripcion'] = $this->input->post('descripcion');
            $caja['estado'] = get_state('ACTIVO');
            $response['success'] = $this->db->insert('caja', $caja);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return $response;
    }

    public function modificar_caja()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        $this->form_validation->set_rules('descripcion_edita', 'descripcion', 'trim|required|is_unique[caja.descripcion]');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            $descripcion = $this->input->post('descripcion_edita');
            $id_caja = $this->input->post('id_caja');

            $this->db->where('id', $id_caja);
            $response['success'] = $this->db->update('caja', array('descripcion' => $descripcion));
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return $response;
    }

    public function eliminar()
    {
        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('caja', array('estado' => get_state('INACTIVO')));
    }

    public function get_all()
    {
//        $this->db->select("c.id, c.descripcion, c.estado, s.sucursal")
//            ->from("caja c, sucursal s")
//            ->where("c.sucursal_id = s.id");
//        return $this->db->get()->result();
        return $this->db->get('caja')->result();
    }

    public function get_box_by_id()
    {
        $id_caja = $this->input->post('id');
        return $this->db->get_where('caja', array('id' => $id_caja))->row();

    }

    public function set_caja()
    {
        $caja['caja_id'] = $this->input->post('caja');
        $caja['sucursal_id'] = get_branch_id_in_session();
        $caja['usuario_id'] = get_user_id_in_session();
        $caja['fecha_apertura'] = date('Y-m-d');
        $caja['monto_apertura'] = $this->input->post('monto_caja');
        $caja['estado'] = 'APERTURA';
        $result['success'] = $this->db->insert('detalle_caja', $caja);
        return json_encode($result);
    }

    public function get_available_boxes()
    {
        $this->db->select('ca.id, ca.descripcion');
        $this->db->from('caja ca');
        $this->db->where("ca.id NOT IN (SELECT d.caja_id FROM detalle_caja d, caja c where d.caja_id = c.id and c.sucursal_id = " . get_branch_id_in_session() . ")");
        $resultado = $this->db->get()->result();
        return $resultado;
    }

    public function verficar_caja_aperturada()
    {
        $response = array(
            'bool' => false,
            'data' => null
        );
        $result = $this->db->select('c.id, c.descripcion')
            ->from('caja c, detalle_caja d')
            ->where('c.id = d.caja_id')
            ->where('d.fecha_cierre IS NULL')
            ->where('d.monto_cierre IS NULL')
            ->where('monto_cierre', null)
            ->where('d.sucursal_id', get_branch_id_in_session())
            ->get()->row();
        if (count($result) > 0) {
            $response['bool'] = true;
            $response['data'] = $result;
        } else {
            $response['bool'] = false;
        }
        return json_encode($response);
    }

    public function get_registros_caja(){

        $this->db->trans_start();
        $sucursal_id = get_branch_id_in_session();
        $resultado['sucursal'] = $this->db->get_where('sucursal',array('id' => $sucursal_id))->row();

        $this->db->select('d.id, c.descripcion, d.fecha_apertura')
            ->from('caja c, detalle_caja d')
            ->where('c.id = d.caja_id')
            ->where('d.fecha_cierre ISNULL')
            ->where('d.monto_cierre ISNULL')
            ->where('monto_cierre', null)
            ->where('d.sucursal_id', $sucursal_id);
        $resultado['caja'] = $this->db->get()->row();
        $detalle_caja_id = $resultado['caja']->id;
        $fecha_apertura = $resultado['caja']->fecha_apertura;
        $fecha_cierre = date('Y-m-d');

        $this->db->select("v.id, to_char(v.fecha,'dd/mm/yyyy')as fecha, n.nro_nota,v.total, v.descuento ,v.subtotal,v.estado, c.ci_nit,
                            c. nombre_cliente, u.nombre_usuario, u.nombre_usuario, s.sucursal");
        $this->db->from("cliente c, venta v, nota_venta n, usuario u, sucursal s");
        $this->db->where("c.id = v.cliente_id");
        $this->db->where(" v.id = n.venta_id");
        $this->db->where("v.usuario_id = u.id");
        $this->db->where("v.sucursal_id = s.id");
        $this->db->where("s.id",$sucursal_id);
        $this->db->where("v.fecha BETWEEN '".$fecha_apertura."' and '".$fecha_cierre."'");
        $this->db->order_by('v.id','ASC');
        $resultado['datos'] = $this->db->get()->result();


        $this->db->select("sum(v.total) as total");
        $this->db->from("cliente c, venta v, nota_venta n, usuario u, sucursal s");
        $this->db->where("c.id = v.cliente_id");
        $this->db->where(" v.id = n.venta_id");
        $this->db->where("v.usuario_id = u.id");
        $this->db->where("v.sucursal_id = s.id");
        $this->db->where("s.id",$sucursal_id);
        $this->db->where("v.fecha BETWEEN '".$fecha_apertura."' and '".$fecha_cierre."'");
        $resultado['total_caja'] = $this->db->get()->row();


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $resultado;
        }
    }

    public function registrar_cierre(){
        $result = false;
        try {
            $total = $this->input->post('total');
            $caja_id = $this->input->post('caja');
            $fecha_cierre = date('Y-m-d');

            // Cerramos la caja
            $this->db->where('id', $caja_id);
            $this->db->update('detalle_caja', array('fecha_cierre' => $fecha_cierre, 'monto_cierre' => $total, 'estado' => 'CERRADO'));
            $result = true;
        } catch (Exception $e) {
            $result = $e;
        }
        return $result;
    }
}