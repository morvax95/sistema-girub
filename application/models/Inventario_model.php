<?php

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 01/02/2018
 * Time: 10:56 AM
 */
class Inventario_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('almacen_model');
        $this->load->model('sucursal_model');
    }

    //region Metodos de inventario
    public function get_income_by_id_producction($id)
    {
        $this->db->select("i.*,date(i.fecha_registro) as fechar, a.descripcion as almacen, s.sucursal")
            ->from("almacen a, ingreso_inventario i, sucursal s")
            ->where("a.id = i.almacen_id")
            ->where("i.sucursal_id = s.id")
            ->where("i.id", $id);
        $data['ingreso'] = $this->db->get()->row();
        // Detalle
        $this->db->select("p.id as id_producto,iv.id,p.nombre_item,dp.cantidad_produccion,iv.estado_producto")
            ->from("ingreso_inventario iv,detalle_producto_ingreso dp,producto p")
            ->where("dp.ingreso_id=iv.id ")
            ->where("p.id=dp.producto_id ")
            ->where("iv.estado_producto=2")
            ->where("iv.id=", $id);
        $data['detalle'] = $this->db->get()->result();
        return $data;
    }

    //region Metodos de inventario
    public function get_income_by_id($id)
    {
        $this->db->select("i.*,date(i.fecha_ingreso) as fecha,a.descripcion as almacen, s.sucursal")
            ->from("almacen a, ingreso_inventario i, sucursal s, detalle_producto_ingreso dti ")
            ->where("a.id = i.almacen_id")
            ->where("i.sucursal_id = s.id")
            ->where("dti.ingreso_id = i.id")
            ->where("dti.ingreso_id", $id);
        $data['ingreso'] = $this->db->get()->row();
        // Detalle
        $this->db->select("p.nombre_item,dti.precio_compra,dti.cantidad_ingresada,i.estado_producto")
            ->from("almacen a, ingreso_inventario i, sucursal s, detalle_producto_ingreso dti,producto p")
            ->where("a.id = i.almacen_id")
            ->where("i.sucursal_id = s.id")
            ->where("dti.ingreso_id = i.id")
            ->where("dti.producto_id = p.id")
            ->where("dti.ingreso_id", $id);
        $data['detalle'] = $this->db->get()->result();
        return $data;
    }

    // Obtener datos de todoo el inventario
    public function get_all($params = array(), $value)
    {
        if ($this->input->post('draw')) {
            $this->db->start_cache();
            $this->db->select("*, CASE WHEN (cantidad <= stock_minimo) THEN 'STOCK MINIMO'
            WHEN (cantidad = 0) THEN 'AGOTADO'
            ELSE 'DISPONIBLE'
          END as estado_inventario")
                ->from("inventario")
                ->where("tipo_producto", $value)
                ->where('id_sucursal', get_branch_id_in_session());
            $this->db->stop_cache();

            $records_total = count($this->db->get()->result_array());

            // Concatenar parametros enviados (solo si existen)
            if (array_key_exists('start', $params) && array_key_exists('limit', $params)) {
                $this->db->limit($params['limit']);
                $this->db->offset($params['start']);
            }

            if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
                $this->db->order_by("{$params['column']}", $params['order']);
            } else {
                $this->db->order_by('producto_id,', 'ASC');
            }

            if (array_key_exists('search', $params) && !empty($params['search'])) {
                $this->db->like('lower(nombre_item)', $params['search']);
                $this->db->or_like('lower(color)', $params['search']);
                $this->db->or_like('lower(talla)', $params['search']);
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
            $this->db->select("*, CASE WHEN (cantidad <= stock_minimo) THEN 'STOCK MINIMO'
            WHEN (cantidad = 0) THEN 'AGOTADO'
            ELSE 'DISPONIBLE'
          END as estado_inventario")
                ->from("inventario");
            $json_data = $this->db->get()->result_array();
        }
        return $json_data;
    }

    // Obtiene los datos de los ingresos realizados
    public function get_all_entry($params = array())
    {
        $this->db->start_cache();
        $this->db->select("i.*, a.descripcion as almacen, s.sucursal,date(fecha_ingreso) as s_fecha")
            ->from("almacen a, ingreso_inventario i, sucursal s")
            ->where("a.id = i.almacen_id")
            ->where("i.sucursal_id = s.id")
            ->where("i.sucursal_id", get_branch_id_in_session())
            ->order_by('i.id', 'desc');
        $this->db->stop_cache();

        $records_total = count($this->db->get()->result_array());

        // Concatenar parametros enviados (solo si existen)
        if (array_key_exists('start', $params) && array_key_exists('limit', $params)) {
            $this->db->limit($params['limit']);
            $this->db->offset($params['start']);
        }

        if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
            $this->db->order_by("{$params['column']}", $params['order']);
        } else {
            $this->db->order_by('i.id,', 'ASC');
        }

        if (array_key_exists('search', $params) && !empty($params['search'])) {
            $this->db->like('lower(s.sucursal)', $params['search']);
            $this->db->or_like('lower(a.descripcion)', $params['search']);
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

    // Metodo para registrar un nuevo ingreso a inventario con el detalle ingresado
    public function registrar()
    {
        $cmd['operacion'] = "INSERCIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "INVENTARIO";
        $this->db->insert('bitacora', $cmd);

        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        $this->form_validation->set_rules('fecha_ingreso', 'fecha ingreso', 'trim|required');
        $this->form_validation->set_rules('seleccion_almacen', 'almacen', "trim|required");
        $this->form_validation->set_rules('sucursal_seleccionada', 'sucursal', "trim|required");

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {

            $this->db->trans_start();
            $obj_data['fecha_ingreso'] = $this->input->post('fecha_ingreso');
            $obj_data['fecha_registro'] = date('Y-m-d H:i:s');
            $obj_data['observacion'] = mb_strtoupper($this->input->post('observacion'), "UTF-8");
            $obj_data['estado'] = get_state('ACTIVO');
            $obj_data['forma_ingreso'] = $this->input->post('forma_ingreso');
            $obj_data['almacen_id'] = $this->input->post('seleccion_almacen');
            $obj_data['usuario_id'] = get_user_id_in_session();
            $obj_data['sucursal_id'] = $this->input->post('sucursal_seleccionada');
            $obj_data['estado_producto'] = $this->input->post('id_estado_producto');

            $this->db->insert('ingreso_inventario', $obj_data);
            $id_ingreso = $this->db->insert_id();

            // Registramos el detalle del producto ingresado a inventario
            $data['producto_id'] = $this->input->post('id_producto[]');
            $data['precio'] = $this->input->post('precio_inventario[]');
            $data['cantidad'] = $this->input->post('cantidad[]');
            $data['contador'] = $this->input->post('contador_inventario');
            $data['estado_producto'] = $this->input->post('id_estado_producto');

            $this->registrar_stock_producto($data, $id_ingreso);
            $this->registrar_detalle_ingreso($data, $id_ingreso);
            // Fin de la transaccion
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response['success'] = false;
            } else {
                $this->db->trans_commit();
                $response['success'] = TRUE;
            }
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        return json_encode($response);
    }

    //registrar los detalles de un ingreso de productos
    public function registrar_detalle_ingreso($data, $id)
    {
        $this->db->trans_start();

        for ($index = 0; $index < $data['contador']; $index++) {
            $inventario['cantidad_ingresada'] = $data['cantidad'][$index];
            $inventario['ingreso_id'] = $id;
            $inventario['producto_id'] = $data['producto_id'][$index];
            $inventario['precio_compra'] = $data['precio'][$index];
            //$inventario['unidad_id'] = 1;

            if ($data['estado_producto'][0] == '1') {
                $inventario['cantidad'] = $data['cantidad'][$index];
                $inventario['cantidad_produccion'] = 0;
            } else {
                $inventario['cantidad_produccion'] = $data['cantidad'][$index];
                $inventario['cantidad'] = 0;
            }
            $this->db->insert('detalle_producto_ingreso', $inventario);

        }
    }
    // Ingresa datos a la tabla producto inventario
    // verificando que un producto con una talla y color no esten ingresados mas de una ves
    // en el mismo ingreso
    public function registrar_stock_producto($data, $id)
    {
        $this->db->trans_start();


        for ($index = 0; $index < $data['contador']; $index++) {
            $inventario['cantidad_ingresada'] = $data['cantidad'][$index];
            $inventario['ingreso_id'] = $id;
            $inventario['producto_id'] = $data['producto_id'][$index];
            $inventario['precio_venta'] = $data['precio'][$index];
            $inventario['unidad_id'] = 1;


            //if ($this->verificar_producto_ingresado($inventario, null)) {
            if ($this->verificar_producto_inventario($inventario['producto_id'])) {

                // Actualizamos la cantidad en ese registro
                $item['producto_id'] = $inventario['producto_id'];

                $data_inventario = $this->db->get_where('producto_inventario', $item)->row();
                $id_inventario = $inventario['producto_id'];
                $estado_producto = $data['estado_producto'][0];
                $cantidad = 0;

                if ($estado_producto == 1) {
                    $total_stock = $data_inventario->cantidad + $inventario['cantidad_ingresada'];
                    // Actualizamos en ese id
                    $this->db->where('producto_id', $id_inventario);
                    $this->db->update('producto_inventario', array('cantidad_ingresada' => $inventario['cantidad_ingresada'], 'cantidad' => $total_stock));
                } else {

                    $total_stock = $data_inventario->cantidad_produccion + $inventario['cantidad_ingresada'];
                    // Actualizamos en ese id
                    $this->db->where('producto_id', $id_inventario);
                    $this->db->update('producto_inventario', array('cantidad_ingresada' => $inventario['cantidad_ingresada'], 'cantidad_produccion' => $total_stock));
                }

            } else {
                // Insertamos la nueva fila del inventario
                if ($data['estado_producto'][0] == '1') {
                    $inventario['cantidad'] = $data['cantidad'][$index];
                    $inventario['cantidad_produccion'] = 0;
                } else {
                    $inventario['cantidad_produccion'] = $data['cantidad'][$index];
                    $inventario['cantidad'] = 0;
                }
                $this->db->insert('producto_inventario', $inventario);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response['success'] = false;
        } else {
            $this->db->trans_commit();
            $response['success'] = TRUE;
        }
    }

    //metodo para verifacar la existencia en la tabla producto_inventario
    public function verificar_producto_inventario($id)
    {
        $item['producto_id'] = $id;
        $response = $this->db->get_where('producto_inventario', $item)->result();
        $res = (count($response) > 0) ? true : false;
        return $res;
    }
    //@ todo sacar el registro de materia prima para ponerlo en el sistema de fabrica
    // Metodo para registrar el ingreso de materia prima
    public function registrar_materia()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        $this->form_validation->set_rules('fecha_ingreso', 'fecha ingreso', 'trim|required');
        // $this->form_validation->set_rules('observacion', 'observacion', 'trim|required');
        $this->form_validation->set_rules('seleccion_almacen', 'almacen', 'trim|required');
        $this->form_validation->set_rules('sucursal_seleccionada', 'sucursal', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {

            $this->db->trans_start();
            $obj_data['fecha_ingreso'] = $this->input->post('fecha_ingreso');
            $obj_data['fecha_registro'] = date('Y-m-d H:i:s');
            $obj_data['observacion'] = mb_strtoupper($this->input->post('observacion'), "UTF-8");
            $obj_data['estado'] = get_state('ACTIVO');
            $obj_data['forma_ingreso'] = $this->input->post('forma_ingreso');
            $obj_data['almacen_id'] = $this->input->post('seleccion_almacen');
            $obj_data['usuario_id'] = get_user_id_in_session();
            $obj_data['sucursal_id'] = $this->input->post('sucursal_seleccionada');

            $this->db->insert('ingreso_inventario', $obj_data);
            $id_ingreso = $this->db->insert_id();

            // Registramos el detalle del producto ingresado a inventario
            $data['producto_id'] = $this->input->post('id_producto[]');
            $data['unidad'] = $this->input->post('unidad[]');
            $data['cantidad'] = $this->input->post('cantidad[]');
            $data['contador'] = $this->input->post('contador_inventario');

            $this->registrar_detalle_inventario_materia($data, $id_ingreso);
            // Fin de la transaccion
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response['success'] = false;
            } else {
                $this->db->trans_commit();
                $response['success'] = TRUE;
            }
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }

        return json_encode($response);
    }

    public function registrar_detalle_inventario_materia($data, $id)
    {
        $this->db->trans_start();

        for ($index = 0; $index < $data['contador']; $index++) {
            $inventario['ingreso_id'] = $id;
            $inventario['producto_id'] = $data['producto_id'][$index];
            $inventario['unidad_id'] = $data['unidad'][$index];
            $inventario['cantidad_ingresada'] = $data['cantidad'][$index];
            $inventario['cantidad'] = $data['cantidad'][$index];
            /*  $inventario['talla_id']             = 1;
              $inventario['color_id']             = 1;*/
            $inventario['precio_venta'] = '0.00';

            if ($this->verificar_producto_ingresado($inventario, 'materia')) {
                // Actualizamos la cantidad en ese registro
                $item['producto_id'] = $inventario['producto_id'];
                $item['ingreso_id'] = $inventario['ingreso_id'];
                // ingresamos el id 1 de talla u color, que es vacio
                /*$item['talla_id']       = 1;
                $item['color_id']       = 1;*/
                $data_inventario = $this->db->get_where('producto_inventario', $item)->row();
                $id_inventario = $data_inventario->id;
                $cantidad_registrada = $data_inventario->cantidad;
                $total_stock = $cantidad_registrada + $inventario['cantidad'];
                // Actualizamos en ese id
                $this->db->where('id', $id_inventario);
                $this->db->update('producto_inventario', array('cantidad' => $total_stock, 'cantidad_ingresada' => $total_stock));
            } else {
                // Insertamos la nueva fila del inventario
                $this->db->insert('producto_inventario', $inventario);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response['success'] = false;
        } else {
            $this->db->trans_commit();
            $response['success'] = TRUE;
        }
    }

    // Verificamos si el producto se ingreso mas de una ves en la interfaz
    public function verificar_producto_ingresado($data, $type)
    {
        if ($type === 'materia') {
            $item['producto_id'] = $data['producto_id'];
            $item['ingreso_id'] = $data['ingreso_id'];
            $item['unidad_id'] = $data['unidad_id'];

            $response = $this->db->get_where('producto_inventario', $item)->result();

            if (count($response) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $item['producto_id'] = $data['producto_id'];
            $item['ingreso_id'] = $data['ingreso_id'];

            $response = $this->db->get_where('producto_inventario', $item)->result();

            if (count($response) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    // Metodo para obtener los datos del inventario con stock minimo de la sucursal logueada
    public function get_quantity_in_inventory()
    {
        // para producto
        $this->db->where('cantidad <= stock_minimo');
        $this->db->where('tipo_producto', 'Producto');
        $this->db->where('id_sucursal', get_branch_id_in_session());
        $data['producto'] = $this->db->get('inventario')->result();
        // para materia prima
//        $this->db->where('cantidad <= stock_minimo');
//        $this->db->where('tipo_producto','Materia prima');
//        $this->db->where('id_sucursal',get_branch_id_in_session());
//        $data['materia'] = $this->db->get('inventario')->result();
        return $data;
    }
    //endregion


}