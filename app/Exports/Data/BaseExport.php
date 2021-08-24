<?php

namespace App\Exports\Data;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class BaseExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
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
            $this->data['header']
        ];
    }

    public function registerEvents(): array
    {
        $style = config('styleExport');
        $data = $this->data;
        return [
            AfterSheet::class => function (AfterSheet $event) use ($style, $data) {
                $event->sheet->getStyle('A1')->applyFromArray($style['border']);
                $event->sheet->duplicateStyle($event->sheet->getStyle('A1'), 'A1:'. $data['last_col'] . $data['last_row']);

                $event->sheet->mergeCells("A1:".$data['last_col']."1");
                $event->sheet->getStyle('A1')->applyFromArray($style['subtitle']);
                $event->sheet->getStyle('A2:'.$data['last_col'].'2')->applyFromArray($style['head']);

            }
        ];
        
    }
}
