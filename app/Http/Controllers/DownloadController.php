<?php

namespace App\Http\Controllers;

use App\Repositories\DownloadRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends BaseController
{
    public function __construct(DownloadRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $data = $this->repo->list($request);
        $res = [];
        foreach($data as $key => $value){
            $res[] = [
                'id' => $value->id,
                'keterangan' => $value->keterangan,
                'date' => $value->created_at->format('Y-m-d H:i:s'),
                'status' => $value->status,
                // 'link' => Storage::url($value->path),
                'link' => 'https://m.penyuluhjabar.id/storage' . $value->path
            ];
        }
        $this->output['data'] = $res;
        $this->output['total'] = $this->repo->total($request);
        $this->output['curPage'] = $request->page ?? 1;
        return $this->done();
    }
}
