<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\AreaRepository;
use App\Repositories\BakulahanRepository;
use App\Repositories\DataLuasRepository;
use App\Repositories\HtpRepository;
use App\Repositories\OpsinRepository;
use App\Repositories\PenyuluhRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenyuluhController extends BaseController
{
    private $bakulahan, $area, $dataluas, $htp, $opsin;

    public function __construct(
        PenyuluhRepository $repo, 
        BakulahanRepository $bakulahan, 
        AreaRepository $area, 
        DataLuasRepository $dataluas, 
        HtpRepository $htp, 
        OpsinRepository $opsin)
    {
        $this->repo = $repo;
        $this->bakulahan = $bakulahan;
        $this->area = $area;
        $this->dataluas = $dataluas;
        $this->htp = $htp;
        $this->opsin = $opsin;
    }

    public function validasi(Request $request)
    {
        return $request->validate([
            'nama' => 'required',
            'alamat' => 'nullable',
            'bp3k' => 'nullable',
            'email' => 'nullable',
            'jabatan_fungsional' => 'nullable',
            'jk' => 'nullable',
            'jp' => 'nullable',
            'jml_kel_tani' => 'nullable|numeric',
            'komoditi_unggulan' => 'nullable',
            'nama_kelembagaan' => 'nullable',
            'nip' => 'nullable',
            'no_hp' => 'nullable',
            'pangkat' => 'nullable',
            'pendidikan' => 'nullable',
            'pertanian_wkpp' => 'nullable',
            'status_penyuluh' => 'nullable',
            'tanggal_evaluasi' => 'nullable',
            'tanggal_lahir' => 'nullable',
            'tempat_lahir' => 'nullable',
            'penyuluh_user_id' => 'nullable',
            'user_id' => 'required',
            'username' => 'required',
            'password' => 'nullable|min:6',
            'konfirmasi_password' => 'nullable|min:6',
            'provinsi_id' => 'nullable',
            'kota_id' => 'nullable',
            'kecamatan_id' => 'nullable',
            'kelurahan_id' => 'nullable',
            'tempat_tugas_kota_id' => 'nullable',
            'tempat_tugas_kecamatan_id' => 'nullable',
            'tempat_tugas_kelurahan_id' => 'nullable',
        ]);
    }

    public function index(Request $request)
    {
        $this->output['data'] = $this->repo->data($request);
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }
    
    public function wilayahkerja(Request $request)
    {
        $penyuluh = $this->repo->getOne($request->id);
        $res = [];
        $area = [];
        foreach ($penyuluh->wilayahkerja as $key => $value) {
            $res[$key] = $value->toArray();
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
            // get luas bakulahan
            $params = [
                'kota_id' => $value->kota_id,
                'kecamatan_id' => $value->kecamatan_id,
                'kelurahan_id' => $value->kelurahan_id
            ];
            if($request->has('tahun') && $request->tahun != 'Semua'){
                $params['tahun'] = $request->tahun;
            }
            $res[$key]['bakulahan'] = $this->bakulahan->sumLuas($params);
            $res[$key]['htp'] = round($this->htp->getAvg(['params'=>$params]), 2);
            $res[$key]['luas_tanam'] = $this->dataluas->sumLuasTanam(['params'=>$params]);
            $res[$key]['luas_panen'] = $this->dataluas->sumLuasPanen(['params'=>$params]);
            $res[$key]['tambah_tanam'] = $this->dataluas->sumTambahTanam(['params'=>$params]);
            $res[$key]['produksi'] = $this->dataluas->sumProduksi(['params'=>$params]);
            $res[$key]['produksi'] = $this->dataluas->sumProduksi(['params'=>$params]);
            $res[$key]['produktivitas'] = $res[$key]['luas_panen'] > 0 ? $res[$key]['produksi'] * 10 / $res[$key]['luas_panen'] : 0;
            $res[$key]['jumlah_alsin'] = $this->opsin->sum(['params'=>$params]);
        }
        $this->output['data'] = $res;
        $this->output['total'] = count($res);
        return $this->done();
    }

    public function file(Request $request)
    {
        $data = $this->repo->file($request, 'profile/' . $request->id . '/image');
        if (!$data) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }
    
    public function show($id)
    {
        $data = $this->output['data'] = $this->repo->getOne($id);
        if (!$data) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        $this->output['data']['image_url'] = url('img/avatars/avatar.png');
        $file = Storage::exists('/profile/' . $data->id. '/image/' . $data->image);
        if($file){
            $this->output['data']['image_url'] = Storage::url('/profile/' . $data->id. '/image/' . $data->image);
        }
        return $this->done();
    }

    public function getAll(Request $request)
    {
        $data = $this->repo->getAll($request);
        $this->output['data'] = $data;
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }
}
