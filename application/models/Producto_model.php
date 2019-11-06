<?php

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 19/02/2018
 * Time: 09:53 AM
 */
class Producto_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    // Cambia el estado del registro a inactivo
    public function eliminar_producto($id)
    {
        $cmd['operacion'] = "ELIMINACIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "PRODUCTO";
        $this->db->insert('bitacora', $cmd);

        $this->db->where('id', $id);
        return $this->db->update('producto', array('estado' => get_state('INACTIVO')));
    }

    // Cambia el estado del registro a activo (prueba correcta)
    public function habilitar_producto($id)
    {
        $cmd['operacion'] = "ACTIVACIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "PRODUCTO";
        $this->db->insert('bitacora', $cmd);

        $this->db->where('id', $id);
        return $this->db->update('producto', array('estado' => get_state('ACTIVO')));
    }

    //region Funciones de producto

    public function get_all()
    {
        return $this->db->get('producto')->result();
    }

    public function get_item_by_id($id)
    {
        $this->db->select('i.*,t.descripcion as nombre_tipo_item')
            ->from('producto i, tipo_item t')
            ->where('i.tipo_item_id = t.id')
            ->where('i.id', $id);
        return $this->db->get()->row();
    }

    // Registro de productos
    public function registrar_producto()
    {
        $cmd['operacion'] = "REGISTRO";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "PRODUCTO";
        $this->db->insert('bitacora', $cmd);

        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        $this->form_validation->set_rules('codigo_barras', 'codigo de barras', 'trim|required|is_unique[producto.codigo_barra]');
        $this->form_validation->set_rules('nombre_item', 'nombre de producto', 'trim|required');
        $this->form_validation->set_rules('precio_venta', 'precio de venta', 'trim|required');
        //  $this->form_validation->set_rules('precio_compra', 'precio de compra', 'trim');
        $this->form_validation->set_rules('stock_minimo', 'stock minimo', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $codigo = $this->input->post('codigo_barras');
            $tipo_producto = $this->input->post('tipo_item');
            $tipo = 2; // Materia prima
            if ($tipo_producto == 'Producto') {
                $tipo = 1; // Para productos
            }
            $obj_item['codigo_barra'] = $codigo;
            $obj_item['nombre_item'] = mb_strtoupper($this->input->post('nombre_item'), "UTF-8");
            $obj_item['precio_venta'] = $this->input->post('precio_venta');
            $obj_item['precio_compra'] = $this->input->post('precio_compra');
            $obj_item['stock_minimo'] = 1;//$this->input->post('stock_minimo');
            $obj_item['descripcion'] = mb_strtoupper($this->input->post('descripcion'), "UTF-8");
            $obj_item['dimension'] = $this->input->post('dimension');
            $obj_item['marca_id'] = $this->input->post('marca_id');
            $obj_item['categoria_interna_id'] = $this->input->post('categoria_interna_id');
            $obj_item['unidad_medida'] = $this->input->post('unidad_medida_id');
            $obj_item['unidad_compra'] = $this->input->post('unidad_compra_id');
            $obj_item['fecha_registro'] = date('Y-m-d H:i:s');
            $obj_item['talla_id'] = 26;
            $obj_item['color_id'] = 1;
            $obj_item['estado'] = get_state('ACTIVO');
            $obj_item['tipo_item_id'] = $tipo;


            $response['success'] = $this->db->insert('producto', $obj_item);

            // Creamos la imagen del codigo de barras
            /*include APPPATH . '/libraries/barcode.inc.php';
            $dir = $this->config->item('dir');
            new barCodeGenrator($codigo, 1, $dir . $codigo . '.png', 190, 130, true);*/

        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($response);
    }

    // Modificacion de producto seleccionado
    public function modificar_producto()
    {
        $cmd['operacion'] = "MODIFICACIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] ="";
        $cmd['fechaModificacion'] =date('Y-m-d H:i:s');
        $cmd['tabla'] = "PRODUCTO";
        $this->db->insert('bitacora', $cmd);

        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        $id_item = $this->input->post('id_item');
        //$this->form_validation->set_rules('codigo_barras', 'codigo de barras', 'trim|is_unique_edit[' . $id_item . ',producto, codigo_barra]');
        $this->form_validation->set_rules('nombre_item', 'nombre de producto', 'trim|required');
        //$this->form_validation->set_rules('precio_venta', 'precio de venta', 'trim|required');
        // $this->form_validation->set_rules('precio_compra', 'precio de compra', 'trim');
        //$this->form_validation->set_rules('stock_minimo', 'stock minimo', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $codigo = $this->input->post('codigo_barras');
            $obj_item['codigo_barra'] = $codigo;
            $obj_item['nombre_item'] = mb_strtoupper($this->input->post('nombre_item'), "UTF-8");
            $obj_item['precio_venta'] = $this->input->post('precio_venta');
            $obj_item['precio_compra'] = $this->input->post('precio_compra');
            $obj_item['stock_minimo'] = $this->input->post('stock_minimo');
            $obj_item['dimension'] = $this->input->post('dimension');
            $obj_item['descripcion'] = mb_strtoupper($this->input->post('descripcion'), "UTF-8");
            $obj_item['unidad_medida'] = $this->input->post('unidad_medida_id');
            $obj_item['unidad_compra'] = $this->input->post('unidad_compra_id');
            $obj_item['fecha_modificacion'] = date('Y-m-d H:i:s');
            $obj_item['marca_id'] = $this->input->post('marca_id');
            $obj_item['categoria_interna_id'] = $this->input->post('categoria_interna_id');
            $obj_item['talla_id'] = 26;
            $obj_item['color_id'] = 1;

            $this->db->where('id', $id_item);
            $response['success'] = $this->db->update('producto', $obj_item);

            /* include APPPATH . '/libraries/barcode.inc.php';
            $dir = $this->config->item('dir');
            $codigo_viejo = $this->input->post('codigo_barras_old');
            if ($codigo != $codigo_viejo) {
                if (file_exists($dir . $codigo_viejo . '.png')) {
                    unlink($dir . $codigo_viejo . '.png');
                }
            }
            new barCodeGenrator($codigo, 1, $dir . $codigo . '.png', 190, 130, true);*/

        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($response);
    }

    // Obtiene el producto segun el
    // @todo Ver si esta funcion es necesaria
    public function get_items()
    {
        return $this->db->get_where('producto', array('estado' => get_state('ACTIVO')))->result();
    }

    // Obtiene todos los items usan procesamiendo de datatable (probado)
    public function get_all_items($params = array())
    {
        if (!empty($this->input->post('draw'))) {

            $this->db->start_cache();
            $this->db->select('*')
                ->from('producto p')
                ->where('p.id > 0');
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
                $this->db->order_by("p.{$params['column']}", $params['order']);
            } else {
                $this->db->order_by('p.id', 'ASC');
            }

            if (array_key_exists('search', $params) && !empty($params['search'])) {
                $this->db->like('p.nombre_item', $params['search']);
                $this->db->or_like('lower(p.nombre_item)', $params['search']);
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
        } else {
            return $this->db->get_where('producto', array('estado' => get_state('ACTIVO')))->result();
        }
    }


    //@todo Verificar si esta funcion se va a utilizar
    public function get_all_inventury()
    {

        $this->db->select('t.id, t.nombre_item, sum(it.cantidad_actual) as stock, t.precio_venta, it.talla');
        $this->db->from('ingreso_inventario i, ingreso_item it, producto t');
        $this->db->where('i.id = it.ingreso_id');
        $this->db->where('it.item_id = t.id');
        $this->db->where('t.estado', get_state('ACTIVO'));
        $this->db->group_by('t.id,it.talla');
        return $this->db->get()->result();
    }

    //endregion


    //region Funciones de tipo producto

    // Obtiene todos los tipo_item habilitados
    public function registrar_tipo_item()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        $this->form_validation->set_rules('descripcion', 'descripcion', 'trim|required]');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_item['codigo_barra'] = trim($this->input->post('codigo_barras'));
            $obj_item['codigo_alterno'] = trim($this->input->post('codigo_alterno'));
            $obj_item['nombre_item'] = mb_strtoupper($this->input->post('nombre_item'), "UTF-8");
            $obj_item['precio_venta'] = $this->input->post('precio_venta');
            $obj_item['tipo_item_id'] = $this->input->post('tipo_item');
            $obj_item['stock_minimo'] = trim($this->input->post('stock'));
            $obj_item['fecha_registro'] = date('Y-m-d');
            if ($this->input->post('control_inventario')) {
                $control = 1;
            } else {
                $control = 0;
            }
            $obj_item['control_inventario'] = $control;
            $obj_item['estado'] = get_state('ACTIVO');
            $obj_item['precio_compra'] = $this->input->post('precio_compra');

            $response['success'] = $this->db->insert('Producto', $obj_item);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($response);
    }

    public function get_all_type_item()
    {
        return $this->db->get_where('tipo_item', array('estado' => get_state('ACTIVO')))->result();
    }

    //endregion


    //region Metodos alternos de marca y categoria interna

    // Metodo para registrar los marca y categoria interna
    public function registrar_marca()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $this->form_validation->set_rules('descripcion_marca', 'nombre marca', 'trim|required|is_unique[marca.descripcion]');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_data['descripcion'] = mb_strtoupper($this->input->post('descripcion_marca'), "UTF-8");
            $response['success'] = $this->db->insert('marca', $obj_data);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }

    public function registrar_categoria_interna()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $this->form_validation->set_rules('descripcion_categoria_interna', 'descripcion categoria', 'trim|required|is_unique[categoria_interna.descripcion]');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_data['descripcion'] = mb_strtoupper($this->input->post('descripcion_categoria_interna'), "UTF-8");
            $response['success'] = $this->db->insert('categoria_interna', $obj_data);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }

    public function registrar_unidad_medida()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $this->form_validation->set_rules('descripcion_unidad_medida', 'descripcion unidad', 'trim|required|is_unique[unidad_medida.descripcion]');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_data['descripcion'] = mb_strtoupper($this->input->post('descripcion_unidad_medida'), "UTF-8");
            $obj_data['abreviatura'] = mb_strtoupper($this->input->post('abreviatura_unidad_medida'), "UTF-8");
            $response['success'] = $this->db->insert('unidad_medida', $obj_data);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }


    // Obtiene todas los marcas
    public function get_marcas()
    {
        $this->db->order_by('descripcion', 'ASC');
        return $this->db->get('marca')->result();
    }

    // Obtiene todas las categorias internas
    public function get_categoria_interna()
    {
        $this->db->order_by('descripcion', 'ASC');
        return $this->db->get('categoria_interna')->result();
    }

    // Obtiene todas las unidades de medidas
    public function get_unidad_medidas()
    {
        $this->db->order_by('descripcion', 'ASC');
        return $this->db->get('unidad_medida')->result();
    }

    public function get_unidad_medida()
    {
        $this->db->order_by('descripcion', 'ASC');
        return $this->db->get('unidad_medida')->result();
    }

    // Obtiene todas las marcas
    public function get_marca()
    {
        $this->db->order_by('descripcion', 'ASC');
        return $this->db->get('marca')->result();
    }

    // Obtiene todas las categorias
    public function get_categoria()
    {
        $this->db->order_by('descripcion', 'ASC');
        return $this->db->get('categoria_interna')->result();
    }
    //endregion

    /*********************************** Retorna los colores de los pantalones que cumplen la condicion LIKe *******/
    public function get_color_inventario_venta()
    {
        $nombre_item = $this->input->post('item_name');
        $talla_id = $this->input->post('talla_id');
        $this->db->select('color, id_color')
            ->from('inventario')
            ->where('nombre_item', $nombre_item)
            ->where('id_talla', $talla_id)
            ->where('id_sucursal', get_branch_id_in_session())
            ->group_by('color,id_color')
            ->order_by('color');
        return $data = $this->db->get()->result();
    }

    /*********************************** Retorna los colores de los pantalones que cumplen la condicion LIKe *******/
    public function get_talla_inventario_venta()
    {
        $nombre_item = $this->input->post('item_name');
        $this->db->select('talla, id_talla')
            ->from('inventario')
            ->where('nombre_item', $nombre_item)
            ->where('id_sucursal', get_branch_id_in_session())
            ->group_by('talla,id_talla')
            ->order_by('talla');
        return $data = $this->db->get()->result();
    }


    public function get_color_by_id($color_id)
    {
        $this->db->order_by('descripcion', 'ASC');
        $this->db->where('id', $color_id);
        return $this->db->get('color')->result();
    }

    public function get_talla_by_id($talla_id)
    {
        $this->db->order_by('descripcion', 'ASC');
        $this->db->where('id', $talla_id);
        return $this->db->get('talla')->result();
    }


    public function get_item_inventario()
    {
        $nombre_item = $this->input->post('item_name');
        $talla_id = $this->input->post('talla_id');
        $color_id = $this->input->post('color_id');
        $this->db->select('*')
            ->from('inventario')
            ->where('id_talla', $talla_id)
            ->where('id_color', $color_id)
            ->where('nombre_item', $nombre_item);
        return $this->db->get()->row();
    }
}