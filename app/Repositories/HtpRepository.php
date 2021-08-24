<?php
namespace App\Repositories;

use App\Jobs\Data\BaseJob;
use App\Model\Download;
use App\Model\Htp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HtpRepository extends BaseRepository
{
    private $user, $area, $komoditi;
    public function __construct(
        Htp $model,
        AreaRepository $area, 
        KomoditasHtpRepository $komoditi,
        UserRepository $user)
    {
        $this->model = $model;
        $this->user = $user;
        $this->area = $area;
        $this->komoditi = $komoditi;
        $this->relation = ['user', 'penyuluh'];
    }

    public function getAvg($request = [])
    {
        $this->params = $request['params'];
        $query = $this->withParameter($this->model);
        return $query->avg('htp');
    }

    public function create($request)
    {
        $this->params = [
            'kota_id' => $request['kota_id'],
            'kecamatan_id' => $request['kecamatan_id'],
            'kelurahan_id' => $request['kelurahan_id'],
            'tanggal' => $request['tanggal'],
            'komoditi_id' => $request['komoditi_id'],
        ];
        if($request['tanggal'] > date('Y-m-d')){
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

        $tanggal = Carbon::create($request['tanggal']);
        $request['hari'] = $tanggal->format('d');
        $request['bulan'] = $tanggal->format('m');
        $request['tahun'] = $tanggal->format('Y');
        
        return $this->save($request);
    }
    
    public function edit($id, $data = [])
    {
        $this->params = [
            'kota_id' => $data['kota_id'],
            'kecamatan_id' => $data['kecamatan_id'],
            'kelurahan_id' => $data['kelurahan_id'],
            'tanggal' => $data['tanggal'],
            'komoditi_id' => $data['komoditi_id'],
        ];
        if($data['tanggal'] > date('Y-m-d')){
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
            $this->deleteFile($model->file, 'htp');
            $this->deleteFile($model->file_produksi, 'v1/att_htp');
        }
        return $model->delete();
    }

    public function data(Request $request)
    {
        $data = $this->list($request);
        $status_penyuluh = ['-','PNS','TBPP','TBPPD','THL-POPT', 'PPPK'];
        $listKota = $listKecamatan = $listKelurahan = $listKomoditi = [];
        $res = [];
        foreach($data as $key => $value){
            $res[$key] = $value->toArray();
            $res[$key]['name'] = $value->penyuluh->nama ?? ($value->user->name ?? '');
            $res[$key]['nip'] = $value->penyuluh->nip ?? ($value->user->penyuluh->nip ?? '');
            $sp = $value->penyuluh->status_penyuluh ?? ($value->user->penyuluh->status_penyuluh ?? 0);
            $res[$key]['status_penyuluh'] = $status_penyuluh[$sp];
            
            $keyKomoditi = $value->komoditi_id;
            if(!isset($listKomoditi[$keyKomoditi])){
                $listKomoditi[$keyKomoditi] = $this->komoditi->getOne($value->komoditi_id)['nama'];
            }
            $res[$key]['komoditi'] = $listKomoditi[$keyKomoditi];

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
                $file = Storage::exists('/htp/'.$value->file);
                if($file){
                    $res[$key]['file_url'] = Storage::url('/htp/'.$value->file);
                }else{
                    $res[$key]['file_url'] = Storage::url('/v1/att_htp/'.$value->file);
                }
            }
        }

        return $res;
    }

    public function download(Request $request)
    {
        $download['judul'] = 'Harga Tingkat Petani';
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
            'Komoditas',
            'Harga Tingkat Petani [Rp/Kg]',
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
                $value['name'],
                $value['nip'],
                $value['status_penyuluh'],
                $value['tanggal'],
                $value['kota'],
                $value['kecamatan'],
                $value['desa'],
                $value['komoditi'],
                $value['htp'],
                $value['vrf_kota'] == 1 ? "Sudah" : "Belum",
                $value['vrf_kec'] == 1 ? "Sudah" : "Belum",
            ];
        }
        $download['last_row'] = $no + 2;

        dispatch(new BaseJob($download, $createOne->id));
        return true;
    }
}