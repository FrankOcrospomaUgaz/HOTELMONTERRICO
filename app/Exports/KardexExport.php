<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KardexExport implements FromArray, WithStyles
{
    use Exportable;

    protected $kardexData;
    protected $tituloData;

    public function __construct(array $kardexData, $titulo)
    {
        $this->kardexData = $kardexData;
        $this->tituloData = $titulo;
    }

    public function array(): array
    {
        return $this->kardexData;
    }

    public function styles(Worksheet $sheet)
    {
     
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', $this->tituloData); 
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center')->setVertical('center');

        $sheet->getStyle('A1')->getFont()->setBold(true); 
        $sheet->getRowDimension(1)->setRowHeight(30); 

        $sheet->getRowDimension(4)->setRowHeight(30);
        $sheet->getStyle('I4:K4')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C4:E4')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('F4:H4')->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A4:K5')->getFont()->setBold(true);
        $sheet->getStyle('A4:K5')->getAlignment()->setHorizontal('center')->setVertical('center');

        $sheet->getColumnDimension('B')->setAutoSize(true);

        // Combina las celdas para "ENTRADAS", "SALIDAS" y "SALDO"
        $sheet->mergeCells('C4:E4');
        $sheet->mergeCells('F4:H4');
        $sheet->mergeCells('I4:K4');




    }

}
