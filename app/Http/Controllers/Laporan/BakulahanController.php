<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Jobs\Laporan\BakulahanJob;
use App\Repositories\AreaRepository;
use App\Repositories\BakulahanDetailRepository;
use App\Repositories\BakulahanRepository;
use App\Repositories\DownloadRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BakulahanController extends BaseController
{
    private $bakulahan, $bakulahan_detail, $download;
    public function __construct(
        AreaRepository $repo,
        BakulahanRepository $bakulahan,
        BakulahanDetailRepository $bakulahan_detail,
        DownloadRepository $download)
    {
        $this->repo = $repo;
        $this->bakulahan = $bakulahan;
        $this->bakulahan_detail = $bakulahan_detail;
        $this->download = $download;
    }

    protected function index(Request $request)
    {
        $data = $this->data($request);
        $this->output['data'] = $data;
        $this->output['total'] = count($data);
        return $this->done();
    }

    public function download(Request $request)
    {
        $download['data'] = [];
        $data = $this->data($request);
        $i = 1;
        foreach ($data as $key => $value) {
            $download['data'][$key][] = $i;
            $download['data'][$key][] = $value['nama'];
            $download['data'][$key][] = $value['bakulahan'] ?? 0;
            $download['data'][$key][] = $value['rencana_tanam'] ?? 0;
            $download['data'][$key][] = $value['realisasi_tanam'] ?? 0;
            $i++; 
        }
        
        $download['tahun'] = $request->tahun ?? Carbon::now()->format('Y');
        $download['jenis'] = 'Sawah dan Ladang';
        $download['field'] = ['#', 'Kota/Kabupaten', 'Luas Baku Lahan [Ha]', 'Rencana Tanam [Ha]', 'Realisasi Tanam [Ha]'];
        if($request->has('jenis')){
            $download['jenis'] = $request->jenis == 1 ? 'Sawah' : 'Ladang';
        }
        $download['area'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['field'][1] = 'Kecamatan';
            $download['area'] = $this->repo->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['field'][1] = 'Kelurahan/Desa';
                $download['area'] = 'Kecamatan ' . $this->repo->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->repo->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $createOne = $this->download->create([
            'keterangan' => 'Laporan Baku Lahan',
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new BakulahanJob($download, $createOne->id));
        return $this->done();
    }

    private function data(Request $request)
    {
        $filter['tahun'] = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $filter['tahun'] = $request->tahun;
        }
        
        if ($request->has('jenis')) {
            $filter['jenis'] = $request->jenis;
        }

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;

            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $data = $this->repo->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $res = [];
                foreach ($data as $key => $value) {
                    if($value->kelurahan_id != 0){
                        $filter['kelurahan_id'] = $value->kelurahan_id;
        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'bakulahan' => $this->bakulahan->sumLuas($filter),
                            'rencana_tanam' => $this->bakulahan_detail->sumRencanaTanam($filter),
                            'realisasi_tanam' => $this->bakulahan_detail->sumRealisasiTanam($filter)
                        ];
                    }
                }
            }else{
                $data = $this->repo->listKecamatan($filter['kota_id']);
                $res = [];
                foreach ($data as $key => $value) {
                    if($value->kecamatan_id != 0){
                        $filter['kecamatan_id'] = $value->kecamatan_id;
        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'bakulahan' => $this->bakulahan->sumLuas($filter),
                            'rencana_tanam' => $this->bakulahan_detail->sumRencanaTanam($filter),
                            'realisasi_tanam' => $this->bakulahan_detail->sumRealisasiTanam($filter)
                        ];
                    }
                }
            }
        }else{
            $data = $this->repo->listKota();
            $res = [];
            foreach ($data as $key => $value) {
                if($value->kota_id != 0){
                    $filter['kota_id'] = $value->kota_id;
    
                    $res[] = [
                        'nama' => ucwords(strtolower($value->name)),
                        'kota_id' => $value->kota_id,
                        'kecamatan_id' => $value->kecamatan_id,
                        'kelurahan_id' => $value->kelurahan_id,
                        'bakulahan' => $this->bakulahan->sumLuas($filter),
                        'rencana_tanam' => $this->bakulahan_detail->sumRencanaTanam($filter),
                        'realisasi_tanam' => $this->bakulahan_detail->sumRealisasiTanam($filter)
                    ];
                }
            }
        }

        return $res;
    }
}
