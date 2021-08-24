<?php
namespace App\Repositories;

use App\Jobs\Data\BaseJob;
use App\Model\BakulahanDetail;
use App\Model\Download;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BakulahanDetailRepository extends BaseRepository
{
    private $user, $area;
    public function __construct(
        BakulahanDetail $model, 
        AreaRepository $area,
        UserRepository $user)
    {
        $this->model = $model;
        $this->user = $user;
        $this->area = $area;
        $this->relation = ['user'];
    }

    public function sumRencanaTanam($request = [])
    {
        if (is_array($request)) {
            $this->params = $request;
        }else if (isset($request['params'])) {
            $this->params = json_decode($request['params']);
        }
        $query = $this->withParameter($this->model);
        return $query->sum('rencana_tanam');
    }

    public function sumRealisasiTanam($request = [])
    {
        if (is_array($request)) {
            $this->params = $request;
        }else if (isset($request['params'])) {
            $this->params = json_decode($request['params']);
        }
        $query = $this->withParameter($this->model);
        return $query->sum('realisasi_tanam');
    }

    public function create($request)
    {
        $this->params = [
            'kota_id' => $request['kota_id'],
            'kecamatan_id' => $request['kecamatan_id'],
            'kelurahan_id' => $request['kelurahan_id'],
            'tanggal_input' => $request['tanggal_input'],
            'jenis' => $request['jenis'],
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
            'jenis' => $data['jenis'],
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
            $this->deleteFile($model->file, 'bakulahan/detail');
            $this->deleteFile($model->file_produksi, 'v1/att_bakulahan/detail');
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
                $file = Storage::exists('/bakulahan/detail/'.$value->file);
                if($file){
                    $res[$key]['file_url'] = Storage::url('/bakulahan/detail/'.$value->file);
                }else{
                    $res[$key]['file_url'] = Storage::url('/v1/att_bakulahan/detail/'.$value->file);
                }
            }
        }
        return $res;
    }

    public function download(Request $request)
    {
        $download['judul'] = 'Data Rencana Tanam dan Realisasi Tanam';
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
            'Rencana Tanam [Ha]',
            'Realisasi Tanam [Ha]',
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
                $value['jenis'] == 1 ? "Sawah" : "Ladang",
                $value['rencana_tanam'],
                $value['realisasi_tanam'],
                $value['vrf_kota'] == 1 ? "Sudah" : "Belum",
                $value['vrf_kec'] == 1 ? "Sudah" : "Belum",
            ];
        }
        $download['last_row'] = $no + 2;

        dispatch(new BaseJob($download, $createOne->id));
        return true;
    }

}