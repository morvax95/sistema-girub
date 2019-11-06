<?php

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 01/03/2018
 * Time: 07:47 PM
 */
class Pago_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Obtiene todas las ventas a credito que no esten canceladas
    public function get_all_debts()
    {
        $this->db->select("v.id, date(v.fecha) as fecha, c.id as cliente_id, c.nombre_cliente, v.total, sum(p.monto) as total_pagado, (v.total - sum(p.monto))as saldo, p.estado,p.forma_pago,v.nota")
            ->from("cliente c, venta v, venta_pago p")
            ->where("c.id = v.cliente_id")
            ->where("v.id = p.venta_id")
            ->where("p.forma_pago = 'plazo'")
            ->where("p.estado != 'Cancelado'")
            ->group_by("v.id,c.id,p.estado,v.fecha,c.nombre_cliente,v.total");
        $result = $this->db->get()->result();
        return $result;
    }


    public function registrar_pago()
    {
        $cmd['operacion'] = "INSERCIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "PAGO";
        $this->db->insert('bitacora', $cmd);

        $response = array(
            'success' => false,
            'message' => null
        );
        $this->db->trans_start();
        $venta_id = $this->input->post('venta_id');
        $monto_pagado = $this->input->post('monto_pagar');
        $saldo = $this->input->post('saldo');
        $fecha_pago = $this->input->post('fecha_pago');
        $forma_pago = $this->input->post('forma_pago');
        $nota = $this->input->post('nota');

        $estado = '';
        if ($saldo == '0' or $saldo == 0.00) {
            $estado = 'Cancelado';
            $this->db->where('venta_id', $venta_id)
                ->update('venta_pago', array('estado' => 'Cancelado'));
        } else {
            $estado = 'Debe';
        }
        /**/
        $this->db->where('id', $venta_id)
            ->update('venta', array('nota' => $nota));
        /**/
        $deuda['venta_id'] = $venta_id;
        $deuda['forma_pago'] = 'Plazo';
        $deuda['fecha_pago'] = $fecha_pago;
        $deuda['monto'] = $monto_pagado;
        $deuda['saldo'] = $saldo;
        $deuda['estado'] = $estado;
        $deuda['fecha_registro'] = date('Y-m-d');
        $this->db->insert('venta_pago', $deuda);

        $ingreso['fecha_ingreso'] = date('Y-m-d');
        $ingreso['detalle'] = 'Ingreso por pago de deuda';
        $ingreso['monto'] = $monto_pagado;
        $ingreso['estado'] = get_state('ACTIVO');
        $ingreso['tipo_ingreso_id'] = 5; // Este tipo de ingreso de ingreso es por pago de deudas, es ingreso fijo
        $ingreso['sucursal_id'] = get_branch_id_in_session();
        $ingreso['usuario_id'] = get_user_id_in_session();
        $ingreso['fecha_registro'] = date('Y-m-d');

        $this->db->insert('ingreso_caja', $ingreso);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response['message'] = 'Error';
        } else {
            $this->db->trans_commit();
            $response['success'] = true;
        }
        return json_encode($response);
    }
}