<?php

class Reporte_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //deudas por cobrar
    public function get_deudas_por_cobrar($codigo)
    {
        $this->db->select("*");
        $this->db->from("deudas_por_cobrar");
        if (!empty($codigo)) {
            $this->db->where("id =", $codigo);//
        }
        $resultado['datos'] = $this->db->get()->result();
        return $resultado;
    }

    public function get_deudas($fecha_inicio, $fecha_fin, $sucursal)
    {
        $this->db->select("v.id, date(v.fecha) as fecha, c.id as cliente_id, c.nombre_cliente, v.total, sum(p.monto) as total_pagado, (v.total - sum(p.monto))as saldo, p.estado,u.nombre_usuario as usuario");
        $this->db->from("cliente c, venta v, venta_pago p,sucursal s,usuario u")
            ->where("c.id = v.cliente_id")
            ->where("v.id = p.venta_id")
            ->where("v.sucursal_id = s.id")
            ->where("v.usuario_id = u.id")
            ->where("p.forma_pago = 'plazo'")
            ->where("p.estado != 'Cancelado'")
            ->where("s.id=", $sucursal)
            ->where("v.fecha>=", $fecha_inicio)
            ->where("v.fecha<=", $fecha_fin)
            ->group_by("v.id,c.id,p.estado,v.fecha,c.nombre_cliente,v.total");

        $resultado['datos'] = $this->db->get()->result();
        return $resultado;

    }

    public function getNitEmpresa()
    {
        return $this->db->get('sucursal')->row();
    }

    public function get_ventas_emitidas($fecha_inicio, $fecha_fin, $sucursal)
    {
        $this->db->select("v.id as codigo_venta,v.total,n.nro_nota,date(v.fecha) as fecha,cl.nombre_cliente,dt.cantidad,s.sucursal,vp.forma_pago,u.nombre_usuario as usuario");
        $this->db->from("detalle_venta dt,venta v,cliente cl,sucursal s,nota_venta n,venta_pago vp,usuario u");
        $this->db->where("dt.venta_id=v.id");
        $this->db->where("cl.id=v.cliente_id");
        $this->db->where("s.id=v.sucursal_id");
        $this->db->where("n.venta_id=v.id ");
        $this->db->where("v.id=vp.venta_id");
        $this->db->where("u.id=v.usuario_id");
        $this->db->group_by("v.id");
        if (!empty($fecha_inicio) && !empty($fecha_fin) && !empty($sucursal)) {
            $this->db->where("v.fecha >='" . $fecha_inicio . "' and v.fecha <='" . $fecha_fin . "' and v.sucursal_id='" . $sucursal . "'");
        }
        $resultado['datos'] = $this->db->get()->result();
        return $resultado;
    }

    public function get_ventas__montos_emitidas($fecha_inicio, $fecha_fin, $sucursal)
    {
        $this->db->select("sum(v.total) as monto");
        $this->db->from("venta v,venta_pago vp");
        $this->db->where("v.id=vp.venta_id");
        if (!empty($fecha_inicio) && !empty($fecha_fin) && !empty($sucursal)) {
            $this->db->where("v.fecha >='" . $fecha_inicio . "' and v.fecha <='" . $fecha_fin . "' and v.sucursal_id='" . $sucursal . "'");
        }
        $resultado['datos_montos'] = $this->db->get()->result();
        return $resultado;
    }

    public function get_ventas_diarias($fecha_inicio, $sucursal)
    {
        $this->db->select("n.nro_nota,v.fecha,cl.nombre_cliente,cl.ci_nit,pr.nombre_item as producto,dt.cantidad,s.sucursal,pr.precio_venta,v.total");
        $this->db->from("detalle_venta dt,producto pr,venta v,cliente cl,sucursal s,nota_venta n");
        $this->db->where("dt.producto_id=pr.id");
        $this->db->where("dt.venta_id=v.id");
        $this->db->where("cl.id=v.cliente_id");
        $this->db->where("s.id=v.sucursal_id");
        $this->db->where("n.venta_id=v.id ");

        if (!empty($fecha_inicio) && !empty($sucursal)) {
            $this->db->where("v.fecha ='" . $fecha_inicio . "' and v.sucursal_id='" . $sucursal . "'");
        }

        $resultado['datos'] = $this->db->get()->result();

        return $resultado;
    }

    public function vacio($data)
    {
        if (empty($data)) {
            $data = '%';
        }
        return $data;
    }

    public function get_ventas($inicio, $fin)
    {
        $this->db->select("v.id, to_char(v.fecha,'dd/mm/yyyy')as fecha, v.hora, n.nro_nota, v.subtotal, v.descuento, v.total, v.tipo_venta,
      v.estado, c.ci_nit, c.nombre_cliente, u.nombre_usuario, s.sucursal");
        $this->db->from("cliente c, venta v, nota_venta n, usuario u, sucursal s");
        $this->db->where("c.id = v.cliente_id");
        $this->db->where("v.id = n.venta_id");
        $this->db->where("v.usuario_id = u.id");
        $this->db->where("v.sucursal_id = s.id");
        $this->db->where("v.fecha BETWEEN '" . $inicio . "' and '" . $fin . "'");

        $resultado['datos'] = $this->db->get()->result();

        $this->db->select("SUM(v.total) total");
        $this->db->from("cliente c, venta v");
        $this->db->where("c.id = v.cliente_id");
        $this->db->where("v.fecha BETWEEN '" . $inicio . "' and '" . $fin . "'");
        $resultado['total'] = $this->db->get()->row();
        return $resultado;
    }

    //clientes
    public function get_clientes($fecha_inicio, $fecha_fin)
    {

        $this->db->select("c.nombre_cliente,c.telefono,c.ci_nit,c.correo,date(c.fecha_registro) as fechar,u.nombre_usuario as usuario,c.id");
        $this->db->from("cliente c,usuario u");
        $this->db->where("c.usuario_id=u.id");
        $this->db->order_by("c.nombre_cliente asc");
        if (!empty($fecha_inicio)) {
            $this->db->where("c.fecha_registro >=", $fecha_inicio);
        }
        if (!empty($fecha_fin)) {
            $this->db->where('c.fecha_registro <=', $fecha_fin);
        }
        $resultado['datos'] = $this->db->get()->result();

        return $resultado;
    }

    public function get_count_clientes($fecha_inicio, $fecha_fin)
    {
        $this->db->select("count(c.id) as id");
        $this->db->from("cliente c");
        if (!empty($fecha_inicio)) {
            $this->db->where("c.fecha_registro >=", $fecha_inicio);
        }
        if (!empty($fecha_fin)) {
            $this->db->where('c.fecha_registro <=', $fecha_fin);
        }
        $resultado['cantidad_datos'] = $this->db->get()->result();

        return $resultado;
    }


    public function reporte_inventario($id_sucursal)
    {
        $this->db->select("*,CASE WHEN (cantidad <= stock_minimo) THEN 'STOCK MINIMO' WHEN (cantidad = 0) THEN 'AGOTADO'  ELSE 'DISPONIBLE'
        END as estado_inventario")
            ->from("inventario")
            ->where('id_sucursal', $id_sucursal);
        $json_data = $this->db->get()->result();
        return $json_data;

    }


}