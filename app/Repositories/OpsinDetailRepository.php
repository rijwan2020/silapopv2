<?php
namespace App\Repositories;

use App\Jobs\Data\BaseJob;
use App\Jobs\Laporan\RealisasiOpsinDetailJob;
use App\Jobs\Laporan\RealisasiOpsinJob;
use App\Model\Download;
use App\Model\OpsinDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OpsinDetailRepository extends BaseRepository
{
    private $user, $area, $jenis;
    public function __construct(
        OpsinDetail $model, 
        AreaRepository $area,
        JenisAlsinRepository $jenis,
        UserRepository $user)
    {
        $this->model = $model;
        $this->user = $user;
        $this->area = $area;
        $this->jenis = $jenis;
        $this->relation = ['user', 'penyuluh', 'jenis'];
    }

    public function sumLttLtp($request = [])
    {
        if (isset($request['params'])) {
            $this->params = json_decode($request['params']);
        }
        $query = $this->withParameter($this->model);
        return $query->sum('ltt_ltp');
    }

    public function sumAlsin($request = [])
    {
        if (isset($request['params'])) {
            $this->params = json_decode($request['params']);
        }
        $query = $this->withParameter($this->model);
        return $query->sum('alsin');
    }

    public function create($request)
    {
        $this->params = [
            'kota_id' => $request['kota_id'],
            'kecamatan_id' => $request['kecamatan_id'],
            'kelurahan_id' => $request['kelurahan_id'],
            'tanggal_input' => $request['tanggal_input'],
            'jenis_id' => $request['jenis_id'],
        ];
        if($request['tanggal_input'] > date('Y-m-d')){
            $this->error = 'Tidak boleh melebihi tanggal sekarang';
            return false;
        }
        $query = $this->withParameter($this->model)->first();
        if($query){
            $this->error = 'Data sudah ada';
            return false;
        }
        
        $user = $this->user->getOne($request['insert_by']);
        $request['penyuluh_id'] = $user->penyuluh->id ?? 0;

        $tanggal_input = Carbon::create($request['tanggal_input']);
        $request['hari'] = $tanggal_input->format('d');
        $request['bulan'] = $tanggal_input->format('m');
        $request['tahun'] = $tanggal_input->format('Y');
        
        return $this->save($request);
    }

    public function edit($id, $data = [])
    {
        $this->params = [
            'kota_id' => $data['kota_id'],
            'kecamatan_id' => $data['kecamatan_id'],
            'kelurahan_id' => $data['kelurahan_id'],
            'tanggal_input' => $data['tanggal_input'],
            'jenis_id' => $data['jenis_id'],
        ];
        if($data['tanggal_input'] > date('Y-m-d')){
            $this->error = 'Tidak boleh melebihi tanggal sekarang';
            return false;
        }
        $query = $this->withParameter($this->model)->first();
        if($query && $query->id != $id){
            $this->error = 'Data sudah ada';
            return false;
        }
        return $this->update($id, $data);
    }

    public function delete($id)
    {
        $model = $this->getOne($id);
        if (!$model) {
            $this->error = 'Data tidak ditemukan.';
            return false;
        }
        // cek apakah sudah ada verifikasi atau belum
        if($model->vrf_kota != 0 || $model->vrf_kec != 0){
            $this->error = 'Data tidak bisa dihapus karena sudah diverifikasi.';
            return false;
        }
        if (!empty($model->file)) {
            $this->deleteFile($model->file, 'opsin/detail');
            $this->deleteFile($model->file_produksi, 'v1/att_opsin/detail');
        }
        return $model->delete();
    }

    public function laporan(Request $request)
    {
        $filter['tahun'] = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $filter['tahun'] = $request->tahun;
        }
        
        if ($request->has('jenis_id')) {
            $filter['jenis_id'] = $request->jenis_id;
        }

        $this->params = $filter;

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;
            $this->params = $filter;

            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $area = $this->area->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $this->params = $filter;
                $res = [];
                $data = $this->dataLaporan('grup_kelurahan');

                foreach ($area as $key => $value) {
                    if($value->kelurahan_id != 0){
                        $bulan = [];
                        $jml_ltt_ltp = $jml_alsin = 0;
                        for ($i=1; $i <= 12; $i++) { 
                            $keyData = array_search(($filter['tahun'] . '-' . $i . '-' .$value->kota_id . '-' . $value->kecamatan_id . '-' . $value->kelurahan_id), array_column($data, "grup_kelurahan"));
                            $alsin = $ltt_ltp = 0;
                            if ($keyData !== false) {
                                $alsin = $data[$keyData]['total_alsin'];
                                $ltt_ltp = $data[$keyData]['total_ltt_ltp'];
                            }
                            $bulan[] = $ltt_ltp;
                            $bulan[] = $alsin;

                            $jml_ltt_ltp += $ltt_ltp;
                            $jml_alsin += $alsin;
                        }

        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'bulan' => $bulan,
                            'ltt_ltp' => round($jml_ltt_ltp, 2),
                            'alsin' => round($jml_alsin, 2),
                        ];
                    }
                }
            }else{
                $area = $this->area->listKecamatan($filter['kota_id']);
                $filter['kecamatan'] = true;
                $data = $this->dataLaporan('grup_kecamatan');
                $res = [];
                foreach ($area as $key => $value) {
                    if($value->kecamatan_id != 0){
                        $bulan = [];
                        $jml_ltt_ltp = $jml_alsin = 0;
                        for ($i=1; $i <= 12; $i++) { 
                            $keyData = array_search(($filter['tahun'] . '-' . $i . '-' .$value->kota_id . '-' . $value->kecamatan_id), array_column($data, "grup_kecamatan"));
                            $alsin = $ltt_ltp = 0;
                            if ($keyData !== false) {
                                $alsin = $data[$keyData]['total_alsin'];
                                $ltt_ltp = $data[$keyData]['total_ltt_ltp'];
                            }
                            $bulan[] = $ltt_ltp;
                            $bulan[] = $alsin;

                            $jml_ltt_ltp += $ltt_ltp;
                            $jml_alsin += $alsin;
                        }

        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'bulan' => $bulan,
                            'ltt_ltp' => round($jml_ltt_ltp, 2),
                            'alsin' => round($jml_alsin, 2),
                        ];
                    }
                }
            }
        }else{
            $area = $this->area->listKota();
            $filter['kota'] = true;
            $data = $this->dataLaporan('grup_kota');
            $res = [];
            foreach ($area as $key => $value) {
                if($value->kota_id != 0){

                    $bulan = [];
                    $jml_ltt_ltp = $jml_alsin = 0;
                    for ($i=1; $i <= 12; $i++) { 
                        $keyData = array_search(($filter['tahun'] . '-' . $i . '-' .$value->kota_id), array_column($data, "grup_kota"));
                        $alsin = $ltt_ltp = 0;
                        if ($keyData !== false) {
                            $alsin = $data[$keyData]['total_alsin'];
                            $ltt_ltp = $data[$keyData]['total_ltt_ltp'];
                        }
                        $bulan[] = $ltt_ltp;
                        $bulan[] = $alsin;

                        $jml_ltt_ltp += $ltt_ltp;
                        $jml_alsin += $alsin;
                    }

    
                    $res[] = [
                        'nama' => ucwords(strtolower($value->name)),
                        'kota_id' => $value->kota_id,
                        'kecamatan_id' => $value->kecamatan_id,
                        'kelurahan_id' => $value->kelurahan_id,
                        'bulan' => $bulan,
                        'ltt_ltp' => round($jml_ltt_ltp, 2),
                        'alsin' => round($jml_alsin, 2),
                    ];
                }
            }
        }

        $data = $this->dataLaporan('grup_tahun');
        $jumlah = [];
        $total_ltt_ltp = $total_alsin = 0;

        for ($i=1; $i <= 12; $i++) { 
            $key = array_search(($filter['tahun'] . '-' . $i), array_column($data, "grup_tahun"));
            $ltt_ltp = $alsin = 0;
            if ($key !== false) {
                $ltt_ltp = $data[$key]['total_ltt_ltp'];
                $alsin = $data[$key]['total_alsin'];
            }
            $total_ltt_ltp += $ltt_ltp;
            $total_alsin += $alsin;
            $jumlah[] = $ltt_ltp;
            $jumlah[] = $alsin;
        }
        $jumlah[] = $total_ltt_ltp;
        $jumlah[] = $total_alsin;
        
        $hasil['data'] = $res;
        $hasil['jumlah'] = $jumlah;
        
        return $hasil;
    }

    public function laporanDownload(Request $request)
    {
        $data = $this->laporan($request);
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

        $download['judul_1'] = 'Laporan Realisasi Alsin Tahun ' . $request->tahun;
        $download['judul_2'] = 'Semua Jenis Alsin';
        if ($request->has('jenis_id')) {
            $download['judul_2'] = $this->jenis->getOne($request->jenis_id)['nama'];
        }
        $download['judul_3'] = "Provinsi Jawa Barat";
        $download['judul_4'] = ['#', 'Kota/Kabupaten'];
        $download['judul_5'] = ['', ''];
        $download['judul_6'] = ['', ''];
        for ($i=1; $i <= 12; $i++) { 
            $download['judul_4'][] = '';
            $download['judul_4'][] = '';
            $download['judul_5'][] = $bulan[$i];
            $download['judul_5'][] = '';
            $download['judul_6'][] = 'LTT/LTP';
            $download['judul_6'][] = 'Alsin';
        }
        $download['judul_4'][] = 'Jumlah';
        $download['judul_4'][2] = 'Bulan';
        $download['judul_6'][] = 'LTT/LTP';
        $download['judul_6'][] = 'Alsin';

        if($request->has('kota_id')){
            $download['judul_4'][1] = 'Kecamatan';
            $download['judul_3'] = $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['judul_4'][1] = 'Kelurahan/Desa';
                $download['judul_3'] = 'Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $download['data'] = [];
        $no = 1;
        foreach ($data['data'] as $key => $value) {
            $download['data'][$key] = [
                $no,
                $value['nama']
            ];
            foreach ($value['bulan'] as $hsl => $hasil) {
                $download['data'][$key][] = $hasil;
            }
            $download['data'][$key][] = $value['ltt_ltp'];
            $download['data'][$key][] = $value['alsin'];
            $no++;
        }
        $i = $key + 1;
        $download['data'][$i] = ['Jumlah', ''];
        foreach ($data['jumlah'] as $key => $value) {
            $download['data'][$i][] = $value;
        }
        $createOne = Download::create([
            'keterangan' => $download['judul_1'],
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new RealisasiOpsinJob($download, $createOne->id));
        return true;
    }

    private function dataLaporan($field = 'grup_kota')
    {
        $model = $this->withParameter($this->model);
        $raw = DB::raw("
            SUM(ltt_ltp) AS 'total_ltt_ltp',
            SUM(alsin) AS 'total_alsin',
            CONCAT(tahun,\"-\",bulan,\"-\",kota_id) AS 'grup_kota',
            CONCAT(tahun,\"-\",bulan,\"-\",kota_id,\"-\",kecamatan_id) AS 'grup_kecamatan',
            CONCAT(tahun,\"-\",bulan,\"-\",kota_id,\"-\",kecamatan_id,\"-\",kelurahan_id) AS 'grup_kelurahan',
            CONCAT(tahun,\"-\",bulan,\"-\",hari,\"-\",kota_id) AS 'grup_kota_detail',
            CONCAT(tahun,\"-\",bulan,\"-\",hari,\"-\",kota_id,\"-\",kecamatan_id) AS 'grup_kecamatan_detail',
            CONCAT(tahun,\"-\",bulan,\"-\",hari,\"-\",kota_id,\"-\",kecamatan_id,\"-\",kelurahan_id) AS 'grup_kelurahan_detail',
            CONCAT(tahun,\"-\",bulan) AS 'grup_tahun',
            CONCAT(tahun,\"-\",bulan,\"-\",hari) AS 'grup_bulan'
        ");
        $detail = $model->select($raw)->groupBy($field)->get();
        return $detail->toArray();
    }

    public function laporanDetail(Request $request)
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
        
        if ($request->has('jenis_id')) {
            $filter['jenis_id'] = $request->jenis_id;
        }

        $this->params = $filter;

        if ($request->has('kota_id')) {
            $filter['kota_id'] = $request->kota_id;
            $this->params = $filter;

            if ($request->has('kecamatan_id')) {
                $filter['kecamatan_id'] = $request->kecamatan_id;
                $area = $this->area->listDesa($filter['kota_id'], $filter['kecamatan_id']);
                $this->params = $filter;
                $res = [];
                $data = $this->dataLaporan('grup_kelurahan_detail');

                foreach ($area as $key => $value) {
                    if($value->kelurahan_id != 0){
                        $hari = [];
                        $jml_ltt_ltp = $jml_alsin = 0;
                        for ($i=1; $i <= $jml_hari; $i++) { 
                            $keyData = array_search(($filter['tahun'] . '-' . $filter['bulan'] . '-' . $i . '-' .$value->kota_id . '-' . $value->kecamatan_id . '-' . $value->kelurahan_id), array_column($data, "grup_kelurahan_detail"));
                            $alsin = $ltt_ltp = 0;
                            if ($keyData !== false) {
                                $alsin = $data[$keyData]['total_alsin'];
                                $ltt_ltp = $data[$keyData]['total_ltt_ltp'];
                            }
                            $hari[] = $ltt_ltp;
                            $hari[] = $alsin;

                            $jml_ltt_ltp += $ltt_ltp;
                            $jml_alsin += $alsin;
                        }

        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'hari' => $hari,
                            'ltt_ltp' => round($jml_ltt_ltp, 2),
                            'alsin' => round($jml_alsin, 2),
                        ];
                    }
                }
            }else{
                $area = $this->area->listKecamatan($filter['kota_id']);
                $filter['kecamatan'] = true;
                $data = $this->dataLaporan('grup_kecamatan_detail');
                $res = [];
                foreach ($area as $key => $value) {
                    if($value->kecamatan_id != 0){
                        $hari = [];
                        $jml_ltt_ltp = $jml_alsin = 0;
                        for ($i=1; $i <= $jml_hari; $i++) { 
                            $keyData = array_search(($filter['tahun'] . '-' . $filter['bulan'] . '-' . $i . '-' .$value->kota_id . '-' . $value->kecamatan_id), array_column($data, "grup_kecamatan_detail"));
                            $alsin = $ltt_ltp = 0;
                            if ($keyData !== false) {
                                $alsin = $data[$keyData]['total_alsin'];
                                $ltt_ltp = $data[$keyData]['total_ltt_ltp'];
                            }
                            $hari[] = $ltt_ltp;
                            $hari[] = $alsin;

                            $jml_ltt_ltp += $ltt_ltp;
                            $jml_alsin += $alsin;
                        }

        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'hari' => $hari,
                            'ltt_ltp' => round($jml_ltt_ltp, 2),
                            'alsin' => round($jml_alsin, 2),
                        ];
                    }
                }
            }
        }else{
            $area = $this->area->listKota();
            $filter['kota'] = true;
            $data = $this->dataLaporan('grup_kota_detail');
            $res = [];
            foreach ($area as $key => $value) {
                if($value->kota_id != 0){

                    $hari = [];
                    $jml_ltt_ltp = $jml_alsin = 0;
                    for ($i=1; $i <= $jml_hari; $i++) { 
                        $keyData = array_search(($filter['tahun'] . '-' . $filter['bulan'] . '-' . $i . '-' .$value->kota_id), array_column($data, "grup_kota_detail"));
                        $alsin = $ltt_ltp = 0;
                        if ($keyData !== false) {
                            $alsin = $data[$keyData]['total_alsin'];
                            $ltt_ltp = $data[$keyData]['total_ltt_ltp'];
                        }
                        $hari[] = $ltt_ltp;
                        $hari[] = $alsin;

                        $jml_ltt_ltp += $ltt_ltp;
                        $jml_alsin += $alsin;
                    }

    
                    $res[] = [
                        'nama' => ucwords(strtolower($value->name)),
                        'kota_id' => $value->kota_id,
                        'kecamatan_id' => $value->kecamatan_id,
                        'kelurahan_id' => $value->kelurahan_id,
                        'hari' => $hari,
                        'ltt_ltp' => round($jml_ltt_ltp, 2),
                        'alsin' => round($jml_alsin, 2),
                    ];
                }
            }
        }

        $hasil['data'] = [
            'data' => $res,
            'jml_hari' => $jml_hari
        ];

        $data = $this->dataLaporan('grup_bulan');
        $jumlah = [];
        $total_ltt_ltp = $total_alsin = 0;

        for ($i=1; $i <= $jml_hari; $i++) { 
            $key = array_search(($filter['tahun'] . '-' . $filter['bulan'] . '-' . $i), array_column($data, "grup_bulan"));
            $ltt_ltp = $alsin = 0;
            if ($key !== false) {
                $ltt_ltp = $data[$key]['total_ltt_ltp'];
                $alsin = $data[$key]['total_alsin'];
            }
            $total_ltt_ltp += $ltt_ltp;
            $total_alsin += $alsin;
            $jumlah[] = $ltt_ltp;
            $jumlah[] = $alsin;
        }
        $jumlah[] = $total_ltt_ltp;
        $jumlah[] = $total_alsin;

        $hasil['jumlah'] = $jumlah;

        return $hasil;
    }

    public function laporanDetailDownload(Request $request)
    {
        $data = $this->laporanDetail($request);
        $download['jumlah_hari'] = $data['data']['jml_hari'];
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
        $download['judul_1'] = "Laporan Realisasi Alsin " . $bulan[$request->bulan] . ' ' . $request->tahun;
        $download['judul_2'] = 'Semua Jenis Alsin';
        if ($request->has('jenis_id')) {
            $download['judul_2'] = $this->jenis->getOne($request->jenis_id)['nama'];
        }
        $download['judul_3'] = "Provinsi Jawa Barat";
        $download['judul_4'] = ['#', 'Kota/Kabupaten'];
        $download['judul_5'] = ['', ''];
        $download['judul_6'] = ['', ''];
        for ($i=1; $i <= $download['jumlah_hari']; $i++) { 
            $download['judul_4'][] = '';
            $download['judul_4'][] = '';
            $download['judul_5'][] = $i;
            $download['judul_5'][] = '';
            $download['judul_6'][] = 'LTT/LTP';
            $download['judul_6'][] = 'Alsin';
        }
        $download['judul_4'][] = 'Jumlah';
        $download['judul_4'][2] = 'Tanggal';
        $download['judul_6'][] = 'LTT/LTP';
        $download['judul_6'][] = 'Alsin';

        if($request->has('kota_id')){
            $download['judul_4'][1] = 'Kecamatan';
            $download['judul_3'] = $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['judul_4'][1] = 'Kelurahan/Desa';
                $download['judul_3'] = 'Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $download['data'] = [];
        $no = 1;
        foreach ($data['data']['data'] as $key => $value) {
            $download['data'][$key] = [
                $no,
                $value['nama']
            ];
            foreach ($value['hari'] as $hsl => $hasil) {
                $download['data'][$key][] = $hasil;
            }
            $download['data'][$key][] = $value['ltt_ltp'];
            $download['data'][$key][] = $value['alsin'];
            $no++;
        }
        $i = $key + 1;
        $download['data'][$i] = ['Jumlah', ''];
        foreach ($data['jumlah'] as $key => $value) {
            $download['data'][$i][] = $value;
        }
        $createOne = Download::create([
            'keterangan' => $download['judul_1'],
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new RealisasiOpsinDetailJob($download, $createOne->id));
        return true;
    }

    public function data(Request $request)
    {
        $data = $this->list($request);
        $status_penyuluh = ['-','PNS','TBPP','TBPPD','THL-POPT', 'PPPK'];
        $listKota = $listKecamatan = $listKelurahan = [];
        $res = [];
        foreach($data as $key => $value){
            $res[$key] = $value->toArray();
            $res[$key]['name'] = $value->penyuluh->nama ?? ($value->user->name ?? '');
            $res[$key]['nip'] = $value->penyuluh->nip ?? ($value->user->penyuluh->nip ?? '');
            $sp = $value->penyuluh->status_penyuluh ?? ($value->user->penyuluh->status_penyuluh ?? 0);
            $res[$key]['status_penyuluh'] = $status_penyuluh[$sp];
            $keyKota = $value->kota_id;
            if(!isset($listKota[$keyKota])){
                $listKota[$keyKota] = $this->area->getNamaKota(32, $value->kota_id);
            }
            $res[$key]['kota'] = $listKota[$keyKota];

            $keyKecamatan = $value->kota_id . '-' . $value->kecamatan_id;
            if (!isset($listKecamatan[$keyKecamatan])) {
                $listKecamatan[$keyKecamatan] = $this->area->getNamaKecamatan(32, $value->kota_id, $value->kecamatan_id);
            }
            $res[$key]['kecamatan'] = $listKecamatan[$keyKecamatan];

            $keyKelurahan = $value->kota_id . '-' . $value->kecamatan_id . '-' . $value->kelurahan_id;
            if(!isset($listKelurahan[$keyKelurahan])){
                $listKelurahan[$keyKelurahan] = $this->area->getNamaDesa(32, $value->kota_id, $value->kecamatan_id, $value->kelurahan_id);
            }
            $res[$key]['desa'] = $listKelurahan[$keyKelurahan];
            $res[$key]['file_url'] = null;
            if($value->file){
                $file = Storage::exists('/opsin/detail/'.$value->file);
                if($file){
                    $res[$key]['file_url'] = Storage::url('/opsin/detail/'.$value->file);
                }else{
                    $res[$key]['file_url'] = Storage::url('/v1/att_opsin/detail/'.$value->file);
                }
            }
        }
        return $res;
    }

    public function download(Request $request)
    {
        $download['judul'] = 'Data Realisasi LTT/LTP dan Kerja Alsintan';
        $createOne = Download::create([
            'keterangan' => $download['judul'],
            'user_id' => $request->user_id ?? 0
        ]);
        $data = $this->data($request);
        $download['header'] = [
            'No',
            'Nama',
            'NIP',
            'Status Penyuluh',
            'Tanggal',
            'Kota/Kabupaten',
            'Kecamatan',
            'Kelurahan/Desa',
            'Jenis',
            'LTT/LTP [Ha]',
            'Alsin [Unit]',
            'Verifikasi Kota',
            'Verifikasi Kecamatan'
        ];
        $download['last_col'] = 'M';
        $download['data'] = [];
        $no = 0;
        foreach ($data as $key => $value) {
            $no++;
            $download['data'][] = [
                $no,
                $value['name'],
                $value['nip'],
                $value['status_penyuluh'],
                $value['tanggal_input'],
                $value['kota'],
                $value['kecamatan'],
                $value['desa'],
                $value['jenis']['nama'] ?? "",
                $value['ltt_ltp'],
                $value['alsin'],
                $value['vrf_kota'] == 1 ? "Sudah" : "Belum",
                $value['vrf_kec'] == 1 ? "Sudah" : "Belum",
            ];
        }
        $download['last_row'] = $no + 2;

        dispatch(new BaseJob($download, $createOne->id));
        return true;
    }

}