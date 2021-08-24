<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\BaseController;
use App\Jobs\Laporan\HtpAreaDetailJob;
use App\Jobs\Laporan\HtpAreaJob;
use App\Jobs\Laporan\HtpDetailJob;
use App\Jobs\Laporan\HtpJob;
use App\Repositories\AreaRepository;
use App\Repositories\DownloadRepository;
use App\Repositories\HtpRepository;
use App\Repositories\KomoditasHtpRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HtpController extends BaseController
{
    private $htp, $download, $area;
    public function __construct(
        KomoditasHtpRepository $repo,
        HtpRepository $htp,
        AreaRepository $area,
        DownloadRepository $download)
    {
        $this->repo = $repo;
        $this->htp = $htp;
        $this->download = $download;
        $this->area = $area;
    }

    // HTP
    protected function index(Request $request)
    {
        $data = $this->data($request);
        $this->output['data'] = $data;
        $this->output['average'] = $this->average($request);
        $this->output['total'] = count($data);
        return $this->done();
    }

    protected function download(Request $request)
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
            $download['data'][$key][] = $value['rata_rata'];
            $download['data'][$key][] = $value['min'];
            $download['data'][$key][] = $value['max'];
            $i++; 
        }
        $rata_rata = $this->average($request);
        $i = $key + 1;
        $download['data'][$i] = ['Rata Rata', ''];
        $j = $key + 2;
        $download['data'][$j] = ['Min', ''];
        $k = $key + 3;
        $download['data'][$k] = ['Max', ''];
        foreach ($rata_rata as $key => $value) {
            $download['data'][$i][] = $value['rata_rata'];
            $download['data'][$j][] = $value['min'];
            $download['data'][$k][] = $value['max'];
        }
        $download['judul'] = 'Laporan Harga Tingkat Petani Tahun ' . $request->tahun;
        $download['kategori'] = 'Komoditi Gabah/Beras dan Palawija';
        if($request->has('params')){
            if (isset($request->params['kategori_id'])) {
                $download['kategori'] = $request->params['kategori_id'] == 1 ? 'Komoditi Gabah/Beras' : 'Komoditi Palawija';
            }
        }
        $download['field'][] = ['#', 'Komoditi', 'Bulan [Ha]', '', '', '', '', '', '', '', '', '', '', '', 'Rata Rata [Rp/Kg]', 'Min [Rp/Kg]', 'Max [Rp/Kg]'];
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
            '',
            '',
            '',
        ];
        $download['area'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['area'] = $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['area'] = 'Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
                if ($request->has('kelurahan_id')) {
                    $download['area'] = 'Desa ' . $this->area->getNamaDesa(32, $request->kota_id, $request->kecamatan_id, $request->kelurahan_id) . ', Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
                }
            }
        }
        $createOne = $this->download->create([
            'keterangan' => $download['judul'],
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new HtpJob($download, $createOne->id));
        return $this->done();
    }

    private function data(Request $request)
    {
        $filter['tahun'] = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $filter['tahun'] = $request->tahun;
        }
        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;
            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                if ($request->has('kelurahan_id')) {
                    $filter['kelurahan_id'] = $request->kelurahan_id;
                }
            }
        }

        $data = $this->repo->list($request);
        $res = [];
        foreach ($data as $key => $value) {
            $filter['komoditi_id'] = $value->id;
            $bulan = [];
            for ($i=1; $i <= 12; $i++) { 
                $filter['bulan'] = $i;
                $bulan[] = round($this->htp->getAvg(['params' => $filter]), 2);
            }

            $collect = collect($bulan)->filter(function ($value, $key) {
                return $value != 0;
            });

            $res[] = [
                'id' => $value->id,
                'nama' => ucwords(strtolower($value->nama)),
                'bulan' => $bulan,
                'rata_rata' => round($collect->average(), 2),
                'min' => round($collect->min(), 2),
                'max' => round($collect->max(), 2),
            ];
        }

        return $res;
    }

    private function average(Request $request)
    {
        $filter['tahun'] = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $filter['tahun'] = $request->tahun;
        }
        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;
            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                if ($request->has('kelurahan_id')) {
                    $filter['kelurahan_id'] = $request->kelurahan_id;
                }
            }
        }
        $data = $this->repo->list($request);
        $res = [];
        for ($i=1; $i <= 12; $i++) { 
            $filter['bulan'] = $i;
            $bulan = [];
            foreach ($data as $key => $value) {
                $filter['komoditi_id'] = $value->id;
                $bulan[$key] = $this->htp->getAvg(['params' => $filter]) ?? 0;
            }
            $collect = collect($bulan)->filter(function ($value, $key) {
                return $value != 0;
            });
            $res[$i]['rata_rata'] = round($collect->average(), 2);
            $res[$i]['min'] = round($collect->min(), 2);
            $res[$i]['max'] = round($collect->max(), 2);
        }
        
        return $res;
    }

    // HTP Detail
    protected function detail(Request $request)
    {
        $data = $this->detailData($request);
        $this->output['data'] = $data;
        $this->output['average'] = $this->detailAverage($request);
        return $this->done();
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
            $download['data'][$key][] = $value['rata_rata'];
            $download['data'][$key][] = $value['min'];
            $download['data'][$key][] = $value['max'];
            $i++; 
        }

        $average = $this->detailAverage($request);
        $i = $key + 1;
        $download['data'][$i] = ['Rata Rata',''];
        $j = $key + 2;
        $download['data'][$j] = ['Min',''];
        $k = $key + 3;
        $download['data'][$k] = ['Max',''];
        foreach ($average as $key => $value) {
            $download['data'][$i][] = $value['rata_rata'];
            $download['data'][$j][] = $value['min'];
            $download['data'][$k][] = $value['max'];
        }

        $download['judul'] = 'Laporan Harga Tingkat Petani ' . $bulan[$request->bulan] . ' ' .$request->tahun;

        $download['kategori'] = 'Komoditi Gabah/Beras dan Palawija';
        if($request->has('params')){
            if (isset($request->params['kategori_id'])) {
                $download['kategori'] = $request->params['kategori_id'] == 1 ? 'Komoditi Gabah/Beras' : 'Komoditi Palawija';
            }
        }

        $download['field'][0] = ['#', 'Komoditi', 'Tanggal [Rp/Kg]'];
        $download['field'][1] = ['', ''];
        for ($i=1; $i <= $download['jumlah_hari']; $i++) { 
            $download['field'][0][] = '';
            $download['field'][1][] = $i;
        }
        $download['field'][0][$i + 1] = 'Rata Rata [Rp/Kg]';
        $download['field'][0][$i + 2] = 'Min [Rp/Kg]';
        $download['field'][0][$i + 3] = 'Max [Rp/Kg]';


        $download['area'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['area'] = $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['area'] = 'Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
            if ($request->has('kelurahan_id')) {
                $download['area'] = 'Desa ' . $this->area->getNamaDesa(32, $request->kota_id, $request->kecamatan_id, $request->kelurahan_id) . ', Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $createOne = $this->download->create([
            'keterangan' =>  $download['judul'],
            'user_id' => $request->user_id ?? 1
        ]);

        dispatch(new HtpDetailJob($download, $createOne->id));
        return $this->done();
    }

    private function detailData(Request $request)
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

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;
            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                if ($request->has('kelurahan_id')) {
                    $filter['kelurahan_id'] = $request->kelurahan_id;
                }
            }
        }

        $data = $this->repo->list($request);
        $res = [];
        foreach ($data as $key => $value) {
            $filter['komoditi_id'] = $value->id;
            $hari = [];
            for ($i=1; $i <= $jml_hari; $i++) { 
                $filter['hari'] = $i;
                $hari[] = round($this->htp->getAvg(['params' => $filter]), 2);
            }

            $collect = collect($hari)->filter(function ($value, $key) {
                return $value != 0;
            });

            $res[] = [
                'id' => $value->id,
                'nama' => ucwords(strtolower($value->nama)),
                'hari' => $hari,
                'rata_rata' => round($collect->average(), 2),
                'min' => round($collect->min(), 2),
                'max' => round($collect->max(), 2),
            ];
        }
        $hasil['data'] = $res;
        $hasil['jml_hari'] = $jml_hari;
        return $hasil;
    }
    
    private function detailAverage(Request $request)
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

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;
            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                if ($request->has('kelurahan_id')) {
                    $filter['kelurahan_id'] = $request->kelurahan_id;
                }
            }
        }
        $data = $this->repo->list($request);
        $res = [];
        for ($i=1; $i <= $jml_hari; $i++) { 
            $filter['hari'] = $i;
            $hari = [];
            foreach ($data as $key => $value) {
                $filter['komoditi_id'] = $value->id;
                $hari[$key] = $this->htp->getAvg(['params' => $filter]) ?? 0;
            }
            $collect = collect($hari)->filter(function ($value, $key) {
                return $value != 0;
            });
            $res[$i]['rata_rata'] = round($collect->average(), 2);
            $res[$i]['min'] = round($collect->min(), 2);
            $res[$i]['max'] = round($collect->max(), 2);
        }
        
        return $res;
    }

    // HTP Area
    protected function area(Request $request)
    {
        $data = $this->dataArea($request);
        $this->output['data'] = $data;
        $this->output['average'] = $this->averageArea($request);
        $this->output['total'] = count($data);
        return $this->done();
    }

    protected function areaDownload(Request $request)
    {
        $download['data'] = [];
        $data = $this->dataArea($request);
        $i = 1;
        foreach ($data as $key => $value) {
            $download['data'][$key][] = $i;
            $download['data'][$key][] = $value['nama'];
            foreach ($value['bulan'] as $hsl => $hasil) {
                $download['data'][$key][] = $hasil;
            }
            $download['data'][$key][] = $value['rata_rata'];
            $download['data'][$key][] = $value['min'];
            $download['data'][$key][] = $value['max'];
            $i++; 
        }

        $average = $this->averageArea($request);
        $i = $key + 1;
        $download['data'][$i] = [
            'Rata Rata [Rp/Kg]',
            '',
        ];
        $j = $key + 2;
        $download['data'][$j] = [
            'Min [Rp/Kg]',
            '',
        ];
        $k = $key + 3;
        $download['data'][$k] = [
            'Max [Rp/Kg]',
            '',
        ];
        foreach ($average as $key => $value) {
            $download['data'][$i][] = $value['rata_rata'];
            $download['data'][$j][] = $value['min'];
            $download['data'][$k][] = $value['max'];
        }
        
        $download['judul'] = 'Laporan Harga Tingkat Petani Tahun '. $request->tahun;
        
        $download['field'][] = ['#', 'Kota/Kabupaten', 'Bulan [Ha]', '', '', '', '', '', '', '', '', '', '', '', 'Rata Rata [Rp/Kg]', 'Min [Rp/Kg]', 'Max [Rp/Kg]'];
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
            '',
            '',
            '',
        ];
        $download['komoditi'] = 'Semua Komoditi';
        if($request->has('komoditi_id')){
            $download['komoditi'] = 'Komoditi ' . $this->repo->getOne($request->komoditi_id)->nama;
        }
        $download['area'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['field'][0][1] = 'Kecamatan';
            $download['area'] = $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['field'][0][1] = 'Kelurahan/Desa';
                $download['area'] = 'Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }
        $createOne = $this->download->create([
            'keterangan' => $download['judul'],
            'user_id' => $request->user_id ?? 1
        ]);

        dispatch(new HtpAreaJob($download, $createOne->id));
        return $this->done();
    }

    private function dataArea(Request $request)
    {
        $filter['tahun'] = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $filter['tahun'] = $request->tahun;
        }
        
        if ($request->has('komoditi_id')) {
            $filter['komoditi_id'] = $request->komoditi_id;
        }

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;

            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $data = $this->area->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $res = [];
                foreach ($data as $key => $value) {
                    if($value->kelurahan_id != 0){
                        $filter['kelurahan_id'] = $value->kelurahan_id;
                        $bulan = [];
                        for ($i=1; $i <= 12; $i++) { 
                            $filter['bulan'] = $i;
                            $bulan[] = round($this->htp->getAvg(['params' => $filter]), 2);
                        }

                        $collect = collect($bulan)->filter(function ($val) {
                            return $val != 0;
                        });
        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'bulan' => $bulan,
                            'rata_rata' => round($collect->average(), 2),
                            'min' => round($collect->min(), 2),
                            'max' => round($collect->max(), 2),
                        ];
                    }
                }
            }else{
                $data = $this->area->listKecamatan($filter['kota_id']);
                $res = [];
                foreach ($data as $key => $value) {
                    if($value->kecamatan_id != 0){
                        $filter['kecamatan_id'] = $value->kecamatan_id;

                        $bulan = [];
                        for ($i=1; $i <= 12; $i++) { 
                            $filter['bulan'] = $i;
                            $bulan[] = round($this->htp->getAvg(['params' => $filter]), 2);
                        }

                        $collect = collect($bulan)->filter(function ($val) {
                            return $val != 0;
                        });
        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'bulan' => $bulan,
                            'rata_rata' => round($collect->average(), 2),
                            'min' => round($collect->min(), 2),
                            'max' => round($collect->max(), 2),
                        ];
                    }
                }
            }
        }else{
            $data = $this->area->listKota();
            $res = [];
            foreach ($data as $key => $value) {
                if($value->kota_id != 0){
                    $filter['kota_id'] = $value->kota_id;
                    $bulan = [];
                    for ($i=1; $i <= 12; $i++) { 
                        $filter['bulan'] = $i;
                        $bulan[] = round($this->htp->getAvg(['params' => $filter]), 2);
                    }

                    $collect = collect($bulan)->filter(function ($val) {
                        return $val != 0;
                    });
    
                    $res[] = [
                        'nama' => ucwords(strtolower($value->name)),
                        'kota_id' => $value->kota_id,
                        'kecamatan_id' => $value->kecamatan_id,
                        'kelurahan_id' => $value->kelurahan_id,
                        'bulan' => $bulan,
                        'rata_rata' => round($collect->average(), 2),
                        'min' => round($collect->min(), 2),
                        'max' => round($collect->max(), 2),
                    ];
                }
            }
        }

        return $res;
    }

    private function averageArea(Request $request)
    {
        $filter['tahun'] = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $filter['tahun'] = $request->tahun;
        }

        if ($request->has('komoditi_id')) {
            $filter['komoditi_id'] = $request->komoditi_id;
        }

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;

            if($request->has('kecamatan_id')){
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $data = $this->area->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $res = [];
                for ($i=1; $i <= 12; $i++) { 
                    $filter['bulan'] = $i;
                    $bulan = [];
                    foreach ($data as $key => $value) {
                        $filter['kelurahan_id'] = $value->kelurahan_id;
                        $bulan[$key] = $this->htp->getAvg(['params' => $filter]) ?? 0;
                    }
                    $collect = collect($bulan)->filter(function ($val) {
                        return $val != 0;
                    });
                    $res[$i]['rata_rata'] = round($collect->average(), 2);
                    $res[$i]['min'] = round($collect->min(), 2);
                    $res[$i]['max'] = round($collect->max(), 2);
                }
            }else{
                $data = $this->area->listKecamatan($filter['kota_id']);
                $res = [];
                for ($i=1; $i <= 12; $i++) { 
                    $filter['bulan'] = $i;
                    $bulan = [];
                    foreach ($data as $key => $value) {
                        $filter['kecamatan_id'] = $value->kecamatan_id;
                        $bulan[$key] = $this->htp->getAvg(['params' => $filter]) ?? 0;
                    }
                    $collect = collect($bulan)->filter(function ($val) {
                        return $val != 0;
                    });
                    $res[$i]['rata_rata'] = round($collect->average(), 2);
                    $res[$i]['min'] = round($collect->min(), 2);
                    $res[$i]['max'] = round($collect->max(), 2);
                }
            }
        }else{
            $data = $this->area->listKota();
            $res = [];
            for ($i=1; $i <= 12; $i++) { 
                $filter['bulan'] = $i;
                $bulan = [];
                foreach ($data as $key => $value) {
                    $filter['kota_id'] = $value->kota_id;
                    $bulan[$key] = $this->htp->getAvg(['params' => $filter]) ?? 0;
                }
                $collect = collect($bulan)->filter(function ($val) {
                    return $val != 0;
                });
                $res[$i]['rata_rata'] = round($collect->average(), 2);
                $res[$i]['min'] = round($collect->min(), 2);
                $res[$i]['max'] = round($collect->max(), 2);
            }
        }
        return $res;
    }

    // Htp Area Detail
    protected function areaDetail(Request $request)
    {
        $data = $this->dataAreaDetail($request);
        $this->output['data'] = $data;
        $this->output['average'] = $this->averageAreaDetail($request);
        $this->output['total'] = count($data);
        return $this->done();
    }

    private function dataAreaDetail(Request $request)
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
        
        if ($request->has('komoditi_id')) {
            $filter['komoditi_id'] = $request->komoditi_id;
        }

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;

            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $data = $this->area->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $res = [];
                foreach ($data as $key => $value) {
                    if($value->kelurahan_id != 0){
                        $filter['kelurahan_id'] = $value->kelurahan_id;
                        $hari = [];
                        for ($i=1; $i <= $jml_hari; $i++) { 
                            $filter['hari'] = $i;
                            $hari[] = round($this->htp->getAvg(['params' => $filter]), 2);
                        }
        
                        $collect = collect($hari)->filter(function ($val) {
                            return $val != 0;
                        });
            
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'hari' => $hari,
                            'rata_rata' => round($collect->average(), 2),
                            'min' => round($collect->min(), 2),
                            'max' => round($collect->max(), 2),
                        ];
                    }
                }
            }else{
                $data = $this->area->listKecamatan($filter['kota_id']);
                $res = [];
                foreach ($data as $key => $value) {
                    if($value->kecamatan_id != 0){
                        $filter['kecamatan_id'] = $value->kecamatan_id;

                        $hari = [];
                        for ($i=1; $i <= $jml_hari; $i++) { 
                            $filter['hari'] = $i;
                            $hari[] = round($this->htp->getAvg(['params' => $filter]), 2);
                        }

                        $collect = collect($hari)->filter(function ($val) {
                            return $val != 0;
                        });
            
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'hari' => $hari,
                            'rata_rata' => round($collect->average(), 2),
                            'min' => round($collect->min(), 2),
                            'max' => round($collect->max(), 2),
                        ];
                    }
                }
            }
        }else{
            $data = $this->area->listKota();
            $res = [];
            foreach ($data as $key => $value) {
                if($value->kota_id != 0){
                    $filter['kota_id'] = $value->kota_id;
                    $hari = [];
                    for ($i=1; $i <= $jml_hari; $i++) { 
                        $filter['hari'] = $i;
                        $hari[] = round($this->htp->getAvg(['params' => $filter]), 2);
                    }

                    $collect = collect($hari)->filter(function ($val) {
                        return $val != 0;
                    });
        
                    $res[] = [
                        'nama' => ucwords(strtolower($value->name)),
                        'kota_id' => $value->kota_id,
                        'kecamatan_id' => $value->kecamatan_id,
                        'kelurahan_id' => $value->kelurahan_id,
                        'hari' => $hari,
                        'rata_rata' => round($collect->average(), 2),
                        'min' => round($collect->min(), 2),
                        'max' => round($collect->max(), 2),
                    ];
                }
            }
        }
        $hasil['data'] = $res;
        $hasil['jml_hari'] = $jml_hari;
        return $hasil;
    }

    protected function averageAreaDetail(Request $request)
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
        
        if ($request->has('komoditi_id')) {
            $filter['komoditi_id'] = $request->komoditi_id;
        }

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;

            if($request->has('kecamatan_id')){
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $data = $this->area->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $res = [];
                for ($i=1; $i <= $jml_hari; $i++) { 
                    $filter['hari'] = $i;
                    $hari = [];
                    foreach ($data as $key => $value) {
                        $filter['kelurahan_id'] = $value->kelurahan_id;
                        $hari[] = $this->htp->getAvg(['params' => $filter]);
                    }
                    $collect = collect($hari)->filter(function ($value, $key) {
                        return $value != 0;
                    });

                    $res[$i]['rata_rata'] = round($collect->average(), 2);
                    $res[$i]['min'] = round($collect->min(), 2);
                    $res[$i]['max'] = round($collect->max(), 2);
                }
            }else{
                $data = $this->area->listKecamatan($filter['kota_id']);
                $res = [];
                for ($i=1; $i <= $jml_hari; $i++) { 
                    $filter['hari'] = $i;
                    $hari = [];
                    foreach ($data as $key => $value) {
                        $filter['kecamatan_id'] = $value->kecamatan_id;
                        $hari[] = $this->htp->getAvg(['params' => $filter]);
                    }
                    $collect = collect($hari)->filter(function ($value, $key) {
                        return $value != 0;
                    });

                    $res[$i]['rata_rata'] = round($collect->average(), 2);
                    $res[$i]['min'] = round($collect->min(), 2);
                    $res[$i]['max'] = round($collect->max(), 2);
                }
            }
        }else{
            $data = $this->area->listKota();
            $res = [];
            for ($i=1; $i <= $jml_hari; $i++) { 
                $filter['hari'] = $i;
                $hari = [];
                foreach ($data as $key => $value) {
                    $filter['kota_id'] = $value->kota_id;
                    $hari[] = $this->htp->getAvg(['params' => $filter]);
                }
                $collect = collect($hari)->filter(function ($value, $key) {
                    return $value != 0;
                });

                $res[$i]['rata_rata'] = round($collect->average(), 2);
                $res[$i]['min'] = round($collect->min(), 2);
                $res[$i]['max'] = round($collect->max(), 2);
            }
        }
        return $res;
    }

    protected function areaDetailDownload(Request $request)
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
        $data = $this->dataAreaDetail($request);
        $i = 1;
        foreach ($data['data'] as $key => $value) {
            $download['data'][$key][] = $i;
            $download['data'][$key][] = $value['nama'];
            foreach ($value['hari'] as $hsl => $hasil) {
                $download['data'][$key][] = $hasil;
            }
            $download['data'][$key][] = $value['rata_rata'];
            $download['data'][$key][] = $value['min'];
            $download['data'][$key][] = $value['max'];
            $i++; 
        }

        $average = $this->averageAreaDetail($request);
        $i = $key + 1;
        $download['data'][$i] = ['Rata Rata',''];
        $j = $key + 2;
        $download['data'][$j] = ['Min',''];
        $k = $key + 3;
        $download['data'][$k] = ['Max',''];
        foreach ($average as $key => $value) {
            $download['data'][$i][] = $value['rata_rata'];
            $download['data'][$j][] = $value['min'];
            $download['data'][$k][] = $value['max'];
        }

        $download['judul'] = 'Laporan Harga Tingkat Petani ' . $bulan[$request->bulan] . ' ' .$request->tahun;
        $download['komoditi'] = 'Semua Komoditi';
        if($request->has('komoditi_id')){
            $download['komoditi'] = 'Komoditi ' . $this->repo->getOne($request->komoditi_id)->nama;
        }

        $download['field'][0] = ['#', 'Kota/Kabupaten', 'Tanggal [Rp/Kg]'];
        $download['field'][1] = ['', ''];
        for ($i=1; $i <= $download['jumlah_hari']; $i++) { 
            $download['field'][0][] = '';
            $download['field'][1][] = $i;
        }
        $download['field'][0][$i + 1] = 'Rata Rata [Rp/Kg]';
        $download['field'][0][$i + 2] = 'Min [Rp/Kg]';
        $download['field'][0][$i + 3] = 'Max [Rp/Kg]';

        $download['area'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['field'][0][1] = 'Kecamatan';
            $download['area'] = $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['field'][0][1] = 'Kelurahan/Desa';
                $download['area'] = 'Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $createOne = $this->download->create([
            'keterangan' =>  $download['judul'],
            'user_id' => $request->user_id ?? 1
        ]);

        dispatch(new HtpAreaDetailJob($download, $createOne->id));
        return $this->done();
    }
}
