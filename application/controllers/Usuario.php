<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 25/02/2018
 * Time: 21:00 PM
 */
class Usuario extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuario_model','usuario');
    }
    public function index()
    {
        plantilla('usuario/index',null);
    }
    public function nuevo()
    {
        $this->load->model('login_model','login');
        $res['sucursal'] = $this->login->obtener_sucursales();
        $res['cargos']   = $this->db->get('cargo')->result();
        $res['menu'] = $this->usuario->get_menu();
        $res['sucursales'] = $this->db->get('sucursal')->result();
        plantilla('usuario/nuevo_usuario',$res);
    }
    public function editar() {
        if ($this->input->post()){

            $usuario_id = $this->input->post('id');
            $data['menu'] = $this->usuario->get_menu();
            $data['funciones_seleccionadas'] = $this->usuario->obtener_privilegios_seleccionados($usuario_id);
            $data['sucursales'] = $this->db->get('sucursal')->result();
            $data['sucursales_seleccionadas'] = $this->usuario->obtener_sucursales_seleccionadas($usuario_id);
            $data['usuario'] = $this->usuario->obtener_usuario($usuario_id);
            $data['cargos']   = $this->db->get('cargo')->result();
            plantilla('usuario/editar_usuario',$data);
        }else{
            show_404();
        }
    }
    public function registrar_usuario()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->usuario->registrar_usuario();
        } else {
            show_404();
        }
    }
    public function editar_usuario()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->usuario->editar_usuario();
        } else {
            show_404();
        }
    }

    public function eliminar()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            echo $this->usuario->eliminar($id);
        } else {
            show_404();
        }
    }

    public function obtener_usuario(){
        $id_usuario = $this->input->post('usuario');
        $datos = $this->usuario->obtener_usuario($id_usuario);
        echo json_encode($datos);
    }

    /********* Funcion para obtener los usuario con el nombre y apellido concatenado para autocompletar *******/
    public function getUsuarios(){
        $res = $this->usuario->getUsuarios();
        echo json_encode($res);
    }

    /******************* FUNCION PARA OBTENER A TODOS LOS usuario *********************/
    public function obtener_usuarios()
    {
        $datos = $this->usuario->obtener_usuarios();
        echo json_encode($datos);
    }

    /***************** FUNCION QUE ACTIVA LOS usuario DADOS DE BAJA ******************/
    public function reactivar()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id_usuario');
            $res = $this->usuario->reactivar($id);
            if ($res){
                echo true;
            }else{
                echo false;
            }
        } else {
            show_404();
        }
    }

    public function obtener_privilegios(){
        $res = $this->usuario->obtener_privilegios();
        echo json_encode($res);
    }

}