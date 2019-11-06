<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 23/02/2018
 * Time: 04:27 PM
 */
class Inicio extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inicio_model', 'inicio');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $res = $this->inicio->obtener_avisos();
        plantilla('inicio/index', $res);
    }

    public function prueba()
    {
        $month = date('m');
        $year = date('Y');
        $day = date("d", mktime(0, 0, 0, $month + 1, 0, $year));
        echo date('m-d', mktime(0, 0, 0, $month, $day, $year));
    }

    /** vista para el cambio de contraseña, funcion no explicita por cuestion de seguridad */
    public function cambio()
    {
        plantilla('inicio/cambiar', null);
    }

    public function cerrar()
    {
        $this->model_login->cerrar_sesion();
        $this->session->sess_destroy();
        redirect(site_url('login'));
    }

    public function avisos()
    {
        $res = $this->model_inicio->obtenerAvisos();
        echo var_dump($res);
    }

    public function verificar()
    {
        if ($this->input->is_ajax_request()) {
            $sesion = $this->session->userdata('usuario_sesion');
            $usuario = $sesion['id_usuario'];
            $clave = trim($this->input->post('clave'));

            $respuesta = $this->inicio->verificar($usuario);
            if (password_verify($clave, $respuesta->clave)) {
                echo true;
            } else {
                echo 'error';
            }
        } else {
            show_404();
        }
    }

    public function confirmar_cambio()
    {
        if ($this->input->is_ajax_request()) {

            // Reglas de validacion
            $response = array(
                'success' => FALSE,
                'messages' => array()
            );
            $validation_rules = array(
                array(
                    'field' => 'clave-nueva',
                    'label' => 'clave nueva',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'clave-nueva-r',
                    'label' => 'confirmar clave',
                    'rules' => 'required'
                )
            );
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
            if ($this->form_validation->run() === TRUE) {
                $sesion = $this->session->userdata('usuario_sesion');
                $usuario = $sesion['id_usuario'];
                $clave_nueva = trim($this->input->post('clave-nueva'));
                $clave_confirmar = trim($this->input->post('clave-nueva-r'));

                if ($clave_nueva === $clave_confirmar) {
                    $this->inicio->confirma_cambio($usuario, $clave_confirmar);
                    $response['success'] = TRUE;
                } else {
                    $response['success'] = TRUE;
                    $response['status'] = "error";
                    $response['messages'] = "contraseñas no coinciden";
                }
            } else {
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }
            echo json_encode($response);

        } else {
            show_404();
        }
    }
}