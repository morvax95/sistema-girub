<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function get_all()
    {
        return $this->db->get('cliente')->result();
    }

    public function get_size()
    {
        return $this->db->get('talla')->result();
    }

    // Obtiene los datos de un cliente seleccionado por su id
    public function get_customer_by_id($id)
    {
        return $this->db->get_where('cliente', array('id' => $id))->row();
    }

    // Obtener datos de todos los clientes
    public function listar_clientes($params = array())
    {
        if (!empty($this->input->post('draw'))) {

            $this->db->start_cache();
            $this->db->select('*')
                ->from('cliente c')
                ->where('c.id > 0');
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
                $this->db->order_by("c.{$params['column']}", $params['order']);
            } else {
                $this->db->order_by('c.id', 'ASC');
            }

            if (array_key_exists('search', $params) && !empty($params['search'])) {
                $this->db->like('c.ci_nit', $params['search']);
                $this->db->or_like('lower(c.nombre_cliente)', $params['search']);
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
            return $this->db->get_where('cliente', array('estado' => get_state('ACTIVO')))->result();
        }
    }
    public function get_customer_by_id1($id)
    {
        return $this->db->get_where('bitacora', array('id' => $id))->row();
    }
   
    // Registro de cliente 
    public function registrar_cliente()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        $this->form_validation->set_rules('nombre_cliente', 'nombre', 'trim|required');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim|required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_cliente['ci_nit'] = $this->input->post('ci_nit');
            $obj_cliente['nombre_cliente'] = mb_strtoupper($this->input->post('nombre_cliente'), "UTF-8");
            $obj_cliente['telefono'] = $this->input->post('telefono');
            $obj_cliente['trabajo'] = mb_strtoupper($this->input->post('trabajo'), "UTF-8");
            $obj_cliente['estado'] = get_state('ACTIVO');
            $obj_cliente['correo'] = $this->input->post('correo');
            $obj_cliente['fecha_registro'] = date('Y-m-d H:i:s');
            $obj_cliente['fecha_modificacion'] = date('Y-m-d H:i:s');
            $obj_cliente['direccion'] = mb_strtoupper($this->input->post('direccion'), "UTF-8");
            $obj_cliente['usuario_id'] = 1;

            $obj_cliente1['operacion'] = "INSERCIÓN";
            $obj_cliente1['usuario'] =1;
            $obj_cliente1['host'] = "LOCALHOST";
            $obj_cliente1['fechaRegistro'] = date('Y-m-d H:i:s');
            $obj_cliente1['fechaModificacion'] = "";
            $obj_cliente1['tabla'] = "CLIENTE";
            $this->db->insert('bitacora', $obj_cliente1);

            // Inicio de transacción
            $this->db->trans_begin();
            $this->db->insert('cliente', $obj_cliente);
            $last_customer_id = $this->db->insert_id();

            // Obtener resultado de transacción
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $response['success'] = TRUE;
                $response['customer'] = $this->get_customer_by_id($last_customer_id);
            } else {
                $this->db->trans_rollback();
                $response['success'] = FALSE;
            }

        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }



    // Modificacion de cliente 
    public function modificar_cliente()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

       

        $id_cliente = $this->input->post('id_cliente');
        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        //$this->form_validation->set_rules('ci_nit', 'CI o NIT', 'trim');
        $this->form_validation->set_rules('nombre_cliente', 'nombre', 'trim|required');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            $obj_cliente2['operacion'] = "MODIFICACIÓN";
            $obj_cliente2['usuario'] =1;
            $obj_cliente2['host'] = "LOCALHOST";
            $obj_cliente2['fechaRegistro'] ="";
            $obj_cliente2['fechaModificacion'] =date('Y-m-d H:i:s');
           $obj_cliente2['tabla'] = "CLIENTE";
            $this->db->insert('bitacora', $obj_cliente2);

            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_cliente['ci_nit'] = $this->input->post('ci_nit');
            $obj_cliente['nombre_cliente'] = mb_strtoupper($this->input->post('nombre_cliente'), "UTF-8");
            $obj_cliente['telefono'] = $this->input->post('telefono');
            $obj_cliente['trabajo'] = mb_strtoupper($this->input->post('trabajo'), "UTF-8");
            $obj_cliente['estado'] = get_state('ACTIVO');
            $obj_cliente['correo'] = $this->input->post('correo');
            $obj_cliente['fecha_modificacion'] = date('Y-m-d H:i:s');
            $obj_cliente['direccion'] = mb_strtoupper($this->input->post('direccion'), "UTF-8");
            $this->db->where('id', $id_cliente);
            $response['success'] = $this->db->update('cliente', $obj_cliente);

          

        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }

    // Cambia el estado del registro (prueba correcta)
    public function eliminar($id)
    {
        $obj_cliente2['operacion'] = "ELIMINACIÓN";
        $obj_cliente2['usuario'] =1;
        $obj_cliente2['host'] = "LOCALHOST";
        $obj_cliente2['fechaRegistro'] =date('Y-m-d H:i:s');
        $obj_cliente2['fechaModificacion'] = "";
       $obj_cliente2['tabla'] = "CLIENTE";
        $this->db->insert('bitacora', $obj_cliente2);

        $this->db->where('id', $id);
        return $this->db->update('cliente', array('estado' => get_state('INACTIVO')));

    
    }

    // Cambia el estado del registro a activo (prueba correcta)
    public function habilitar($id)
    {
        $obj_cliente2['operacion'] = "ACTIVACIÓN";
        $obj_cliente2['usuario'] =1;
        $obj_cliente2['host'] = "LOCALHOST";
        $obj_cliente2['fechaRegistro'] =date('Y-m-d H:i:s');
        $obj_cliente2['fechaModificacion'] = "";
       $obj_cliente2['tabla'] = "CLIENTE";
        $this->db->insert('bitacora', $obj_cliente2);

        $this->db->where('id', $id);
        return $this->db->update('cliente', array('estado' => get_state('ACTIVO')));
    }

    /*-------------------------------------------------------------------
    * Metodo llamado en la funcion registroVenta para extraer al cliente PARTICULAR en casco
    * de que el usuario no haya puesto datos del cliente en formulario
    * **/
    public function obtener_cliente_particular()
    {
        return $this->db->get_where('cliente', array('id' => 1))->row();
    }

    public function exite_cliente($nitCliente)
    {
        $resultado = $this->db->get_where('cliente', array('ci_nit' => $nitCliente));
        if ($resultado->num_rows() > 0) {
            // Si esta registrado
            return true;
        } else {
            // No esta registrado
            return false;
        }
    }

    // Registro de cliente desde la interfaz de venta
    public function registrar_cliente_venta($cliente)
    {
        $this->db->insert('cliente', $cliente);
        $res = $this->db->get_where('cliente', array('ci_nit' => $cliente['ci_nit'], 'nombre_cliente' => $cliente['nombre_cliente']))->row();
        return $res->id;
    }

    public function deudas_por_cobrar()
    {
        $this->db->select("count(venta_id) as codigo")
            ->from("venta_pago ")
            ->where("estado='debe'")
            ->group_by("venta_id");
        $result = $this->db->get()->result();
        return $result;
    }

    public function ventas_mes()
    {
        $user_data = $this->session->userdata('usuario_sesion');
        $mes_actual = date('m');
        $result = $this->db->query("select * from venta i  where month( i.fecha) = ?  ", array($mes_actual))->result();
        return $result;
    }

    public function get_birthday()
    {
        $mes_actual = date('m');
        $result = $this->db->query("select * from cliente where DAYOFMONTH('2018-06-06') = ?", array($mes_actual))->result();
        return $result;
    }

}