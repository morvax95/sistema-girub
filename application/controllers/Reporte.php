<?php


class Reporte extends CI_Controller
{
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Este es el pie de página creado con el método Footer() de la clase creada PDF que hereda de FPDF', 'T', 0, 'C');
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->model('reporte_model', 'reporte');
        $this->load->model('sucursal_model', 'sucursal');
        $this->load->model('cliente_model', 'cliente');
        $this->load->model('proveedor_model', 'proveedor');
        $this->load->model('producto_model');
    }

    public function index()
    {
        show_error('Pagina no habilitada.<br><br><a class="btn btn-danger" href="' . base_url('inicio') . '"> Volver</a> ', 'Error de acceso', '<b>PAGINA EN CONSTRUCCION</b>');
    }

    public function reporte_productos()
    {
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('reporte/reporte_producto', $data);
    }
    public function reporte_venta()
    {
        $inicio_mes = date("Y-m");
        $inicio_dia = $inicio_mes . "-01";
        $data["fecha_inicio"] = $inicio_dia;
        $data["fecha_actual"] = date("Y-m-d");

        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('reporte/reporte_ventas', $data);
    }

    public function reporte_venta_diaria()
    {
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('reporte/reporte_venta_diaria', $data);
    }

    public function reporte_inventario()
    {
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('reporte/reporte_stock', $data);
    }
    public function reporte_clientes()
    {
        $inicio_mes = date("Y-m");
        $inicio_dia = $inicio_mes . "-01";
        $data["fecha_inicio"] = $inicio_dia;
        $data["fecha_actual"] = date("Y-m-d");

        plantilla('reporte/reporte_clientes', $data);
    }
    public function stock_minimo()
    {
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('reporte/reporte_stock', $data);
    }

    public function reporte_deudas()
    {
        $inicio_mes = date("Y-m");
        $inicio_dia = $inicio_mes . "-01";
        $data["fecha_inicio"] = $inicio_dia;
        $data["fecha_actual"] = date("Y-m-d");
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('reporte/deudas', $data);
    }

    public function deudas()
    {
        $data['sucursales'] = $this->sucursal->get_all();
        plantilla('reporte/deudas', $data);
    }

    public function get_clientes()
    {
        if ($this->input->is_ajax_request()) {
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            $talla_pantalon = $this->input->post('talla_pantalon');
            $talla_camisa = $this->input->post('talla_camisa');
            $talla_saco = $this->input->post('talla_saco');
            echo json_encode($this->reporte->get_clientes($fecha_inicio, $fecha_fin, $talla_saco, $talla_camisa, $talla_pantalon));
        } else {
            show_404();
        }
    }

    public function exportar_excel_clientes()
    {
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');

        $datos = $this->reporte->get_clientes($fecha_inicio, $fecha_fin);

        $this->load->library("excel/PHPExcel");

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
        $objPHPExcel->setActiveSheetIndex(0)//elegimos hoja donde vamos a escribir
//empesamos a escribir en la hoja de excel

        ->setCellValue('A1', 'Listado de Clientes')
//            //CABEZERA DE LA TABLA
            ->setCellValue('A3', 'Empresa: ' . 'GIRUB ')
            ////Encabezado de la tabla
            ->setCellValue('A5', 'NRO.')
            ->setCellValue('B5', 'CI /NIT')
            ->setCellValue('C5', 'NOMBRE CLIENTE')
            ->setCellValue('D5', 'TELEFONO')
            ->setCellValue('E5', 'CORREO ELECTONICO')
            ->setCellValue('F5', 'DIRECCION');

        //poner en negritas
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F5')->getFont()->setBold(TRUE);

//centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// //Pintamos los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $fila = 6; //enpieza a escribir desde la linea 6\
        $i = 1;
        foreach ($datos['datos'] as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row->ci_nit)
                ->setCellValue('C' . $fila, $row->nombre_cliente)
                ->setCellValue('D' . $fila, $row->telefono)
                ->setCellValue('E' . $fila, $row->correo)
                ->setCellValue('F' . $fila, $row->direccion);


//     //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $fila = $fila + 1;
            $i++;
        }
//ESTABLECE LA ANCHURA DE LAS CELDA
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(TRUE);


//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="ReportesClientes.xlsx"');
//unduh file
        $objWriter->save("php://output");
    }

    public function get_ventas()
    {
        if ($this->input->is_ajax_request()) {
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            echo json_encode($this->reporte->get_ventas($fecha_inicio, $fecha_fin));
        } else {
            show_404();
        }
    }

    public function get_deudas()
    {
        if ($this->input->is_ajax_request()) {
            $sucursal = $this->input->post('sucursales');
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            echo json_encode($this->reporte->get_deudas($fecha_inicio, $fecha_fin, $sucursal));
        } else {
            show_404();
        }
    }

    public function reporte_inventario1()
    {

        if ($this->input->is_ajax_request()) {
            $sucursal = $this->input->post('sucursales');
            echo json_encode($this->reporte->reporte_inventario($sucursal));
        } else {
            show_404();
        }
    }


    public function getTxt()
    {
        $mes = $this->uri->segment(3);
        $anio = $this->uri->segment(4);
        $sucursal = $this->uri->segment(5);

        $respuesta = $this->reporte->getFacturasLCV($mes, $anio, $sucursal);
        $empresa = $this->reporte->getNitEmpresa();
        $nit_empresa = $empresa->nit;
        $archivo = "ventas_" . $mes . $anio . "_" . $nit_empresa . ".txt";
        header('Content-Disposition: attachment;filename="' . $archivo . '"');
        ob_start();

        $linea = "";
        $i = 1;
        foreach ($respuesta as $row) {
            $nit_cliente = $row->ci_nit;
            $nombre = $row->nombre_cliente;
            $nro_factura = $row->nro_factura;
            $autorizacion = $row->autorizacion;
            $fecha = $row->fecha;
            $monto_total = $row->monto_total;
            $ice = $row->importe_ice;
            $excento = $row->importe_excento;
            $codigo_control = $row->codigo_control;
            $ventasGrabadas = $row->ventas_grabadas_taza_cero;
            $descuento = $row->descuento;
            $subtotal = $row->subtotal;
            $base_iva = $row->importe_base;
            $iva = $row->debito_fiscal;
            $estado = $row->estado;

            $linea .= $i . "|" . $fecha . "|" . $nro_factura . "|" . $autorizacion . "|" . $estado . "|" . $nit_cliente . "|" . $nombre . "|" . $monto_total . "|" . $ice . "|" . $excento . "|" . $ventasGrabadas . "|" . $subtotal . "|" . $descuento . "|" . $base_iva . "|" . $iva . "|" . $codigo_control . "\r\n";
            $i++;
        }
        ob_end_clean();
        header('Content-Type: application/txt');
        header('Content-Disposition: attachment;filename=' . $archivo . '');
        header('Pragma:no-cache');
        echo $linea;
    }

    function obtener_mes($valor)
    {
        $result = '';
        switch ($valor) {
            case '01':
                $result = 'Enero';
                break;
            case '02':
                $result = 'Febrero';
                break;
            case '03':
                $result = 'Marzo';
                break;
            case '04':
                $result = 'Abril';
                break;
            case '05':
                $result = 'Mayo';
                break;
            case '06':
                $result = 'Junio';
                break;
            case '07':
                $result = 'Julio';
                break;
            case '08':
                $result = 'Agosto';
                break;
            case '09':
                $result = 'Septiembre';
                break;
            case '10':
                $result = 'Octubre';
                break;
            case '11':
                $result = 'Noviembre';
                break;
            case '12':
                $result = 'Diciembre';
                break;
        }
        return $result;
    }

    public function getExcel()
    {
        $mes = $this->uri->segment(3);
        $anio = $this->uri->segment(4);
        $sucursal = $this->uri->segment(5);

        $respuesta = $this->reporte->getFacturasLCV($mes, $anio, $sucursal);
        $empresa = $this->reporte->getNitEmpresa();
        $nit_empresa = $empresa->nit;
        $nombre_empresa = $empresa->nombre_empresa;


        $this->load->library("excel/PHPExcel");

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
        $objPHPExcel->setActiveSheetIndex(0)//elegimos hoja donde vamos a escribir
//empesamos a escribir en la hoja de excel

        ->setCellValue('A1', 'Libro de Ventas I.V.A.')
//            //CABEZERA DE LA TABLA

            ->setCellValue('A3', 'Empresa: ' . $nombre_empresa)
            ->setCellValue('F3', 'Periodo Fiscal: ' . $this->obtener_mes($mes))
            ->setCellValue('H3', 'Año Fiscal: ' . $anio)
            ////Encabezado de la tabla
            ->setCellValue('A5', 'NRO.')
            ->setCellValue('B5', 'FECHA')
            ->setCellValue('C5', 'NRO. FACTURA')
            ->setCellValue('D5', 'NRO. AUTORIZACION')
            ->setCellValue('E5', 'ESTADO')
            ->setCellValue('F5', 'NIT')
            ->setCellValue('G5', 'NOMBRE CLIENTE')
            ->setCellValue('H5', 'MONTO TOTAL')
            ->setCellValue('I5', 'IMPORTE ICE')
            ->setCellValue('J5', 'IMPORTE EXENCTO')
            ->setCellValue('K5', 'VENTAS GRABADAS A TASA CERO')
            ->setCellValue('L5', 'SUBTOTAL')
            ->setCellValue('M5', 'DESCUENTOS Y BONIDICACIONES')
            ->setCellValue('N5', 'IMPORTE BASE PARA DEBITO FISCAL')
            ->setCellValue('O5', 'DEBITO FISCAL')
            ->setCellValue('P5', 'CODIGO DE CONTROL');
        //poner en negritas
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('I5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('K5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('L5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('M5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('N5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('O5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('P5')->getFont()->setBold(TRUE);
//centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('K5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('L5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('M5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('N5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('O5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// //Pintamos los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('H5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('I5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('J5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('K5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('L5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('M5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('N5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('O5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('P5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $fila = 6; //enpieza a escribir desde la linea 6\
        $i = 1;
        foreach ($respuesta as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row->fecha)
                ->setCellValue('C' . $fila, $row->nro_factura)
                ->setCellValue('D' . $fila, $row->autorizacion . ' ')
                ->setCellValue('E' . $fila, $row->estado)
                ->setCellValue('F' . $fila, $row->ci_nit)
                ->setCellValue('G' . $fila, $row->nombre_cliente)
                ->setCellValue('H' . $fila, number_format($row->monto_total, 2) . ' ')
                ->setCellValue('I' . $fila, number_format($row->importe_ice, 2) . ' ')
                ->setCellValue('J' . $fila, number_format($row->importe_excento, 2) . ' ')
                ->setCellValue('K' . $fila, number_format($row->ventas_grabadas_taza_cero, 2) . ' ')
                ->setCellValue('L' . $fila, number_format($row->subtotal, 2) . ' ')
                ->setCellValue('M' . $fila, number_format($row->descuento, 2) . ' ')
                ->setCellValue('N' . $fila, number_format($row->importe_base, 2) . ' ')
                ->setCellValue('O' . $fila, number_format($row->debito_fiscal, 2) . ' ')
                ->setCellValue('P' . $fila, $row->codigo_control);
//     //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('H' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('I' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('J' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('K' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('L' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('M' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('N' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('O' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('P' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $fila = $fila + 1;
            $i++;
        }
//ESTABLECE LA ANCHURA DE LAS CELDA
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("O")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("P")->setAutoSize(TRUE);

//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="ventas' . $mes . $anio . "_" . $nit_empresa . '.xlsx"');
//unduh file
        $objWriter->save("php://output");
    }

    public function get_ventas_emitidas()
    {
        if ($this->input->is_ajax_request()) {
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            $sucursal_id = $this->input->post('sucursales');
            $forma_pago = $this->input->post('forma_pagos');
            echo json_encode($this->reporte->get_ventas_emitidas($fecha_inicio, $fecha_fin, $sucursal_id, $forma_pago));
        } else {
            show_404();
        }
    }

    public function get_ventas_diarias()
    {
        if ($this->input->is_ajax_request()) {
            $fecha_inicio = $this->input->post('fecha_inicio');
            $sucursal_id = $this->input->post('sucursales');
            echo json_encode($this->reporte->get_ventas_diarias($fecha_inicio, $sucursal_id));
        } else {
            show_404();
        }
    }

    public function exportar_excel_ventas_emitidas()
    {
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');
        $sucursal_id = $this->input->post('sucursal');
        $forma_pago = $this->input->post('forma_pagos');

        $datos = $this->reporte->get_ventas_emitidas($fecha_inicio, $fecha_fin, $sucursal_id, $forma_pago);
        $empresa = $this->reporte->getNitEmpresa();
        $nit_empresa = $empresa->nit;
        $nombre_empresa = $empresa->nombre_empresa;

        $this->load->library("excel/PHPExcel");

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
        $objPHPExcel->setActiveSheetIndex(0)//elegimos hoja donde vamos a escribir
//empesamos a escribir en la hoja de excel

        ->setCellValue('A1', 'Ventas Emitidas')
//            //CABEZERA DE LA TABLA
            ->setCellValue('A3', 'Empresa: ' . 'GIRUB')//. $nombre_empresa)
            ////Encabezado de la tabla
            ->setCellValue('A5', 'NRO.')
            ->setCellValue('B5', 'FECHA')
            ->setCellValue('C5', 'CI/NIT')
            ->setCellValue('D5', 'NOMBRE CLIENTE')
            ->setCellValue('E5', 'PRODUCTO')
            ->setCellValue('F5', 'CANTIDAD')
            ->setCellValue('G5', 'SUCURSAL');
        //poner en negritas
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G5')->getFont()->setBold(TRUE);
//centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// //Pintamos los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $fila = 6; //enpieza a escribir desde la linea 6\
        $i = 1;
        foreach ($datos['datos'] as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('A' . $fila, $row->nro_nota)
                ->setCellValue('B' . $fila, $row->fecha)
                ->setCellValue('C' . $fila, $row->ci_nit)
                ->setCellValue('D' . $fila, $row->nombre_cliente)
                ->setCellValue('E' . $fila, $row->producto)
                ->setCellValue('F' . $fila, $row->cantidad)
                ->setCellValue('G' . $fila, $row->sucursal);
//     //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $fila = $fila + 1;
            $i++;
        }
//ESTABLECE LA ANCHURA DE LAS CELDA
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(TRUE);

//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="ReporteVentasRealizadas.xlsx"');
//unduh file
        $objWriter->save("php://output");
    }

    public function exportar_excel_ventas_diarias()
    {
        $fecha_inicio = $this->input->post('fecha_inicio');
        $sucursal_id = $this->input->post('sucursal');
        $datos = $this->reporte->get_ventas_diarias($fecha_inicio, $sucursal_id);
        $empresa = $this->reporte->getNitEmpresa();
        $nit_empresa = $empresa->nit;
        $nombre_empresa = $empresa->nombre_empresa;

        $this->load->library("excel/PHPExcel");

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
        $objPHPExcel->setActiveSheetIndex(0)//elegimos hoja donde vamos a escribir
//empesamos a escribir en la hoja de excel

        ->setCellValue('A1', 'Facturas Emitidas')
//            //CABEZERA DE LA TABLA
            ->setCellValue('A3', 'Empresa: ' . $nombre_empresa)
            ////Encabezado de la tabla
            ->setCellValue('A5', 'NRO.')
            ->setCellValue('B5', 'FECHA')
            ->setCellValue('C5', 'CI/NIT')
            ->setCellValue('D5', 'NOMBRE CLIENTE')
            ->setCellValue('E5', 'PRODUCTO')
            ->setCellValue('F5', 'PRECIO VENTA')
            ->setCellValue('G5', 'CANTIDAD')
            ->setCellValue('H5', 'MONTO BS')
            ->setCellValue('I5', 'SUCURSAL');
        //poner en negritas
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('I5')->getFont()->setBold(TRUE);
//centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// //Pintamos los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('H5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('I5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $fila = 6; //enpieza a escribir desde la linea 6\
        $i = 1;
        foreach ($datos['datos'] as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('A' . $fila, $row->nro_nota)
                ->setCellValue('B' . $fila, $row->fecha)
                ->setCellValue('C' . $fila, $row->ci_nit)
                ->setCellValue('D' . $fila, $row->nombre_cliente)
                ->setCellValue('E' . $fila, $row->producto)
                ->setCellValue('F' . $fila, $row->precio_venta)
                ->setCellValue('G' . $fila, $row->cantidad)
                ->setCellValue('H' . $fila, $row->total)
                ->setCellValue('I' . $fila, $row->sucursal);
//     //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('H' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('I' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $fila = $fila + 1;
            $i++;
        }
//ESTABLECE LA ANCHURA DE LAS CELDA
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(TRUE);

//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="ReporteVentasDiarias.xlsx"');
//unduh file
        $objWriter->save("php://output");
    }

    //imprimir en excel el inventario
    public function exportar_inventario()
    {
        $id_sucursal = $this->input->post('sucursales');
        $result = $this->reporte->reporte_stock_minimo($id_sucursal);
        $empresa = $this->reporte->getNitEmpresa();
        $nit_empresa = $empresa->nit;
        $nombre_empresa = $empresa->nombre_empresa;

        $this->load->library("excel/PHPExcel");

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
        $objPHPExcel->setActiveSheetIndex(0)//elegimos hoja donde vamos a escribir
//empesamos a escribir en la hoja de excel

        ->setCellValue('A1', ' REPORTE DE STOCK DE INVENTARIO')
//            //CABEZERA DE LA TABLA
            ->setCellValue('A3', 'Empresa: ' . $nombre_empresa)
            ->setCellValue('F3', 'Fecha: ' . date('Y-m-d'))
            ////Encabezado de la tabla
            ->setCellValue('A5', 'NRO.')
            ->setCellValue('B5', 'CODIGO')
            ->setCellValue('C5', 'PRODUCTO')
            ->setCellValue('D5', 'COLOR')
            ->setCellValue('E5', 'TALLA')
            ->setCellValue('F5', 'PRECIO VENTA')
            ->setCellValue('G5', 'ESTADO INVENTARIO')
            ->setCellValue('H5', 'CANTIDAD')
            ->setCellValue('I5', 'DEPOSITO');
        //poner en negritas
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('I5')->getFont()->setBold(TRUE);
//centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// //Pintamos los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('H5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('I5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $fila = 6; //enpieza a escribir desde la linea 6\
        $i = 1;
        foreach ($result as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row->codigo_barra)
                ->setCellValue('C' . $fila, $row->nombre_item)
                ->setCellValue('D' . $fila, $row->color)
                ->setCellValue('E' . $fila, $row->talla)
                ->setCellValue('F' . $fila, $row->precio_venta)
                ->setCellValue('G' . $fila, $row->estado_inventario)
                ->setCellValue('H' . $fila, $row->cantidad)
                ->setCellValue('I' . $fila, $row->almacen);
//     //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('H' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('I' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $fila = $fila + 1;
            $i++;
        }
//ESTABLECE LA ANCHURA DE LAS CELDA
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
        $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO

//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="StockInventario.xlsx"');
//unduh file
        $objWriter->save("php://output");
    }


    public function exportar_deudas()
    {
        $fecha_inicio = $this->input->post('inicio');
        $fecha_fin = $this->input->post('fin');
        $sucursal = $this->input->post('sucursal');

        $result = $this->reporte->get_deudas($fecha_inicio, $fecha_fin, $sucursal);
        $empresa = $this->reporte->getNitEmpresa();
        $nit_empresa = $empresa->nit;
        $nombre_empresa = $empresa->nombre_empresa;


        $this->load->library("excel/PHPExcel");

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
        $objPHPExcel->setActiveSheetIndex(0)//elegimos hoja donde vamos a escribir
//empesamos a escribir en la hoja de excel

        ->setCellValue('A1', 'Deudas por cobrar')
//            //CABEZERA DE LA TABLA
//            ->setCellValue('A2', 'ID Transaccion: '.$_REQUEST['idTransaccion'])
//            ->setCellValue('C2', 'Paciente: '.$_REQUEST['paciente'])
            ->setCellValue('A3', 'Empresa: ' . $nombre_empresa)
            ->setCellValue('F3', 'Fecha: ' . date('Y-m-d'))
            ////Encabezado de la tabla
            ->setCellValue('A5', 'NRO.')
            ->setCellValue('B5', 'FECHA VENTA')
            ->setCellValue('C5', 'CLIENTE')
            ->setCellValue('D5', 'TOTAL VENTA')
            ->setCellValue('E5', 'TOTAL PAGADO')
            ->setCellValue('F5', 'SALDO')
            ->setCellValue('G5', 'ESTADO');
        //poner en negritas
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G5')->getFont()->setBold(TRUE);
//centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// //Pintamos los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $fila = 6; //enpieza a escribir desde la linea 6\
        $i = 1;
        foreach ($result as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row->fecha)
                ->setCellValue('C' . $fila, $row->nombre_cliente)
                ->setCellValue('D' . $fila, $row->total)
                ->setCellValue('E' . $fila, $row->total_pagado)
                ->setCellValue('F' . $fila, $row->saldo)
                ->setCellValue('G' . $fila, $row->estado);
//     //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $fila = $fila + 1;
            $i++;
        }
//ESTABLECE LA ANCHURA DE LAS CELDA
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(TRUE); //DAR ANCHURA  A LAS CELDAS AUTOMATICO

//mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="DeudasPorCobrar.xlsx"');
//unduh file
        $objWriter->save("php://output");
    }
//----------------------------------------------------------------------------------

    /*** Imprimir  en pdf clientes ****/
    public function imprimir_clientes()
    {
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');
        $datos = $this->reporte->get_clientes($fecha_inicio, $fecha_fin);
        $datos_contador = $this->reporte->get_count_clientes($fecha_inicio, $fecha_fin);

        $sucursal_id = 1;// $this->input->post('sucursal');
        $datos_empresa = $this->sucursal->get_datos_empresa($sucursal_id);

        $lista_datos = $datos['datos'];
        $cantidad_datos = $datos_contador['cantidad_datos'];

        $suma = 0;
        foreach ($cantidad_datos as $row_detalle) {
            $suma = $row_detalle->id;
        }
        foreach ($lista_datos as $row_detalle) {
            $usuario = $row_detalle->usuario;

        }

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
        $this->pdf->SetTitle("REPORTE");
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 45, 25);

        /*  intenando poner multicell   */
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(53, 5, $datos_empresa->nombre_empresa, 0, 0, 'C');
        $this->pdf->Cell(55, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        $this->pdf->Ln(4);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(52, 5, $datos_empresa->sucursal, 0, 0, 'C');
        $this->pdf->Cell(60, 5, '', 0);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(70, 5, ' ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(60, 5, 'REPORTE DE CLIENTE', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(60, 4, $datos_empresa->direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(55, 5, utf8_decode($datos_empresa->nombre_empresa), 0, 0, 'C');
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(60, 4, 'Telf. ' . $datos_empresa->telefono, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(4);
        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(40, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(6);

        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(10);

        /*  FECHA IMRESION*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode(' Fecha de Impresión   :  ' . date('d/m/Y')), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  CANTIDAD  */
        $this->pdf->Cell(27, 5, utf8_decode(' Nombre Usuario         :  ' . $usuario), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);


        /* Encabezado de la columna*/
        $this->pdf->Cell(10, 5, "NRO", 1, 0, 'C');
        $this->pdf->Cell(82, 5, "NOMBRE CLIENTE", 1, 0, 'C');
        $this->pdf->Cell(45, 5, "TELEFONO", 1, 0, 'C');
        $this->pdf->Cell(55, 5, "CORREO", 1, 0, 'C');


        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);
        $cantidad_filas = 0;

        $lista_compras = $datos['datos'];

        foreach ($lista_compras as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas == count($lista_compras)) {
                $estilo = 'LRB';
            }
            $this->pdf->Cell(10, 4, utf8_decode($cantidad_filas), $estilo, 0, 'C');
            $this->pdf->Cell(82, 4, utf8_decode($row_detalle->nombre_cliente), $estilo, 0, 'C');
            $this->pdf->Cell(45, 4, utf8_decode($row_detalle->telefono), $estilo, 0, 'C');
            $this->pdf->Cell(55, 4, utf8_decode($row_detalle->correo), $estilo, 0, 'C');



            $this->pdf->Ln(4);
            $nro = $nro + 1;

        }
        // Convertimos el monto en literal
       /* include APPPATH . '/libraries/convertidor.php';
        $v = new EnLetras();
        $valor = $v->ValorEnLetras($suma, " ");
        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'CANTIDAD CLIENTES Bs  :  ', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '                                           ' . $valor, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL  :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, $suma, 1, 0, 'R');
        $this->pdf->Ln(8);*/
        $this->pdf->Output("ReporteClientes.pdf", 'I');

    }

    /*** Imprimir  en pdf clientes ****/
    public function imprimir_deudas_por_cobrar()
    {
        $codigo = $this->input->post('codigo_venta');
        $datos = $this->reporte->get_deudas_por_cobrar($codigo);

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        $lista_datos = $datos['datos'];


        foreach ($lista_datos as $row_detalle1) {
            $fecha_1 = $row_detalle1->fecha;
            $nro_venta = $row_detalle1->nro_venta;

            if ($nro_venta > 0) {
                // Define el alias para el número de página que se imprimirá en el pie
                $this->pdf->AliasNbPages();
                $this->pdf->SetTitle("DEUDAS POR COBRAR");
                $var_img = base_url() . 'assets/img/logo_empresa.jpg';
                $this->pdf->Image($var_img, 10, 10, 45, 25);

                $this->pdf->SetFont('Arial', 'B', 8);
                $this->pdf->Cell(133, 5, '', 0, 0, 'C');
                $this->pdf->Cell(65, 5, 'DICARP ', 0, 0, 'C');
                $this->pdf->SetFont('Arial', 'B', 9);
                $this->pdf->SetTextColor(248, 000, 000);

                $this->pdf->Ln(5);
                $this->pdf->Cell(133, 5, '', 0, 0, 'C');
                $this->pdf->SetFont('Arial', 'B', 9);
                $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

                $this->pdf->Cell(60, 5, '      Colchones y Complementos', 0, 0, 'C');
                $this->pdf->SetFont('Arial', 'B', 9);
                $this->pdf->MultiCell(72, 5, '', 0, 'C');
                $this->pdf->Cell(75, 5, ' ', 0, 0, 'C');

                $this->pdf->SetFont('Arial', 'B', 12);
                $this->pdf->SetTextColor(248, 0, 0);
                $this->pdf->Cell(50, 5, 'DEUDAS POR COBRAR', 0, 0, 'C');
                $this->pdf->SetFont('Arial', '', 9);
                $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

                $this->pdf->Cell(70, 4, 'Av 26 de Febrero 2do anillo casi centenario', 0, 0, 'C');
                $this->pdf->Cell(60, 4, '', 0);
                $this->pdf->SetFont('Arial', '', 9);

                $this->pdf->MultiCell(72, 4, '', 0, 'C');
                $this->pdf->Cell(75, 5, '', 0, 0, 'C');
                $this->pdf->SetFont('Arial', 'B', 12);
                $this->pdf->SetTextColor(248, 0, 0);
                $this->pdf->Cell(51, 5, 'DICARP', 0, 0, 'C');

                $this->pdf->SetTextColor(0, 0, 0);
                $this->pdf->SetFont('Arial', 'B', 7);
                $this->pdf->Cell(77, 4, 'Telf. 9302099 - 70838701', 0, 0, 'C');

                $this->pdf->Ln(8);

                $nro = 1;
                $nro = $nro + 1;

                $this->pdf->SetFont('Arial', 'B', 8);
                $this->pdf->Ln(10);

                /*  FECHA IMRESION*/
                $this->pdf->SetFont('Arial', 'B', 8);
                $this->pdf->Cell(27, 5, utf8_decode(' FECHA DE IMPRESIÓN   :  ' . date('d/m/Y') . '                                                                                                        NRO VENTA  : ' . $nro_venta), 'TL');
                $this->pdf->Cell(165, 5, '', 'TR');
                $this->pdf->SetFont('Arial', 'B', 8);;
                $this->pdf->Ln(5);
                /*  FECHA DE VENTA  */
                $this->pdf->Cell(27, 5, utf8_decode(' FECHA VENTA               :  ' . $fecha_1), 'LB');
                $this->pdf->Cell(165, 5, '', 'RB');
                $this->pdf->Ln(7);


                /* Encabezado de la columna*/
                $this->pdf->Cell(10, 5, "NRO", 1, 0, 'C');
                $this->pdf->Cell(70, 5, "NOMBRE CLIENTE", 1, 0, 'C');
                $this->pdf->Cell(35, 5, "TOTAL VENTA", 1, 0, 'C');
                $this->pdf->Cell(35, 5, "TOTAL PAGADO", 1, 0, 'C');
                $this->pdf->Cell(42, 5, "SALDO", 1, 0, 'C');

                $this->pdf->Ln(5);
                $this->pdf->SetFont('Arial', '', 8);
                $cantidad_filas = 0;

                $lista_compras = $datos['datos'];

                foreach ($lista_compras as $row_detalle) {
                    $cantidad_filas++;
                    $estilo = 'RL';
                    if ($nro == 1) {
                        $estilo = $estilo . 'T';
                    }
                    if ($cantidad_filas == count($lista_compras)) {
                        $estilo = 'LRB';
                    }
                    $this->pdf->Cell(10, 4, utf8_decode($cantidad_filas), $estilo, 0, 'C');
                    $this->pdf->Cell(70, 4, utf8_decode($row_detalle->nombre_cliente), $estilo, 0, 'C');
                    $this->pdf->Cell(35, 4, utf8_decode($row_detalle->total), $estilo, 0, 'C');
                    $this->pdf->Cell(35, 4, utf8_decode($row_detalle->total_pagado), $estilo, 0, 'C');
                    $this->pdf->Cell(42, 4, utf8_decode($row_detalle->saldo), $estilo, 0, 'C');


                    $this->pdf->Ln(4);
                    $nro = $nro + 1;

                }
                $this->pdf->Output("DeudasPorCobrar.pdf", 'I');

            }
            echo "POR FAVOR IMPRIMIR EL HISTRIAL DE PAGOS DEL CLIENTE";

        }


    }


    /*** Imprimir  en pdf deudas ****/
    public function imprimir_deudas()
    {
        $sucursal = $this->input->post('sucursal');
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');

        $datos = $this->reporte->get_deudas($fecha_inicio, $fecha_fin, $sucursal);

        $sucursal_id = 1;//$this->input->post('sucursal');
        $datos_empresa = $this->sucursal->get_datos_empresa($sucursal_id);

        $lista_datos = $datos['datos'];

        $suma = 0;
        foreach ($lista_datos as $row_detalle) {

            $usuario = $row_detalle->usuario;
            $total_saldo = $row_detalle->saldo;
            $suma = $suma + $total_saldo;

        }


        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();


        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();
        $this->pdf->SetTitle("DEUDAS POR COBRAR");
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 45, 25);

        /*  intenando poner multicell   */
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(53, 5, $datos_empresa->nombre_empresa, 0, 0, 'C');
        $this->pdf->Cell(55, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        $this->pdf->Ln(4);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(52, 5, $datos_empresa->sucursal, 0, 0, 'C');
        $this->pdf->Cell(60, 5, '', 0);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(70, 5, ' ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(60, 5, 'REPORTE DE VENTA', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(60, 4, $datos_empresa->direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(55, 5, utf8_decode($datos_empresa->nombre_empresa), 0, 0, 'C');
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(60, 4, 'Telf. ' . $datos_empresa->telefono, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(4);
        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(40, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(5);

        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(10);

        /*  FECHA IMRESION*/
        $this->pdf->SetFont('Arial', 'B', 8);
//        $this->pdf->Cell(27, 5, utf8_decode(' Fecha de Impresión   :  ' . date('d/m/Y') . '                                           Usuario   :  ' . $usuario), 'TL');
        $this->pdf->Cell(27, 5, utf8_decode(' Fecha de Impresión   :  ' . date('d/m/Y')), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(3);
        /*  FECHA DE VENTA  */
        $this->pdf->Cell(27, 5, utf8_decode('  '), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);


        /* Encabezado de la columna*/
        $this->pdf->Cell(10, 5, "NRO", 1, 0, 'C');
        $this->pdf->Cell(70, 5, "NOMBRE CLIENTE", 1, 0, 'C');
        $this->pdf->Cell(35, 5, "TOTAL VENTA", 1, 0, 'C');
        $this->pdf->Cell(35, 5, "TOTAL PAGADO", 1, 0, 'C');
        $this->pdf->Cell(42, 5, "SALDO", 1, 0, 'C');

        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);
        $cantidad_filas = 0;

        $lista_compras = $datos['datos'];

        foreach ($lista_compras as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas == count($lista_compras)) {
                $estilo = 'LRB';
            }
            $this->pdf->Cell(10, 4, utf8_decode($cantidad_filas), $estilo, 0, 'C');
            $this->pdf->Cell(70, 4, utf8_decode($row_detalle->nombre_cliente), $estilo, 0, 'C');
            $this->pdf->Cell(35, 4, utf8_decode($row_detalle->total), $estilo, 0, 'C');
            $this->pdf->Cell(35, 4, utf8_decode($row_detalle->total_pagado), $estilo, 0, 'C');
            $this->pdf->Cell(42, 4, utf8_decode($row_detalle->saldo), $estilo, 0, 'C');


            $this->pdf->Ln(4);
            $nro = $nro + 1;

        }

        // Convertimos el monto en literal
        include APPPATH . '/libraries/convertidor.php';
        $v = new EnLetras();
        $valor = $v->ValorEnLetras($suma, " ");
        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'MONTO TOTAL Bs  :  ', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '                                ' . $valor, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, $suma, 1, 0, 'R');
        $this->pdf->Ln(8);

        $this->pdf->Output("DeudasPorCobrar.pdf", 'I');

    }

    /*** Imprimir  en pdf ventas ****/
    public function imprimir_ventas()
    {
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');
        $sucursal_id = 1;//$this->input->post('sucursal');

        $datos = $this->reporte->get_ventas_emitidas($fecha_inicio, $fecha_fin, $sucursal_id);
        $datos_montos = $this->reporte->get_ventas__montos_emitidas($fecha_inicio, $fecha_fin, $sucursal_id);
        $datos_empresa = $this->sucursal->get_datos_empresa($sucursal_id);

        $lista_compras = $datos['datos'];
        $lista_monto = $datos_montos['datos_montos'];
        $monto_total = 0;

        foreach ($lista_compras as $row_detalle) {
            $monto_total = $monto_total + $row_detalle->cantidad;
            $usuario = $row_detalle->usuario;
        }
        foreach ($lista_monto as $row_detalle) {
            $monto_precio = $row_detalle->monto;
        }

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        $this->pdf->SetTitle("REPORTE");
        $var_img = base_url() . 'assets/img/logo_empresa.jpg';
        $this->pdf->Image($var_img, 10, 10, 45, 25);

        /*  intenando poner multicell   */
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(53, 5, $datos_empresa->nombre_empresa, 0, 0, 'C');
        $this->pdf->Cell(55, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        $this->pdf->Ln(4);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(52, 5, $datos_empresa->sucursal, 0, 0, 'C');
        $this->pdf->Cell(60, 5, '', 0);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(70, 5, ' ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(60, 5, 'REPORTE DE VENTA', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(60, 4, $datos_empresa->direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(55, 5, utf8_decode($datos_empresa->nombre_empresa), 0, 0, 'C');
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(60, 4, 'Telf. ' . $datos_empresa->telefono, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(4);
        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(40, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(5);
        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(10);

        /*  FECHA INICIO*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('Fecha desde        : ' . $fecha_inicio . '                     ' . '                Fecha de Impresión   :  ' . date('d/m/Y') . '                      Sucursal : ' . $datos_empresa->sucursal), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  FECHA FIN  */
        $this->pdf->Cell(27, 5, utf8_decode('Fecha Hasta        : ' . $fecha_fin . '                     ') . '                 Usuario : ' . $usuario, 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /* Encabezado de la columna*/
        $this->pdf->Cell(10, 5, "NRO", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "FECHA", 1, 0, 'C');
        $this->pdf->Cell(65, 5, "CLIENTE", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "NRO VENTA", 1, 0, 'C');
        $this->pdf->Cell(38, 5, "TOTAL VENTA", 1, 0, 'C');
        $this->pdf->Cell(39, 5, "CANTIDAD PRODUCTO", 1, 0, 'C');

        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);

        $cantidad_filas = 0;

        foreach ($lista_compras as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas == count($lista_compras)) {
                $estilo = 'LRB';
            }
            $this->pdf->Cell(10, 4, utf8_decode($cantidad_filas), $estilo, 0, 'C');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->fecha), $estilo, 0, 'C');
            $this->pdf->Cell(65, 4, utf8_decode($row_detalle->nombre_cliente), $estilo, 0, 'C');
            $this->pdf->Cell(20, 4, utf8_decode('00' . $row_detalle->codigo_venta), $estilo, 0, 'C');
            $this->pdf->Cell(38, 4, utf8_decode($row_detalle->total . ' Bs'), $estilo, 0, 'C');
            $this->pdf->Cell(39, 4, utf8_decode($row_detalle->cantidad), $estilo, 0, 'C');

            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }

        // Convertimos el monto en literal
        include APPPATH . '/libraries/convertidor.php';
        $v = new EnLetras();
        $valor = $v->ValorEnLetras($monto_total, " ");
        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'CANTIDAD PRODUCTO TOTAL  :  ', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '                                                  ' . $valor, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL  :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, $monto_total, 1, 0, 'R');
        $this->pdf->Ln(5);


        $v = new EnLetras();
        $valor = $v->ValorEnLetras($monto_precio, " ");
        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'MONTO TOTAL Bs       :  ', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, '                                 ' . $valor, 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, $monto_precio, 1, 0, 'R');
        $this->pdf->Ln(8);

        $this->pdf->Output("ReporteVentas.pdf", 'I');


    }

    /*** Imprimir  en pdf ventas diarias ****/
    public function imprimir_ventas_diarias()
    {
        $fecha_inicio = $this->input->post('fecha_inicio');
        $sucursal_id = $this->input->post('sucursal');

        $datos = $this->reporte->get_ventas_diarias($fecha_inicio, $sucursal_id);
        $datos_empresa = $this->sucursal->get_datos_empresa($sucursal_id);

        $lista_compras = $datos['datos'];
        $monto_total = 0;

        foreach ($lista_compras as $row_detalle) {
            $monto_total = $monto_total + $row_detalle->total;
        }

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        $this->pdf->SetTitle("REPORTE");
        $var_img = base_url() . 'assets/img/logo_black.png';
        $this->pdf->Image($var_img, 10, 10, 40, 28);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(65, 5, 'M & K ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        /**/
        $this->pdf->Ln(5);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(60, 5, '      Tienda de Ropa', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Cell(75, 5, ' ', 0, 0, 'C');

        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(60, 5, 'REPORTE DE VENTAS', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(60, 4, 'Casa Matriz Calle velasco # 250', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);

        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(61, 5, 'M & K', 0, 0, 'C');

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(60, 4, 'Telf. 3425147 - 79845632', 0, 0, 'C');

        $this->pdf->Ln(10);
        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(10);

        /*  FECHA INICIO*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode('Fecha Busqueda   : ' . $fecha_inicio . '                     ' . 'Sucusal   : ' . $datos_empresa->sucursal . '            Fecha de Impresion   :  ' . date('d/m/Y')), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  CANTIDAD   */
        $this->pdf->Cell(27, 5, utf8_decode('Monto Total           : ' . $monto_total . ' BS'), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /* Encabezado de la columna*/
        $this->pdf->Cell(10, 5, "NRO", 1, 0, 'C');
        $this->pdf->Cell(50, 5, "CLIENTE", 1, 0, 'C');
        $this->pdf->Cell(72, 5, "PRODUCTO", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "PRECIO V.", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "MONTO", 1, 0, 'C');

        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);

        $cantidad_filas = 0;

        foreach ($lista_compras as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas == count($lista_compras)) {
                $estilo = 'LRB';
            }
            $this->pdf->Cell(10, 4, utf8_decode($cantidad_filas), $estilo, 0, 'C');
            $this->pdf->Cell(50, 4, utf8_decode($row_detalle->nombre_cliente), $estilo, 0, 'C');
            $this->pdf->Cell(72, 4, utf8_decode($row_detalle->producto), $estilo, 0, 'C');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->precio_venta), $estilo, 0, 'C');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->cantidad), $estilo, 0, 'C');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->total), $estilo, 0, 'C');

            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }


        $this->pdf->Output("ReporteVentas.pdf", 'I');

    }

    /*** Imprimir  en pdf inventario ****/
    public function imprimir_inventario()
    {
        $id_sucursal = $this->input->post('sucursales');
        $datos = $this->reporte->reporte_stock_minimo($id_sucursal);
        $datos_empresa = $this->sucursal->get_datos_empresa($id_sucursal);
        $monto_total = 0;
        $lista_compras = $datos;

        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        $this->pdf->AddPage();

        foreach ($lista_compras as $row_detalle) {
            $monto_total = $monto_total + $row_detalle->cantidad;
        }
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        $this->pdf->SetTitle("REPORTE");
        $var_img = base_url() . 'assets/img/logo_black.png';
        $this->pdf->Image($var_img, 10, 10, 40, 28);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(65, 5, 'M & K ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        $this->pdf->Ln(5);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(60, 5, '   Tienda de Ropa', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Cell(75, 5, ' ', 0, 0, 'C');

        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(60, 5, 'REPORTE DE INVENTARIO', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/

        $this->pdf->Cell(60, 4, 'Casa Matriz Calle velasco # 250', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);

        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 0, 0);
        $this->pdf->Cell(61, 5, 'M & K', 0, 0, 'C');

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(60, 4, 'Telf. 3425147 - 79845632', 0, 0, 'C');

        $this->pdf->Ln(10);

        $nro = 1;
        $nro = $nro + 1;

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Ln(10);

        /*  FECHA IMRESION*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, utf8_decode(' Fecha de Impresion   :  ' . date('d/m/Y') . '              Sucusal   : ' . $datos_empresa->sucursal), 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);
        /*  CANTIDAD  */
        $this->pdf->Cell(27, 5, utf8_decode(' Cantidad Total            : ' . $monto_total), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);


        /* Encabezado de la columna*/
        $this->pdf->Cell(10, 5, "NRO", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "CODIGO", 1, 0, 'C');
        $this->pdf->Cell(70, 5, "PRODUCTO", 1, 0, 'C');
        $this->pdf->Cell(45, 5, "COLOR", 1, 0, 'C');
        $this->pdf->Cell(15, 5, "CANT.", 1, 0, 'C');
        $this->pdf->Cell(30, 5, "ESTADO", 1, 0, 'C');
        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 8);

        $cantidad_filas = 0;


        foreach ($lista_compras as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($cantidad_filas == count($lista_compras)) {
                $estilo = 'LRB';
            }

            $this->pdf->Cell(10, 4, utf8_decode($cantidad_filas), $estilo, 0, 'C');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->codigo_barra), $estilo, 0, 'C');
            $this->pdf->Cell(70, 4, utf8_decode($row_detalle->nombre_item), $estilo, 0, 'C');
            $this->pdf->Cell(45, 4, utf8_decode($row_detalle->color), $estilo, 0, 'C');
            $this->pdf->Cell(15, 4, utf8_decode($row_detalle->cantidad), $estilo, 0, 'C');
            $this->pdf->Cell(30, 4, utf8_decode($row_detalle->estado_inventario), $estilo, 0, 'C');
            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }

        $this->pdf->Output("ReporteStock.pdf", 'I');

    }


}