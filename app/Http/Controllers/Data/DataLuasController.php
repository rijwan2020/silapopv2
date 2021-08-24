<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\BaseController;
use App\Repositories\AreaRepository;
use App\Repositories\DataLuasRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataLuasController extends BaseController
{
    
    private $area;

    public function __construct(DataLuasRepository $repo, AreaRepository $area)
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
            'jenis' => 'required',
            'komoditi_id' => 'required',
            'luas_tanam' => 'required|numeric',
            'tambah_tanam' => 'required|numeric',
            'produksi' => 'required|numeric',
            'luas_panen' => 'required|numeric',
            'lat' => 'required',
            'long' => 'required',
            'user_id' => 'required',
            'ket_file_luas_tanam' => 'nullable',
            'ket_file_luas_panen' => 'nullable',
            'ket_file_tambah_tanam' => 'nullable',
            'ket_file_produksi' => 'nullable',
        ]);
    }
    
    public function index(Request $request)
    {
        $this->output['total'] = $this->repo->total($request);
        $param = [];
        if($request->has('params')){
            $param = (array) json_decode($request->params);
        }
        $this->output['data'] = $this->repo->Data($request);
        $this->output['total_luas_tanam'] = $this->repo->sumLuasTanam(['params' => $param]);
        $this->output['total_luas_panen'] = $this->repo->sumLuasPanen(['params' => $param]);
        $this->output['total_produksi'] = $this->repo->sumProduksi(['params' => $param]);
        $this->output['total_tambah_tanam'] = $this->repo->sumTambahTanam(['params' => $param]);
        $this->output['total_produktivitas'] = round(($this->output['total_luas_panen'] > 0 ? $this->output['total_produksi'] * 10 / $this->output['total_luas_panen'] : 0), 2);
        return $this->done();
    }
    
    public function file(Request $request)
    {
        $data = $this->repo->upload($request);
        if (!$data) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }
}
