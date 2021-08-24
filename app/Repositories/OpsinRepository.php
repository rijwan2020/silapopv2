<?php
namespace App\Repositories;

use App\Jobs\Data\BaseJob;
use App\Jobs\Laporan\OpsinJob;
use App\Model\Download;
use App\Model\Opsin;
use App\Model\OpsinDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OpsinRepository extends BaseRepository
{
    private $user, $opsindetail, $area, $jenis;
    public function __construct(
        Opsin $model, 
        UserRepository $user, 
        AreaRepository $area,
        JenisAlsinRepository $jenis,
        OpsinDetail $opsindetail)
    {
        $this->model = $model;
        $this->user = $user;
        $this->opsindetail = $opsindetail;
        $this->area = $area;
        $this->jenis = $jenis;
        $this->relation = ['user', 'penyuluh', 'jenis'];
    }
    
    public function sum($request = [])
    {
        $this->params = $request['params'];
        $query = $this->withParameter($this->model);
        return $query->sum('jumlah_alsin');
    }
    
    public function create($request)
    {
        $this->params = [
            'kota_id' => $request['kota_id'],
            'kecamatan_id' => $request['kecamatan_id'],
            'kelurahan_id' => $request['kelurahan_id'],
            'tahun' => $request['tahun'],
            'jenis_id' => $request['jenis_id'],
        ];
        if($request['tahun'] > date('Y')){
            $this->error = 'Tahun tidak boleh melebihi tahun sekarang';
            return false;
        }
        $query = $this->withParameter($this->model)->first();
        if($query){
            $this->error = 'Data sudah ada';
            return false;
        }

        $user = $this->user->getOne($request['insert_by']);
        $request['penyuluh_id'] = $user->penyuluh->id ?? 0;

        return $this->save($request);
    }

    public function edit($id, $data = [])
    {
        $this->params = [
            'kota_id' => $data['kota_id'],
            'kecamatan_id' => $data['kecamatan_id'],
            'kelurahan_id' => $data['kelurahan_id'],
            'tahun' => $data['tahun'],
            'jenis_id' => $data['jenis_id'],
        ];
        if($data['tahun'] > date('Y')){
            $this->error = 'Tahun tidak boleh melebihi tahun sekarang';
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
        //cek opsin detail detail
        $this->params = [
            'kota_id' => $model->kota_id,
            'kecamatan_id' => $model->kecamatan_id,
            'kelurahan_id' => $model->kelurahan_id,
            'tahun' => $model->tahun,
            'jenis_id' => $model->jenis_id,
        ];
        if($this->withParameter($this->opsindetail)->first()){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }
        // cek apakah sudah ada verifikasi atau belum
        if($model->vrf_kota != 0 || $model->vrf_kec != 0){
            $this->error = 'Data tidak bisa dihapus karena sudah diverifikasi.';
            return false;
        }
        if (!empty($model->file)) {
            $this->deleteFile($model->file, 'opsin');
            $this->deleteFile($model->file_produksi, 'v1/att_opsin');
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
                        $keyOpsin = array_search(($filter['tahun'] . '-' .$value->kota_id . '-' . $value->kecamatan_id . '-' . $value->kelurahan_id), array_column($data['opsin'], "grup_kelurahan"));
                        $opsin = 0;
                        if ($keyOpsin !== false) {
                            $opsin = $data['opsin'][$keyOpsin]['jumlah'];
                        }

                        $keyOpsinDetail = array_search(($filter['tahun'] . '-' .$value->kota_id . '-' . $value->kecamatan_id . '-' . $value->kelurahan_id), array_column($data['detail'], "grup_kelurahan"));
                        $alsin = $ltt_ltp = 0;
                        if ($keyOpsinDetail !== false) {
                            $alsin = $data['detail'][$keyOpsinDetail]['total_alsin'];
                            $ltt_ltp = $data['detail'][$keyOpsinDetail]['total_ltt_ltp'];
                        }
        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'opsin' => round($opsin, 2),
                            'ltt_ltp' => round($ltt_ltp, 2),
                            'alsin' => round($alsin, 2),
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
                        $keyOpsin = array_search(($filter['tahun'] . '-' .$value->kota_id . '-' . $value->kecamatan_id), array_column($data['opsin'], "grup_kecamatan"));
                        $opsin = 0;
                        if ($keyOpsin !== false) {
                            $opsin = $data['opsin'][$keyOpsin]['jumlah'];
                        }

                        $keyOpsinDetail = array_search(($filter['tahun'] . '-' .$value->kota_id . '-' . $value->kecamatan_id), array_column($data['detail'], "grup_kecamatan"));
                        $alsin = $ltt_ltp = 0;
                        if ($keyOpsinDetail !== false) {
                            $alsin = $data['detail'][$keyOpsinDetail]['total_alsin'];
                            $ltt_ltp = $data['detail'][$keyOpsinDetail]['total_ltt_ltp'];
                        }
        
                        $res[] = [
                            'nama' => ucwords(strtolower($value->name)),
                            'kota_id' => $value->kota_id,
                            'kecamatan_id' => $value->kecamatan_id,
                            'kelurahan_id' => $value->kelurahan_id,
                            'opsin' => round($opsin, 2),
                            'ltt_ltp' => round($ltt_ltp, 2),
                            'alsin' => round($alsin, 2),
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

                    $keyOpsin = array_search(($filter['tahun'] . '-' .$value->kota_id), array_column($data['opsin'], "grup_kota"));
                    $opsin = 0;
                    if ($keyOpsin !== false) {
                        $opsin = $data['opsin'][$keyOpsin]['jumlah'];
                    }

                    $keyOpsinDetail = array_search(($filter['tahun'] . '-' .$value->kota_id), array_column($data['detail'], "grup_kota"));
                    $alsin = $ltt_ltp = 0;
                    if ($keyOpsinDetail !== false) {
                        $alsin = $data['detail'][$keyOpsinDetail]['total_alsin'];
                        $ltt_ltp = $data['detail'][$keyOpsinDetail]['total_ltt_ltp'];
                    }
    
                    $res[] = [
                        'nama' => ucwords(strtolower($value->name)),
                        'kota_id' => $value->kota_id,
                        'kecamatan_id' => $value->kecamatan_id,
                        'kelurahan_id' => $value->kelurahan_id,
                        'opsin' => round($opsin, 2),
                        'ltt_ltp' => round($ltt_ltp, 2),
                        'alsin' => round($alsin, 2),
                    ];
                }
            }
        }

        $hasil['data'] = $res;
        $hasil['jumlah'] = [
            'opsin' => round(collect($res)->sum('opsin'), 2),
            'ltt_ltp' => round(collect($res)->sum('ltt_ltp'), 2),
            'alsin' => round(collect($res)->sum('alsin'), 2),
        ];

        return $hasil;
    }

    public function laporanDownload(Request $request)
    {
        $data = $this->laporan($request);
        $download['judul_1'] = 'Laporan Optimalisasi Alsin Tahun ' . $request->tahun;
        $download['judul_2'] = 'Semua Jenis Alsin';
        if ($request->has('jenis_id')) {
            $download['judul_2'] = $this->jenis->getOne($request->jenis_id)['nama'];
        }
        $download['judul_3'] = "Provinsi Jawa Barat";
        $download['judul_4'] = [
            '#',
            'Kota/Kabupaten',
            'Jumlah Alsin [Unit]',
            'LTT/LTP [Ha]',
            'Realisasi Alsin [Unit]',
        ];
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
            $download['data'][] = [
                $no,
                $value['nama'],
                $value['opsin'],
                $value['ltt_ltp'],
                $value['alsin'],
            ];
            $no++;
        }
        $download['data'][] = [
            'Jumlah',
            '',
            $data['jumlah']['opsin'],
            $data['jumlah']['ltt_ltp'],
            $data['jumlah']['alsin'],
        ];
        $createOne = Download::create([
            'keterangan' => $download['judul_1'],
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new OpsinJob($download, $createOne->id));
        return true;
    }

    private function dataLaporan($field = 'grup_kota')
    {
        $opsin = $this->withParameter($this->model);
        $raw = DB::raw("
            SUM(jumlah_alsin) AS 'jumlah',
            CONCAT(tahun,\"-\",kota_id) AS 'grup_kota',
            CONCAT(tahun,\"-\",kota_id,\"-\",kecamatan_id) AS 'grup_kecamatan',
            CONCAT(tahun,\"-\",kota_id,\"-\",kecamatan_id,\"-\",kelurahan_id) AS 'grup_kelurahan'
        ");
        $data = $opsin->select($raw)->groupBy($field)->get();
        $hasil['opsin'] = $data->toArray();

        $opsindetail = $this->withParameter($this->opsindetail);
        $raw = DB::raw("
            SUM(ltt_ltp) AS 'total_ltt_ltp',
            SUM(alsin) AS 'total_alsin',
            CONCAT(tahun,\"-\",kota_id) AS 'grup_kota',
            CONCAT(tahun,\"-\",kota_id,\"-\",kecamatan_id) AS 'grup_kecamatan',
            CONCAT(tahun,\"-\",kota_id,\"-\",kecamatan_id,\"-\",kelurahan_id) AS 'grup_kelurahan'
        ");
        $detail = $opsindetail->select($raw)->groupBy($field)->get();
        $hasil['detail'] = $detail->toArray();
        return $hasil;
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
                $file = Storage::exists('/opsin/'.$value->file);
                if($file){
                    $res[$key]['file_url'] = Storage::url('/opsin/'.$value->file);
                }else{
                    $res[$key]['file_url'] = Storage::url('/v1/att_opsin/'.$value->file);
                }
            }

            $res[$key]['file_old'] = false;
            if (!empty($value->file)) {
                $cek_file = Storage::exists('/opsin/'.$value->file);
                if(!$cek_file){
                    $res[$key]['file_old'] = true;
                }
            }
        }

        return $res;
    }

    public function download(Request $request)
    {
        $download['judul'] = 'Data Alsin';
        $createOne = Download::create([
            'keterangan' => $download['judul'],
            'user_id' => $request->user_id ?? 0
        ]);
        $data = $this->data($request);
        $download['header'] = [
            'No',
            'Tahun',
            'Nama',
            'NIP',
            'Status Penyuluh',
            'Kota/Kabupaten',
            'Kecamatan',
            'Kelurahan/Desa',
            'Jenis',
            'Jumlah Alsin [Unit]',
            'Verifikasi Kota',
            'Verifikasi Kecamatan'
        ];
        $download['last_col'] = 'L';
        $download['data'] = [];
        $no = 0;
        foreach ($data as $key => $value) {
            $no++;
            $download['data'][] = [
                $no,
                $value['tahun'],
                $value['name'],
                $value['nip'],
                $value['status_penyuluh'],
                $value['kota'],
                $value['kecamatan'],
                $value['desa'],
                $value['jenis']['nama'] ?? "",
                $value['jumlah_alsin'],
                $value['vrf_kota'] == 1 ? "Sudah" : "Belum",
                $value['vrf_kec'] == 1 ? "Sudah" : "Belum",
            ];
        }
        $download['last_row'] = $no + 2;

        dispatch(new BaseJob($download, $createOne->id));
        return true;
    }
}