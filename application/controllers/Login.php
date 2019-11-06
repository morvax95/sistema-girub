<?php

/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 23/03/2018
 * Time: 08:22 AM
 */
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model','login');
        $this->load->model('sucursal_model','sucursal');

    }

    function index(){
        $res['sucursal'] = $this->sucursal->get_all();
        $this->load->view('login/login',$res);
    }

    function verificar(){
        if ($this->input->is_ajax_request()) {

            $username = $this->input->post('usuario');
            $clave = $this->input->post('clave');
            $sucursal = $this->input->post('sucursal');

            /*  Mensajes de respuesta
             *  error1 = datos incorrectos
             *  error2 = usuario de baja.
             * */
            echo $this->login->login($username, $clave,$sucursal);
        } else {
            show_404();
        }
    }

    function set_impresora(){
        $this->load->view('login/set_impresora');
    }

    function guardarSesion(){
//        if ($this->input->is_ajax_request())
//        {
            $user_id = $this->input->post('user_id');
            $impre_id = $this->input->post('impresora_sel');
            $marca = $this->input->post('marca');

            echo $this->login->inicio_sesion($user_id, $impre_id,$marca);
//        } else {
//            show_404();
//        }
    }

    public function cerrar_sesion()
    {
        $this->login->cerrar_sesion();
        $this->session->sess_destroy();
        redirect(site_url('login'));
    }

    public function cerrar(){
        $this->session->sess_destroy();
        redirect(site_url('login'));
    }

    public function registrar_sucursal(){
        $registro['nit'] = trim($this->input->post('nit_empresa'));
        $registro['nombre_empresa'] = trim(mb_strtoupper($this->input->post('nombre_empresa'),'UTF-8'));
        $registro['sucursal'] = trim($this->input->post('nombre_sucursal'));
        $registro['estado'] = 1;

        $this->db->insert('sucursal',$registro);

        // Registramos la impresora
        $impresora['marca'] = trim($this->input->post('marca_impresora'));
        $impresora['serial'] = trim($this->input->post('serial_impresora'));
        $impresora['sucursal_id'] = 1;
        $impresora['activo'] = 0;
        $this->db->insert('impresora',$impresora);

        redirect(site_url('login'));
    }
}