<?php

class Almacen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('almacen_model','almacen');
        $this->load->library('form_validation');
    }

    public function registrar(){
        if ($this->input->is_ajax_request()){
            $response = array(
                'success' => FALSE,
                'messages' => array()
            );

            $this->form_validation->set_rules('descripcion_almacen', 'nombre almacen', 'trim|required');

            $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

            if ($this->form_validation->run() === true) {
                /** OBTENERMOS VALORES DE LOS INPUT **/
                $obj_data['descripcion']    = mb_strtoupper($this->input->post('descripcion_almacen'),'UTF-8');
                $obj_data['estado']         = get_state('ACTIVO');
                $obj_data['tipo_almacen']   = $this->input->post('tipo_almacen');

                $response['success'] = $this->almacen->registrar($obj_data);
            } else {
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }
            echo json_encode($response);
        }else{
            show_404();
        }
    }

    public function get_all(){
        if ($this->input->is_ajax_request()){
            echo json_encode($this->almacen->get_all());
        }else{
            show_404();
        }
    }
}