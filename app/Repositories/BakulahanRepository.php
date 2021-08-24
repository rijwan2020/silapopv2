<?php
namespace App\Repositories;

use App\Jobs\Data\BaseJob;
use App\Model\Bakulahan;
use App\Model\BakulahanDetail;
use App\Model\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BakulahanRepository extends BaseRepository
{
    private $bakulahan_detail, $user, $area;
    public function __construct(
        Bakulahan $bakulahan, 
        BakulahanDetail $bakulahan_detail, 
        AreaRepository $area,
        UserRepository $user)
    {
        $this->model = $bakulahan;
        $this->user = $user;
        $this->area = $area;
        $this->relation = ['user', 'penyuluh'];
        $this->bakulahan_detail = $bakulahan_detail;
    }

    public function sumLuas($param = [])
    {
        $this->params = $param;
        $model = $this->withParameter($this->model);
        return $model->sum('luas_baku_lahan');
    }

    public function sum($request = [])
    {
        if (isset($request['params'])) {
            $this->params = json_decode($request['params']);
        }
        $query = $this->withParameter($this->model);
        return $query->sum('luas_baku_lahan');
    }

    public function create($request)
    {
        $this->params = [
            'kota_id' => $request['kota_id'],
            'kecamatan_id' => $request['kecamatan_id'],
            'kelurahan_id' => $request['kelurahan_id'],
            'tahun' => $request['tahun'],
            'jenis' => $request['jenis'],
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
            'jenis' => $data['jenis'],
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
        //cek baku lahan detail
        $this->params = [
            'kota_id' => $model->kota_id,
            'kecamatan_id' => $model->kecamatan_id,
            'kelurahan_id' => $model->kelurahan_id,
            'tahun' => $model->tahun,
            'jenis' => $model->jenis,
        ];
        if($this->withParameter($this->bakulahan_detail)->first()){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }
        // cek apakah sudah ada verifikasi atau belum
        if($model->vrf_kota != 0 || $model->vrf_kec != 0){
            $this->error = 'Data tidak bisa dihapus karena sudah diverifikasi.';
            return false;
        }
        if (!empty($model->file)) {
            $this->deleteFile($model->file, 'bakulahan');
            $this->deleteFile($model->file_produksi, 'v1/att_bakulahan');
        }
        return $model->delete();
    }

    public function data(Request $request)
    {
        $data = $this->list($request);
        $status_penyuluh = ['-','PNS','TBPP','TBPPD','THL-POPT', 'PPPK'];
        $res = [];

        $listKota = $listKecamatan = $listKelurahan = [];

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
                $file = Storage::exists('/bakulahan/'.$value->file);
                if($file){
                    $res[$key]['file_url'] = Storage::url('/bakulahan/'.$value->file);
                }else{
                    $res[$key]['file_url'] = Storage::url('/v1/att_bakulahan/'.$value->file);
                }
            }

            $res[$key]['file_old'] = false;
            if (!empty($value->file)) {
                $cek_file = Storage::exists('/bakulahan/'.$value->file);
                if(!$cek_file){
                    $res[$key]['file_old'] = true;
                }
            }
        }
        return $res;
    }

    public function download(Request $request)
    {
        $download['judul'] = 'Data Baku Lahan';
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
            'Luas [Ha]',
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
                $value['jenis'] == 1 ? "Sawah" : "Ladang",
                $value['luas_baku_lahan'],
                $value['vrf_kota'] == 1 ? "Sudah" : "Belum",
                $value['vrf_kec'] == 1 ? "Sudah" : "Belum",
            ];
        }
        $download['last_row'] = $no + 2;

        dispatch(new BaseJob($download, $createOne->id));
        return true;
    }
}