<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseController;
use App\Repositories\AreaRepository;
use App\Repositories\WilayahKerjaRepository;
use Illuminate\Http\Request;

class WilayahKerjaController extends BaseController
{
    private $area;

    public function __construct(WilayahKerjaRepository $repo, AreaRepository $area)
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
            'penyuluh_id' => 'required',
            'user_id' => 'required',
        ]);
    }

    public function index(Request $request)
    {
        $data = $this->repo->list($request);
        $area = [];
        $res = [];
        foreach($data as $key => $value){
            $res[$key] = $value;
            $res[$key]['name'] = $value->penyuluh->nama;
            $res[$key]['jenis_penyuluh'] = $value->jenis_penyuluh;
            $res[$key]['username'] = $value->penyuluh->user->username ?? '';
            // get nama kota
            if (isset($area[$value->kota_id]['name'])) {
                $res[$key]['kota'] = $area[$value->kota_id]['name'];
            }else{
                $res[$key]['kota'] = $area[$value->kota_id]['name'] = $this->area->getNamaKota(32, $value->kota_id);
            }
            // get nama kecamatan
            if (isset($area[$value->kota_id][$value->kecamatan_id]['name'])) {
                $res[$key]['kecamatan'] = $area[$value->kota_id][$value->kecamatan_id]['name'];
            }else{
                $res[$key]['kecamatan'] = $area[$value->kota_id][$value->kecamatan_id]['name'] = $this->area->getNamaKecamatan(32, $value->kota_id, $value->kecamatan_id);
            }
            // get nama desa
            if (isset($area[$value->kota_id][$value->kecamatan_id][$value->kelurahan_id]['name'])) {
                $res[$key]['desa'] = $area[$value->kota_id][$value->kecamatan_id][$value->kelurahan_id]['name'];
            }else{
                $res[$key]['desa'] = $area[$value->kota_id][$value->kecamatan_id][$value->kelurahan_id]['name'] = $this->area->getNamaDesa(32, $value->kota_id, $value->kecamatan_id, $value->kelurahan_id);
            }
        }
        $this->output['data'] = $res;
        $this->output['total'] = $this->repo->total($request);
        $this->output['curPage'] = $request->page ?? 1;
        return $this->done();
    }
}
