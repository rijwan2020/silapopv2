<?php

namespace App\Exports\Laporan;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class BakulahanExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
{

    private $data, $tahun, $jenis, $area, $field;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($data = [])
    {
        $this->data = $data['data'];
        $this->tahun = $data['tahun'];
        $this->jenis = $data['jenis'];
        $this->area = $data['area'];
        $this->field = $data['field'];
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            ['Laporan Baku Lahan Tahun '. $this->tahun],
            [$this->jenis],
            [$this->area],
            $this->field
        ];
    }

    public function registerEvents(): array
    {
        $style = config('styleExport');
        return [
            AfterSheet::class => function (AfterSheet $event) use ($style) {
                $event->sheet->mergeCells("A1:E1");
                $event->sheet->getStyle('A1')->applyFromArray($style['title']);
                $event->sheet->mergeCells("A2:E2");
                $event->sheet->getStyle('A2')->applyFromArray($style['subtitle']);
                $event->sheet->mergeCells("A3:E3");
                $event->sheet->getStyle('A3')->applyFromArray($style['periode']);
                $event->sheet->getStyle('A1:E3')->applyFromArray($style['border']);

                $i = count($this->data) + 4;
                $event->sheet->getStyle('A5')->applyFromArray($style['border']);
                $event->sheet->duplicateStyle($event->sheet->getStyle('A5'), 'A4:E' . $i);

                $event->sheet->getStyle('A4:E4')->applyFromArray($style['head']);
            }
        ];
        
    }
}
