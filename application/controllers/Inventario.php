<?php

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 01/03/2018
 * Time: 10:48 AM
 */
class Inventario extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inventario_model', 'inventario');
        $this->load->model('almacen_model', 'almacen');
        $this->load->model('sucursal_model', 'sucursal');
      //  $this->load->model('unidad_model', 'unidad');
    }


    //region Vistas de ingreso de producto


    public function index()
    {
        plantilla('inventario/index');
    }

    public function nuevo()
    {
        $data['almacenes'] = $this->almacen->get_all();
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('inventario/nuevo', $data);
    }

    public function materia_prima()
    {
        $data['almacenes'] = $this->almacen->get_all();
        $data['sucursales'] = $this->sucursal->get_all();
        $data['unidades'] = $this->unidad->get_all();
        plantilla('inventario/materia', $data);
    }

    public function vista_inventario_producto()
    {
        plantilla('inventario/vista_producto');
    }

    public function vista_inventario_materia()
    {
        plantilla('inventario/vista_materia');
    }

    public function ver()
    {
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $data = $this->inventario->get_income_by_id($id);
            plantilla('inventario/ver', $data);
        } else {
            show_404();
        }
    }
    //endregion


    //region Metodos para ingreso a inventario

    public function get_all()
    {
        if ($this->input->is_ajax_request()) {
            $tipo_producto = $this->input->post('tipo_producto');
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

            echo json_encode($this->inventario->get_all($params, $tipo_producto));
        } else {
            show_404();
        }
    }

    public function get_all_entry()
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

            echo json_encode($this->inventario->get_all_entry($params));
        } else {
            show_404();
        }
    }

    // Registro de los datos de productos
    public function registrar()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->inventario->registrar();
        } else {
            show_404();
        }
    }

    // Registro de los datos de materia prima
    public function registrar_materia()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->inventario->registrar_materia();
        } else {
            show_404();
        }
    }

    public function agregar()
    {
        if ($this->input->is_ajax_request()) {
            // Recibimos los parametros del formulario
            $contador = $this->input->post('contador_inventario');
            $id_producto = $this->input->post('producto_id');
            $color_id = $this->input->post('color_id');
            $talla_id = $this->input->post('talla_id');
            $color = $this->input->post('seleccion_color');
            $talla = $this->input->post('seleccion_talla');
            $descripcion = $this->input->post('nombre_producto');
            $cantidad = $this->input->post('cantidad_inventario');
            $precio = $this->input->post('precio_venta');
            // Creamos las filas del detalle
            $fila = '<tr>';
            $fila .= '<td><input type="text" value="' . $id_producto . '" id="id_producto" name="id_producto[]" hidden/><input type="text" value="' . $descripcion . '" id="descripcion" name="descripcion[]" hidden/>' . $descripcion . '</td>';
            $fila .= '<td><input type="text" value="' . $cantidad . '" id="cantidad' . $contador . '" name="cantidad[]" hidden/>' . $cantidad . '</td>';
            $fila .= '<td><input id="precio_inventario' . $contador . '" name="precio_inventario[]" value="' . $precio . '" hidden>' . round($precio, 2) . '</td>';
            //  $fila .= '<td><input id="color' . $contador . '" name="color[]" value="' . $color_id . '" hidden>' . $color . '</td>';
            //  $fila .= '<td><input id="talla' . $contador . '" name="talla[]" value="' . $talla_id . '" hidden>' . $talla . '</td>';
            $fila .= '<td class="text-center"><a class="eliminar"><i class="fa fa-trash-o" /></a></td></tr>';

            $datos = array(
                0 => $fila,
                1 => $contador
            );

            echo json_encode($datos);
        } else {
            show_404();
        }
    }

    public function agregar_materia()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('unidad_model', 'unidad');
            // Recibimos los parametros del formulario
            $contador = $this->input->post('contador_inventario');
            $id_producto = $this->input->post('producto_id');
            $descripcion = $this->input->post('nombre_producto');
            $unidad = $this->input->post('seleccion_unidad');
            $cantidad = $this->input->post('cantidad_inventario');
            // Creamos las filas del detalle
            $fila = '<tr>';
            $fila .= '<td><input type="text" value="' . $id_producto . '" id="id_producto" name="id_producto[]" hidden/><input type="text" value="' . $descripcion . '" id="descripcion" name="descripcion[]" hidden/>' . $descripcion . '</td>';
            $fila .= '<td><input id="unidad' . $contador . '" name="unidad[]" value="' . $unidad . '" hidden>' . $this->unidad->get_unit_by_id($unidad) . '</td>';
            $fila .= '<td><input type="text" value="' . $cantidad . '" id="cantidad' . $contador . '" name="cantidad[]" hidden/>' . $cantidad . '</td>';
            $fila .= '<td class="text-center"><a class="eliminar"><i class="fa fa-trash-o" /></a></td></tr>';

            $datos = array(
                0 => $fila,
                1 => $contador
            );

            echo json_encode($datos);
        } else {
            show_404();
        }
    }


    public function get_color_by_id($data)
    {
        $name = $this->db->get_where('color', array('id' => $data))->row()->descripcion;
        return $name;
    }


    public function get_size_by_id($data)
    {
        $name = $this->db->get_where('talla', array('id' => $data))->row()->descripcion;
        return $name;
    }
    //endregion

}