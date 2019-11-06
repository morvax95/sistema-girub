<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Proforma_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }


    public function verificar_cliente_seleccionado($id_cliente, $nit_cliente, $nombre_cliente)
    {
        if ($id_cliente === '') {
            if ($nit_cliente === '' and $id_cliente === '') {
                $datosCliente = $this->cliente_model->obtener_cliente_particular();
                $id_seleccionado = $datosCliente->id;
            } else {
                if (!$this->cliente_model->exite_cliente(trim($nit_cliente))) {
                    $cliente['ci_nit'] = trim($nit_cliente);
                    $cliente['nombre_cliente'] = mb_strtoupper(trim($nombre_cliente), 'UTF-8');
                    $id_seleccionado = $this->cliente_model->registrar_cliente_venta($cliente);
                } else {
                    $id_seleccionado = $id_cliente;
                }
            }
        } else {
            if ($id_cliente === 0 or $id_cliente === '0') {
                $datosCliente = $this->cliente_model->obtener_cliente_particular();
                $id_seleccionado = $datosCliente->id;
            } else {
                $id_seleccionado = $id_cliente;
            }
        }
        return $id_seleccionado;
    }

    public function verificar_stock($producto_id, $cantidad_pedida) {
        $bool = false;
        $this->db->select("sum(cantidad)as cantidad_total")
            ->from("producto_inventario")
            ->where("producto_id", $producto_id)
            ->group_by("producto_id");
        $result = $this->db->get()->row();
        $cantidad_total = $result->cantidad_total;
        if ($cantidad_pedida <= $cantidad_total) {
            $bool = true;
        }
        return $bool;
    }

    public function registro_proforma()/* Registro como proforma  */
    {
        $cmd['operacion'] = "INSERCIÓN";
        $cmd['usuario'] =1;
        $cmd['host'] = "LOCALHOST";
        $cmd['fechaRegistro'] =date('Y-m-d H:i:s');
        $cmd['fechaModificacion'] ="";
        $cmd['tabla'] = "PROFORMA";
        $this->db->insert('bitacora', $cmd);

        $this->db->trans_start();
        $this->load->model('cliente_model');
        $id_cliente = $this->input->post('idCliente');
        $nit_cliente = $this->input->post('nit_cliente');
        $nombre_cliente = $this->input->post('nombre_cliente');
        $cliente_id = $this->verificar_cliente_seleccionado($id_cliente, $nit_cliente, $nombre_cliente);
        $this->db->where('sucursal_id', get_branch_id_in_session());
        $nro_venta = $this->db->get('proforma');

        $this->db->select_max('nro_proforma');
        $this->db->where('sucursal_id', get_branch_id_in_session());
        $result = $this->db->get('proforma')->row();
        $nro_venta =  $result->nro_proforma;
        if($nro_venta == ''){
            $nro_venta = 0;
        }
        $obj_proforma['fecha'] = date('Y-m-d');
        $obj_proforma['hora'] = date('H:i:s');
        $obj_proforma['subtotal'] = $this->input->post('subtotal_as');
        $obj_proforma['descuento'] = $this->input->post('descuento_as');
        $obj_proforma['total'] = $this->input->post('total_as');
        $obj_proforma['cliente_id'] = $cliente_id;
        $obj_proforma['nro_proforma'] = $nro_venta + 1;
        $obj_proforma['estado'] = get_state('ACTIVO');
        $obj_proforma['tipo_venta'] = $this->input->post('tipo_venta');
        $obj_proforma['sucursal_id'] = get_branch_id_in_session();
        $obj_proforma['usuario_id'] = get_user_id_in_session();


        $this->db->insert('proforma', $obj_proforma);
        // Extraemos el id insertado
        $id_proforma = $this->db->insert_id();

        // Registramos el detalle
        $detalle['venta_id'] = $id_proforma;
        $detalle['producto'] = $this->input->post('producto_id[]');
        $detalle['talla'] = $this->input->post('talla_id[]');
        $detalle['color'] = $this->input->post('color_id[]');
        $detalle['cantidad'] = $this->input->post('cantidad[]');
        $detalle['precio'] = $this->input->post('precio[]');
        $contador = $this->input->post('contador');
        for ($index = 0; $index < $contador; $index++) {
            $detalle_proforma['proforma_id'] = $id_proforma;
            $detalle_proforma['producto_id'] = $detalle['producto'][$index];
            $detalle_proforma['talla_id']    = $detalle['talla'][$index];
            $detalle_proforma['color_id']    = $detalle['color'][$index];
            $detalle_proforma['cantidad']    = $detalle['cantidad'][$index];
            $detalle_proforma['precio_venta']= $detalle['precio'][$index];
            $this->db->insert('detalle_proforma', $detalle_proforma);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $id_proforma;
        }
    }

    public function registrar_nota_Venta($id_venta)
    {
        $nota = $this->db->get('nota_venta')->result();
        $nro_nota = count($nota) + 1;
        $this->db->insert('nota_venta', array('venta_id' => $id_venta, 'nro_nota' => $nro_nota));
    }


    public function obtener_id_productos($data)
    {
        return $this->db->get_where('producto_inventario', array('producto_id' => $data['producto_id']))->result();
    }

    public function imprimirFactura($idVenta)
    {
        //Datos genericos de la factura
        $sql_datos_factura = "SELECT v.id, f.fecha,f.nro_factura,f.monto_total,f.importe_ice,f.importe_excento,f.ventas_grabadas_taza_cero,f.subtotal,f.descuento,f.importe_base,f.debito_fiscal,
                              f.codigo_control,f.estado, d.autorizacion, d.fecha_limite, d.leyenda, a.nombre_actividad,a.direccion, a.telefono, a.celular, a.email, c.ci_nit, c.nombre_cliente, s.nit, s.nombre_empresa,s.sucursal
                              FROM cliente c, venta v, factura f, dosificacion d, actividad a, sucursal s
                              WHERE c.id = v.cliente_id and v.id = f.venta_id and f.dosificacion_id = d.id and d.actividad_id = a.id AND a.sucursal_id = s.id AND v.id = ?";
        $datos_factura = $this->db->query($sql_datos_factura, $idVenta)->row();
        $datos_array['datos_factura'] = $datos_factura;
        $datos_array['datos_venta_detalle'] = $this->obtenerDetalleVenta($idVenta);

        $qr['id_venta'] = $datos_factura->id;
        $qr['nit_empresa'] = $datos_factura->nit;
        $qr['empresa'] = $datos_factura->nombre_empresa;
        $qr['nit_cliente'] = $datos_factura->ci_nit;
        $qr['nombre_cliente'] = $datos_factura->nombre_cliente;
        $qr['fecha_trans'] = $datos_factura->fecha;
        $qr['autorizacion'] = $datos_factura->autorizacion;
        $qr['nro_factura'] = $datos_factura->nro_factura;
        $qr['total'] = $datos_factura->monto_total;
        $qr['subtotal'] = $datos_factura->subtotal;
        $qr['descuento'] = $datos_factura->descuento;
        $qr['importe_base'] = $datos_factura->importe_base;
        $qr['debito'] = $datos_factura->debito_fiscal;
        $qr['codigo'] = $datos_factura->codigo_control;
        $qr['estado'] = $datos_factura->estado;

        $datos_array['qr_image'] = $this->generarQR($qr);


        return $datos_array;
    }

    public function imprimir_proforma($id)
    {
        $this->db->select("v.id, v.fecha, v.hora, v.subtotal, v.descuento, v.total, u.nombre_usuario, v.nro_proforma, c.ci_nit, c.nombre_cliente, s.nit, s.nombre_empresa,s.sucursal, s.direccion, s.telefono, s.email")
            ->from("cliente c, proforma v, sucursal s, usuario u")
            ->where("c.id = v.cliente_id")
            ->where("v.sucursal_id = s.id")
            ->where("v.usuario_id = u.id")
            ->where("v.id", $id);
        $datos_venta = $this->db->get()->row();
        $datos_array['datos_proforma'] = $datos_venta;
        $datos_array['datos_proforma_detalle'] = $this->obtener_detalle_proforma($id);
        return $datos_array;
    }

    function obtener_detalle_proforma($idventa)
    {
        $this->db->select("v.id, sum(d.cantidad) as cantidad,d.precio_venta, t.descripcion as talla, c.descripcion as color, p.nombre_item")
            ->from("proforma v, detalle_proforma d, producto p, talla t, color c")
            ->where("v.id = d.proforma_id")
            ->where("d.producto_id = p.id")
            ->where("p.talla_id = t.id")
            ->where("p.color_id = c.id")
            ->where(" v.id", $idventa)
            ->group_by("v.id,d.precio_venta,t.descripcion,c.descripcion, p.nombre_item");
        $datos_detalle = $this->db->get()->result();

        return $datos_detalle;
    }


    /*----------------------------------------------------------
     * Registro de la factura y los datos asociados
     * ----------------------------------------------------------*/
    public function registrar_factura($idVenta, $datos)
    {
        $this->db->trans_start();

        // Consultamos el nit de la venta del cliente
        $query_cliente = "select ci_nit,nombre_cliente from cliente WHERE id = ?";
        $datosCliente = $this->db->query($query_cliente, $datos['cliente_id'])->row();
        $nit_cliente = trim($datosCliente->ci_nit);
        $nombre_cliente = $datosCliente->nombre_cliente;

        $sesion = $this->session->userdata('usuario_sesion');
        //Consulta a dosificacion                                                               //Verificamos con la actividad seteada en la sesion
        $datosDosificacion = $this->db->query("select id,autorizacion,llave from dosificacion where estado = 'ACTIVO' and sucursal_id = ? AND impresora_id = ?", array($sesion['idSucursal'], $sesion['id_impresora']))->row();
        $dosificacion_id = $datosDosificacion->id;
        $dosificacion_autorizacion = trim($datosDosificacion->autorizacion);
        $dosificacion_llave = trim($datosDosificacion->llave);

        // Verificamos si existe factura
        $sql = "select count(nro_factura)as cantidad from factura where dosificacion_id = ?";
        $nro_factura = $this->db->query($sql, array($dosificacion_id))->row()->cantidad;
        if ($nro_factura === '0' or $nro_factura === 0) {
            $query = "select nro_inicio from dosificacion where id = ?";
            $datoNro = $this->db->query($query, $dosificacion_id)->row();
            $nro_factura = trim($datoNro->nro_inicio);
        } else {
            $sql = "select max(nro_factura)as cantidad from factura where dosificacion_id = ?";
            $nro_factura = $this->db->query($sql, array($dosificacion_id))->row()->cantidad;
            $nro_factura++;
        }

        include APPPATH . '/libraries/CodigoControl.class.php';
        //Formateamos la fecha para que quede sin separadores
        $anio = substr($datos['fecha'], 0, 4);
        $mes = substr($datos['fecha'], 5, 2);
        $dia = substr($datos['fecha'], 8);
        $fechaFormateada = $anio . $mes . $dia;
        // Formateamos el total para que se aplique o no el redondeo
        $datosMonto = explode(".", $datos['total']); // DIVIDO EL MONTO A PAGAR PARA EXTRAER LA PARTE ENTERA Y LA PARTE DECIMAL
        $entero = $datosMonto[0];
        $decimal = @$datosMonto[1];
        if ($decimal >= 50) {
            $entero = $entero + 1;
        }
        $crear_codigo = new CodigoControl(
            $dosificacion_autorizacion, $nro_factura, $nit_cliente, $fechaFormateada, $entero, $dosificacion_llave
        );

        $codigo_control = $crear_codigo->generar();

        // Insertamos los datos de la factura
        $factura['venta_id'] = $idVenta;
        $factura['dosificacion_id'] = $dosificacion_id;
        $factura['fecha'] = $fechaFormateada;
        $factura['nro_factura'] = $nro_factura;
        $factura['monto_total'] = $datos['total'];
        $factura['subtotal'] = $datos['subtotal'];
        $factura['descuento'] = $datos['descuento'];
        $factura['importe_base'] = ($datos['subtotal'] - $datos['descuento']);
        $factura['debito_fiscal'] = ($datos['subtotal'] - $datos['descuento']) * 0.13;
        $factura['codigo_control'] = $codigo_control;
        $factura['estado'] = 'V';

        $this->db->insert('factura', $factura);
        $factura_datos['venta_id'] = $idVenta;
        $factura_datos['autorizacion'] = $dosificacion_autorizacion;
        $factura_datos['nit_cliente'] = $nit_cliente;
        $factura_datos['nombre_cliente'] = $nombre_cliente;
        $factura_datos['fecha'] = $fechaFormateada;
        $factura_datos['nro_factura'] = $nro_factura;
        $factura_datos['monto_total'] = $datos['total'];
        $factura_datos['subtotal'] = $datos['subtotal'];
        $factura_datos['descuento'] = $datos['descuento'];
        $factura_datos['importe_base'] = ($datos['subtotal'] - $datos['descuento']);
        $factura_datos['debito_fiscal'] = ($datos['subtotal'] - $datos['descuento']) * 0.13;
        $factura_datos['codigo_control'] = $codigo_control;
        $factura_datos['estado'] = 'V';
        $factura_datos['sucursal_id'] = $sesion['idSucursal'];

        $this->db->insert('factura_datos', $factura_datos);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 'error';
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function generarQR($datos)
    {
        $PNG_TEMP_DIR = 'assets/temp/';

        //html PNG location prefix
        $PNG_WEB_DIR = 'assets/temp/';

        include APPPATH . '/libraries/qrcode/qrlib.php';
        //ofcourse we need rights to create temp dir
        if (!file_exists($PNG_TEMP_DIR))
            mkdir($PNG_TEMP_DIR);
        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 3;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

        $filename = $PNG_TEMP_DIR . 'test' . $datos['id_venta'] . $datos['nro_factura'] . '.png';
        $datos = $datos['nit_empresa'] . '|' . $datos['nro_factura'] . '|' . $datos['autorizacion'] . '|' . $datos['fecha_trans'] . "|" . $datos['total'] .
            '|' . $datos['importe_base'] . '|' . $datos['codigo'] . "|" . $datos['nit_cliente'] . '|0|0|0|' .
            $datos['descuento'];

        QRcode::png($datos, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

        return $PNG_WEB_DIR . basename($filename);
    }
}