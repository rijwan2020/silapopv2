<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\AreaRepository;
use App\Repositories\KoordinatorWilayahRepository;
use Illuminate\Http\Request;

class KoordinatorWilayahController extends BaseController
{
    public function __construct(KoordinatorWilayahRepository $repo, AreaRepository $area)
    {
        $this->repo = $repo;
        $this->area = $area;
    }
    
    public function validasi(Request $request)
    {
        return $request->validate([
            'nama' => 'required',
            'penyuluh_id' => 'required',
            'user_id' => 'required',
            'kota_id' => 'required',
            'kecamatan_id' => 'nullable',
            'level' => 'required',
            'username' => 'required',
            'password' => 'nullable',
            'konfirmasi_password' => 'nullable',
        ]);
    }

    public function index(Request $request)
    {
        $data = $this->repo->list($request);
        foreach($data as $key => $value){
            $res[$key] = $value->toArray();
            $res[$key]['kota'] = $this->area->getNamaKota(32, $value->kota_id);
            $res[$key]['kecamatan'] = $this->area->getNamaKecamatan(32, $value->kota_id, $value->kecamatan_id);
        }
        $this->output['data'] = $res;
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }

    public function getByUser($user_id)
    {
        $data = $this->output['data'] = $this->repo->getOne(['user_id', $user_id]);
        if (!$data) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }
}
