<?php

class Flujo_caja_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function obtener_ingresos($fecha_inicio,$fecha_fin)
    {
        $datos_ingreso = [];
        $lista_tipos_ingreso = $this->obtener_tipo_ingresos();
        foreach ($lista_tipos_ingreso as $tipo_ingreso):
            $row_tipo_ingreso = [];
            $nombre_tipo_ingreso = ucfirst(strtolower($tipo_ingreso->descripcion));
            $tipo_ingreso_id = $tipo_ingreso->id;
            $monto_total_tipo_ingreso = $this->obtener_monto_total_ingreso($tipo_ingreso_id, $fecha_inicio, $fecha_fin);
            $row_tipo_ingreso['nombre'] = $nombre_tipo_ingreso;
            $row_tipo_ingreso['monto_total'] = $monto_total_tipo_ingreso;
            $datos_ingreso[] = $row_tipo_ingreso;
        endforeach;
        return $datos_ingreso;
    }


    public function obtener_egresos($fecha_inicio, $fecha_fin)
    {
        $datos_egreso = [];
        $lista_tipos_egreso = $this->obtener_tipo_egresos();
        foreach ($lista_tipos_egreso as $tipo_egreso):
            $row_tipo_egreso = [];
            $nombre_tipo_egreso = ucfirst(strtolower($tipo_egreso->descripcion));
            $tipo_egreso_id = $tipo_egreso->id;
            $monto_total_tipo_egreso = $this->obtener_monto_total_egreso($tipo_egreso_id,$fecha_inicio, $fecha_fin);
            $row_tipo_egreso['nombre'] = $nombre_tipo_egreso;
            $row_tipo_egreso['monto_total'] = $monto_total_tipo_egreso;
            $datos_egreso[] = $row_tipo_egreso;
        endforeach;
        return $datos_egreso;
    }

    /*  obtiene el listado de todos los ingresos*/
    public function obtener_tipo_ingresos()
    {
        $sql = "SELECT * FROM tipo_ingreso_egreso tie WHERE tie.tipo_dato = 1 AND tie.estado != 0";
        $result = $this->db->query($sql);
        return $result->result();
    }

    /*  obtiene el listado de todos los egresos*/
    public function obtener_tipo_egresos()
    {
        $sql = "SELECT * FROM tipo_ingreso_egreso tie WHERE tie.tipo_dato = 0 AND tie.estado != 0";
        $result = $this->db->query($sql);
        return $result->result();
    }

    /*  obeiene el monto total de ingresos  */
    public function obtener_monto_total_ingreso($tipo_ingreso_id, $fecha_inicio, $fecha_fin)
    {
     //   $this->db->select("COALESCE(SUM(trunc(ing.monto)),0) as monto_total")
        $this->db->select("COALESCE(SUM((ing.monto)),0) as monto_total")
            ->from("ingreso_caja ing")
            ->where("ing.tipo_ingreso_id",$tipo_ingreso_id)
            ->where("ing.fecha_registro >=",$fecha_inicio)
            ->where("ing.fecha_registro <=",$fecha_fin)
            ->where("ing.estado !=",get_state('INACTIVO'));
        $result = $this->db->get();
        return $result->row()->monto_total;
    }

    /*  obeiene el monto total de egresos  */
    public function obtener_monto_total_egreso($tipo_egreso_id, $fecha_inicio, $fecha_fin)
    {
      //  $this->db->select("COALESCE(SUM(trunc(egr.monto)),0) as monto_total")
        $this->db->select("COALESCE(SUM((egr.monto)),0) as monto_total")
            ->from("egreso_caja egr")
            ->where("egr.tipo_egreso_id",$tipo_egreso_id)
            ->where("egr.fecha_registro >=",$fecha_inicio)
            ->where("egr.fecha_registro <=",$fecha_fin)
            ->where("egr.estado !=",get_state('INACTIVO'));
        $result = $this->db->get();
        return $result->row()->monto_total;
    }

    /*  Retorna el monto total de ingreso  */
    public function total_ingreso($fecha_inicio,$fecha_fin)
    {
        $monto_ingreso_total = 0;
        $ingresos = $this->obtener_ingresos($fecha_inicio,$fecha_fin);
        foreach ($ingresos as $ingreso):
            $monto_ingreso_total = $monto_ingreso_total + $ingreso["monto_total"];
        endforeach;
        return round($monto_ingreso_total,2);
    }

    public function total_egreso($fecha_inicio,$fecha_fin)
    {
        $monto_egreso_total = 0;
        $egresos = $this->obtener_egresos($fecha_inicio,$fecha_fin);
        foreach ($egresos as $egreso):
            $monto_egreso_total = $monto_egreso_total + $egreso["monto_total"];
        endforeach;
        return round($monto_egreso_total, 2);
    }

    public function lista_ingreso_detalle($fecha_inicio, $fecha_fin)
    {
        /*  lista de tipos de ingresos  */
        $lista_tipo_ingreso = $this->obtener_tipo_ingresos();
        $objeto_ingreso = [];
        $objeto_ingreso['row_tipo_ingreso'] = count($lista_tipo_ingreso);
        $objeto_ingreso['row_total'] = count($lista_tipo_ingreso)*2;
        $lista_ingresos = [];
        foreach ($lista_tipo_ingreso as $tipo_ingreso):
            $ingreso = [];
            $ingreso['descripcion'] = $tipo_ingreso->descripcion;
            $ingreso['lista'] = $this->obtener_ingreso_tipo($tipo_ingreso->id, $fecha_inicio, $fecha_fin);
            $ingreso['monto_total_tipo'] = $this->obtener_monto_total_ingreso($tipo_ingreso->id, $fecha_inicio, $fecha_fin);
            $ingreso['cantidad_datos'] = count($ingreso['lista']);
            $objeto_ingreso['row_total'] = $objeto_ingreso['row_total'] + count($ingreso['lista']);
            $lista_ingresos[] = $ingreso;
        endforeach;
        $objeto_ingreso['lista_ingresos'] = $lista_ingresos;
        return $objeto_ingreso;
    }

    public function lista_egreso_detalle($fecha_inicio, $fecha_fin)
    {
        /*  lista de tipos de egresos  */
        $lista_tipo_egreso = $this->obtener_tipo_egresos();
        $objeto_egreso = [];
        $lista_egresos = [];
        $objeto_egreso['row_tipo_egreso'] = count($lista_tipo_egreso);
        $objeto_egreso['row_total'] = count($lista_tipo_egreso)*2;
        foreach ($lista_tipo_egreso as $tipo_egreso):
            $egreso = [];
            $egreso['descripcion']       = $tipo_egreso->descripcion;
            $egreso['lista']             = $this->obtener_egreso_tipo($tipo_egreso->id, $fecha_inicio, $fecha_fin);
            $egreso['monto_total_tipo']  = $this->obtener_monto_total_egreso($tipo_egreso->id, $fecha_inicio, $fecha_fin);
            $egreso['cantidad_datos']    = count($egreso['lista']);
            $objeto_egreso['row_total']  = $objeto_egreso['row_total'] + count($egreso['lista']);
            $lista_egresos[]             = $egreso;
        endforeach;
        $objeto_egreso['lista_egresos'] = $lista_egresos;
        return $objeto_egreso;
    }


    public function obtener_ingreso_tipo($tipo_ingreso_id,$fecha_inicio, $fecha_fin) {

       // $this->db->select("ing.id, to_char(ing.fecha_registro,'DD/MM/YYYY') as fecha_registro, ing.monto,ing.detalle")
        $this->db->select("ing.id, (ing.fecha_registro) as fecha_registro, ing.monto,ing.detalle")
            ->from("ingreso_caja ing")
            ->where("ing.estado !=",get_state('INACTIVO'))
            ->where("ing.tipo_ingreso_id",$tipo_ingreso_id)
            ->where("ing.fecha_registro >=",$fecha_inicio)
            ->where("ing.fecha_registro <=", $fecha_fin);
        $result = $this->db->get();
        return $result->result();
    }

    public function obtener_egreso_tipo($tipo_id, $fecha_inicio, $fecha_fin) {

       // $this->db->select("egr.id, to_char(egr.fecha_registro,'DD/MM/YYYY') as fecha_registro, egr.monto, egr.detalle")
        $this->db->select("egr.id, (egr.fecha_registro) as fecha_registro, egr.monto, egr.detalle")
            ->from("egreso_caja egr")
            ->where("egr.estado !=",get_state('INACTIVO'))
            ->where("egr.tipo_egreso_id",$tipo_id)
            ->where("egr.fecha_registro >=",$fecha_inicio)
            ->where("egr.fecha_registro <=", $fecha_fin);
        $result = $this->db->get();
        return $result->result();
    }


}