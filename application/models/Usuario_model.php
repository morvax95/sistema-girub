<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 25/02/2018
 * Time: 17:00 PM
 */
class Usuario_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function get_all(){
        return $this->db->get('usuario')->result();
    }

    /* --------------------------------------------------------
     * Funcion para obtener los datos un usuario seleccionado
     * --------------------------------------------------------
     * */
    public function obtener_usuario($id_usuario) {
        return $this->db->get_where('usuario',array('id'=>$id_usuario))->row();

    }

    /*-------------------------------------------------------
     * Funcion para obtener el listado de todos los usuarios
     * -------------------------------------------------------
     * */
    public function obtener_usuarios()
    {
        $this->db->select('usr.*, car.descripcion')
            ->from('usuario as usr')
            ->join('cargo as car', 'car.id = usr.cargo')
            ->where('usr.id !=',1);
        $cmd = $this->db->get();
        return $cmd->result();
    }

    /*------------------------------------------------------------------------------
     * Obtenemos los usuarios habilitados a excepcion del id =1 ya que es la cuenta por defecto
     *------------------------------------------------------------------------------
     */

    public function getUsuarios()
    {
        $sql = "SELECT id , nombre_usuario FROM usuario WHERE estado = 1 and id != 1";
        $cmd = $this->db->query($sql);
        return $cmd->result();
    }

    /* ----------------------------------------------------------------
     * Registramos a un usuario y sus privilegios seleccionados
     * ----------------------------------------------------------------
     */
    public function registrar_usuario()
    {
        $cmd['operacion'] = "INSERCIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "USUARIO";
        $this->db->insert('bitacora', $cmd);

        $this->db->trans_start();
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        $this->form_validation->set_rules('ci', 'CI', 'trim|required');
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim');
        $this->form_validation->set_rules('usuario', 'usuario', 'trim|required|min_length[3]|max_length[15]|is_unique[usuario.usuario]');
        $this->form_validation->set_rules('clave', 'clave', 'trim|required|min_length[3]|max_length[15]');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_usuario['ci'] = $this->input->post('ci');
            $obj_usuario['nombre_usuario'] = mb_strtoupper($this->input->post('nombre'), "UTF-8");
            $obj_usuario['telefono'] = $this->input->post('telefono');
            $obj_usuario['cargo'] = $this->input->post('cargo');
            $obj_usuario['usuario'] = $this->input->post('usuario');
            $obj_usuario['clave'] = password_hash($this->input->post('clave'), PASSWORD_BCRYPT);
            $obj_usuario['estado'] = get_state('ACTIVO');

            $response['success'] = $this->db->insert('usuario', $obj_usuario);
            $id_usuario = $this->db->insert_id();
            // Registramos las sucursales seleccionadas
            $lista_sucursales = $this->input->post('seleccion_sucursal');

            foreach ($lista_sucursales as $row) {
                $response['success'] = $this->db->insert('usuario_sucursal', array('usuario_id' => $id_usuario, 'sucursal_id' => $row));
            }

            // Registramos las funciones seleccionadas
            $menu = $this->input->post('menu');
            if ($obj_usuario['cargo'] != 1) {
                $this->registrar_privilegios_usuario($menu, $id_usuario);
            }

        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response['success'] = false;
        } else {
            $this->db->trans_commit();
            $response['success'] = true;
        }
        return json_encode($response);
    }

    /*-----------------------------------------------------------
     * Funcion para editar datos y privilegios de un usuario
     * ----------------------------------------------------------
     */
    public function editar_usuario()
    {
        $cmd['operacion'] = "MODIFICACIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] ="";
        $cmd['fechaModificacion'] =date('Y-m-d H:i:s');
        $cmd['tabla'] = "USUARIO";
        $this->db->insert('bitacora', $cmd);

        $this->db->trans_start();
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $id_usuario = $this->input->post('id_usuario');
        /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
        $this->form_validation->set_rules('ci', 'CI', 'trim|required');
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim');
        $this->form_validation->set_rules('usuario', 'usuario', 'trim|required|min_length[3]|max_length[15]|is_unique_edit['.$id_usuario.',usuario, usuario]');

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        if ($this->form_validation->run() === true) {
            /** OBTENERMOS VALORES DE LOS INPUT **/
            $obj_usuario['ci'] = $this->input->post('ci');
            $obj_usuario['nombre_usuario'] = mb_strtoupper($this->input->post('nombre'), "UTF-8");
            $obj_usuario['telefono'] = $this->input->post('telefono');
            $obj_usuario['cargo'] = $this->input->post('cargo');
            $obj_usuario['usuario'] = $this->input->post('usuario');

            $this->db->where('id',$id_usuario);
            $response['success'] = $this->db->update('usuario', $obj_usuario);

            // Registramos las sucursales seleccionadas
            $lista_sucursales = $this->input->post('seleccion_sucursal');

            // Borramos las sucursales registradas
            $this->db->where('usuario_id', $id_usuario);
            $this->db->delete('usuario_sucursal');

            foreach ($lista_sucursales as $row) {
                $response['success'] = $this->db->insert('usuario_sucursal', array('usuario_id' => $id_usuario, 'sucursal_id' => $row));
            }

            // Borramos las funciones seleccionadas
            $this->db->where('usuario_id', $id_usuario);
            $this->db->delete('acceso');

            // Registramos las funciones seleccionadas
            $menu = $this->input->post('menu');
            if ($obj_usuario['cargo'] != 1) {
                $this->registrar_privilegios_usuario($menu, $id_usuario);
            }

        } else {
            foreach ($_POST as $key => $value) {
                $response['messages'][$key] = form_error($key);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $response['success'] = false;
        } else {
            $this->db->trans_commit();
            $response['success'] = true;
        }
        return json_encode($response);
    }

    /*------------------------------------------------------------
     * Funcion para dar de baja a un usuario
     * -----------------------------------------------------------
     */
    public function eliminar($usuario)
    {
        $cmd['operacion'] = "ELIMINACIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "USUARIO";
        $this->db->insert('bitacora', $cmd);

        $this->db->where('id', $usuario);
        return $this->db->update('usuario', array('estado' => 0));
    }

    /*-----------------------------------------------------
     *  Funcion para habilitar a un usuario dado de baja
     * ----------------------------------------------------
     */
    public function reactivar($id)
    {
        $cmd['operacion'] = "ACTIVACIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "USUARIO";
        $this->db->insert('bitacora', $cmd);

        $this->db->where('id', $id);
        return $this->db->update('usuario', array('estado' => 1));
    }

    /*-----------------------------------------------------------------
     * Funcion para obtener todas las funciones hijas de la tabla menu
     *-----------------------------------------------------------------
    */
    public function obtener_privilegios()
    {
        $this->db->select("m.id, m.name")
            ->from("menu m")
            ->where("m.parent is not null");
        $res = $this->db->get();
        return $res->result();
    }

    /* ------------------------------------------------------------------------------
     * Funcion para registrar los privilegios seleccionados del usuario registrado
     * ------------------------------------------------------------------------------
     */
    public function registrar_privilegios_usuario($funciones, $id_usuario)
    {
        $this->db->trans_start();
        foreach ($funciones as $row) {
            $respuesta = $this->existe_menu_padre($row, $id_usuario);
            if ($respuesta == false) {
                $padre = $this->get_menu_padre($row);
                $this->db->insert('acceso', array('usuario_id' => $id_usuario, 'menu_id' => $padre));
            }
            // Aqui insertamos la funcion (hijo)
            $this->db->insert('acceso', array('usuario_id' => $id_usuario, 'menu_id' => $row));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function existe_menu_padre($id_menu, $id_usuario){

        $menu_padre = $this->get_menu_padre($id_menu);

        $this->db->select('COUNT(a.menu_id) AS existe');
        $this->db->from('acceso a');
        $this->db->where('a.usuario_id',$id_usuario);
        $this->db->where('a.menu_id',$menu_padre);
        $exite_padre = $this->db->get()->row()->existe;
        // Verificamos si el menu padre de la funcion analida existe,
        // si es cero => no existe
        // si es uno => existe;
        if ($exite_padre == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function get_menu_padre($menu_hijo){
        $this->db->select('parent');
        $this->db->from('menu m');
        $this->db->where('m.id',$menu_hijo);
        return $this->db->get()->row()->parent;
    }

    /* ----------------------------------------------------------------------------------
     * Verificacion de menu padre
     * ----------------------------------------------------------------------------------
     * Funcion que verifica si existen un modulo registrado de una funcion hija,
     * si existe = 0 este devuelve false;
     * si existe = 1 se devuelve el id del modulo.
     */
    public function verificar_menu($id_menu, $id_usuario)
    {
        $this->db->select("count(m.parent)existe, m.parent")
            ->from("menu m, acceso a, usuario u")
            ->where("m.id = a.menu_id")
            ->where("a.usuario_id = u.id")
            ->where("u.id", $id_usuario)
            ->where("m.id", $id_menu);
        $result = $this->db->get();
        $datos = $result->row();
        $existe = $datos->existe;
        $modulo = $datos->parent;
        if ($existe == 0) {
            return $modulo;
        } else {
            return false;
        }
    }

    /*----------------------------------------------------------------
    * Funcion para obtener los privilegios del usuario seleccionado
    *-----------------------------------------------------------------
    */
    public function obtener_privilegios_seleccionados($id)
    {
        $this->db->select("m.id, m.name")
            ->from("menu m, acceso a, usuario u")
            ->where("m.id = a.menu_id")
            ->where("a.usuario_id = u.id")
            ->where("u.id",$id);
        $res['seleccionados'] = $this->db->get()->result();
        return $res;
    }

    public function obtener_sucursales_seleccionadas($usuario_id) {
        $this->db->select("s.id")
            ->from("usuario u, usuario_sucursal su, sucursal s")
            ->where("u.id = su.usuario_id")
            ->where("su.sucursal_id = s.id")
            ->where("u.estado",get_state('ACTIVO'))
            ->where("u.id",$usuario_id);
        $res = $this->db->get()->result();
        return $res;
    }

    public function activar_inicio_usuario($user)
    {
        $this->db->set('activo', 1);
        $this->db->where('id', $user);
        $this->db->update('usuario');
    }

    //region Metodos para el cargado de modulos y funciones del menu

    public function get_menu()
    {
        $this->db->select('id');
        $this->db->from('menu');
        $this->db->where('parent is null');
//        $res = $this->db->query('select id from menu_cms where parent is null')->result();
        $res = $this->db->get()->result();
        $menu = [];
        foreach ($res as $row) {
            $lista = [];
            $lista['modulos'] = $this->get_modulos($row->id);
            $lista['funciones'] = $this->get_funciones($row->id);
            $menu[] = $lista;
        }
        return $menu;
    }

    /**
     * Obtiene un modulo en especifico
     * **/
    public function get_modulos($id)
    {
        return $this->db->get_where('menu', array('id' => $id))->row();
    }

    /**
     * Obtiene todas las funciones de un modulo padre
     ***/
    public function get_funciones($idModulo)
    {
        $res = $this->db->get_where('menu', array('parent' => $idModulo))->result();
        return $res;
    }
    //endregion
}