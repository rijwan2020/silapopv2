<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\BaseController;
use App\Repositories\AreaRepository;
use App\Repositories\BakulahanRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BakuLahanController extends BaseController
{
    private $area;

    public function __construct(BakulahanRepository $repo, AreaRepository $area)
    {
        $this->repo = $repo;
        $this->area = $area;
    }

    public function validasi(Request $request)
    {
        return $request->validate([
            'kota_id' => 'required',
            'kecamatan_id' => 'required',
            'kelurahan_id' => 'required',
            'tahun' => 'required',
            'jenis' => 'required',
            'luas_baku_lahan' => 'required|numeric',
            'lat' => 'nullable',
            'long' => 'nullable',
            'user_id' => 'required',
            'ket_file' => 'nullable',
        ]);
    }
    
    public function index(Request $request)
    {
        $this->output['data'] = $this->repo->data($request);
        $this->output['total_luas'] = $this->repo->sum($request);
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }

    public function file(Request $request)
    {
        $data = $this->repo->file($request, 'bakulahan');
        if (!$data) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }
    
}
