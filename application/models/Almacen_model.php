<?php

/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 23/03/2018
 * Time: 10:35 AM
 */
class Almacen_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_(){
        return $this->db->get('almacen')->result();
    }
    public function get_all($type = 'object'){
        if($this->input->post('draw')){
            // @todo Si es necesario poner aqui el codigo para datatable con procesamiento.
        }else{
            $json_data = $this->db->get_where('almacen',array('estado'=>get_state('ACTIVO')))->result($type);
        }
        return $json_data;
    }


    public function get_warehouse_by_brach_office($type = 'object'){
        if($this->input->post('draw')){
            // @todo Si es necesario poner aqui el codigo para datatable con procesamiento.
        }else{
            $json_data = $this->db->get_where('almacen',array('estado'=>get_state('ACTIVO'),'sucursal_id'=>get_branch_id_in_session()))->result($type);
        }
        return $json_data;
    }
    public function registrar($data){
        return $this->db->insert('almacen',$data);
    }

}