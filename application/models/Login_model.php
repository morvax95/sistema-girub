<?php

/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 23/03/2018
 * Time: 09:23 AM
 */
class Login_model extends CI_Model
{
    public function login($nombre_usuario, $pass, $idSucursal)
    {
        $response = array(
            'success' => FALSE,
            'messages' => null
        );

        $user_data = $this->db->get_where('usuario', array('usuario' => $nombre_usuario));
        if ($user_data->num_rows() > 0) {
            $cargo_usuario = $user_data->row()->cargo;
            if ($cargo_usuario != 1) {
                $this->db->select('u.id, u.ci, u.nombre_usuario, u.estado, u.telefono, u.cargo, u.clave')
                    ->from('usuario u, usuario_sucursal us, sucursal s')
                    ->where('u.id = us.usuario_id')
                    ->where('us.sucursal_id = s.id')
                    ->where('u.usuario', $nombre_usuario)
                    ->where('s.id', $idSucursal);
                $user_data = $this->db->get();
            }
            if ($user_data->num_rows() > 0) {
                $usuario = $user_data->row();
                if (password_verify($pass, $usuario->clave)) { //Verifica si la clave ingresada es correcta
                    $this->load->model('inicio_model', 'inicio');
                    // Verificar estado del usuario
                    if ($usuario->estado === '1') {
                        //Verificamos si la sesion esta activa
                        if ($this->sesion_activa($usuario->id)) {
//                            $this->obtner_sesion_activa($usuario->id, $idSucursal);
                            $this->obtner_sesion_activa($usuario->id, $idSucursal);
                            $menu = $this->inicio->cargar_menu($usuario->id, $usuario->cargo);
                            $this->session->set_userdata('menu', $menu);
                            $response['success'] = true;
                            return json_encode($response);
                        } else {
                            // Registrar la informacion necesaria en sesion
                            $this->set_sesion_usuario($usuario, $idSucursal);
                            $menu = $this->inicio->cargar_menu($usuario->id, $usuario->cargo);
                            $this->session->set_userdata('menu', $menu);
                            $response['success'] = true;
                            return json_encode($response);
                        }
                    } else {
                        $response['messages'] = 'Usuario no habilitado'; // Usuario inhabilitado
                        return json_encode($response);
                    }
                } else {
                    $response['messages'] = 'Datos incorrectos'; // Datos incorrectos
                    return json_encode($response);
                }
            } else {
                $response['messages'] = 'Usuario no asignado a sucursal seleccionada'; // Usuario no esta asignado a sucursal
                return json_encode($response);
            }
        } else {
            $response['messages'] = 'Datos incorrectos'; // Usuario no existe o datos incorrectos
            return json_encode($response);
        }
    }

    /** Metodo que sobre escribe la sesion usuario_sesion para incorporar el id de la impresora */
    public function inicio_sesion($idU, $idImpresora, $marca)
    {

        $this->session->set_userdata('logueado', true);
        $sesion = $this->session->userdata('usuario_sesion');
        $this->db->insert('inicio_sesion', array('fecha' => date('Y-m-d H:i:s'), 'usuario_id' => $idU, 'impresora_id' => $idImpresora));

        $idInicioSesion = $this->db->insert_id();
        $nombre_sucursal = $this->db->get_where('sucursal', array('id' => $sesion['sucursal']))->row()->sucursal;
        $usuario_sesion = array(
            'idSesion' => $idInicioSesion,
            'idUsuario' => $sesion['id'],
            'ci' => $sesion['ci'],
            'nombre' => $sesion['nombre'],
            'cargo' => $sesion['cargo'],
            'telf' => $sesion['telf'],
            'id_impresora' => $idImpresora,
            'marca' => $marca,
            'idSucursal' => $sesion['sucursal'],
            'nombre_sucursal' => $nombre_sucursal,
        );
        $this->session->set_userdata('usuario_sesion', $usuario_sesion);

        $this->db->where('id', $idImpresora);
        $res = $this->db->update('impresora', array('activo' => 1));
        return $res;
    }


    public function set_sesion_usuario($datosUsuario, $idSucursal)
    {

        $this->session->set_userdata('logueado', true);

        $nombre_sucursal = $this->db->get_where('sucursal', array('id' => $idSucursal))->row()->sucursal;
        $this->db->insert('inicio_sesion', array('fecha' => date('Y-m-d H:i:s'), 'usuario_id' => $datosUsuario->id, 'impresora_id' => null));
        $id_sesion = $this->db->insert_id();
        $usuario_data = array(
            'id_usuario' => $datosUsuario->id,
            'ci' => $datosUsuario->ci,
            'nombre' => $datosUsuario->nombre_usuario,
            'telf' => $datosUsuario->telefono,
            'cargo' => $datosUsuario->cargo,
            'id_sucursal' => $idSucursal,
            'sucursal' => $nombre_sucursal,
            'id_sesion' => $id_sesion,
            // @todo si se trabaja con impresora borrar esto
            'id_impresora' => null,
        );
        $this->session->set_userdata('usuario_sesion', $usuario_data);
        $this->db->where('id', $datosUsuario->id);
        $this->db->update('usuario', array('activo' => 1));
    }


    public function cerrar_sesion()
    {
        // Recuperar datos de la sesion activa
        $sesion = $this->session->userdata('usuario_sesion');
        if (isset($sesion)) {
            $id_sesion = $sesion['id_sesion'];
            $usuario_id = $sesion['id_usuario'];
            $idImpresora = isset($sesion['id_impresora']) ? $sesion['id_impresora'] : null;
            $data = array(
                'fecha' => date('Y-m-d H:i:s'),
                'sesion_id' => $id_sesion
            );

            // Insertar en tabla "cierre_sesion"
            $this->db->insert('cierre_sesion', $data);

            // Cambiar el estado del usuario a "0"
            $sql = "UPDATE usuario set activo = ? WHERE id = ?";
            $this->db->query($sql, array(0, $usuario_id));

            if (isset($sesion['id_impresora'])) {
                $this->db->where('id', $idImpresora);
                $this->db->update('impresora', array('activo' => 0));
            }
        }
    }


    public function obtener_sucursales()
    {
        return $this->db->get('sucursal')->result();
    }


    public function sesion_activa($idusuario)
    {
        $datos = $this->db->get_where('usuario', array('id' => $idusuario))->row();
        if ($datos->activo === 1 or $datos->activo === '1') {
            return true;
        } else {
            return false;
        }
    }

    public function obtner_sesion_activa($idUsuario, $idSucursal)
    {

        $idImpresora = null;
        $this->db->select("i.id, i.usuario_id,u.ci, u.nombre_usuario, u.cargo, u.telefono")
            ->from("inicio_sesion i, usuario u")
            ->where("i.usuario_id = u.id")
            ->where("i.usuario_id", $idUsuario)
            ->order_by("i.id", "DESC")
            ->limit(1);
        $res = $this->db->get()->row();
        $nombre_sucursal = $this->db->get_where('sucursal', array('id' => $idSucursal))->row()->sucursal;
        $usuario_sesion = array(
            'id_usuario' => $res->usuario_id,
            'ci' => $res->ci,
            'nombre' => $res->nombre_usuario,
            'telf' => $res->telefono,
            'cargo' => $res->cargo,
            'id_sucursal' => $idSucursal,
            'sucursal' => $nombre_sucursal,
            'id_sesion' => $res->id,
            // @todo si se trabaja con impresora borrar esto
            'id_impresora' => null,
        );
        $this->session->set_userdata('usuario_sesion', $usuario_sesion);
        $this->session->set_userdata('logueado', true);
    }

    public function existe_sucursal()
    {
        return $this->db->query('select count(*)cantidad from sucursal')->row()->cantidad;
    }
}