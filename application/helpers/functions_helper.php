<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 16/03/2017
 * Time: 03:59 PM
 */

function get_type_of_impresion($value){
    $ci = &get_instance();
    $impression = $ci->config->item('impresion');
    return array_search($value, $impression);
}

function get_state($state){
    $ci = &get_instance();
    $estados = $ci->config->item('estados');
    return array_search($state, $estados);
}

function is_selected($value, $itemvalue)
{
    if ($value === $itemvalue) {
        return 'selected';
    } else {
        return '';
    }
}

// Regla de validacion de dato unico de un result
function is_unique_edit($value, $array)
{
    $array = explode(',', $array);
    $id = $array[0];
    $table = $array[1];
    $fieldname = $array[2];
    $ci = get_instance();
    // $fieldvalue = $array[3];
    if (isset($ci->db)) {
        // Obtener cantidad de registros con el mismo valor (ya sean el mismo o diferente id)
        $query = $ci->db->from($table)
            ->where($fieldname, $value)
            ->where('estado', get_state('ACTIVO'))->get();
        if ($query->num_rows() <= 1 OR $query->num_rows() === 0) {
            // Verificar si el id obtenido en la consulta es igual al enviado como parametro
            if ($query->num_rows() === 1) {
                if ($id === $query->row()->id) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else { // cantidad = 0, el valor se encuentra disponible
                return TRUE;
            }
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

function implode_array($object_array, $fieldname)
{
    $simple_array = array_object_to_simple_array($object_array, $fieldname);
    return implode(',', $simple_array);
}

function array_object_to_simple_array($object, $fieldname) {
    $simple_array = array();
    foreach ($object as $row)
    {
        array_push($simple_array, $row->$fieldname);
    }
    return $simple_array;
}

// Devulve el id del usuario en sesion
function get_user_id_in_session(){
    $ci = get_instance();
    $sesion = $ci->session->userdata('usuario_sesion');
    if(isset($sesion)){
        return $sesion['id_usuario'];
    }else{
        return '1';
    }
}

//Devuelve el id de la sucursal en sesion
function get_branch_id_in_session(){
    $ci = get_instance();
    $sesion = $ci->session->userdata('usuario_sesion');
    if(isset($sesion)){
        return $sesion['id_sucursal'];
    }else{
        redirect(site_url('login'));
    }
}

// NUll si esta vacia
function null_if_empty($value)
{
    return empty($value) ? NULL : $value;
}

function get_combo_unidad($unidad_id){
    $ci = get_instance();
    $ci->load->model('unidad_model','unidad');
    $unidades = $ci->unidad->get_all();
    $combo = '';
    foreach ($unidades as $row){
        $combo = $combo . "<option value='$row->id' ".is_selected($unidad_id,$row->id).">".$row->abreviatura."</option>";
    }
    return $combo;
}