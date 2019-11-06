<?php

class Egreso_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function registrar_tipo_egreso()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $this->form_validation->set_rules('descripcion', 'descripcion', 'trim|required');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        if ($this->form_validation->run() === true) {
            $nombre = mb_strtoupper($this->input->post('descripcion'), "UTF-8");
            $tipo_egreso = array(
                'descripcion' => $nombre,
                'estado' => get_state('ACTIVO'),
                'tipo_dato' => 0    // egreso = 0
            );
            $response['success'] = $this->db->insert('tipo_ingreso_egreso', $tipo_egreso);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }


    public function registro_egreso()
    {
        $cmd['operacion'] = "INSERCIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "EGRESO";
        $this->db->insert('bitacora', $cmd);

        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $this->form_validation->set_rules('fecha_egreso', 'fecha de egreso', 'trim|required');
        $this->form_validation->set_rules('monto', 'monto', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $egreso['fecha_registro'] = date('Y-m-d');
            $egreso['fecha_egreso'] = $this->input->post('fecha_egreso');
            $egreso['detalle'] = $this->input->post('detalle');
            $egreso['monto'] = $this->input->post('monto');
            $egreso['estado'] = get_state('ACTIVO');
            $egreso['tipo_egreso_id'] = $this->input->post('tipo_egreso');
            $egreso['sucursal_id'] = get_branch_id_in_session();
            $egreso['usuario_id'] = get_user_id_in_session();
            $response['success'] = $this->db->insert('egreso_caja', $egreso);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }

    // Registro de egreso por compra
    public function registrar_egreso_compra($data, $id_compra)
    {
        $this->db->trans_start();
        $egreso['fecha_registro'] = $data['fecha_registro'];
        $egreso['fecha_egreso'] = $data['fecha_egreso'];
        $egreso['detalle'] = $data['detalle'];
        $egreso['monto'] = $data['monto'];
        $egreso['estado'] = get_state('ACTIVO');
        $egreso['tipo_egreso_id'] = $data['tipo_egreso'];
        $egreso['sucursal_id'] = get_branch_id_in_session();
        $egreso['usuario_id'] = get_user_id_in_session();

        $this->db->insert('egreso_caja', $egreso);
        $id_egreso = $this->db->insert_id();

        // Registramos el egreso por compra
        $response['success'] = $this->db->insert('egreso_compra', array('compra_id' => $id_compra, 'egreso_id' => $id_egreso));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response['success'] = false;
        } else {
            $this->db->trans_commit();
            $response['success'] = TRUE;
        }
        return json_encode($response);
    }


    public function modificar()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $this->form_validation->set_rules('fecha_egreso', 'fecha de egreso', 'trim|required');
        $this->form_validation->set_rules('monto', 'monto', 'trim|required');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        if ($this->form_validation->run() === true) {
            $id_egreso = $this->input->post('id_egreso');
            $egreso['fecha_egreso'] = $this->input->post('fecha_egreso');
            $egreso['detalle'] = $this->input->post('detalle');
            $egreso['monto'] = $this->input->post('monto');
            $egreso['tipo_egreso_id'] = $this->input->post('tipo_egreso');

            $this->db->where('id', $id_egreso);
            $response['success'] = $this->db->update('egreso_caja', $egreso);
        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        return json_encode($response);
    }


    public function eliminar($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('egreso_caja', array('estado' => get_state('INACTIVO')));
    }


    public function get_all($params = array())
    {
        $this->db->start_cache();
        $this->db->select('e.id,date(e.fecha_egreso) as fecha_egreso, e.monto, s.sucursal, e.estado, t.descripcion')
            ->from('egreso_caja e, tipo_ingreso_egreso t, sucursal s')
            ->where('e.tipo_egreso_id = t.id')
            ->where('e.sucursal_id = s.id')
            ->where('t.tipo_dato', 0)
            ->where('e.estado', get_state('ACTIVO'));
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
            $this->db->order_by("e.{$params['column']}", $params['order']);
        } else {
            $this->db->order_by('e.id', 'ASC');
        }

        if (array_key_exists('search', $params) && !empty($params['search'])) {
            $this->db->like('lower(t.descripcion)', $params['search']);
            $this->db->or_like('lower(s.sucursal)', $params['search']);
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

    public function get_egress_by_id($id)
    {
        return $this->db->get_where('egreso_caja', array('id' => $id))->row();
    }

    // Registramos el gasto del ingreso como egreso
    public function registrarGastoIngreso($campos)
    {
        $this->db->trans_start();
        $sesion = $this->session->userdata('usuario_sesion');
        //iniciamos la transaccion registrando primero el egreso con los datos de cuenta, cliente, y tipo de egreso.
        $idTipoEgreso = $this->db->query("Select id from tipo_ingreso_egreso WHERE descripcion = ? and tipo = 'EGRESO'", $campos['tipo_egreso'])->row();
        $idCuenta = $this->db->query("Select id from cuenta WHERE descripcion = ?", $campos['cuenta'])->row();
        $egreso = array(
            'tipo_egreso_id' => $idTipoEgreso->id,
            'cuenta_id' => $idCuenta->id,
            'glosa' => $campos['glosa'],
            'fecha_egreso' => $campos['fecha_egreso'],
            'hora' => $campos['hora'],
            'monto_egresado' => $campos['monto_gastado'],
            'comprobante' => $campos['comprobante'],
            'usuario_id' => $sesion['id'],
            'gestion_id' => $sesion['idGestion'],
            'estado' => 1
        );
        $this->db->insert('egreso', $egreso);
        // Obtenermos el id del egreso ingresado
        $idE = $this->maxEgreso();
        $idEgreso = $idE - 1;
        // Insertamos el detalle del egreso
        $detalle = array(
            'ingreso_id' => $campos['id_ingreso'],
            'egreso_id' => $idEgreso,
            'monto_depositado' => $campos['monto_depositado'],
            'monto_gastado' => $campos['monto_gastado'],
            'saldo' => $campos['monto_saldo'],
            'debe' => $campos['monto_deuda'],
            'comprobante' => $campos['comprobante'],
            'estado' => 1
        );

        $this->db->insert('detalle_egreso', $detalle);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 'error';
        } else {
            $this->db->trans_commit();
            return 'true';
        }
    }


    public function getListaEgresos()
    {
        $sesion = $this->session->userdata('usuario_sesion');
        $sql = "SELECT i.id, t.descripcion as tipo, DATE_FORMAT(i.fecha_egreso,'%d/%m/%Y')as fecha, c.descripcion, i.monto_egresado, i.estado, i.cuenta_id, i.comprobante
                FROM tipo_ingreso_egreso t, egreso i, cuenta c WHERE t.id = i.tipo_egreso_id and i.cuenta_id = c.id and t.tipo = 'EGRESO' and i.gestion_id = ?";
        $res = $this->db->query($sql, $sesion['idGestion']);
        return $res->result();
    }

    public function get_all_type_of_egress()
    {
        return $this->db->get_where('tipo_ingreso_egreso', array('tipo_dato' => 0))->result();
    }

    //para imprimir el pdf de la nota de egresos
    public function nota_egresos($codigo)
    {
        $this->db->select("e.id, e.fecha_egreso, e.monto, s.sucursal, e.estado, t.descripcion as tipo_egreso, t.descripcion,s.nombre_empresa,s.sucursal,s.direccion,s.telefono,u.nombre_usuario as usuario");
        $this->db->from("egreso_caja e, tipo_ingreso_egreso t, sucursal s,usuario u");
        $this->db->where("e.tipo_egreso_id = t.id");
        $this->db->where("e.sucursal_id = s.id");
        $this->db->where("e.usuario_id = u.id");
        if (!empty($codigo)) {
            $this->db->where("e.id ='" . $codigo . "'");

        }
        $resultado['datos'] = $this->db->get()->result();
        return $resultado;
    }

}