<?php

namespace App\Jobs\Laporan;

use App\Exports\Laporan\RencanaTanamExport;
use App\Model\Download;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class RencanaTanamJob implements ShouldQueue
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
        $path = '/download/laporan/rencana_tanam/file_'. Carbon::now()->format('YmdHis') . '.xlsx';
        try {
            Excel::store(new RencanaTanamExport($data), $path);
            // $download = Download::find($id);
            $download['status'] = 1;
            $download['path'] = $path;
            Download::where('id', $this->id)->update($download);
        } catch (\Exception $e) {
            Log::info($e);
        }
    }
}
