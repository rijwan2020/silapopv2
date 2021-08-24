<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\BakulahanRepository;
use App\Repositories\DataLuasRepository;
use App\Repositories\KomoditasRepository;
use Illuminate\Http\Request;

class KomoditasController extends BaseController
{
    private $dataluas;
    public function __construct(KomoditasRepository $repo, DataLuasRepository $dataluas)
    {
        $this->repo = $repo;
        $this->dataluas = $dataluas;
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
            $data[$key]['luas_tanam'] = $this->dataluas->sumLuasTanam(['params' => ['komoditi_id' => $value->id]]);
            $data[$key]['tambah_tanam'] = $this->dataluas->sumTambahTanam(['params' => ['komoditi_id' => $value->id]]);
            $data[$key]['luas_panen'] = $this->dataluas->sumLuasPanen(['params' => ['komoditi_id' => $value->id]]);
            $data[$key]['produksi'] = $this->dataluas->sumProduksi(['params' => ['komoditi_id' => $value->id]]);
            $data[$key]['produktivitas'] = round( $data[$key]['luas_panen'] > 0 ? ($data[$key]['produksi'] * 10 / $data[$key]['luas_panen']) : 0, 2);
        }
        $this->output['data'] = $data;
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }
}
