<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseController;
use App\Repositories\HtpRepository;
use App\Repositories\KomoditasHtpRepository;
use Illuminate\Http\Request;

class KomoditasHtpController extends BaseController
{
    private $htp;
    public function __construct(KomoditasHtpRepository $repo, HtpRepository $htp)
    {
        $this->repo = $repo;
        $this->htp = $htp;
    }
    
    public function validasi(Request $request)
    {
        return $request->validate([
            'nama' => 'required',
            'keterangan' => 'nullable',
            'kategori_id' => 'required',
            'user_id' => 'required',
        ]);
    }

    public function index(Request $request)
    {
        $data = $this->repo->list($request);
        foreach ($data as $key => $value) {
            $data[$key]['rata_rata'] = round($this->htp->getAvg(['params' => ['komoditi_id' => $value->id]]), 2);
        }
        $this->output['data'] = $data;
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }
}
