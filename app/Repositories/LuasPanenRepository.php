<?php
namespace App\Repositories;

use App\Jobs\Laporan\BaseDetailJob;
use App\Jobs\Laporan\BaseJob;
use App\Model\DataLuas;
use App\Model\Download;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LuasPanenRepository extends BaseRepository
{
    private $area, $komoditi;
    public function __construct(
        DataLuas $model, 
        AreaRepository $area,
        KomoditasRepository $komoditi
    ){
        $this->model = $model;
        $this->komoditi = $komoditi;
        $this->area = $area;
    }

    public function data(Request $request)
    {
        $filter['tahun'] = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $filter['tahun'] = $request->tahun;
        }
        
        if ($request->has('jenis')) {
            $filter['jenis'] = $request->jenis;
        }

        if ($request->has('komoditi_id')) {
            $filter['komoditi_id'] = $request->komoditi_id;
        }

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;

            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $area = $this->area->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $data = $this->sumByKelurahan($filter, 'luas_panen', "id_group_bulan");
                $res = [];

                foreach ($area as $value) {
                    if($value->kelurahan_id != 0){
                        $jumlah = 0;
                        $bulan = [];
                        for ($i=1; $i <= 12; $i++) { 
                            $cari = $value->kota_id . "-" . $value->kecamatan_id . "-" . $value->kelurahan_id . "-". $filter['tahun'] . "-" . $i;
                            $key = array_search($cari, array_column($data, "id_group_bulan"));
                            $luas = 0;
                            if ($key !== false) {
                                $luas = round($data[$key]['jumlah'], 2);
                            }
                            $jumlah += $luas;
                            $bulan[] = $luas;
                        }
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'bulan' => $bulan,
                            'jumlah' => round($jumlah, 2),
                        ];
                    }
                }

            }else{
                $area = $this->area->listKecamatan($filter['kota_id']);
                $data = $this->sumByKecamatan($filter, 'luas_panen', "id_group_bulan");
                $res = [];
                foreach ($area as $value) {
                    if($value->kecamatan_id != 0){
                        $jumlah = 0;
                        $bulan = [];
                        for ($i=1; $i <= 12; $i++) { 
                            $cari = $value->kota_id . "-" . $value->kecamatan_id . "-". $filter['tahun'] . "-" . $i;
                            $key = array_search($cari, array_column($data, "id_group_bulan"));
                            $luas = 0;
                            if ($key !== false) {
                                $luas = round($data[$key]['jumlah'], 2);
                            }
                            $jumlah += $luas;
                            $bulan[] = $luas;
                        }
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'bulan' => $bulan,
                            'jumlah' => round($jumlah, 2),
                        ];
                    }
                }
            }
        }else{
            $area = $this->area->listKota();
            $data = $this->sumByKota($filter, 'luas_panen', "id_group_bulan");
            $res = [];
            foreach ($area as $value) {
                if($value->kota_id != 0){
                    $jumlah = 0;
                    $bulan = [];
                    for ($i=1; $i <= 12; $i++) { 
                        $cari = $value->kota_id . "-". $filter['tahun'] . "-" . $i;
                        $key = array_search($cari, array_column($data, "id_group_bulan"));
                        $luas = 0;
                        if ($key !== false) {
                            $luas = round($data[$key]['jumlah'], 2);
                        }
                        $jumlah += $luas;
                        $bulan[] = $luas;
                    }
                    $res[] = [
                        'nama' => ucwords(strtolower($value->name)),
                        'kota_id' => $value->kota_id,
                        'kecamatan_id' => $value->kecamatan_id,
                        'kelurahan_id' => $value->kelurahan_id,
                        'bulan' => $bulan,
                        'jumlah' => round($jumlah, 2),
                    ];
                }
            }
        }
        return $res;
    }

    public function jumlah(Request $request)
    {
        $filter['tahun'] = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $filter['tahun'] = $request->tahun;
        }
        
        if ($request->has('jenis')) {
            $filter['jenis'] = $request->jenis;
        }
        
        if ($request->has('komoditi_id')) {
            $filter['komoditi_id'] = $request->komoditi_id;
        }

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;

            if($request->has('kecamatan_id')){
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $area = $this->area->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $data = $this->sumByKelurahan($filter, 'luas_panen', "id_group_bulan");
                $res = [];
                for ($i=1; $i <= 12; $i++) {
                    $jumlah = 0;
                    foreach ($area as $value) {
                        $cari = $value->kota_id . "-" . $value->kecamatan_id . "-" .  $value->kelurahan_id . "-" . $filter['tahun'] . "-" . $i;
                        $key = array_search($cari, array_column($data, "id_group_bulan"));
                        $luas = 0;
                        if ($key !== false) {
                            $luas = round($data[$key]['jumlah'], 2);
                        }
                        $jumlah += $luas;
                    }
                    $res[$i] = round($jumlah, 2);
                }
            }else{
                $area = $this->area->listKecamatan($filter['kota_id']);
                $res = [];
                
                $data = $this->sumByKecamatan($filter, 'luas_panen', "id_group_bulan");
                for ($i=1; $i <= 12; $i++) {
                    $jumlah = 0;
                    foreach ($area as $value) {
                        $cari = $value->kota_id . "-" . $value->kecamatan_id . "-" . $filter['tahun'] . "-" . $i;
                        $key = array_search($cari, array_column($data, "id_group_bulan"));
                        $luas = 0;
                        if ($key !== false) {
                            $luas = round($data[$key]['jumlah'], 2);
                        }
                        $jumlah += $luas;
                    }
                    $res[$i] = round($jumlah, 2);
                }
            }
        }else{
            $area = $this->area->listKota();
            $res = [];

            $data = $this->sumByKota($filter, 'luas_panen', "id_group_bulan");
            for ($i=1; $i <= 12; $i++) {
                $jumlah = 0;
                foreach ($area as $value) {
                    $cari = $value->kota_id . "-". $filter['tahun'] . "-" . $i;
                    $key = array_search($cari, array_column($data, "id_group_bulan"));
                    $luas = 0;
                    if ($key !== false) {
                        $luas = round($data[$key]['jumlah'], 2);
                    }
                    $jumlah += $luas;
                }
                $res[$i] = round($jumlah, 2);
            }
        }
        $hasil['data'] = $res;
        $hasil['total'] = round(collect($res)->sum(), 2);
        return $hasil;
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
        
        $download['judul_1'] = 'Laporan Luas Panen Tahun ' . $request->tahun;
        $download['judul_2'] = 'Semua Komoditi';
        if ($request->has('komoditi_id')) {
            $download['judul_2'] = 'Komoditi ' . $this->komoditi->getOne($request->komoditi_id)['nama'];
        }

        if($request->has('jenis')){
            $download['judul_2'] .= $request->jenis == 1 ? ' - Sawah' : ' - Ladang';
        }else{
            $download['judul_2'] .= ' - Sawah dan Ladang';
        }
        $download['judul_4'] = ['#', 'Kota/Kabupaten', 'Bulan [Ha]', '', '', '', '', '', '', '', '', '', '', '', 'Jumlah [Ha]'];
        $download['judul_5'] = [
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
        $download['judul_3'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['judul_4'][1] = 'Kecamatan';
            $download['judul_3'] = $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['judul_4'][1] = 'Kelurahan/Desa';
                $download['judul_3'] = 'Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $createOne = Download::create([
            'keterangan' => $download['judul_1'],
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new BaseJob($download, $createOne->id, 'luas_panen'));
        return true;
    }

    public function detailData(Request $request)
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
        
        if ($request->has('komoditi_id')) {
            $filter['komoditi_id'] = $request->komoditi_id;
        }


        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;
            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $data = $this->sumByKelurahan($filter, 'luas_panen');
                $area = $this->area->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $res = [];

                foreach ($area as $value) {
                    if($value->kelurahan_id != 0){
                        $jumlah = 0;
                        $hari = [];
                        for ($i=1; $i <= $jml_hari; $i++) {
                            $cari = $value->kota_id . "-". $value->kecamatan_id . "-". $value->kelurahan_id . "-". $filter['tahun'] . "-" . sprintf("%02d", $filter['bulan']) . "-" . sprintf("%02d", $i);
                            $key = array_search($cari, array_column($data, "id_group_tanggal"));
                            $luas = 0;
                            if ($key !== false) {
                                $luas = round($data[$key]['jumlah'], 2);
                            }
                            $jumlah += $luas;
                            $hari[] = $luas;
                        }
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'hari' => $hari,
                            'jumlah' => $jumlah,
                        ];
                    }
                }
            }else{
                $area = $this->area->listKecamatan($filter['kota_id']);
                $res = [];
                $data = $this->sumByKecamatan($filter, 'luas_panen');
                foreach ($area as $value) {
                    if($value->kecamatan_id != 0){
                        $jumlah = 0;
                        $hari = [];
                        for ($i=1; $i <= $jml_hari; $i++) {
                            $cari = $value->kota_id . "-". $value->kecamatan_id . "-". $filter['tahun'] . "-" . sprintf("%02d", $filter['bulan']) . "-" . sprintf("%02d", $i);
                            $key = array_search($cari, array_column($data, "id_group_tanggal"));
                            $luas = 0;
                            if ($key !== false) {
                                $luas = round($data[$key]['jumlah'], 2);
                            }
                            $jumlah += $luas;
                            $hari[] = $luas;
                        }
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'hari' => $hari,
                            'jumlah' => $jumlah,
                        ];
                    }
                }
            }
        }else{
            $area = $this->area->listKota();
            $res = [];
            $data = $this->sumByKota($filter, 'luas_panen');            
            foreach ($area as $value) {
                if($value->kota_id != 0){
                    $jumlah = 0;
                    $hari = [];
                    for ($i=1; $i <= $jml_hari; $i++) { 
                        $cari = $value->kota_id . "-". $filter['tahun'] . "-" . sprintf("%02d", $filter['bulan']) . "-" . sprintf("%02d", $i);
                        $key = array_search($cari, array_column($data, "id_group_tanggal"));
                        $luas = 0;
                        if ($key !== false) {
                            $luas = round($data[$key]['jumlah'], 2);
                        }
                        $jumlah += $luas;
                        $hari[] = $luas;
                    }
                    $res[] = [
                        'nama' => ucwords(strtolower($value->name)),
                        'kota_id' => $value->kota_id,
                        'kecamatan_id' => $value->kecamatan_id,
                        'kelurahan_id' => $value->kelurahan_id,
                        'hari' => $hari,
                        'jumlah' => $jumlah,
                    ];
                }
            }
        }
        $hasil['data'] = $res;
        $hasil['jml_hari'] = $jml_hari;
        return $hasil;
    }

    public function detailJumlah(Request $request)
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

        if ($request->has('komoditi_id')) {
            $filter['komoditi_id'] = $request->komoditi_id;
        }

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;

            if($request->has('kecamatan_id')){
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $area = $this->area->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $res = [];
                $data = $this->sumByKelurahan($filter, 'luas_panen');
                for ($i=1; $i <= $jml_hari; $i++) {
                    $jumlah = 0;
                    foreach ($area as $value) {
                        $cari = $value->kota_id . "-" . $value->kecamatan_id . "-" . $value->kelurahan_id . "-" . $filter['tahun'] . "-" . sprintf("%02d", $filter['bulan']) . "-" . sprintf("%02d", $i);
                        $key = array_search($cari, array_column($data, "id_group_tanggal"));
                        $luas = 0;
                        if ($key !== false) {
                            $luas = round($data[$key]['jumlah'], 2);
                        }
                        $jumlah += $luas;
                    }
                    $res[$i] = round($jumlah, 2);
                }
            }else{
                $area = $this->area->listKecamatan($filter['kota_id']);
                $res = [];

                $data = $this->sumByKecamatan($filter, 'luas_panen');
                for ($i=1; $i <= $jml_hari; $i++) {
                    $jumlah = 0;
                    foreach ($area as $value) {
                        $cari = $value->kota_id . "-" . $value->kecamatan_id . "-". $filter['tahun'] . "-" . sprintf("%02d", $filter['bulan']) . "-" . sprintf("%02d", $i);
                        $key = array_search($cari, array_column($data, "id_group_tanggal"));
                        $luas = 0;
                        if ($key !== false) {
                            $luas = round($data[$key]['jumlah'], 2);
                        }
                        $jumlah += $luas;
                    }
                    $res[$i] = round($jumlah, 2);
                }
            }
        }else{
            $area = $this->area->listKota();
            $res = [];

            $dataLuas = $this->sumByKota($filter, 'luas_panen');
            for ($i=1; $i <= $jml_hari; $i++) {
                $jumlah = 0;
                foreach ($area as $value) {
                    $cari = $value->kota_id . "-". $filter['tahun'] . "-" . sprintf("%02d", $filter['bulan']) . "-" . sprintf("%02d", $i);
                    $key = array_search($cari, array_column($dataLuas, "id_group_tanggal"));
                    $luas = 0;
                    if ($key !== false) {
                        $luas = round($dataLuas[$key]['jumlah'], 2);
                    }
                    $jumlah += $luas;
                }
                $res[$i] = round($jumlah, 2);
            }
        }
        $hasil['data'] = $res;
        $hasil['total'] = round(collect($res)->sum(), 2);
        return $hasil;
    }

    public function detailDownload(Request $request)
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

        
        $download['judul_1'] = 'Laporan Luas Panen ' . $bulan[$request->bulan] . ' ' .$request->tahun;

        $download['judul_2'] = 'Semua Komoditi';
        if ($request->has('komoditi_id')) {
            $download['judul_2'] = 'Komoditi ' . $this->komoditi->getOne($request->komoditi_id)['nama'];
        }

        if($request->has('jenis')){
            $download['judul_2'] .= $request->jenis == 1 ? ' - Sawah' : ' - Ladang';
        }else{
            $download['judul_2'] .= ' - Sawah dan Ladang';
        }
        
        $download['judul_4'] = ['#', 'Kota/Kabupaten', 'Tanggal [Ha]'];
        $download['judul_5'] = ['Jumlah [Ha]', ''];
        for ($i=1; $i <= $download['jumlah_hari']; $i++) { 
            $download['judul_4'][] = '';
            $download['judul_5'][] = $i;
        }
        $download['judul_4'][$i + 1] = 'Jumlah [Ha]';

        $download['judul_3'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['judul_4'][1] = 'Kecamatan';
            $download['judul_3'] = $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['judul_4'][1] = 'Kelurahan/Desa';
                $download['judul_3'] = 'Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $createOne = Download::create([
            'keterangan' => $download['judul_1'],
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new BaseDetailJob($download, $createOne->id, 'luas_panen'));
    }
}