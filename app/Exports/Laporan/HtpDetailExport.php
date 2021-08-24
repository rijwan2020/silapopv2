<?php

namespace App\Exports\Laporan;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class HtpDetailExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
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
            [$this->data['judul']],
            [$this->data['kategori']],
            [$this->data['area']],
            $this->data['field'][0],
            $this->data['field'][1]
        ];
    }

    public function registerEvents(): array
    {
        $style = config('styleExport');
        $optionCol = [
            25 => 'AD',
            26 => 'AE',
            27 => 'AF',
            28 => 'AG',
            29 => 'AH',
            30 => 'AI',
            31 => 'AJ',
        ];
        $col = $optionCol[$this->data['jumlah_hari']];
        $colAvr = $optionCol[$this->data['jumlah_hari'] - 1];
        $colMin = $optionCol[$this->data['jumlah_hari'] - 2];
        $colHeader = $optionCol[$this->data['jumlah_hari'] - 3];
        return [
            AfterSheet::class => function (AfterSheet $event) use ($style, $col, $colHeader, $colAvr, $colMin) {
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
                $event->sheet->mergeCells($colAvr . "4:" . $colAvr . "5");
                $event->sheet->mergeCells($colMin . "4:" . $colMin . "5");
                $event->sheet->getStyle('A4:' . $col . '5')->applyFromArray($style['head']);

                $event->sheet->mergeCells("A" . ($i - 2) . ":B" . ($i - 2));
                $event->sheet->mergeCells("A" . ($i - 1) . ":B" . ($i - 1));
                $event->sheet->mergeCells("A" . $i . ":B" . $i);
                $event->sheet->getStyle("A" . ($i - 2) . ":" . $col . $i)->applyFromArray($style['footer']);;
            }
        ];
        
    }
}

