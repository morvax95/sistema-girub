<?php

class Producto extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('producto_model', 'producto');
        $this->load->model('inventario_model', 'inventario');
    }

    public function index()
    {
        plantilla('producto/index');

        // Recibimos los parametros del formulario
       /*  $Codigo_producto = $this->input->post('producto_id');
           $this->load->library("unit_test");
           $nombre = " TEST CASE";
           $datos['verificacion_tipo_datos_enviados_I'] = $this->unit->run($this->verificacion_tipo_datos_enviados(), 'is_string', $Codigo_producto, 'Nota  test case');
           $datos['titulo'] = 'Library Unit Test';
           $datos['contenido'] = 'pruebas';
           echo $this->unit->run($datos);*/
        //fin
    }

    public function verificacion_tipo_datos_enviados()
    {
        return "nombre";
    }

    public function nuevo()
    {
        $data['configuracion'] = $this->config->item('inventario');
        $data['tipo_item'] = $this->producto->get_all_type_item();
        $data['marcas'] = $this->producto->get_marca();
        $data['categorias'] = $this->producto->get_categoria();
        $data['unidad_medida'] = $this->producto->get_unidad_medida();

        plantilla('producto/nuevo', $data);
    }

    /* Eliminamos al producto seleccionado */
    public function eliminar_producto()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            echo $this->producto->eliminar_producto($id);
        } else {
            show_404();
        }
    }

    public function habilitar_producto()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id_producto');
            echo($id);

            $res = $this->producto->habilitar_producto($id);
            if ($res !== 1) {
                echo 'true';
            } else {
                echo 'error';
            }
        } else {
            show_404();
        }
    }

    public function editar()
    {
        $data['configuracion'] = $this->config->item('inventario');
        $data['tipo_item'] = $this->producto->get_all_type_item();
        $data['producto'] = $this->producto->get_item_by_id($this->input->post('id'));
        $data['marcas'] = $this->producto->get_marca();
        $data['categorias'] = $this->producto->get_categoria();
        plantilla('producto/editar', $data);
    }

    public function imprimir_codigo()
    {
        if (!logueado()) {
            redirect(site_url('login'));
        }
        $producto_id = $this->uri->segment(3);
        $codigo['codigo'] = $this->db->get_where('producto', array('id' => $producto_id))->row();
        $codigo['tipo'] = 1;
        $this->load->view('producto/imprimir_codigo', $codigo);
    }

    public function imprimir_codigos()
    {
        if (!logueado()) {
            redirect(site_url('login'));
        }
        $this->db->select("p.id, p.codigo_barra, p.nombre_item, p.precio_venta, t.descripcion as talla, c.descripcion as color")
            ->from("talla t, color c, producto p")
            ->where("t.id = p.talla_id")
            ->where("p.estado", 1)
            ->where("c.id = p.color_id");
        $codigo['codigos'] = $this->db->get()->result();
        $codigo['tipo'] = 2;
        $this->load->view('producto/imprimir_codigo', $codigo);
    }

    //endregion


    public function registrar_producto()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->producto->registrar_producto();
        } else {
            show_404();
        }
    }

    public function modificar_producto()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->producto->modificar_producto();
        } else {
            show_404();
        }
    }

    public function verificar_inventario($dato)
    {
        $this->db->like('lower(nombre_item)', $dato);
        $result = $this->db->get_where('inventario');
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function buscar_item()
    {
        $tipo = $this->input->post_get('type');
        // se busca en producto
        $dato = strtolower($this->input->post_get('name_startsWith'));
        $this->db->group_start();
        $this->db->like('lower(p.nombre_item)', $dato);
        $this->db->or_where('lower(p.codigo_barra)', $dato);
        $this->db->or_where('lower(p.codigo_alterno)', $dato);
        $this->db->group_end();
        $this->db->select('p.*,c.descripcion as color, t.descripcion as talla')
            ->from('producto p, color c, talla t')
            ->where('p.color_id = c.id')
            ->where('p.talla_id = t.id')
            ->where('p.estado', get_state('ACTIVO'));
        $res = $this->db->get();
        if ($res->num_rows() > 0) {
            foreach ($res->result_array() as $row) {
                //$data[$row['nombre_item'] . "|" . $row['precio_venta'] . " Bs" . "|" . $row['talla'] . "|" . $row['color'] . "|" . $row['precio_compra']] = $row['id'] . "|" . $row['color_id'] . "|" . $row['talla_id'];
                $data[$row['nombre_item'] . "|" . $row['precio_venta'] . " Bs" . "|" . $row['descripcion']] = $row['id'] . "|";
            }
            echo json_encode($data); //format the array into json data
        } else {
            $data["No existen datos"] = "No existe datos";
            echo json_encode($data);
        }
    }

    // Busqueda de productos en el inventario desde la interfaz de venta
    public function buscar_producto_inventario()
    {
        $dato = $this->input->post_get('name_startsWith');
        $almacen = $this->input->post_get('almacen_id');
        $this->db->where('id_almacen', $almacen);
        $this->db->where('id_sucursal', get_branch_id_in_session());
        $this->db->group_start();
        $this->db->like('lower(nombre_item)', strtolower($dato));
        $this->db->or_like('codigo_barra', $dato);
        $this->db->group_end();
        $res = $this->db->get_where('inventario');
        if ($res->num_rows() > 0) {
            foreach ($res->result_array() as $row) {
                $data[$row['nombre_item'] . '|' . $row['talla'] . '|' . $row['color'] . "|" . $row['precio_venta'] . "|" . $row['cantidad']] = $row['producto_id'] . "|" . $row['id_talla'] . "|" . $row['id_color'];
            }
            echo json_encode($data); //format the array into json data
        } else {
            $data["No existen datos en esta sucursal"] = "No existe datos en esta sucursal";
            echo json_encode($data);
        }
    }

    // Busqueda de productos en el inventario desde la interfaz de venta
    public function get_producto_generico()
    {
        $type = $this->input->post_get('type');
        $data = '';
        $dato = $this->input->post_get('name_startsWith');
        $almacen = $this->input->post_get('almacen_id');
        $this->db->where('id_almacen', $almacen);
        $this->db->where('id_sucursal', get_branch_id_in_session());

        switch ($type) {
            case 'detalle_inventario':
                $this->db->group_start();
                $this->db->like('lower(nombre_item)', strtolower($dato));
                $this->db->group_end();

                $res = $this->db->get_where('inventario_producto');
                if ($res->num_rows() > 0) {
                    foreach ($res->result_array() as $row) {
                        $data[$row['nombre_item'] . '|' . $row['talla'] . '|' . $row['color'] . "|" . $row['precio_venta'] . "|" . $row['cantidad'] . "|" . $row['cantidad_produccion'] . "|" . $row['codigo_barra']] = $row['producto_id'] . "|" . $row['id_talla'] . "|" . $row['id_color'];


                    }
                } else {
                    $data["No existen datos en esta sucursal"] = "No existe datos en esta sucursal";
                }

                echo json_encode($data);
                break;
            case 'codigo_barra_inventario':
                $this->db->group_start();
                $this->db->where('lower(codigo_barra)', strtolower($dato));
                $this->db->group_end();
                $res = $this->db->get_where('inventario_producto');
                if ($res->num_rows() > 0) {
                    foreach ($res->result_array() as $row) {
                        $data[$row['nombre_item'] . '|' . $row['talla'] . '|' . $row['color'] . "|" . $row['precio_venta'] . "|" . $row['cantidad'] . "|" . $row['cantidad_produccion'] . "|" . $row['codigo_barra']] = $row['producto_id'] . "|" . $row['id_talla'] . "|" . $row['id_color'];

                    }
                } else {
                    $data["No existen datos en esta sucursal"] = "No existe datos en esta sucursal";
                }
                echo json_encode($data);
                break;
        }
    }

    // Busqueda de productos en la proforma
    public function get_producto_proforma()
    {
        $type = $this->input->post_get('type');
        $data = '';
        $dato = $this->input->post_get('name_startsWith');
        switch ($type) {
            case 'detalle_inventario':
                $this->db->group_start();
                $this->db->like('lower(nombre_item)', strtolower($dato));
                $this->db->group_end();

                $res = $this->db->get_where('vista_proforma');
                if ($res->num_rows() > 0) {
                    foreach ($res->result_array() as $row) {

                        $data[$row['nombre_item'] . '|' . '|' . $row['color'] . "|" . $row['precio_venta'] . "|" . $row['stock_minimo'] . "|" . $row['stock_minimo'] . "|" . $row['stock_minimo']] = $row['id'] . "|" . $row['id_talla'] . "|" . $row['id_color'];

                    }
                } else {
                    $data["No existen datos en esta sucursal"] = "No existe datos en esta sucursal";
                }

                echo json_encode($data);
                break;
            case 'codigo_barra_inventario':
                $this->db->group_start();
                $this->db->where('lower(codigo_barra)', strtolower($dato));
                $this->db->group_end();
                $res = $this->db->get_where('vista_proforma');
                if ($res->num_rows() > 0) {
                    foreach ($res->result_array() as $row) {

                        $data[$row['nombre_item'] . '|' . '|' . $row['color'] . "|" . $row['precio_venta'] . "|" . $row['stock_minimo'] . "|" . $row['stock_minimo'] . "|" . $row['codigo_barra']] = $row['id'] . "|" . $row['id_talla'] . "|" . $row['id_color'];

                    }
                } else {
                    $data["No existen datos en esta sucursal"] = "No existe datos en esta sucursal";
                }
                echo json_encode($data);
                break;
        }
    }

    public function get_item_inventario()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->producto->get_item_inventario());
        } else {
            show_404();
        }
    }

    public function buscar_producto_codigo_barras()
    {
        $codigo = $this->input->post_get('codigo');
        $almacen = $this->input->post_get('almacen_id');
        $this->db->select('*')
            ->from('inventario')
            ->where('codigo_barra', $codigo)
            ->where('id_almacen', $almacen)
            ->where('id_sucursal', get_branch_id_in_session());
        $res = $this->db->get();
        if ($res->num_rows() > 0) {
            foreach ($res->result_array() as $row) {
                $data[$row['nombre_item'] . '|' . $row['talla'] . '|' . $row['color'] . "|" . $row['precio_venta'] . "|" . $row['cantidad']] = $row['producto_id'] . "|" . $row['id_talla'] . "|" . $row['id_color'];
            }
            echo json_encode($data); //format the array into json data
        } else {
            $data["No existen datos en esta sucursal"] = "No existe datos en esta sucursal";
            echo json_encode($data);
        }
    }

    public function get_all_items()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order
            );

            echo json_encode($this->producto->get_all_items($params));
        } else {
            show_404();
        }
    }

    public function get_all_type_item()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->producto->get_all_type_item());
        } else {
            show_404();
        }
    }

    //region Metodo adicional para categorias internas y marcas
    public function get_categoria_interna()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->producto->get_categoria_interna());
        } else {
            show_404();
        }
    }

    public function get_unidad_medidas()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->producto->get_unidad_medidas());
        } else {
            show_404();
        }
    }

    public function get_unidad_medida()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->producto->get_unidad_medida());
        } else {
            show_404();
        }
    }

    public function get_marcas()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->producto->get_marcas());
        } else {
            show_404();
        }
    }

    //
    public function registrar_marca()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->producto->registrar_marca();
        } else {
            show_404();
        }
    }

    public function registrar_categoria_interna()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->producto->registrar_categoria_interna();
        } else {
            show_404();
        }
    }

    public function registrar_unidad_medida()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->producto->registrar_unidad_medida();
        } else {
            show_404();
        }
    }

    public function get_color_inventario_venta()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->producto->get_color_inventario_venta());
        } else {
            show_404();
        }
    }

    public function get_talla_inventario_venta()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->producto->get_talla_inventario_venta());
        } else {
            show_404();
        }
    }

    //endregion

    public function get_color_by_id()
    {
        if ($this->input->is_ajax_request()) {
            $color_id = $this->input->post('color_id');
            echo json_encode($this->producto->get_color_by_id($color_id));
        } else {
            show_404();
        }
    }

    public function get_talla_by_id()
    {
        if ($this->input->is_ajax_request()) {
            $talla_id = $this->input->post('talla_id');
            echo json_encode($this->producto->get_talla_by_id($talla_id));
        } else {
            show_404();
        }
    }

    //actualizar el estado de producto produccion a producto existente
    public function modificar_estado_producto()
    {
        if ($this->input->is_ajax_request()) {

            $ingreso_id = $this->input->post('idIngre');
            $producto_id = $this->input->post('idProducto');
            $res = $this->producto_produccion->cambiar_estado_produccion($ingreso_id, $producto_id);
            if ($res !== 1) {
                echo 'true';
            } else {
                echo 'error';
            }
        } else {
            show_404();
        }
    }

}