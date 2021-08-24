<?php

namespace App\Exports\Laporan;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class HtpAreaExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
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
            [$this->data['komoditi']],
            [$this->data['area']],
            $this->data['field'][0],
            $this->data['field'][1]
        ];
    }

    public function registerEvents(): array
    {
        $style = config('styleExport');
        return [
            AfterSheet::class => function (AfterSheet $event) use ($style) {
                $event->sheet->mergeCells("A1:Q1");
                $event->sheet->getStyle('A1')->applyFromArray($style['title']);
                $event->sheet->mergeCells("A2:Q2");
                $event->sheet->getStyle('A2')->applyFromArray($style['subtitle']);
                $event->sheet->mergeCells("A3:Q3");
                $event->sheet->getStyle('A3')->applyFromArray($style['periode']);
                $event->sheet->getStyle('A1:Q3')->applyFromArray($style['border']);

                $i = count($this->data['data']) + 5;
                $event->sheet->getStyle('A6')->applyFromArray($style['border']);
                $event->sheet->duplicateStyle($event->sheet->getStyle('A6'), 'A4:Q' . $i);
                $event->sheet->mergeCells("A4:A5");
                $event->sheet->mergeCells("B4:B5");
                $event->sheet->mergeCells("C4:N4");
                $event->sheet->mergeCells("O4:O5");
                $event->sheet->mergeCells("P4:P5");
                $event->sheet->mergeCells("Q4:Q5");
                $event->sheet->getStyle('A4:Q5')->applyFromArray($style['head']);

                $event->sheet->mergeCells("A" . ($i - 2) . ":B" . ($i - 2));
                $event->sheet->mergeCells("A" . ($i - 1) . ":B" . ($i - 1));
                $event->sheet->mergeCells("A" . $i . ":B" . $i);
                $event->sheet->getStyle("A" . ($i - 2) . ":Q" . $i)->applyFromArray($style['footer']);;
            }
        ];
        
    }
}

