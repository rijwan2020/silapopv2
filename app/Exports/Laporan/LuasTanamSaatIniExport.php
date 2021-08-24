<?php

namespace App\Exports\Laporan;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class LuasTanamSaatIniExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
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
        ];
    }

    public function registerEvents(): array
    {
        $style = config('styleExport');
        return [
            AfterSheet::class => function (AfterSheet $event) use ($style) {
                $event->sheet->mergeCells("A1:F1");
                $event->sheet->getStyle('A1')->applyFromArray($style['title']);
                $event->sheet->mergeCells("A2:F2");
                $event->sheet->getStyle('A2')->applyFromArray($style['subtitle']);
                $event->sheet->mergeCells("A3:F3");
                $event->sheet->getStyle('A3')->applyFromArray($style['periode']);
                $event->sheet->getStyle('A1:F3')->applyFromArray($style['border']);

                $i = count($this->data['data']) + 4;
                $event->sheet->getStyle('A5')->applyFromArray($style['border']);
                $event->sheet->duplicateStyle($event->sheet->getStyle('A5'), 'A4:F' . $i);
                $event->sheet->getStyle('A4:F4')->applyFromArray($style['head']);

                $event->sheet->mergeCells("A".$i.":B".$i);
                $event->sheet->getStyle("A".$i.":F".$i)->applyFromArray($style['footer']);;
            }
        ];
        
    }
}
