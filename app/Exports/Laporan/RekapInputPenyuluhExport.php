<?php

namespace App\Exports\Laporan;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class RekapInputPenyuluhExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
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
            $this->data['judul_3']
        ];
    }

    public function registerEvents(): array
    {
        $style = config('styleExport');
        return [
            AfterSheet::class => function (AfterSheet $event) use ($style) {
                $event->sheet->mergeCells("A1:P1");
                $event->sheet->getStyle('A1')->applyFromArray($style['title']);
                $event->sheet->mergeCells("A2:P2");
                $event->sheet->getStyle('A2')->applyFromArray($style['subtitle']);
                $event->sheet->getStyle('A1:P2')->applyFromArray($style['border']);

                $i = count($this->data['data']) + 3;
                $event->sheet->getStyle('A4')->applyFromArray($style['border']);
                $event->sheet->duplicateStyle($event->sheet->getStyle('A4'), 'A3:P' . $i);
                $event->sheet->getStyle('A3:P3')->applyFromArray($style['head']);
            }
        ];
        
    }
}
