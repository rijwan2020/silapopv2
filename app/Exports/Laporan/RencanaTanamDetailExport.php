<?php

namespace App\Exports\Laporan;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class RencanaTanamDetailExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
{
    private $data;
    public function __construct($data = [])
    {
        $this->data = $data;
    }
    
    public function array(): array
    {
        return $this->data['data'];
    }

    public function headings(): array
    {
        return [
            ['Laporan Rencana Tanam '. $this->data['judul']],
            [$this->data['jenis']],
            [$this->data['area']],
            $this->data['field'][0],
            $this->data['field'][1]
        ];
    }

    public function registerEvents(): array
    {
        $style = config('styleExport');
        $optionCol = [
            25 => 'AB',
            26 => 'AC',
            27 => 'AD',
            28 => 'AE',
            29 => 'AF',
            30 => 'AG',
            31 => 'AH',
        ];
        $col = $optionCol[$this->data['jumlah_hari']];
        $colHeader = $optionCol[$this->data['jumlah_hari'] - 1];
        return [
            AfterSheet::class => function (AfterSheet $event) use ($style, $col, $colHeader) {
                $event->sheet->mergeCells("A1:" . $col . "1");
                $event->sheet->getStyle('A1')->applyFromArray($style['title']);
                $event->sheet->mergeCells("A2:" . $col . "2");
                $event->sheet->getStyle('A2')->applyFromArray($style['subtitle']);
                $event->sheet->mergeCells("A3:" . $col . "3");
                $event->sheet->getStyle('A3')->applyFromArray($style['periode']);
                $event->sheet->getStyle('A1:' . $col .'3')->applyFromArray($style['border']);

                $i = count($this->data['data']) + 5;
                $event->sheet->getStyle('A6')->applyFromArray($style['border']);
                $event->sheet->duplicateStyle($event->sheet->getStyle('A6'), 'A4:' . $col . $i);
                $event->sheet->mergeCells("A4:A5");
                $event->sheet->mergeCells("B4:B5");
                $event->sheet->mergeCells("C4:". $colHeader . "4");
                $event->sheet->mergeCells($col . "4:" . $col . "5");
                $event->sheet->getStyle('A4:' . $col . '5')->applyFromArray($style['head']);

                $event->sheet->mergeCells("A" . $i . ":B" . $i);
                $event->sheet->getStyle("A" . $i . ":" . $col . $i)->applyFromArray($style['footer']);;
            }
        ];
        
    }
}
