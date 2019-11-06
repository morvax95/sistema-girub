<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 23/03/2018
 * Time: 12:32 AM
 */
class Migrar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
    }

    public function index(){

    }

    public function ejecutar(){
        $respuesta = $this->migration->current();
        if ($respuesta === FALSE)
        {
            show_error($this->migration->error_string());
        }else{
            echo 'Migraciones ejecutadas.';
        }
    }

    public function version($int){
        $respuesta = $this->migration->version($int);
        if ($respuesta === FALSE)
        {
            show_error($this->migration->error_string());
        }else{
            echo 'Migraciones ejecutadas.';
        }
    }
}