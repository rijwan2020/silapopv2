<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\AreaRepository;
use Illuminate\Http\Request;

class AreaController extends BaseController
{
    private $area;

    public function __construct(AreaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function validasi(Request $request)
    {
        return $request->validate([
            'name' => 'required',
            'koordinat' => 'nullable',
            'provinsi_id' => 'nullable',
            'kota_id' => 'nullable',
            'kecamatan_id' => 'nullable',
            'kelurahan_id' => 'nullable',
            'level' => 'nullable'
        ]);
    }

    public function kotaList(Request $request)
    {
        $data = $this->repo->listKota($request);
        $res = [];
        foreach ($data as $key => $value) {
            if($value->kota_id != 0)
                $res[] = $value->toArray();
        }
        $this->output['data'] = $res;
        $this->output['total'] = count($res);
        return $this->done();
    }

    public function kecamatanList(Request $request)
    {
        $data = $this->repo->listKecamatan($request->kota_id);
        $res = [];
        foreach ($data as $key => $value) {
            if($value->kecamatan_id != 0)
                $res[] = $value->toArray();
        }
        $this->output['data'] = $res;
        $this->output['total'] = count($res);
        return $this->done();
    }

    public function desaList(Request $request)
    {
        $data = $this->repo->listDesa($request->kota_id, $request->kecamatan_id);
        $res = [];
        foreach ($data as $key => $value) {
            if($value->kelurahan_id != 0)
                $res[] = $value->toArray();
        }
        $this->output['data'] = $res;
        $this->output['total'] = count($res);
        return $this->done();
    }
}
