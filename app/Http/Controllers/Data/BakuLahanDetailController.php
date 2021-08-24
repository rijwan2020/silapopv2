<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\BaseController;
use App\Repositories\AreaRepository;
use App\Repositories\BakulahanDetailRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BakuLahanDetailController extends BaseController
{
    private $area;

    public function __construct(BakulahanDetailRepository $repo, AreaRepository $area)
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
            'tanggal_input' => 'required',
            'jenis' => 'required',
            'rencana_tanam' => 'required|numeric',
            'realisasi_tanam' => 'required|numeric',
            'lat' => 'required',
            'long' => 'required',
            'user_id' => 'required',
            'ket_file' => 'nullable',
        ]);
    }
    
    public function index(Request $request)
    {
        
        $this->output['data'] = $this->repo->data($request);
        $this->output['total_rencana_tanam'] = $this->repo->sumRencanaTanam($request);
        $this->output['total_realisasi_tanam'] = $this->repo->sumRealisasiTanam($request);
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }

    public function file(Request $request)
    {
        $data =  $this->repo->file($request, 'bakulahan/detail');
        if (!$data) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }

}
