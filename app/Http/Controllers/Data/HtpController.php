<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\BaseController;
use App\Repositories\AreaRepository;
use App\Repositories\HtpRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HtpController extends BaseController
{
    private $area;

    public function __construct(HtpRepository $repo, AreaRepository $area)
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
            'tanggal' => 'required',
            'komoditi_id' => 'required',
            'htp' => 'required|numeric',
            'lat' => 'required',
            'long' => 'required',
            'user_id' => 'required',
            'ket_file' => 'nullable',
        ]);
    }

    public function index(Request $request)
    {
        
        $this->output['data'] = $this->repo->data($request);
        $this->output['total'] = $this->repo->total($request);
        $param = [];
        if($request->has('params')){
            $param = (array) json_decode($request->params);
        }
        $this->output['rata_rata'] = round($this->repo->getAvg(['params' => $param]), 2);
        return $this->done();
    }
    
    public function file(Request $request)
    {
        $data = $this->repo->file($request, 'htp');
        if (!$data) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }
    
}
