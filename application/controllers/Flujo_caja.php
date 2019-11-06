<?php
/**
 * Created by PhpStorm.
 * User: Juan
 * Date: 23/03/2018
 * Time: 08:39 PM
 */
class Flujo_caja extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('flujo_caja_model', 'flujo');
    }

    public function index()
    {
        $inicio_mes = date("Y-m");
        $inicio_dia = $inicio_mes . "-01";
        $data["total_ingreso"] = $this->flujo->total_ingreso($inicio_dia, date("Y-m-d"));
        $data["total_egreso"] = $this->flujo->total_egreso($inicio_dia, date("Y-m-d"));
        $data["flujo_caja"] = round($data["total_ingreso"] - $data["total_egreso"], 2);
        $data["fecha_inicio"] = $inicio_dia;
        $data["fecha_actual"] = date("Y-m-d");
        $data["ingreso"] = $this->flujo->lista_ingreso_detalle($inicio_dia, date("Y-m-d"));
        $data["egreso"] = $this->flujo->lista_egreso_detalle($inicio_dia, date("Y-m-d"));
        plantilla('flujo/index', $data);
    }
    public function obtener_ingresos()
    {
        if ($this->input->is_ajax_request()) {
            $lista_ingreso = [];
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            $lista_ingreso = $this->flujo->obtener_ingresos($fecha_inicio, $fecha_fin);
            echo json_encode($lista_ingreso);
        } else {
            show_404();
        }
    }
    public function obtener_egresos()
    {
        if ($this->input->is_ajax_request()) {
            $lista_egreso = [];
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin = $this->input->post('fecha_fin');
            $lista_egreso = $this->flujo->obtener_egresos($fecha_inicio, $fecha_fin);
            echo json_encode($lista_egreso);
        } else {
            show_404();
        }
    }
    public function obtener_totales()
    {
        if ($this->input->is_ajax_request()) {
            $data["total_ingreso"] = $this->flujo->total_ingreso($this->input->post('fecha_inicio'), $this->input->post('fecha_fin'));
            $data["total_egreso"] = $this->flujo->total_egreso($this->input->post('fecha_inicio'), $this->input->post('fecha_fin'));
            $data["flujo_caja"] = round($data["total_ingreso"] - $data["total_egreso"], 2);
            echo json_encode($data);
        } else {
            show_404();
        }
    }
    public function detalle_buscar()
    {
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');
        $data["ingreso"] = $this->flujo->lista_ingreso_detalle($fecha_inicio, $fecha_fin);
        $data["egreso"] = $this->flujo->lista_egreso_detalle($fecha_inicio, $fecha_fin);
        $data["monto_ingreso_total"] = $this->flujo->total_ingreso($fecha_inicio, $fecha_fin);
        $data["monto_egreso_total"] = $this->flujo->total_egreso($fecha_inicio, $fecha_fin);
        echo json_encode($data);
    }

    /*  metodo para exportacion en archivo excel de manera  simple  */
    public function exportar_excel_simple()
    {
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');
        $monto_total_ingreso = ($this->flujo->total_ingreso($fecha_inicio, $fecha_fin));
        $monto_total_egreso = ($this->flujo->total_egreso($fecha_inicio, $fecha_fin));
        $monto_flujo_caja = $monto_total_ingreso - $monto_total_egreso;
        $ingresos = $this->flujo->lista_ingreso_detalle($fecha_inicio, $fecha_fin);
        $egresos = $this->flujo->lista_egreso_detalle($fecha_inicio, $fecha_fin);
        //load librarynya terlebih dahulu
        //jika digunakan terus menerus lebih baik load ini ditaruh di auto load
        $this->load->library("excel/PHPExcel");

        /*  Creamos Objeto excel    */
        $objPHPExcel = new PHPExcel();

        /*  unimos la celda */
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->setActiveSheetIndex(0)//elegimos hoja donde vamos a escribir
        /*empesamos a escribir en la hoja   de excel*/

        ->setCellValue('A1', 'FLUJO DE CAJA')
            ->setCellValue('A3', 'Fecha inicial: ')
            ->setCellValue('B3', date('d-m-Y', strtotime($fecha_inicio)))
            ->setCellValue('D3', 'Fecha final: ')
            ->setCellValue('E3', date('d-m-Y', strtotime($fecha_fin)))
            ->setCellValue('A5', 'Total ingreso(Bs.): ')
            ->setCellValue('B5', number_format($monto_total_ingreso, 2))
            ->setCellValue('D5', 'Total egreso( Bs.): ')
            ->setCellValue('E5', number_format($monto_total_egreso, 2))
            ->setCellValue('A7', 'Flujo caja(Bs.): ')
            ->setCellValue('B7', number_format($monto_flujo_caja, 2));

        $objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        //poner negritas
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A7')->getFont()->setBold(TRUE);

        //centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $borders = array(
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('B10:D10')->applyFromArray($borders);

        $fila = 10;
        $total_filas_ingresos = $ingresos['row_tipo_ingreso'];
        /*combinamos para crear columna INGRESO*/
        $cantidad_filas_ingresos = $this->obtener_cantidad_ingresos($ingresos);
        /* $objPHPExcel->getActiveSheet()->mergeCells('B10:B' . ($total_filas_ingresos + $fila - 1))
             ->setCellValue('B10', 'Ingresos');*/

        //centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('B10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $fila_inicio = $fila;
        $lista_ingresos = $ingresos['lista_ingresos'];

        /* cabezera */
        /*  coloreamos las cabezeras*/
        $objPHPExcel->getActiveSheet()->getStyle('B' . ($fila_inicio - 1) . ':D' . ($fila_inicio - 1))->getFill()->applyFromArray(
            array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => '2d2828'
                )
            ));

        $styleArray = array(
            'font' => array(
                'color' => array('rgb' => 'FFFFFF')
            ));
        $objPHPExcel->getActiveSheet()->getStyle('B' . ($fila_inicio - 1) . ':D' . ($fila_inicio - 1))->applyFromArray($styleArray);
        /*  seteamos titulos de cabezera    */
        $objPHPExcel->getActiveSheet()
            ->setCellValue('B' . ($fila_inicio - 1), 'Flujo')
            ->setCellValue('C' . ($fila_inicio - 1), 'Descripcion')
            ->setCellValue('D' . ($fila_inicio - 1), 'Monto total(Bs.)');
        /*  Graficado de los datos de Ingreso   */
        foreach ($lista_ingresos as $ingreso):
            $cantidad_datos = $ingreso['cantidad_datos'];
            $fila_final = $fila_inicio;
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila_inicio, $ingreso['descripcion']);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $fila_inicio)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


            $borders = array(
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    )
                ),
            );
            $objPHPExcel->getActiveSheet()->getStyle('C' . $fila_inicio . ':D' . $fila_inicio)->applyFromArray($borders);
            /*  cargamos las filas de los datos obtenidos   */
            $ingreso_lista_filas = $ingreso['lista'];

            $objPHPExcel->getActiveSheet()
                ->setCellValue('D' . $fila_final, $ingreso['monto_total_tipo']);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $fila_final)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $fila_inicio = $fila_final + 1;
        endforeach;
        /*  Graficado de los datos de Egreso   */
        $total_filas_egresos = $egresos['row_tipo_egreso'];
        /*combinamos para crear columna INGRESO*/
        $cantidad_filas_ingresos = $this->obtener_cantidad_ingresos($egresos);
        /* $objPHPExcel->getActiveSheet()->mergeCells('B' . $fila_inicio . ':B' . ($total_filas_egresos + $fila_inicio - 1))
             ->setCellValue('B' . $fila_inicio, 'Egresos');*/
        $objPHPExcel->getActiveSheet()->getStyle('B' . $fila_inicio)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $lista_egresos = $egresos['lista_egresos'];

        $borders = array(
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('B' . $fila_inicio . ':D' . $fila_inicio)->applyFromArray($borders);

        foreach ($lista_egresos as $egreso):
            $cantidad_datos = $egreso['cantidad_datos'];
            $fila_final = $fila_inicio;
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()
                ->setCellValue('C' . $fila_inicio, $egreso['descripcion']);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $fila_inicio)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $borders = array(
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    )
                ),
            );
            $objPHPExcel->getActiveSheet()->getStyle('C' . $fila_inicio . ':D' . $fila_inicio)->applyFromArray($borders);
            /*  cargamos las filas de los datos obtenidos   */
            $egreso_lista_filas = $egreso['lista'];
            $objPHPExcel->getActiveSheet()
                ->setCellValue('D' . $fila_final, number_format($egreso['monto_total_tipo'], 2));
            $objPHPExcel->getActiveSheet()->getStyle('D' . $fila_final)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $fila_inicio = $fila_final + 1;
        endforeach;
        $borders = array(
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );
        $fila_inicio--;
        $objPHPExcel->getActiveSheet()->getStyle('B' . $fila_inicio . ':D' . $fila_inicio)->applyFromArray($borders);

        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('A')
            ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('B')
            ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('C')
            ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('D')
            ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('E')
            ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('F')
            ->setAutoSize(true);

        /*mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5*/
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        /*sesuaikan headernya*/
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        /*ubah nama file saat diunduh*/
        header('Content-Disposition: attachment;filename="Flujo_caja.xlsx"');
        /*unduh file*/
        $objWriter->save("php://output");
    }


    /*  metodo para exportacion en archivo excel de manera detallada    */
    public function detalle_excel()
    {
        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin = $this->input->post('fecha_fin');
        $monto_total_ingreso = ($this->flujo->total_ingreso($fecha_inicio, $fecha_fin));
        $monto_total_egreso = ($this->flujo->total_egreso($fecha_inicio, $fecha_fin));
        $monto_flujo_caja = $monto_total_ingreso - $monto_total_egreso;
        $ingresos = $this->flujo->lista_ingreso_detalle($fecha_inicio, $fecha_fin);
        $egresos = $this->flujo->lista_egreso_detalle($fecha_inicio, $fecha_fin);
        //load librarynya terlebih dahulu
        //jika digunakan terus menerus lebih baik load ini ditaruh di auto load
        $this->load->library("excel/PHPExcel");

        /*  Creamos Objeto excel    */
        $objPHPExcel = new PHPExcel();

        /*  unimos la celda */
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->setActiveSheetIndex(0)//elegimos hoja donde vamos a escribir
        /*empesamos a escribir en la hoja   de excel*/

        ->setCellValue('A1', 'FLUJO DE CAJA DETALLADO')
            ->setCellValue('A3', 'Fecha inicial: ')
            ->setCellValue('B3', date('d-m-Y', strtotime($fecha_inicio)))
            ->setCellValue('D3', 'Fecha final: ')
            ->setCellValue('E3', date('d-m-Y', strtotime($fecha_fin)))
            ->setCellValue('A5', 'Total ingreso(Bs.): ')
            ->setCellValue('B5', number_format($monto_total_ingreso, 2))
            ->setCellValue('D5', 'Total egreso( Bs.): ')
            ->setCellValue('E5', number_format($monto_total_egreso, 2))
            ->setCellValue('A7', 'Flujo caja(Bs.): ')
            ->setCellValue('B7', number_format($monto_flujo_caja, 2));

        $objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        //poner negritas
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A7')->getFont()->setBold(TRUE);

        //centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $borders = array(
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('B9:F9')->applyFromArray($borders);

        $fila = 9;
        $total_filas_ingresos = $ingresos['row_total'];
        /*combinamos para crear columna INGRESO*/
        $cantidad_filas_ingresos = $this->obtener_cantidad_ingresos($ingresos);
        $objPHPExcel->getActiveSheet()->mergeCells('B9:B' . ($total_filas_ingresos + $fila - 1))
            ->setCellValue('B9', 'Ingresos');

        //centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $fila_inicio = $fila;
        $lista_ingresos = $ingresos['lista_ingresos'];

        /*  Graficado de los datos de Ingreso   */
        foreach ($lista_ingresos as $ingreso):
            $cantidad_datos = $ingreso['cantidad_datos'];
            $fila_final = $fila_inicio + $cantidad_datos + 1;
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->mergeCells('C' . $fila_inicio . ':C' . $fila_final)
                ->setCellValue('C' . $fila_inicio, $ingreso['descripcion']);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $fila_inicio)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            /*  coloreamos las cabezeras*/
            $objPHPExcel->getActiveSheet()->getStyle('D' . $fila_inicio . ':F' . $fila_inicio)->getFill()->applyFromArray(
                array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'F28A8C'
                    )
                ));

            /* seteamos los titulos */
            $objPHPExcel->getActiveSheet()
                ->setCellValue('D' . $fila_inicio, 'Fecha');
            $objPHPExcel->getActiveSheet()
                ->setCellValue('E' . $fila_inicio, 'Concepto');
            $objPHPExcel->getActiveSheet()
                ->setCellValue('F' . $fila_inicio, 'Monto(Bs.)');


            $borders = array(
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    )
                ),
            );
            $objPHPExcel->getActiveSheet()->getStyle('D' . $fila_inicio . ':F' . $fila_inicio)->applyFromArray($borders);
            /*  cargamos las filas de los datos obtenidos   */
            $ingreso_lista_filas = $ingreso['lista'];
            foreach ($ingreso_lista_filas as $fila_datos) {
                $fila_inicio++;
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('D' . $fila_inicio, $fila_datos->fecha_registro);
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('E' . $fila_inicio, $fila_datos->detalle);
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('F' . $fila_inicio, $fila_datos->monto);
                $objPHPExcel->getActiveSheet()->getStyle('F' . $fila_inicio)
                    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $borders = array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FF000000'),
                        ),
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FF000000'),
                        )
                    ),
                );
                $objPHPExcel->getActiveSheet()->getStyle('D' . $fila_inicio . ':F' . $fila_inicio)->applyFromArray($borders);
            }

            $objPHPExcel->getActiveSheet()->mergeCells('D' . $fila_final . ':E' . $fila_final)
                ->setCellValue('D' . $fila_final, 'Monto total:')
                ->setCellValue('F' . $fila_final, $ingreso['monto_total_tipo']);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $fila_final)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $fila_final)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $fila_inicio = $fila_final + 1;
        endforeach;

        /*  Graficado de los datos de Egreso   */
        $total_filas_egresos = $egresos['row_total'];
        /*combinamos para crear columna INGRESO*/
        $cantidad_filas_ingresos = $this->obtener_cantidad_ingresos($egresos);
       /* $objPHPExcel->getActiveSheet()->mergeCells('B' . $fila_inicio . ':B' . ($total_filas_egresos + $fila_inicio - 1))
            ->setCellValue('B' . $fila_inicio, 'Egresos');*/
        $objPHPExcel->getActiveSheet()->getStyle('B' . $fila_inicio)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $lista_egresos = $egresos['lista_egresos'];

        $borders = array(
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('B' . $fila_inicio . ':F' . $fila_inicio)->applyFromArray($borders);

        foreach ($lista_egresos as $egreso):
            $cantidad_datos = $egreso['cantidad_datos'];
            $fila_final = $fila_inicio + $cantidad_datos + 1;
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->mergeCells('C' . $fila_inicio . ':C' . $fila_final)
                ->setCellValue('C' . $fila_inicio, $egreso['descripcion']);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $fila_inicio)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            /*  coloreamos las cabezeras*/
            $objPHPExcel->getActiveSheet()->getStyle('D' . $fila_inicio . ':F' . $fila_inicio)->getFill()->applyFromArray(
                array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'F28A8C'
                    )
                ));

            /* seteamos los titulos */
            $objPHPExcel->getActiveSheet()
                ->setCellValue('D' . $fila_inicio, 'Fecha');
            $objPHPExcel->getActiveSheet()
                ->setCellValue('E' . $fila_inicio, 'Forma de pago');
            $objPHPExcel->getActiveSheet()
                ->setCellValue('F' . $fila_inicio, 'Monto(Bs. )');

            /*  cargamos las filas de los datos obtenidos   */
            $egreso_lista_filas = $egreso['lista'];
            $borders = array(
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000'),
                    )
                ),
            );
            $objPHPExcel->getActiveSheet()->getStyle('D' . $fila_inicio . ':F' . $fila_inicio)->applyFromArray($borders);
            foreach ($egreso_lista_filas as $fila_datos) {
                $fila_inicio++;
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('D' . $fila_inicio, $fila_datos->fecha_registro);
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('E' . $fila_inicio, $fila_datos->detalle);
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('F' . $fila_inicio, $fila_datos->monto);
                $objPHPExcel->getActiveSheet()->getStyle('F' . $fila_inicio)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                $borders = array(
                    'borders' => array(
                        'bottom' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FF000000'),
                        ),
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => 'FF000000'),
                        )
                    ),
                );
                $objPHPExcel->getActiveSheet()->getStyle('D' . $fila_inicio . ':F' . $fila_inicio)->applyFromArray($borders);
            }

            $objPHPExcel->getActiveSheet()->mergeCells('D' . $fila_final . ':E' . $fila_final)
                ->setCellValue('D' . $fila_final, 'Monto total:')
                ->setCellValue('F' . $fila_final, $egreso['monto_total_tipo']);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $fila_final)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $fila_final)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $fila_inicio = $fila_final + 1;
        endforeach;
        $borders = array(
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );
        $fila_inicio--;
        $objPHPExcel->getActiveSheet()->getStyle('B' . $fila_inicio . ':F' . $fila_inicio)->applyFromArray($borders);

        $borders = array(
            'borders' => array(
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF000000'),
                )
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A40' . ':A40')->applyFromArray($borders);

        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('A')
            ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('B')
            ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('C')
            ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('D')
            ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('E')
            ->setAutoSize(true);
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension('F')
            ->setAutoSize(true);

        /*mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5*/
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        /*sesuaikan headernya*/
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        /*ubah nama file saat diunduh*/
        header('Content-Disposition: attachment;filename="Flujo_caja_detallado.xlsx"');
        /*unduh file*/
        $objWriter->save("php://output");
    }

    public function obtener_cantidad_ingresos($ingreso)
    {
        return $ingreso['row_total'];
    }

    /*  Imprimir Factura    */
    public function factura()
    {
        $this->load->model('model_venta');
        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        // $ingresos  = $this->flujo_caja_model->obtener_ingresos($fecha_inicio,$fecha_fin); array(216, 279)
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();


        /* DATOS DE LA VENTA    */

        $venta = $this->model_venta->imprimirFactura(24);
        /**/
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("FACTURA");
        /* La variable $x se utiliza para mostrar un número consecutivo */

        /* titulo de ingreso*/
        $var_img = base_url() . 'assets/img/logo.png';
        $this->pdf->Image($var_img, 10, 10, 68, 28);
        /*  NIT Y NRO FACTURA   */
        $this->pdf->Cell(60, 7, '', 0);
        $this->pdf->Cell(60, 7, '', 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(37, 7, 'NIT                           :', 'LT');
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(35, 7, $venta["datos_factura"]->nit, 'TR');/*  celda para poner el numero de NIT   */
        $this->pdf->Ln();

        $this->pdf->Cell(60, 7, '', 0);
        $this->pdf->Cell(60, 7, '', 0);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(37, 7, utf8_decode('Nº FACTURA           :'), 'L');
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(35, 7, $venta["datos_factura"]->nro_factura, 'R');/*  celda para poner el numero de factura   */
        $this->pdf->Ln();

        $this->pdf->Cell(60, 7, '', 0);
        $this->pdf->SetFont('Arial', 'B', 22);
        $this->pdf->Cell(60, 7, 'FACTURA', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(37, 7, utf8_decode('Nº AUTORIZACION :'), 'LB');
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(35, 7, $venta["datos_factura"]->autorizacion, 'BR');/*  celda para poner el numero de autorizacion   */
        $this->pdf->Ln(12);

        /*  intenando poner multicell   */
        // $this->pdf->SetWidths(array(60, 60, 72));
        $this->pdf->SetFont('Arial', 'B', 8);
        //$this->pdf->RowFactura(array($venta["datos_factura"]->nombre_empresa, '', 'O R I G I N A L'));

        $this->pdf->Cell(65, 5, $venta["datos_factura"]->nombre_empresa, 0, 0, 'C');
        $this->pdf->Cell(55, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(72, 5, 'O R I G I N A L', 0, 0, 'C');
        /**/
        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(60, 5, $venta["datos_factura"]->sucursal, 0, 0, 'C');
        $this->pdf->Cell(60, 5, '', 0);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, $venta["datos_factura"]->nombre_actividad, 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(60, 4, $venta["datos_factura"]->direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Ln(0);

        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(60, 4, 'Telf. ' . $venta["datos_factura"]->telefono . ' - ' . $venta["datos_factura"]->celular, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(4);
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(60, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);
        $this->pdf->Ln(7);


        /*  LUGAR Y FECHA ///// NIT CI*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, 'Lugar y fecha :', 'TL');
        $this->pdf->Cell(93, 5, '', 'T');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(18, 5, 'NIT/CI : ', 'T');
        $this->pdf->Cell(54, 5, '', 'TR');
        $this->pdf->Ln(5);


        /*  CLIENTE */
        $this->pdf->Cell(27, 5, utf8_decode('Señor(es)       :'), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /*  DETALLE DE ITEMS */
        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->SetFont('Arial', 'B', 8);
        //un arreglo con alineacion de cada celda
        /* Encabezado de la columna*/
        $this->pdf->Cell(13, 5, "NRO", 1, 0, 'C');
        $this->pdf->Cell(154, 5, "DESCRIPCION", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "SUBTOTAL", 1, 0, 'C');
        $this->pdf->Ln(5);
        /*  detalle*/
        $nro = 1;
        $detalle_venta = $venta["datos_venta_detalle"];
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetAligns(array('C', 'L', 'R'));
        $cantidad_filas = 0;
        $numero_items = 10;
        foreach ($detalle_venta as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($numero_items == $cantidad_filas) {
                $estilo = 'LRB';
            }

            $this->pdf->Cell(13, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(154, 4, utf8_decode($row_detalle->detalle), $estilo, 0, 'L');
            $this->pdf->Cell(25, 4, utf8_decode($row_detalle->precio_venta . '  '), $estilo, 0, 'R');
            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }

        while ($numero_items > $cantidad_filas) {
            $cantidad_filas++;
            if ($numero_items == $cantidad_filas) {
                $estilo = 'LRB';
            }
            $this->pdf->Cell(13, 4, '', $estilo, 0, 'C');
            $this->pdf->Cell(154, 4, 'dont worry be happy', $estilo, 0, 'L');
            $this->pdf->Cell(25, 4, '', $estilo, 0, 'R');
            $this->pdf->Ln(4);
        }

        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'Son:', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(135, 5, 'dasdad', 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs. :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(25, 5, $venta["datos_factura"]->monto_total, 1, 0, 'R');
        $this->pdf->Ln(8);

        /*  QR  */
        $this->pdf->Image($venta["qr_image"], $this->pdf->GetPageWidth() - 29, 126, 22, 22);

        /*  Codigo de control*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, 'Codigo de control:', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(30, 5, $venta["datos_factura"]->codigo_control, 'TBR', 0, 'L');

        /*  fecha lmite de emision  */
        $this->pdf->Ln(6);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(35, 5, utf8_decode("Fecha límite de emisión:"), 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(22, 5, date('d-m-Y', strtotime($venta["datos_factura"]->fecha_limite)), 'TBR', 0, 'L');


        $this->pdf->Ln(15);
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(192, 5, utf8_decode('"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS. EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LEY."'), 1, 0, 'C');
        $this->pdf->SetFont('Arial', '', 8);

        /*  Leyenda */
        $this->pdf->Ln(5);
        $this->pdf->SetFont('Arial', '', 7);
        $this->pdf->Cell(192, 5, utf8_decode($venta["datos_factura"]->leyenda), 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 8);

        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetWidths(array(20, 100, 60));
        //un arreglo con alineacion de cada celda
        $this->pdf->SetAligns(array('C', 'L', 'R'));
        /*  quitamos bolf, y empezamos a dibujar las grillas*/
        $this->pdf->SetFont('Arial', '', 9);


        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->Output("Factura.pdf", 'I');
    }

    /*  Imprimir Proforma  */
    public function proforma()
    {
        $this->load->model('model_venta');
        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        // $ingresos  = $this->flujo_caja_model->obtener_ingresos($fecha_inicio,$fecha_fin); array(216, 279)
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();


        /* DATOS DE LA VENTA    */

        $venta = $this->model_venta->imprimirFactura(24);
        /**/
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("FACTURA");
        /* La variable $x se utiliza para mostrar un número consecutivo */

        /* titulo de ingreso*/
        $var_img = base_url() . 'assets/img/logo.png';
        $this->pdf->Image($var_img, 10, 10, 68, 28);
        /*  NIT Y NRO FACTURA   */

        /*  intenando poner multicell   */
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(65, 5, $venta["datos_factura"]->nombre_empresa, 0, 0, 'C');
        $this->pdf->Cell(55, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        /**/
        $this->pdf->Ln(5);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(60, 5, $venta["datos_factura"]->sucursal, 0, 0, 'C');
        $this->pdf->Cell(60, 5, '', 0);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(75, 5, ' ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(60, 5, 'P R O F O R M A', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(60, 4, $venta["datos_factura"]->direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(61, 5, utf8_decode('Nº '), 0, 0, 'C');
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(60, 4, 'Telf. ' . $venta["datos_factura"]->telefono . ' - ' . $venta["datos_factura"]->celular, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(4);
        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(55, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);
        $this->pdf->Ln(15);


        /*  LUGAR Y FECHA ///// NIT CI*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, 'Lugar y fecha :', 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);


        /*  CLIENTE */
        $this->pdf->Cell(27, 5, utf8_decode('Señor(es)       :'), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /*  DETALLE DE ITEMS */
        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->SetFont('Arial', 'B', 8);
        //un arreglo con alineacion de cada celda
        /* Encabezado de la columna*/
        $this->pdf->Cell(13, 5, "CANT.", 1, 0, 'C');
        $this->pdf->Cell(15, 5, "ESPESOR", 'TB', 0, 'C');
        $this->pdf->Cell(15, 5, "ANCHO", 1, 0, 'C');
        $this->pdf->Cell(15, 5, "LARGO", 1, 0, 'C');
        $this->pdf->Cell(99, 5, "OBSERVACIONES", 1, 0, 'C');
        $this->pdf->Cell(15, 5, "P. UNIT.", 1, 0, 'C');
        $this->pdf->Cell(20, 5, "TOTAL", 1, 0, 'C');
        $this->pdf->Ln(5);
        /*  detalle*/
        $nro = 1;
        $detalle_venta = $venta["datos_venta_detalle"];
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetAligns(array('C', 'L', 'R'));
        $cantidad_filas = 0;
        $numero_items = 15;
        foreach ($detalle_venta as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($numero_items == $cantidad_filas) {
                $estilo = 'LRB';
            }

            $this->pdf->Cell(13, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(15, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(15, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(15, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(99, 4, utf8_decode($row_detalle->detalle), $estilo, 0, 'L');
            $this->pdf->Cell(15, 4, utf8_decode($row_detalle->precio_venta . '  '), $estilo, 0, 'R');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->precio_venta . '  '), $estilo, 0, 'R');
            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }

        while ($numero_items > $cantidad_filas) {
            $cantidad_filas++;
            if ($numero_items == $cantidad_filas) {
                $estilo = 'LRB';
            }
            $this->pdf->Cell(13, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(15, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(15, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(15, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(99, 4, utf8_decode($row_detalle->detalle), $estilo, 0, 'L');
            $this->pdf->Cell(15, 4, utf8_decode($row_detalle->precio_venta . '  '), $estilo, 0, 'R');
            $this->pdf->Cell(20, 4, utf8_decode($row_detalle->precio_venta . '  '), $estilo, 0, 'R');
            $this->pdf->Ln(4);
        }

        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'Son:', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, 'dasdad', 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs. :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, 'TOTAL Bs. :', 1, 0, 'R');
        $this->pdf->Ln(8);


        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetWidths(array(20, 100, 60));
        //un arreglo con alineacion de cada celda
        $this->pdf->SetAligns(array('C', 'L', 'R'));
        /*  quitamos bolf, y empezamos a dibujar las grillas*/
        $this->pdf->SetFont('Arial', '', 9);


        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->Output("Factura.pdf", 'I');
    }

    /*  Imprimir Nota de venta  */
    public function nota()
    {
        $this->load->model('model_venta');
        $this->load->library('pdf');
        $this->pdf = new Pdf('P', 'mm', 'Legal');
        // $ingresos  = $this->flujo_caja_model->obtener_ingresos($fecha_inicio,$fecha_fin); array(216, 279)
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();


        /* DATOS DE LA VENTA    */

        $venta = $this->model_venta->imprimirFactura(24);
        /**/
        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("FACTURA");
        /* La variable $x se utiliza para mostrar un número consecutivo */

        /* titulo de ingreso*/
        $var_img = base_url() . 'assets/img/logo.png';
        $this->pdf->Image($var_img, 10, 10, 68, 28);
        /*  NIT Y NRO FACTURA   */

        /*  intenando poner multicell   */
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->Cell(65, 5, $venta["datos_factura"]->nombre_empresa, 0, 0, 'C');
        $this->pdf->Cell(55, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(248, 000, 000);

        /**/
        $this->pdf->Ln(5);
        $this->pdf->Cell(133, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(60, 5, $venta["datos_factura"]->sucursal, 0, 0, 'C');
        $this->pdf->Cell(60, 5, '', 0);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(72, 5, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(75, 5, ' ', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(60, 5, 'N O T A   D E   V E N T A', 0, 0, 'C');
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->Cell(60, 4, $venta["datos_factura"]->direccion, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(72, 4, '', 0, 'C');
        $this->pdf->Ln(0);
        $this->pdf->Cell(75, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(61, 5, utf8_decode('Nº '), 0, 0, 'C');
        $this->pdf->SetTextColor(0, 0, 0);/* volvemos a color de texto negro*/
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(60, 4, 'Telf. ' . $venta["datos_factura"]->telefono . ' - ' . $venta["datos_factura"]->celular, 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);

        $this->pdf->Ln(4);
        $this->pdf->Cell(139, 5, '', 0, 0, 'C');
        $this->pdf->SetFont('Arial', 'B', 7);
        $this->pdf->Cell(55, 4, 'Santa Cruz - Bolivia', 0, 0, 'C');
        $this->pdf->Cell(60, 4, '', 0);
        $this->pdf->Cell(72, 4, '', 0);
        $this->pdf->Ln(15);


        /*  LUGAR Y FECHA ///// NIT CI*/
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(27, 5, 'Lugar y fecha :', 'TL');
        $this->pdf->Cell(165, 5, '', 'TR');
        $this->pdf->SetFont('Arial', 'B', 8);;
        $this->pdf->Ln(5);


        /*  CLIENTE */
        $this->pdf->Cell(27, 5, utf8_decode('Señor(es)       :'), 'LB');
        $this->pdf->Cell(165, 5, '', 'RB');
        $this->pdf->Ln(7);

        /*  DETALLE DE ITEMS */
        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->SetFont('Arial', 'B', 8);

        /* Encabezado de la columna*/
        $this->pdf->Cell(13, 5, "NRO", 1, 0, 'C');
        $this->pdf->Cell(154, 5, "DESCRIPCION", 1, 0, 'C');
        $this->pdf->Cell(25, 5, "SUBTOTAL", 1, 0, 'C');
        $this->pdf->Ln(5);

        /*  detalle*/
        $nro = 1;
        $detalle_venta = $venta["datos_venta_detalle"];
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetAligns(array('C', 'L', 'R'));
        $cantidad_filas = 0;
        $numero_items = 15;
        foreach ($detalle_venta as $row_detalle) {
            $cantidad_filas++;
            $estilo = 'RL';
            if ($nro == 1) {
                $estilo = $estilo . 'T';
            }
            if ($numero_items == $cantidad_filas) {
                $estilo = 'LRB';
            }

            $this->pdf->Cell(13, 4, utf8_decode($nro), $estilo, 0, 'C');
            $this->pdf->Cell(154, 4, utf8_decode($row_detalle->detalle), $estilo, 0, 'L');
            $this->pdf->Cell(25, 4, utf8_decode($row_detalle->precio_venta . '  '), $estilo, 0, 'R');
            $this->pdf->Ln(4);
            $nro = $nro + 1;
        }

        while ($numero_items > $cantidad_filas) {
            $cantidad_filas++;
            if ($numero_items == $cantidad_filas) {
                $estilo = 'LRB';
            }
            $this->pdf->Cell(13, 4, '', $estilo, 0, 'C');
            $this->pdf->Cell(154, 4, 'dont worry be happy', $estilo, 0, 'L');
            $this->pdf->Cell(25, 4, '', $estilo, 0, 'R');
            $this->pdf->Ln(4);
        }

        $this->pdf->Ln(2);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(7, 5, 'Son:', 'LTB', 0, 'L');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(125, 5, 'dasdad', 'TBR', 0, 'L');
        $this->pdf->Cell(5, 5, '', '', 0, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(20, 5, 'TOTAL Bs. :', 1, 0, 'R');
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->Cell(35, 5, 'TOTAL Bs. :', 1, 0, 'R');
        $this->pdf->Ln(8);


        $this->pdf->SetMargins(10, 10, 10);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetWidths(array(20, 100, 60));
        //un arreglo con alineacion de cada celda
        $this->pdf->SetAligns(array('C', 'L', 'R'));
        /*  quitamos bolf, y empezamos a dibujar las grillas*/
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->Output("Factura.pdf", 'I');
    }


}