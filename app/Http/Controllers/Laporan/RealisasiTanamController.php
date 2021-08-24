<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\BaseController;
use App\Jobs\Laporan\RealisasiTanamDetailJob;
use App\Jobs\Laporan\RealisasiTanamJob;
use App\Repositories\AreaRepository;
use App\Repositories\BakulahanDetailRepository;
use App\Repositories\DownloadRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RealisasiTanamController extends BaseController
{
    private $bakulahan_detail, $download;
    public function __construct(
        AreaRepository $repo,
        BakulahanDetailRepository $bakulahan_detail,
        DownloadRepository $download)
    {
        $this->repo = $repo;
        $this->bakulahan_detail = $bakulahan_detail;
        $this->download = $download;
    }

    // REALISASI TANAM TAHUNAN
    
    protected function index(Request $request)
    {
        $data = $this->data($request);
        $this->output['data'] = $data;
        $this->output['jumlah'] = $this->jumlah($request);
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
            foreach ($value['bulan'] as $hsl => $hasil) {
                $download['data'][$key][] = $hasil;
            }
            $download['data'][$key][] = $value['jumlah'];
            $i++; 
        }

        $jumlah = $this->jumlah($request);
        $i = $key + 1;
        $download['data'][$i] = [
            'Jumlah',
            '',
        ];
        foreach ($jumlah['data'] as $key => $value) {
            $download['data'][$i][] = $value;
        }
        $download['data'][$i][] = $jumlah['total'];
        
        $download['tahun'] = $request->tahun ?? Carbon::now()->format('Y');
        $download['jenis'] = 'Sawah dan Ladang';
        $download['field'][] = ['#', 'Kota/Kabupaten', 'Bulan [Ha]', '', '', '', '', '', '', '', '', '', '', '', 'Jumlah [Ha]'];
        $download['field'][] = [
            '',
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
            ''
        ];
        if($request->has('jenis')){
            $download['jenis'] = $request->jenis == 1 ? 'Sawah' : 'Ladang';
        }
        $download['area'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['field'][0][1] = 'Kecamatan';
            $download['area'] = $this->repo->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['field'][0][1] = 'Kelurahan/Desa';
                $download['area'] = 'Kecamatan ' . $this->repo->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->repo->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $createOne = $this->download->create([
            'keterangan' => 'Laporan Realisasi Tanam Tahun ' . $download['tahun'],
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new RealisasiTanamJob($download, $createOne->id));
        return $this->done();
    }

    protected function data(Request $request)
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
                        $bulan = [];
                        for ($i=1; $i <= 12; $i++) { 
                            $filter['bulan'] = $i;
                            $bulan[] = $this->bakulahan_detail->sumRealisasiTanam($filter);
                        }
        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'bulan' => $bulan,
                            'jumlah' => round(collect($bulan)->sum(), 2),
                        ];
                    }
                }
            }else{
                $data = $this->repo->listKecamatan($filter['kota_id']);
                $res = [];
                foreach ($data as $key => $value) {
                    if($value->kecamatan_id != 0){
                        $filter['kecamatan_id'] = $value->kecamatan_id;

                        $bulan = [];
                        for ($i=1; $i <= 12; $i++) { 
                            $filter['bulan'] = $i;
                            $bulan[] = $this->bakulahan_detail->sumRealisasiTanam($filter);
                        }
        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'bulan' => $bulan,
                            'jumlah' => round(collect($bulan)->sum(), 2),
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
                    $bulan = [];
                    for ($i=1; $i <= 12; $i++) { 
                        $filter['bulan'] = $i;
                        $bulan[] = $this->bakulahan_detail->sumRealisasiTanam($filter);
                    }
    
                    $res[] = [
                        'nama' => ucwords(strtolower($value->name)),
                        'kota_id' => $value->kota_id,
                        'kecamatan_id' => $value->kecamatan_id,
                        'kelurahan_id' => $value->kelurahan_id,
                        'bulan' => $bulan,
                        'jumlah' => round(collect($bulan)->sum(), 2),
                    ];
                }
            }
        }

        return $res;
    }

    protected function jumlah(Request $request)
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

            if($request->has('kecamatan_id')){
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $data = $this->repo->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $res = [];
                for ($i=1; $i <= 12; $i++) { 
                    $filter['bulan'] = $i;
                    $res[$i] = 0;
                    foreach ($data as $key => $value) {
                        $filter['kelurahan_id'] = $value->kelurahan_id;
                        $res[$i] += $this->bakulahan_detail->sumRealisasiTanam($filter);
                    }
                }
            }else{
                $data = $this->repo->listKecamatan($filter['kota_id']);
                $res = [];
                for ($i=1; $i <= 12; $i++) { 
                    $filter['bulan'] = $i;
                    $res[$i] = 0;
                    foreach ($data as $key => $value) {
                        $filter['kecamatan_id'] = $value->kecamatan_id;
                        $res[$i] += $this->bakulahan_detail->sumRealisasiTanam($filter);
                    }
                }
            }
        }else{
            $data = $this->repo->listKota();
            $res = [];
            for ($i=1; $i <= 12; $i++) { 
                $filter['bulan'] = $i;
                $res[$i] = 0;
                foreach ($data as $key => $value) {
                    $filter['kota_id'] = $value->kota_id;
                    $res[$i] += $this->bakulahan_detail->sumRealisasiTanam($filter);
                }
            }
        }
        $hasil['data'] = $res;
        $hasil['total'] = collect($res)->sum();
        return $hasil;
    }

    // REALISASI TANAM BULANAN

    public function detail(Request $request)
    {
        $data = $this->detailData($request);
        $this->output['data'] = $data;
        $this->output['jumlah'] = $this->detailJumlah($request);
        $this->output['total'] = count($data['data']);
        return $this->done();
        
    }

    protected function detailData(Request $request)
    {
        $filter['tahun'] = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $filter['tahun'] = $request->tahun;
        }

        $filter['bulan'] = Carbon::now()->format('m');
        if ($request->has('bulan')) {
            $filter['bulan'] = $request->bulan;
        }

        $jml_hari = cal_days_in_month(CAL_GREGORIAN, $filter['bulan'], $filter['tahun']);
        
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
                        $hari = [];
                        for ($i=1; $i <= $jml_hari; $i++) { 
                            $filter['hari'] = $i;
                            $hari[] = $this->bakulahan_detail->sumRealisasiTanam($filter);
                        }
        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'hari' => $hari,
                            'jumlah' => round(collect($hari)->sum(), 2),
                        ];
                    }
                }
            }else{
                $data = $this->repo->listKecamatan($filter['kota_id']);
                $res = [];
                foreach ($data as $key => $value) {
                    if($value->kecamatan_id != 0){
                        $filter['kecamatan_id'] = $value->kecamatan_id;

                        $hari = [];
                        for ($i=1; $i <= $jml_hari; $i++) { 
                            $filter['hari'] = $i;
                            $hari[] = $this->bakulahan_detail->sumRealisasiTanam($filter);
                        }
        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'hari' => $hari,
                            'jumlah' => round(collect($hari)->sum(), 2),
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
                    $hari = [];
                    for ($i=1; $i <= $jml_hari; $i++) { 
                        $filter['hari'] = $i;
                        $hari[] = $this->bakulahan_detail->sumRealisasiTanam($filter);
                    }
    
                    $res[] = [
                        'nama' => ucwords(strtolower($value->name)),
                        'kota_id' => $value->kota_id,
                        'kecamatan_id' => $value->kecamatan_id,
                        'kelurahan_id' => $value->kelurahan_id,
                        'hari' => $hari,
                        'jumlah' => round(collect($hari)->sum(), 2),
                    ];
                }
            }
        }
        $hasil['data'] = $res;
        $hasil['jml_hari'] = $jml_hari;
        return $hasil;
    }

    protected function detailJumlah(Request $request)
    {
        $filter['tahun'] = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $filter['tahun'] = $request->tahun;
        }

        $filter['bulan'] = Carbon::now()->format('m');
        if ($request->has('bulan')) {
            $filter['bulan'] = $request->bulan;
        }

        $jml_hari = cal_days_in_month(CAL_GREGORIAN, $filter['bulan'], $filter['tahun']);
        
        if ($request->has('jenis')) {
            $filter['jenis'] = $request->jenis;
        }

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;

            if($request->has('kecamatan_id')){
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $data = $this->repo->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $res = [];
                for ($i=1; $i <= $jml_hari; $i++) { 
                    $filter['hari'] = $i;
                    $res[$i] = 0;
                    foreach ($data as $key => $value) {
                        $filter['kelurahan_id'] = $value->kelurahan_id;
                        $res[$i] += $this->bakulahan_detail->sumRealisasiTanam($filter);
                    }
                }
            }else{
                $data = $this->repo->listKecamatan($filter['kota_id']);
                $res = [];
                for ($i=1; $i <= $jml_hari; $i++) { 
                    $filter['hari'] = $i;
                    $res[$i] = 0;
                    foreach ($data as $key => $value) {
                        $filter['kecamatan_id'] = $value->kecamatan_id;
                        $res[$i] += $this->bakulahan_detail->sumRealisasiTanam($filter);
                    }
                }
            }
        }else{
            $data = $this->repo->listKota();
            $res = [];
            for ($i=1; $i <= $jml_hari; $i++) { 
                $filter['hari'] = $i;
                $res[$i] = 0;
                foreach ($data as $key => $value) {
                    $filter['kota_id'] = $value->kota_id;
                    $res[$i] += $this->bakulahan_detail->sumRealisasiTanam($filter);
                }
            }
        }
        $hasil['data'] = $res;
        $hasil['total'] = collect($res)->sum();
        return $hasil;
    }

    protected function detailDownload(Request $request)
    {
        $download['jumlah_hari'] = cal_days_in_month(CAL_GREGORIAN, $request->bulan, $request->tahun);
        $bulan = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $download['data'] = [];
        $data = $this->detailData($request);
        $i = 1;
        foreach ($data['data'] as $key => $value) {
            $download['data'][$key][] = $i;
            $download['data'][$key][] = $value['nama'];
            foreach ($value['hari'] as $hsl => $hasil) {
                $download['data'][$key][] = $hasil;
            }
            $download['data'][$key][] = $value['jumlah'];
            $i++; 
        }

        $jumlah = $this->detailJumlah($request);
        $i = $key + 1;
        $download['data'][$i] = [
            'Jumlah',
            '',
        ];
        foreach ($jumlah['data'] as $key => $value) {
            $download['data'][$i][] = $value;
        }
        $download['data'][$i][] = $jumlah['total'];

        $download['judul'] = $bulan[$request->bulan] . ' ' .$request->tahun;
        $download['jenis'] = 'Sawah dan Ladang';
        $download['field'][0] = ['#', 'Kota/Kabupaten', 'Tanggal [Ha]'];
        $download['field'][1] = ['Jumlah [Ha]', ''];
        for ($i=1; $i <= $download['jumlah_hari']; $i++) { 
            $download['field'][0][] = '';
            $download['field'][1][] = $i;
        }
        $download['field'][0][$i + 1] = 'Jumlah [Ha]';
        $download['field'][1][] = '';

        if($request->has('jenis')){
            $download['jenis'] = $request->jenis == 1 ? 'Sawah' : 'Ladang';
        }
        $download['area'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['field'][0][1] = 'Kecamatan';
            $download['area'] = $this->repo->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['field'][0][1] = 'Kelurahan/Desa';
                $download['area'] = 'Kecamatan ' . $this->repo->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->repo->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $createOne = $this->download->create([
            'keterangan' => 'Laporan Realisasi Tanam ' . $download['judul'],
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new RealisasiTanamDetailJob($download, $createOne->id));
        return $this->done();
    }
}
