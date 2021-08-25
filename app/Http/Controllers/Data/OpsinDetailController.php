<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\BaseController;
use App\Repositories\AreaRepository;
use App\Repositories\OpsinDetailRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OpsinDetailController extends BaseController
{
    private $area;

    public function __construct(OpsinDetailRepository $repo, AreaRepository $area)
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
            'jenis_id' => 'required',
            'ltt_ltp' => 'required|numeric',
            'alsin' => 'required|numeric',
            'lat' => 'nullable',
            'long' => 'nullable',
            'user_id' => 'required',
            'ket_file' => 'nullable',
        ]);
    }

    public function index(Request $request)
    {
        $this->output['data'] = $this->repo->data($request);
        $this->output['total_ltt_ltp'] = $this->repo->sumLttLtp($request);
        $this->output['total_alsin'] = $this->repo->sumAlsin($request);
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }
    
    public function file(Request $request)
    {
        $data = $this->repo->file($request, 'opsin/detail');
        if (!$data) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }
}
