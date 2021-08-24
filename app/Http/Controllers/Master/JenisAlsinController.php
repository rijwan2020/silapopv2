<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseController;
use App\Repositories\JenisAlsinRepository;
use App\Repositories\OpsinRepository;
use Illuminate\Http\Request;

class JenisAlsinController extends BaseController
{
    private $opsin;
    public function __construct(JenisAlsinRepository $repo, OpsinRepository $opsin)
    {
        $this->repo = $repo;
        $this->opsin = $opsin;
    }
    
    public function validasi(Request $request)
    {
        return $request->validate([
            'nama' => 'required',
            'keterangan' => 'nullable',
            'user_id' => 'required',
        ]);
    }

    public function index(Request $request)
    {
        $data = $this->repo->list($request);
        foreach ($data as $key => $value) {
            $data[$key]['jumlah_alsin'] = $this->opsin->sum(['params' => ['jenis_id' => $value->id]]);
        }
        $this->output['data'] = $data;
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }
}
