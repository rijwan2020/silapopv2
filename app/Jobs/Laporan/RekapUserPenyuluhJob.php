<?php

namespace App\Jobs\Laporan;

use App\Exports\Laporan\RekapUserPenyuluhExport;
use App\Model\Download;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class RekapUserPenyuluhJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data, $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data = [], int $id)
    {
        $this->data = $data;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;

        $download['judul_1'] = "Laporan Rekap User Penyuluh";
        $download['judul_2'] = [
            'No',
            'Nama',
            'Jenis Penyuluh',
            'No Telepon',
            'Tempat Tugas',
            // 'Kota/Kabupaten',
            // 'Kecamatan',
            // 'Kelurahan/Desa',
            'Username'
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
                $value['username'],
            ];
            $no++;
        }


        $path = '/download/laporan/penyuluh/rekap_user/'. Carbon::now()->format('YmdHis') . '-' . $download['judul_1'] . '.xlsx';
        try {
            Excel::store(new RekapUserPenyuluhExport($download), $path);
            $updateDownload['status'] = 1;
            $updateDownload['path'] = $path;
            Download::where('id', $this->id)->update($updateDownload);
        } catch (\Exception $e) {
            Log::info($e);
        }
    }
}
