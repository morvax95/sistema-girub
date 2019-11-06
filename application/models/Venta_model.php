<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function verificar_cliente_seleccionado($id_cliente, $nit_cliente, $nombre_cliente)
    {
        if ($id_cliente === '') {
            if ($nit_cliente === '' and $id_cliente === '') {
                $datosCliente = $this->cliente_model->obtener_cliente_particular();
                $id_seleccionado = $datosCliente->id;
            } else {
                if (!$this->cliente_model->exite_cliente(trim($nit_cliente))) {
                    $cliente['ci_nit'] = trim($nit_cliente);
                    $cliente['nombre_cliente'] = mb_strtoupper(trim($nombre_cliente), 'UTF-8');
                    $id_seleccionado = $this->cliente_model->registrar_cliente_venta($cliente);
                } else {
                    $id_seleccionado = $id_cliente;
                }
            }
        } else {
            if ($id_cliente === 0 or $id_cliente === '0') {
                $datosCliente = $this->cliente_model->obtener_cliente_particular();
                $id_seleccionado = $datosCliente->id;
            } else {
                $id_seleccionado = $id_cliente;
            }
        }
        return $id_seleccionado;
    }

    public function verificar_stock($producto_id, $cantidad_pedida)
    {
        $bool = false;
        $this->db->select("cantidad,cantidad_produccion ")
            ->from("producto_inventario")
            ->where("producto_id", $producto_id);
        // ->group_by("cantidad,cantidad_produccion");
        $result = $this->db->get()->row();
        $cantidad_total = $result->cantidad + $result->cantidad_produccion;
        if ($cantidad_pedida <= $cantidad_total) {
            $bool = true;
        }
        return $bool;
    }

    public function registro_venta()
    {
        $cmd['operacion'] = "INSERCIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
       $cmd['tabla'] = "VENTA";
        $this->db->insert('bitacora', $cmd);


        $this->db->trans_start();
        $this->load->model('cliente_model');
        $id_cliente = $this->input->post('idCliente');
        $nit_cliente = $this->input->post('nit_cliente');
        $nombre_cliente = $this->input->post('nombre_cliente');
        $cliente_id = $this->verificar_cliente_seleccionado($id_cliente, $nit_cliente, $nombre_cliente);
        $nro_venta = $this->db->get('venta');

        $obj_venta['fecha'] = date('Y-m-d H:i:s');
        $obj_venta['subtotal'] = $this->input->post('subtotal_as');
        $obj_venta['descuento'] = $this->input->post('descuento_as');
        $obj_venta['total'] = $this->input->post('total_as');
        $obj_venta['cliente_id'] = $cliente_id;
        $obj_venta['nro_venta'] = $nro_venta->num_rows + 1;
        $obj_venta['estado'] = get_state('ACTIVO');
        $obj_venta['sucursal_id'] = get_branch_id_in_session();
        $obj_venta['usuario_id'] = get_user_id_in_session();
        $obj_venta['tipo_venta'] = $this->input->post('tipo_venta');
        $obj_venta['hora'] = date('H:i:s');
        $obj_venta['nota'] = $this->input->post('nota');
        //$obj_venta['estado_venta'] = $this->input->post('tipo_venta');
        $this->db->insert('venta', $obj_venta);
        // Extraemos el id insertado
        $id_venta = $this->db->insert_id();

        // Registramos el detalle
        $detalle['venta_id'] = $id_venta;
        $detalle['producto'] = $this->input->post('producto_id[]');
        $detalle['talla'] = $this->input->post('talla_id[]');
        $detalle['color'] = $this->input->post('color_id[]');
        $detalle['cantidad'] = $this->input->post('cantidad[]');
        $detalle['cantidad_produccion'] = $this->input->post('stock_produccion[]');
        $detalle['precio'] = $this->input->post('precio[]');
        $detalle['estado_entrega'] = $this->input->post('estado_entrega[]');
        $contador = $this->input->post('contador');

        $estado_ent = array();
        if (isset($_SESSION["estado_entrega"])) {
            $estado_ent = $_SESSION["estado_entrega"];
        }

        for ($index = 0; $index < $contador; $index++) {
            $detalle_venta['venta_id'] = $id_venta;
            $detalle_venta['producto_id'] = $detalle['producto'][$index];
            $detalle_venta['talla_id'] = $detalle['talla'][$index];
            $detalle_venta['color_id'] = $detalle['color'][$index];
            $detalle_venta['cantidad'] = $detalle['cantidad'][$index];
            $detalle_venta['cantidad_produccion'] = $detalle['cantidad_produccion'][$index];
            $detalle_venta['precio_venta'] = $detalle['precio'][$index];
            //$detalle_venta['estado_entrega'] = $detalle['estado_entrega'][$index];
            $detalle_venta['estado_entrega'] = $estado_ent[$index];
            // Realizamos el proceso de inventario
            $lista_id_producto = $this->obtener_id_productos($detalle_venta['producto_id']);
            foreach ($lista_id_producto as $row) {

                $cantidad = $row->cantidad;
                $cantidad_produccion = $row->cantidad_produccion;
                $stock_total = $cantidad + $cantidad_produccion;
                $cantida_venta_entregado = 0;
                $cantidad_venta_pendiente = 0;

                //$cantidad_entrante=$detalle_venta['cantidad'] +$detalle_venta['cantidad_produccion'];
                $inventario_id = $row->id;

                if ($stock_total >= $detalle_venta['cantidad']) {// si hay mas de lo que se pide
                    //Realizamos el descuento en el inventario
                    $this->db->where('id', $inventario_id);
                    //$this->db->update('producto_inventario', array('cantidad' => $cantidad - $detalle_venta['cantidad']));
                    if ($cantidad > 0) {
                        if ($cantidad >= $detalle_venta['cantidad']) {
                            $this->db->update('producto_inventario', array('cantidad' => $cantidad - $detalle_venta['cantidad']));
                            $cantida_venta_entregado = $detalle_venta['cantidad'];
                        } else {
                            $this->db->update('producto_inventario', array('cantidad' => $cantidad - $cantidad));
                            $detalle_venta['cantidad'] = $detalle_venta['cantidad'] - $cantidad;
                            $cantida_venta_entregado = $cantidad;
                            if ($cantidad_produccion >= $detalle_venta['cantidad']) {
                                $this->db->update('producto_inventario', array('cantidad_produccion' => $cantidad_produccion - $detalle_venta['cantidad']));
                                $cantidad_venta_pendiente = $detalle_venta['cantidad'];
                            }
                        }

                    } else {
                        if ($cantidad_produccion >= $detalle_venta['cantidad']) {
                            $this->db->update('producto_inventario', array('cantidad_produccion' => $cantidad_produccion - $detalle_venta['cantidad']));
                            $cantidad_venta_pendiente = $detalle_venta['cantidad'];
                        }
                        /*else {
                            $this->db->update('producto_inventario', array('cantidad_produccion' => $detalle_venta['cantidad'] - $cantidad_produccion));
                            $detalle_venta['cantidad']=$detalle_venta['cantidad'] - $cantidad_produccion;
                        }*/
                    }


                    $this->db->insert('detalle_venta', array('venta_id' => $detalle_venta['venta_id'], 'producto_id' => $detalle_venta['producto_id'], 'cantidad' => $cantida_venta_entregado, 'cantidad_produccion' => $cantidad_venta_pendiente, 'precio_venta' => $detalle_venta['precio_venta'], 'talla_id' => $detalle_venta['talla_id'], 'color_id' => $detalle_venta['color_id'], 'inventario_id' => $inventario_id, 'estado_entrega' => $detalle_venta['estado_entrega']));
                    break;
                } else {
                    console . log("hola");
                }

            }
        }
        $_SESSION["estado_entrega"] = null;
        // Proceso de forma pago
        $pagos = $this->input->post('forma_pago[]');
        $monto_pagado = 0;
        $credito = 'normal';
        $total_pagado = 0;
        $total_pagado = $total_pagado + $this->input->post('monto_efectivo') + $this->input->post('monto_deposito') + $this->input->post('monto_cheque') + $this->input->post('monto_tarjeta');
        foreach ($pagos as $row) {
            switch ($row) {
                case 'efectivo':
                    $monto_efectivo = $this->input->post('monto_efectivo');
                    $monto_pagado = $monto_pagado + $monto_efectivo;
                    $elements['venta_id'] = $id_venta;
                    $elements['forma_pago'] = ucwords($row);
                    $elements['fecha_registro'] = date('Y-m-d');
                    $elements['monto'] = $monto_efectivo;
                    $elements['fecha_registro'] = date('Y-m-d');
                    $elements['descripcion'] = $this->input->post('tipo_venta_plazo');
                    $elements['saldo'] = $obj_venta['total'] - $monto_pagado;
                    if ($total_pagado == $obj_venta['total']) {
                        $elements['estado'] = 'Cancelado';
                    } else {
                        $elements['estado'] = 'Debe';
                    }
                    $this->db->insert('venta_pago', $elements);
                    break;
                case 'plazo':
                    $credito = ucwords($row);
                    $monto_credito = $this->input->post('monto_cuenta_credito');
                    $monto_pagado = $monto_pagado + $monto_credito;
                    $elements['venta_id'] = $id_venta;
                    $elements['forma_pago'] = ucwords($row);
                    $elements['fecha_pago'] = $this->input->post('fecha_pago_credito');
                    $elements['fecha_registro'] = date('Y-m-d');
                    $elements['descripcion'] = $this->input->post('tipo_venta_plazo');
                    $elements['monto'] = $monto_credito;
                    $elements['saldo'] = $obj_venta['total'] - $monto_pagado;
                    $elements['estado'] = "Debe";
                    $this->db->insert('venta_pago', $elements);
                    break;
                case 'deposito':
                    $monto_deposito = $this->input->post('monto_deposito');
                    $monto_pagado = $monto_pagado + $monto_deposito;
                    $elements['venta_id'] = $id_venta;
                    $elements['forma_pago'] = ucwords($row);
                    $elements['banco'] = $this->input->post('banco');
                    $elements['nro_cuenta'] = $this->input->post('cuenta');
                    $elements['fecha_registro'] = date('Y-m-d');
                    $elements['descripcion'] = $this->input->post('tipo_venta_plazo');
                    $elements['monto'] = $monto_deposito;
                    $elements['saldo'] = $obj_venta['total'] - $monto_pagado;
                    if ($total_pagado == $obj_venta['total']) {
                        $elements['estado'] = "Cancelado";
                    } else {
                        $elements['estado'] = "Debe";
                    }
                    $this->db->insert('venta_pago', $elements);
                    break;
                case 'cheque':
                    $monto_cheque = $this->input->post('monto_cheque');
                    $monto_pagado = $monto_pagado + $monto_cheque;
                    $elements['venta_id'] = $id_venta;
                    $elements['forma_pago'] = ucwords($row);
                    $elements['banco'] = $this->input->post('banco_cheque');
                    $elements['nro_cheque'] = $this->input->post('nro_cheque');
                    $elements['forma_pago'] = ucwords($row);
                    $elements['fecha_registro'] = date('Y-m-d');
                    $elements['descripcion'] = $this->input->post('tipo_venta_plazo');
                    $elements['monto'] = $monto_cheque;
                    $elements['saldo'] = $obj_venta['total'] - $monto_pagado;
                    if ($total_pagado == $obj_venta['total']) {
                        $elements['estado'] = "Cancelado";
                    } else {
                        $elements['estado'] = "Debe";
                    }
                    $this->db->insert('venta_pago', $elements);
                    break;
                case 'tarjeta':
                    $monto_tarjeta = $this->input->post('monto_tarjeta');
                    $monto_pagado = $monto_pagado + $monto_tarjeta;
                    $elements['venta_id'] = $id_venta;
                    $elements['forma_pago'] = ucwords($row);
                    $elements['banco'] = $this->input->post('banco_tarjeta');
                    $elements['nro_cheque'] = $this->input->post('nro_tarjeta');
                    $elements['forma_pago'] = ucwords($row);
                    $elements['fecha_registro'] = date('Y-m-d');
                    $elements['descripcion'] = $this->input->post('tipo_venta_plazo');
                    $elements['monto'] = $monto_tarjeta;
                    $elements['saldo'] = $obj_venta['total'] - $monto_pagado;
                    if ($total_pagado == $obj_venta['total']) {
                        $elements['estado'] = "Cancelado";
                    } else {
                        $elements['estado'] = "Debe";
                    }
                    $this->db->insert('venta_pago', $elements);
                    break;
            }
        }

        // Factura o nota de venta
        if ($this->input->post('tipo_venta') == 'nota') {
            $this->registrar_nota_Venta($id_venta);
        } else {
            $this->registrar_factura($id_venta, $obj_venta);
        }


        // Registramos el ingreso
        if ($credito != 'Credito') {
            $ingreso['fecha_ingreso'] = date('Y-m-d');
            $ingreso['detalle'] = 'Ingreso por venta generado automaticamente';
            $ingreso['monto'] = $monto_pagado;
            $ingreso['estado'] = get_state('ACTIVO');
            $ingreso['tipo_ingreso_id'] = $this->input->post('tipo_ingreso'); // Este dato es el tipo de ingreso que esta estitico en la interfaz de venta que apunta al id en la tabla tipo_ingreso_egreso
            $ingreso['sucursal_id'] = get_branch_id_in_session();
            $ingreso['usuario_id'] = get_user_id_in_session();
            $ingreso['fecha_registro'] = date('Y-m-d');
            $this->db->insert('ingreso_caja', $ingreso);
            $id_ingreso = $this->db->insert_id();
            // Ingreso por venta
            $this->db->insert('ingreso_venta', array('venta_id' => $id_venta, 'ingreso_id' => $id_ingreso));
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $id_venta;
        }
    }

    public function registro_venta_proforma()/* Registro como proforma  */
    {
        $this->db->trans_start();

        $this->load->model('cliente_model');
        $id_cliente = $this->input->post('idCliente');
        $nit_cliente = $this->input->post('nit_cliente');
        $nombre_cliente = $this->input->post('nombre_cliente');
        $cliente_id = $this->verificar_cliente_seleccionado($id_cliente, $nit_cliente, $nombre_cliente);
        $nro_venta = $this->db->get('venta');

        $obj_venta['fecha'] = date('Y-m-d');
        $obj_venta['subtotal'] = $this->input->post('subtotal_as');
        $obj_venta['descuento'] = $this->input->post('descuento_as');
        $obj_venta['total'] = $this->input->post('total_as');
        $obj_venta['cliente_id'] = $cliente_id;
        $obj_venta['nro_venta'] = $nro_venta->num_rows + 1;
        $obj_venta['estado'] = get_state('ACTIVO');
        $obj_venta['sucursal_id'] = get_branch_id_in_session();
        $obj_venta['usuario_id'] = get_user_id_in_session();
        $obj_venta['tipo_venta'] = $this->input->post('tipo_venta');
        $obj_venta['hora'] = date('H:i:s');
        $this->db->insert('venta', $obj_venta);

        // Extraemos el id insertado
        $id_venta = $this->db->insert_id();

        // Registramos el detalle
        $detalle['venta_id'] = $id_venta;
        $detalle['producto'] = $this->input->post('producto_id[]');
        $detalle['talla'] = $this->input->post('talla_id[]');
        $detalle['color'] = $this->input->post('color_id[]');
        $detalle['cantidad'] = $this->input->post('cantidad[]');
        $detalle['precio'] = $this->input->post('precio[]');
        $contador = $this->input->post('contador');

        for ($index = 0; $index < $contador; $index++) {
            $detalle_venta['venta_id'] = $id_venta;
            $detalle_venta['producto_id'] = $detalle['producto'][$index];
            $detalle_venta['talla_id'] = $detalle['talla'][$index];
            $detalle_venta['color_id'] = $detalle['color'][$index];
            $detalle_venta['cantidad'] = $detalle['cantidad'][$index];
            $detalle_venta['precio_venta'] = $detalle['precio'][$index];

            // Realizamos el proceso de inventario
            $lista_id_producto = $this->obtener_id_productos($detalle_venta);
            foreach ($lista_id_producto as $row) {
                $cantidad_en_stock = $row->cantidad;
                $inventario_id = $row->id;
                if ($cantidad_en_stock >= $detalle_venta['cantidad']) {// si hay mas de lo que se pide
                    //Realizamos el descuento en el inventario
                    $this->db->where('id', $inventario_id);
                    $this->db->update('producto_inventario', array('cantidad' => $cantidad_en_stock - $detalle_venta['cantidad']));
                    $this->db->insert('detalle_venta', array('venta_id' => $detalle_venta['venta_id'], 'producto_id' => $detalle_venta['producto_id'], 'cantidad' => $detalle_venta['cantidad'], 'precio_venta' => $detalle_venta['precio_venta'], 'talla_id' => $detalle_venta['talla_id'], 'color_id' => $detalle_venta['color_id'], 'inventario_id' => $inventario_id));
                    break;
                } else { // cuando el lote no tiene la cantidad requerida
                    $this->db->where('id', $inventario_id);
                    $this->db->update('producto_inventario', array('cantidad' => $cantidad_en_stock - $cantidad_en_stock));
                    $detalle_venta['cantidad'] = $detalle_venta['cantidad'] - $cantidad_en_stock;
                    $this->db->insert('detalle_venta', array('venta_id' => $detalle_venta['venta_id'], 'producto_id' => $detalle_venta['producto_id'], 'cantidad' => $cantidad_en_stock, 'precio_venta' => $detalle_venta['precio_venta'], 'talla_id' => $detalle_venta['talla_id'], 'color_id' => $detalle_venta['color_id'], 'inventario_id' => $inventario_id));
                }

            }
        }

        /* Proceso de forma pago*/
        $pagos = $this->input->post('forma_pago[]');
        $monto_pagado = 0;
        $credito = 'normal';
        $total_pagado = 0;

        // Factura o nota de venta
        if ($this->input->post('tipo_venta') == 'nota') {
            $this->registrar_nota_Venta($id_venta);
        } else {
            $this->registrar_factura($id_venta, $obj_venta);
        }


        // Registramos el ingreso
        if ($credito != 'Credito') {
            $ingreso['fecha_ingreso'] = date('Y-m-d');
            $ingreso['detalle'] = 'Ingreso por venta generado automaticamente';
            $ingreso['monto'] = $monto_pagado;
            $ingreso['estado'] = get_state('ACTIVO');
            $ingreso['tipo_ingreso_id'] = $this->input->post('tipo_ingreso'); // Este dato es el tipo de ingreso que esta estitico en la interfaz de venta que apunta al id en la tabla tipo_ingreso_egreso
            $ingreso['sucursal_id'] = get_branch_id_in_session();
            $ingreso['usuario_id'] = get_user_id_in_session();
            $ingreso['fecha_registro'] = date('Y-m-d');
            $this->db->insert('ingreso_caja', $ingreso);
            $id_ingreso = $this->db->insert_id();
            // Ingreso por venta
            $this->db->insert('ingreso_venta', array('venta_id' => $id_venta, 'ingreso_id' => $id_ingreso));
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $id_venta;
        }
    }

    public function registrar_nota_Venta($id_venta)
    {
        $nota = $this->db->get('nota_venta')->result();
        $nro_nota = count($nota) + 1;
        $this->db->insert('nota_venta', array('venta_id' => $id_venta, 'nro_nota' => $nro_nota));
    }


    public function obtener_id_productos($id_Registro)
    {
        return $this->db->get_where('producto_inventario', array('producto_id' => $id_Registro))->result();
    }

    public function comprobante_ventas($id)
    {
        $this->db->select("v.id,v.nota,vp.forma_pago,v.fecha, v.hora, v.subtotal, v.descuento, v.total, u.nombre_usuario, n.nro_nota, c.ci_nit, c.nombre_cliente, s.nit, s.nombre_empresa,s.sucursal, s.direccion, s.telefono, s.email,vp.descripcion,vp.monto ,vp.saldo");
        $this->db->from("cliente c, venta v, sucursal s, nota_venta n, usuario u,venta_pago vp");
        $this->db->where("c.id = v.cliente_id");
        $this->db->where("v.sucursal_id = s.id");
        $this->db->where("v.id = n.venta_id");
        $this->db->where("v.usuario_id = u.id");
        $this->db->where("v.id = vp.venta_id");
        if (!empty($id)) {
            $this->db->where("v.id=" . $id);
        }
        $resultado['datos'] = $this->db->get()->result();
        $resultado['datos_venta_detalle'] = $this->obtenerDetalleVenta($id);
        return $resultado;

    }

    public function nota_entrega($codigo)
    {
        $this->db->select("p.codigo_barra,v.id,p.nombre_item,dt.estado_entrega ,(dt.cantidad) as cantidad_total,c.nombre_cliente,vp.forma_pago");
        $this->db->from("detalle_venta dt,venta v,cliente c,producto p,venta_pago vp");
        $this->db->where("v.id=dt.venta_id");
        $this->db->where("c.id=v.cliente_id");
        $this->db->where("c.id=v.cliente_id");
        $this->db->where("v.id=vp.venta_id");
        $this->db->group_by(" v.id");
        if (!empty($codigo)) {
            $this->db->where("v.id ='" . $codigo . "'");

        }

        $resultado['datos'] = $this->db->get()->result();

        return $resultado;
    }

    public function imprimir_nota_venta($id)
    {
        $this->db->select("v.id, v.fecha, v.hora, v.subtotal, v.descuento, v.total, u.nombre_usuario, n.nro_nota, c.ci_nit, c.nombre_cliente, s.nit, s.nombre_empresa,s.sucursal, s.direccion, s.telefono, s.email")
            ->from("cliente c, venta v, sucursal s, nota_venta n, usuario u")
            ->where("c.id = v.cliente_id")
            ->where("v.sucursal_id = s.id")
            ->where("v.id = n.venta_id")
            ->where("v.usuario_id = u.id")
            ->where("v.id", $id);
        $datos_venta = $this->db->get()->row();
        $datos_array['datos_venta'] = $datos_venta;
        $datos_array['datos_venta_detalle'] = $this->obtenerDetalleVenta($id);
        return $datos_array;
    }

    function obtenerDetalleVenta($idventa)
    {
        $this->db->select("p.codigo_barra,v.id,(d.cantidad + d.cantidad_produccion) as cantidad,d.precio_venta, t.descripcion as talla, c.descripcion as color, p.nombre_item,d.estado_entrega,vp.monto as monto_plazos,vp.saldo")
            ->from("venta v, detalle_venta d, producto p, talla t, color c,venta_pago vp")
            ->where("v.id = d.venta_id")
            ->where("d.producto_id = p.id")
            ->where("p.talla_id = t.id")
            ->where("p.color_id = c.id")
            ->where("v.id = vp.venta_id")
            ->where("v.id", $idventa)
            ->group_by("v.id,d.precio_venta,t.descripcion,c.descripcion, p.nombre_item");
        $datos_detalle = $this->db->get()->result();

        return $datos_detalle;
    }


    // Obtiene todas los productos con el estado_venta==5
    public function get_all_items($params = array())
    {
        // Si se envia como parametro "draw", la peticion se esta realizando desde datatables
        if (!empty($this->input->post('draw'))) {

            $this->db->start_cache();
            $this->db->select("*");
            $this->db->from('ventas_emitidas');//vista que consulta el estado_venta==5
            $this->db->where('sucursal_id', get_branch_id_in_session());
            $this->db->where('estado=1');
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
                $this->db->order_by('id', 'DESC');
            }

            if (array_key_exists('search', $params) && !empty($params['search'])) {
                $this->db->group_start();
                $this->db->like('lower(nombre_cliente)', strtolower($params['search']));
//                $this->db->or_like('fecha', $params['search']);
                $this->db->or_like('ci_nit', $params['search']);
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
}