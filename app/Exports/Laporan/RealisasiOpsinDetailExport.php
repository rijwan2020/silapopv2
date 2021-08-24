<?php

namespace App\Exports\Laporan;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class RealisasiOpsinDetailExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
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
            $this->data['judul_6'],
        ];
    }

    public function registerEvents(): array
    {
        $style = config('styleExport');
        $col = config('cellExcel');
        $jml_hari = $this->data['jumlah_hari'];
        return [
            AfterSheet::class => function (AfterSheet $event) use ($style, $col, $jml_hari) {
                $last_col = $jml_hari*2 + 3;
                $event->sheet->mergeCells("A1:" . $col[$last_col] . "1");
                $event->sheet->getStyle('A1')->applyFromArray($style['title']);
                $event->sheet->mergeCells("A2:" . $col[$last_col] . "2");
                $event->sheet->getStyle('A2')->applyFromArray($style['subtitle']);
                $event->sheet->mergeCells("A3:" . $col[$last_col] . "3");
                $event->sheet->getStyle('A3')->applyFromArray($style['periode']);
                $event->sheet->getStyle('A1:' . $col[$last_col] .'3')->applyFromArray($style['border']);

                $i = count($this->data['data']) + 6;
                $event->sheet->getStyle('A7')->applyFromArray($style['border']);
                $event->sheet->duplicateStyle($event->sheet->getStyle('A7'), 'A4:' . $col[$last_col] . $i);
                $event->sheet->mergeCells("A4:A6");
                $event->sheet->mergeCells("B4:B6");
                $event->sheet->mergeCells("C4:" . $col[$last_col - 2] . "4");
                
                for ($j=2; $j <= ($jml_hari * 2); $j+=2) { 
                    $x = $j + 1;
                    $event->sheet->mergeCells($col[$j]."5:" . $col[$x] . "5");
                }
                $event->sheet->mergeCells($col[$last_col - 1]."4:" . $col[$last_col] . "5");
                $event->sheet->getStyle('A4:' . $col[$last_col] . '6')->applyFromArray($style['head']);

                $event->sheet->mergeCells("A" . $i . ":B" . $i);
                $event->sheet->getStyle("A" . $i . ":" . $col[$last_col] . $i)->applyFromArray($style['footer']);
            }
        ];
        
    }
}
