<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 14/03/2017
 * Time: 05:43 PM
 */


function plantilla($vista, $data = null)
{
    if (!logueado()) {
        redirect(site_url('login'));
    }

    $ci = get_instance();

    $datosesion = $ci->session->userdata('usuario_sesion');
    $data_header['dato_sesion'] = $datosesion;

    if (!isset($datosesion)){
        redirect(site_url('login'));
    }
    $menu_items = $ci->session->userdata('menu');
    $ci->multi_menu->set_items($menu_items);

    $array_controladores = array();
    $index = 0;
//    foreach ($menu_items as $row) {
//        $array_controladores[$index] = $row['slug'];
//        $index++;
//    }

//    if (!tieneAcceso($array_controladores)) {
//        show_error('Usted no cuenta con los permisos necesarios.<br><br><a class="btn btn-danger" href="' . base_url() . 'inicio"> Volver</a> ', 'Error de acceso', '<b>Restriccion de Acceso</b>');
//    }

    $ci->load->view('plantilla/header', $data_header);
    $ci->load->view($vista, $data);
    $ci->load->view('plantilla/footer');
}

/*----------------------------------------------------
 * Este metodo es para restringir el acceso por url
 * --------------------------------------------------**/
function tieneAcceso($menu)
{
    $ci = get_instance();
    $controlador = $ci->uri->segment(1);
    //$metodo = $ci->uri->segment(2);

    // Verificamos si la se esta accediento a un metodo de un controlador de
    // vista para compara con los slug registrados
//    if ($metodo != '') {
//        $funcion = $controlador ;
//    } else {
    $funcion = $controlador;
//    }

    if ($funcion === 'inicio') { // Si es inicio el controlador inicio con el metodo de cambio (que es metodo que llama a plantilla)
        return true;
    } else {
        if (in_array($funcion, $menu)) {
            return true;
        } else {
            return false;
        }
    }
}


function logueado()
{
    $ci = get_instance();
    $logueado = $ci->session->userdata('logueado');
    return $logueado;
}

function url_verificar(){
    $ci = get_instance();
    $controller = $ci->session->userdata();
}