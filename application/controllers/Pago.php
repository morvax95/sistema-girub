<?php

/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 01/03/2018
 * Time: 07:42 PM
 */
class Pago extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pago_model', 'pagos');
    }

    public function listar()
    {
        plantilla('pagos/index');
    }


    public function get_all_debts()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->pagos->get_all_debts());
        } else {
            show_404();
        }
    }

    public function registrar_pago()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->pagos->registrar_pago();
        } else {
            show_404();
        }
    }

}