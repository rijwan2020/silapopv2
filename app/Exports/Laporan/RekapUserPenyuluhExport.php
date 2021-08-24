<?php

namespace App\Exports\Laporan;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class RekapUserPenyuluhExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
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
            $this->data['judul_2']
        ];
    }

    public function registerEvents(): array
    {
        $style = config('styleExport');
        return [
            AfterSheet::class => function (AfterSheet $event) use ($style) {
                
                $i = count($this->data['data']) + 2;
                $event->sheet->getStyle('A1')->applyFromArray($style['border']);
                $event->sheet->duplicateStyle($event->sheet->getStyle('A1'), 'A1:F' . $i);
                $event->sheet->mergeCells("A1:F1");
                $event->sheet->getStyle('A1')->applyFromArray($style['title']);
                $event->sheet->getStyle('A2:F2')->applyFromArray($style['head']);
            }
        ];
        
    }
}
