<?php


class Reserva extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reserva_model', 'reserva');

    }

    public function index()
    {
       /* $data['curso'] = $this->reserva->get_all_curso();
        $data['turno'] = $this->reserva->get_all_turno();
        $data['aula'] = $this->reserva->get_all_aula();*/
        plantilla('reserva/nuevo');


    }

    public function verificacion_tipo_datos_enviados()
    {
        return "nombre";

    }

    public function nuevo()
    {
        $data['curso'] = $this->PersonaCurso->get_all_curso();
        $data['turno'] = $this->PersonaCurso->get_all_turno();
        $data['aula'] = $this->PersonaCurso->get_all_aula();
        plantilla('PersonaCurso/nuevo', $data);
    }


    public function registrar()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->PersonaCurso->registrar();
        } else {
            show_404();
        }
    }

    public function agregar()
    {
        if ($this->input->is_ajax_request()) {
            // Recibimos los parametros del formulario
            $contador = $this->input->post('contador_inventario');
            $id_producto = $this->input->post('producto_id');
            $descripcion = $this->input->post('nombre_producto');
            $cantidad = $this->input->post('cantidad_inventario');
            //  $id_docente = $this->input->post('idCliente');
            // Creamos las filas del detalle
            $fila = '<tr>';
            $fila .= '<td><input type="text" value="' . $id_producto . '" id="id_producto" name="id_producto[]" hidden/><input type="text" value="' . $descripcion . '" id="descripcion" name="descripcion[]" hidden/>' . $descripcion . '</td>';
            $fila .= '<td><input type="text" value="' . $cantidad . '" id="cantidad' . $contador . '" name="cantidad[]" hidden/>' . $cantidad . '</td>';
            $fila .= '<td class="text-center"><a class="eliminar"><i class="fa fa-trash-o" /></a></td></tr>';

            $datos = array(
                0 => $fila,
                1 => $contador
            );

            echo json_encode($datos);
        } else {
            show_404();
        }
    }

    /*OBTENER ESTUDIANTES*/
    public function get_estudiantes()
    {
        $dato = $this->input->post_get('name_startsWith');
        $tipo = $this->input->post_get('type');
        if ($tipo === 'codigo') {
            $this->db->like('id', $dato);
            $this->db->where('id >0');
            $this->db->distinct();
            $res = $this->db->get('PersonaEstudiante ');
            if ($res->num_rows() > 0) {
                foreach ($res->result_array() as $row) {
                    $data[$row['id'] . '|' . $row['nombre']] = $row['nombre'] . '/' . $row['id'];
                }
                echo json_encode($data); //format the array into json data
            } else {
                $data["No existe este ci"] = "No existe este ci";
                echo json_encode($data);
            }
        } else {
            $this->db->like('lower(nombre)', strtolower($dato));
            $this->db->where('id >0');
            $res = $this->db->get('PersonaEstudiante ');
            if ($res->num_rows() > 0) {
                foreach ($res->result_array() as $row) {
                    $data[$row['nombre'] . '|' . $row['ci']] = $row['id'] . '/' . $row['id'];
                }
                echo json_encode($data); //format the array into json data
            } else {
                $data["No existe este nombre del estudiante"] = "No existe este nombre del estudiante";
                echo json_encode($data);
            }
        }

    }

    //obtener  la busqueda de solo personas-docente
    public function get_docente()
    {
        $dato = $this->input->post_get('name_startsWith');
        $tipo = $this->input->post_get('type');
        if ($tipo === 'ci') {
            $this->db->like('ci', $dato);
            $this->db->where('id >0');
            $this->db->where('rol_id=1');
            $this->db->distinct();
            $res = $this->db->get('persona');
            if ($res->num_rows() > 0) {
                foreach ($res->result_array() as $row) {
                    $data[$row['ci'] . '|' . $row['nombre']] = $row['nombre'] . '/' . $row['id'];
                }
                echo json_encode($data); //format the array into json data
            } else {
                $data["No existe este CI"] = "No existe este CI";
                echo json_encode($data);
            }
        } else {
            $this->db->like('lower(nombre)', strtolower($dato));
            $this->db->where('id >0');
            $this->db->where('rol_id=1');
            $res = $this->db->get('persona');
            if ($res->num_rows() > 0) {
                foreach ($res->result_array() as $row) {
                    $data[$row['nombre'] . '|' . $row['ci']] = $row['ci'] . '/' . $row['id'];
                }
                echo json_encode($data); //format the array into json data
            } else {
                $data["No existe este nombre del estudiante"] = "No existe este nombre del estudiante";
                echo json_encode($data);
            }
        }

    }


}