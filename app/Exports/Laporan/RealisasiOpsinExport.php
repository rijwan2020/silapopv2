<?php

namespace App\Exports\Laporan;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class RealisasiOpsinExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
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
            [$this->data['judul_1']],
            [$this->data['judul_2']],
            [$this->data['judul_3']],
            $this->data['judul_4'],
            $this->data['judul_5'],
            $this->data['judul_6']
        ];
    }

    public function registerEvents(): array
    {
        $style = config('styleExport');
        return [
            AfterSheet::class => function (AfterSheet $event) use ($style) {
                $event->sheet->mergeCells("A1:AB1");
                $event->sheet->getStyle('A1')->applyFromArray($style['title']);
                $event->sheet->mergeCells("A2:AB2");
                $event->sheet->getStyle('A2')->applyFromArray($style['subtitle']);
                $event->sheet->mergeCells("A3:AB3");
                $event->sheet->getStyle('A3')->applyFromArray($style['periode']);
                $event->sheet->getStyle('A1:AB3')->applyFromArray($style['border']);

                $i = count($this->data['data']) + 6;
                $event->sheet->getStyle('A7')->applyFromArray($style['border']);
                $event->sheet->duplicateStyle($event->sheet->getStyle('A7'), 'A4:AB' . $i);
                $event->sheet->mergeCells("A4:A6");
                $event->sheet->mergeCells("B4:B6");
                $event->sheet->mergeCells("C4:Z4");

                $event->sheet->mergeCells("C5:D5");
                $event->sheet->mergeCells("E5:F5");
                $event->sheet->mergeCells("G5:H5");
                $event->sheet->mergeCells("I5:J5");
                $event->sheet->mergeCells("K5:L5");
                $event->sheet->mergeCells("M5:N5");
                $event->sheet->mergeCells("O5:P5");
                $event->sheet->mergeCells("Q5:R5");
                $event->sheet->mergeCells("S5:T5");
                $event->sheet->mergeCells("U5:V5");
                $event->sheet->mergeCells("W5:X5");
                $event->sheet->mergeCells("Y5:Z5");

                $event->sheet->mergeCells("AA4:AB5");
                $event->sheet->getStyle('A4:AB6')->applyFromArray($style['head']);

                $event->sheet->mergeCells("A".$i.":B".$i);
                $event->sheet->getStyle("A".$i.":AB".$i)->applyFromArray($style['footer']);;
            }
        ];
        
    }
}
