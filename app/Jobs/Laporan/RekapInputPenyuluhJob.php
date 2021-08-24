<?php

namespace App\Jobs\Laporan;

use App\Exports\Laporan\RekapInputPenyuluhExport;
use App\Model\Download;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class RekapInputPenyuluhJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data, $id, $tanggal;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data = [], int $id, $tanggal)
    {
        $this->data = $data;
        $this->id = $id;
        $this->tanggal = $tanggal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;

        $download['judul_1'] = "Laporan Rekap Input Penyuluh";
        $download['judul_2'] = Carbon::create($this->tanggal)->format('d M Y');
        $download['judul_3'] = [
            'No',
            'Nama',
            'Jenis Penyuluh',
            'No Telepon',
            'Tempat Tugas',
            // 'Kota/Kabupaten',
            // 'Kecamatan',
            // 'Kelurahan/Desa',
            'Baku Lahan',
            'Rencana Tanam',
            'Realisasi Tanam',
            'Luas Tanam',
            'Tambah Tanam',
            'Luas Panen',
            'Produksi',
            'Htp',
            'Opsin',
            'Ltt/Ltp',
            'Alsin',
        ];
        $download['data'] = [];

        $no = 1;
        foreach ($data['data'] as $key => $value) {
            $download['data'][$key] = [
                $no,
                $value['nama'],
                $value['jp'] == 0 ? 'PPL' : 'POPT',
                $value['no_hp'],
                $value['tempat_tugas'],
                // $value['kota'],
                // $value['kecamatan'],
                // $value['kelurahan'],
                $value['bakulahan'] ? 'Sudah' : 'Belum',
                $value['rencana_tanam'] ? 'Sudah' : 'Belum',
                $value['realisasi_tanam'] ? 'Sudah' : 'Belum',
                $value['luas_tanam'] ? 'Sudah' : 'Belum',
                $value['tambah_tanam'] ? 'Sudah' : 'Belum',
                $value['luas_panen'] ? 'Sudah' : 'Belum',
                $value['produksi'] ? 'Sudah' : 'Belum',
                $value['htp'] ? 'Sudah' : 'Belum',
                $value['opsin'] ? 'Sudah' : 'Belum',
                $value['ltt_ltp'] ? 'Sudah' : 'Belum',
                $value['alsin'] ? 'Sudah' : 'Belum',
            ];
            $no++;
        }


        $path = '/download/laporan/penyuluh/rekap_input/'. Carbon::now()->format('YmdHis') . '-' . $download['judul_1'] . '.xlsx';
        try {
            Excel::store(new RekapInputPenyuluhExport($download), $path);
            $updateDownload['status'] = 1;
            $updateDownload['path'] = $path;
            Download::where('id', $this->id)->update($updateDownload);
        } catch (\Exception $e) {
            Log::info($e);
        }
    }
}
