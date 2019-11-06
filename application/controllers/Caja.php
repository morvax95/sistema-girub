<?php

class Caja extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sucursal_model','sucursal');
        $this->load->model('caja_model','caja');
    }

    public function index(){
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('caja/index',$data);
    }

    public function nuevo(){
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('caja_index',$data);
    }

    public function editar(){
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('caja/index',$data);
    }


    public function registrar_caja(){
        if ($this->input->is_ajax_request()){
            echo json_encode($this->caja->registrar_caja());
        }else{
            show_404();
        }
    }

    public function modificar_caja(){
        if ($this->input->is_ajax_request()){
            echo json_encode($this->caja->modificar_caja());
        }else{
            show_404();
        }
    }

    public function eliminar(){
        if ($this->input->is_ajax_request()){
            echo $this->caja->eliminar();
        }else{
            show_404();
        }
    }

    public function get_all(){
        if ($this->input->is_ajax_request()){
            echo json_encode($this->caja->get_all());
        }else{
            show_404();
        }
    }

    public function get_box_by_id(){
        if ($this->input->is_ajax_request()){
            echo json_encode($this->caja->get_box_by_id());
        }else{
            show_404();
        }
    }

    public function set_caja(){
        if ($this->input->is_ajax_request()){
            echo $this->caja->set_caja();
        }else{
            show_404();
        }
    }

    public function verficar_caja_aperturada(){
        if ($this->input->is_ajax_request()){
            echo json_encode($this->caja->verficar_caja_aperturada());
        }else{
            show_404();
        }
    }

    public function cerrar_caja(){
        $data = $this->caja->get_registros_caja();
        $this->load->view('caja/cerrar_caja', $data);
    }

    public function registrar_cierre(){
        if ($this->input->is_ajax_request()){
            echo json_encode($this->caja->registrar_cierre());
        }else{
            show_404();
        }
    }
}