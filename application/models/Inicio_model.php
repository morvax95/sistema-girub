<?php

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 23/02/2018
 * Time: 01:30 PM
 */
class Inicio_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function obtener_avisos()
    {

        $this->load->model('cliente_model', 'cliente');
        $data['clientes'] = $this->cliente->get_birthday();
        $data['pagos'] = $this->cliente->deudas_por_cobrar();
        $data['ventas_mes'] = $this->cliente->ventas_mes();
        //  $data['ventas'] = $this->cliente->ventas_en_proceso();
        //$data['productos'] = $this->cliente->producto_en_produccion();
        return $data;
    }

    public function cargar_menu($usuario_id, $cargo)
    {

        if ($cargo != 1) {
            $this->db->select('m.*')
                ->from('menu m, acceso a, usuario u')
                ->where('m.id = a.menu_id')
                ->where('a.usuario_id = u.id')
                ->where('u.id', $usuario_id)
                ->order_by('m.id', 'ASC');
            return $this->db->get()->result_array();
        } else {
            return $this->db->get('menu')->result_array();
        }
    }

    public function verificar($id)
    {
        $res = $this->db->get_where('usuario', array('id' => $id));
        return $res->row();
    }

    public function confirma_cambio($idUsuario, $claveNueva)
    {
        $claveEncriptada = password_hash($claveNueva, PASSWORD_BCRYPT);
        $datos = array(
            'clave' => $claveEncriptada
        );
        $this->db->where('id', $idUsuario);
        $res = $this->db->update('usuario', $datos);
        return $res;
    }
}