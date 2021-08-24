<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseController;
use App\Repositories\NamaOptRepository;
use Illuminate\Http\Request;

class NamaOptController extends BaseController
{
    public function __construct(NamaOptRepository $repo)
    {
        $this->repo = $repo;
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
        $this->output['data'] = $data;
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }
}
