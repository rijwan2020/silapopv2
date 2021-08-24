<?php
namespace App\Repositories;

use App\Jobs\Data\BaseJob;
use App\Model\DataLuas;
use App\Model\Download;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DataLuasRepository extends BaseRepository
{
    private $user, $area, $komoditi;
    public function __construct(
        DataLuas $model, 
        AreaRepository $area,
        KomoditasRepository $komoditi,
        UserRepository $user)
    {
        $this->model = $model;
        $this->user = $user;
        $this->area = $area;
        $this->komoditi = $komoditi;
        $this->relation = ['user', 'penyuluh'];
    }
    public function sumLuasTanamKota($request = [])
    {
        $this->params = $request['params'];
        $query = $this->withParameter($this->model);

        $res = DB::raw("kota_id, kecamatan_id, kelurahan_id, hari, tanggal, SUM(luas_tanam) AS 'sum_luas_tanam', CONCAT(kota_id,\"-\",tanggal) AS 'id_group'");
        $data = $query->select($res)->groupBy('id_group')->get();
        return $data->toArray();
    }

    public function sumLuasTanamKecamatan($request = [])
    {
        $this->params = $request['params'];
        $query = $this->withParameter($this->model);
        $res = DB::raw("kota_id, kecamatan_id, kelurahan_id, hari, tanggal, SUM(luas_tanam) AS 'sum_luas_tanam', CONCAT(kota_id,\"-\",kecamatan_id,\"-\",tanggal) AS 'id_group'");
        $data = $query->select($res)->groupBy('id_group')->get();
        return $data->toArray();
    }

    public function sumLuasTanamKelurahan($request = [])
    {
        $this->params = $request['params'];
        $query = $this->withParameter($this->model);
        $res = DB::raw("kota_id, kecamatan_id, kelurahan_id, hari, tanggal, SUM(luas_tanam) AS 'sum_luas_tanam', CONCAT(kota_id,\"-\",kecamatan_id,\"-\",kelurahan_id,\"-\",tanggal) AS 'id_group'");
        $data = $query->select($res)->groupBy('id_group')->get();
         return $data->toArray();
    }

    public function sumLuasTanam($request = [])
    {
        $this->params = $request['params'];
        $query = $this->withParameter($this->model);
        return $query->sum('luas_tanam');
    }

    public function sumLuasPanen($request = [])
    {
        $this->params = $request['params'];
        $query = $this->withParameter($this->model);
        return $query->sum('luas_panen');
    }

    public function sumProduksi($request = [])
    {
        $this->params = $request['params'];
        $query = $this->withParameter($this->model);
        return $query->sum('produksi');
    }

    public function sumTambahTanam($request = [])
    {
        $this->params = $request['params'];
        $query = $this->withParameter($this->model);
        return $query->sum('tambah_tanam');
    }

    public function create($request)
    {
        unset($request['update_time'], $request['insert_time']);
        $this->params = [
            'kota_id' => $request['kota_id'],
            'kecamatan_id' => $request['kecamatan_id'],
            'kelurahan_id' => $request['kelurahan_id'],
            'tanggal' => $request['tanggal'],
            'komoditi_id' => $request['komoditi_id'],
            'jenis' => $request['jenis'],
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
        $tanggal = Carbon::create($request['tanggal']);
        $request['hari'] = $tanggal->format('d');
        $request['bulan'] = $tanggal->format('m');
        $request['tahun'] = $tanggal->format('Y');

        $user = $this->user->getOne($request['created_by']);
        $request['penyuluh_id'] = $user->penyuluh->id ?? 0;

        return $this->save($request);
    }

    public function save($request)
    {
        DB::beginTransaction();
        try {
            $create = $this->model->create($request);
            $this->createToOld($create);
            DB::commit();
            return $create;
        } catch (\Exception $e) {
            Log::info($e);
            $this->error = $e->getMessage();
            DB::rollBack();
            return false;
        }
    }

    public function edit($id, $data = [])
    {
        unset($data['update_time']);
        $this->params = [
            'kota_id' => $data['kota_id'],
            'kecamatan_id' => $data['kecamatan_id'],
            'kelurahan_id' => $data['kelurahan_id'],
            'tanggal' => $data['tanggal'],
            'komoditi_id' => $data['komoditi_id'],
            'jenis' => $data['jenis'],
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

    public function createToOld($request)
    {
        $data = [
            'insert_by' => $request->created_by,
            'update_by' => $request->updated_by,
            'insert_time' => $request->created_at,
            'update_time' => $request->updated_at,
            'tanggal' => $request->tanggal,
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'hari' => $request->hari,
            'komoditi_id' => $request->komoditi_id,
            'kota_id' => $request->kota_id,
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'jenis' => $request->jenis,
            'lat' => $request->lat,
            'long' => $request->long,
            'penyuluh_id' => $request->penyuluh_id,
            'data_luas_id' => $request->id,
        ];

        // Luas Tanam
        $luas_tanam = DB::table('luas_tanam')->where('data_luas_id', $request->id)->first();
        $dataLuasTanam = $data;
        $dataLuasTanam['luas'] = $request->luas_tanam;
        $dataLuasTanam['ket_file'] = $request->ket_file_luas_tanam;
        if($luas_tanam){
            DB::table('luas_tanam')->where('id', $luas_tanam->id)->update($dataLuasTanam);
        }else{
            if($dataLuasTanam['luas'] > 0){
                DB::table('luas_tanam')->insert($dataLuasTanam);
            }
        }

        // Luas Panen
        $luas_panen = DB::table('luas_panen')->where('data_luas_id', $request->id)->first();
        $dataLuasPanen = $data;
        $dataLuasPanen['luas_panen'] = $request->luas_panen;
        $dataLuasPanen['ket_file'] = $request->ket_file_luas_panen;
        if($luas_panen){
            DB::table('luas_panen')->where('id', $luas_panen->id)->update($dataLuasPanen);
        }else{
            if($dataLuasPanen['luas_panen'] > 0){
                DB::table('luas_panen')->insert($dataLuasPanen);
            }
        }
        
        // Produksi
        $produksi = DB::table('produksi')->where('data_luas_id', $request->id)->first();
        $dataProduksi = $data;
        $dataProduksi['luas'] = $request->produksi;
        $dataProduksi['ket_file'] = $request->ket_file_produksi;
        if($produksi){
            DB::table('produksi')->where('id', $produksi->id)->update($dataProduksi);
        }else{
            if($dataProduksi['luas'] > 0){
                DB::table('produksi')->insert($dataProduksi);
            }
        }

        // Tambah Tanam
        $tambah_tanam = DB::table('luas_tambah_tanam')->where('data_luas_id', $request->id)->first();
        $dataTambahTanam = $data;
        $dataTambahTanam['tambah_tanam'] = $request->tambah_tanam;
        $dataTambahTanam['ket_file'] = $request->ket_file_tambah_tanam;
        if($tambah_tanam){
            DB::table('luas_tambah_tanam')->where('id', $tambah_tanam->id)->update($dataTambahTanam);
        }else{
            if($dataTambahTanam['tambah_tanam'] > 0){
                DB::table('luas_tambah_tanam')->insert($dataTambahTanam);
            }
        }

        return true;
    }

    public function update($id, $data = [])
    {
        DB::beginTransaction();
        try {
            $model = $this->getOne($id);
            if ($model == false) {
                throw new \Exception("Data tidak ditemukan.");
            }
            $model->update($data);
            $this->createToOld($model);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            Log::info($e);
            $this->error = $e->getMessage();
            DB::rollBack();
            return false;
        }
    }

    public function upload($request)
    {
        // if ($_FILES['luas_tanam']['size'] > 2097152) {
        //     $this->error = 'File maksimal 2mb';
        //     return false;
        // }
        // if ($_FILES['luas_panen']['size'] > 2097152) {
        //     $this->error = 'File maksimal 2mb';
        //     return false;
        // }
        // if ($_FILES['produksi']['size'] > 2097152) {
        //     $this->error = 'File maksimal 2mb';
        //     return false;
        // }
        // if ($_FILES['tambah_tanam']['size'] > 2097152) {
        //     $this->error = 'File maksimal 2mb';
        //     return false;
        // }
        $model = $this->getOne($request->id);
        // Luas Tanam
        if($request->has('luas_tanam')){
            $updateData['file_luas_tanam'] = date('YmdHis-') . $_FILES['luas_tanam']['name'];
            $request->file('luas_tanam')->storeAs('data_luas/luas_tanam', $updateData['file_luas_tanam']);
            $request->file('luas_tanam')->storeAs('v1/att_luastanam', $updateData['file_luas_tanam']);
            if (!empty($model->file_luas_tanam)) {
                if (Storage::exists('data_luas/luas_tanam/'.$model->file_luas_tanam)) {
                    Storage::delete('data_luas/luas_tanam/'. $model->file_luas_tanam);
                }
                if (Storage::exists('v1/att_luastanam/'.$model->file_luas_tanam)) {
                    Storage::delete('v1/att_luastanam/'. $model->file_luas_tanam);
                }
            }
            $luas_tanam = DB::table('luas_tanam')->where('data_luas_id', $model->id)->first();
            if($luas_tanam){
                DB::table('luas_tanam')->where('id', $luas_tanam->id)->update(['file' => $updateData['file_luas_tanam']]);
            }
        }

        // Luas Panen
        if($request->has('luas_panen')){
            $updateData['file_luas_panen'] = date('YmdHis-') . $_FILES['luas_panen']['name'];
            $request->file('luas_panen')->storeAs('data_luas/luas_panen', $updateData['file_luas_panen']);
            $request->file('luas_panen')->storeAs('v1/att_luaspanen', $updateData['file_luas_panen']);
            if (!empty($model->file_luas_panen)) {
                if (Storage::exists('data_luas/luas_panen/'.$model->file_luas_panen)) {
                    Storage::delete('data_luas/luas_panen/'. $model->file_luas_panen);
                }
                if (Storage::exists('v1/att_luaspanen/'.$model->file_luas_panen)) {
                    Storage::delete('v1/att_luaspanen/'. $model->file_luas_panen);
                }
            }
            $luas_panen = DB::table('luas_panen')->where('data_luas_id', $model->id)->first();
            if($luas_panen){
                DB::table('luas_panen')->where('id', $luas_panen->id)->update(['file' => $updateData['file_luas_panen']]);
            }
        }

        // Produksi
        if($request->has('produksi')){
            $updateData['file_produksi'] = date('YmdHis-') . $_FILES['produksi']['name'];
            $request->file('produksi')->storeAs('data_luas/produksi', $updateData['file_produksi']);
            $request->file('produksi')->storeAs('v1/att_produksi', $updateData['file_produksi']);
            if (!empty($model->file_produksi)) {
                if (Storage::exists('data_luas/produksi/'.$model->file_produksi)) {
                    Storage::delete('data_luas/produksi/'. $model->file_produksi);
                }
                if (Storage::exists('v1/att_produksi/'.$model->file_produksi)) {
                    Storage::delete('v1/att_produksi/'. $model->file_produksi);
                }
            }
            $produksi = DB::table('produksi')->where('data_luas_id', $model->id)->first();
            if($produksi){
                DB::table('produksi')->where('id', $produksi->id)->update(['file' => $updateData['file_produksi']]);
            }
        }

        // Tambah Tanam
        if($request->has('tambah_tanam')){
            $updateData['file_tambah_tanam'] = date('YmdHis-') . $_FILES['tambah_tanam']['name'];
            $request->file('tambah_tanam')->storeAs('data_luas/tambah_tanam', $updateData['file_tambah_tanam']);
            $request->file('tambah_tanam')->storeAs('v1/att_tambahtanam', $updateData['file_tambah_tanam']);
            if (!empty($model->file_tambah_tanam)) {
                if (Storage::exists('data_luas/tambah_tanam/'.$model->file_tambah_tanam)) {
                    Storage::delete('data_luas/tambah_tanam/'. $model->file_tambah_tanam);
                }
                if (Storage::exists('v1/att_tambahtanam/'.$model->file_tambah_tanam)) {
                    Storage::delete('v1/att_tambahtanam/'. $model->file_tambah_tanam);
                }
            }
            $tambah_tanam = DB::table('luas_tambah_tanam')->where('data_luas_id', $model->id)->first();
            if($tambah_tanam){
                DB::table('luas_tambah_tanam')->where('id', $tambah_tanam->id)->update(['file' => $updateData['file_tambah_tanam']]);
            }
        }
        return $model->update($updateData);
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

        if (!empty($model->file_luas_tanam)) {
            $this->deleteFile($model->file_luas_tanam, 'data_luas/luas_tanam');
            $this->deleteFile($model->file_luas_tanam, 'v1/att_luastanam');
        }

        if (!empty($model->file_luas_panen)) {
            $this->deleteFile($model->file_luas_panen, 'data_luas/luas_panen');
            $this->deleteFile($model->file_luas_panen, 'v1/att_luaspanen');
        }

        if (!empty($model->file_tambah_tanam)) {
            $this->deleteFile($model->file_tambah_tanam, 'data_luas/tambah_tanam');
            $this->deleteFile($model->file_tambah_tanam, 'v1/att_tambahtanam');
        }

        if (!empty($model->file_produksi)) {
            $this->deleteFile($model->file_produksi, 'data_luas/produksi');
            $this->deleteFile($model->file_produksi, 'v1/att_produksi');
        }

        DB::table('luas_tanam')->where('data_luas_id', $id)->delete();
        DB::table('luas_tambah_tanam')->where('data_luas_id', $id)->delete();
        DB::table('luas_panen')->where('data_luas_id', $id)->delete();
        DB::table('produksi')->where('data_luas_id', $id)->delete();

        return $model->delete();
    }

    public function verifikasiAll($data)
    {
        $this->params = $data['params'];
        $data['updated_by'] = $data['update_by'];
        $data['updated_at'] = $data['update_time'];
        unset($data['params'], $data['update_time'], $data['update_by']);
        $this->withParameter($this->model)->update($data);
        
        return true;
    }

    public function data(Request $request)
    {
        $data = $this->list($request);
        $status_penyuluh = ['-','PNS','TBPP','TBPPD','THL-POPT', 'PPPK'];
        $listKota = $listKecamatan = $listKelurahan = $listKomoditi = [];
        $res = [];
        foreach($data as $key => $value){
            $res[$key] = $value->toArray();
            $res[$key]['name'] = $value->user->penyuluh->nama ?? '-';
            $res[$key]['nip'] = $value->user->penyuluh->nip ?? '-';
            $res[$key]['status_penyuluh'] = isset($value->user->penyuluh->status_penyuluh) ? $status_penyuluh[$value->user->penyuluh->status_penyuluh] : '';

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

            $res[$key]['produktivitas'] = round(($value->luas_panen > 0 ? $value->produksi * 10 / $value->luas_panen : 0), 2);

            $res[$key]['files'] = [];
            if($value->file_luas_tanam){
                $file = Storage::exists('/data_luas/luas_tanam/'.$value->file);
                $res[$key]['files']['luas_tanam']['file'] = 'File Luas Tanam';
                if($file){
                    $res[$key]['files']['luas_tanam']['url'] = Storage::url('/data_luas/luas_tanam/'.$value->file_luas_tanam);
                }else{
                    $res[$key]['files']['luas_tanam']['url'] = Storage::url('/v1/att_luastanam/'.$value->file_luas_tanam);
                }
            }
            if($value->file_tambah_tanam){
                $file = Storage::exists('/data_luas/tambah_tanam/'.$value->file);
                $res[$key]['files']['tambah_tanam']['file'] = 'File Tambah Tanam';
                if($file){
                    $res[$key]['files']['tambah_tanam']['url'] = Storage::url('/data_luas/tambah_tanam/'.$value->file_tambah_tanam);
                }else{
                    $res[$key]['files']['tambah_tanam']['url'] = Storage::url('/v1/att_tambahtanam/'.$value->file_tambah_tanam);
                }
            }
            if($value->file_luas_panen){
                $file = Storage::exists('/data_luas/luas_panen/'.$value->file);
                $res[$key]['files']['luas_panen']['file'] = 'File Luas Panen';
                if($file){
                    $res[$key]['files']['luas_panen']['url'] = Storage::url('/data_luas/luas_panen/'.$value->file_luas_panen);
                }else{
                    $res[$key]['files']['luas_panen']['url'] = Storage::url('/v1/att_luaspanen/'.$value->file_luas_panen);
                }
            }
            if($value->file_produksi){
                $file = Storage::exists('/data_luas/produksi/'.$value->file);
                $res[$key]['files']['produksi']['file'] = 'File Produksi';
                if($file){
                    $res[$key]['files']['produksi']['url'] = Storage::url('/data_luas/produksi/'.$value->file_produksi);
                }else{
                    $res[$key]['files']['produksi']['url'] = Storage::url('/v1/att_produksi/'.$value->file_produksi);
                }
            }
        }
        return $res;
    }

    public function download(Request $request)
    {
        $download['judul'] = 'Data Luas';
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
            'Komoditas',
            'Luas Tanam [Ha]',
            'Tambah Tanam [Ha]',
            'Produksi [Ton]',
            'Luas Panen [Ha]',
            'Produktivitas [Kw/Ha]',
            'Verifikasi Kota',
            'Verifikasi Kecamatan'
        ];
        $download['last_col'] = 'Q';
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
                $value['jenis'] == 1 ? "Sawah" : "Ladang",
                $value['komoditi'],
                $value['luas_tanam'],
                $value['tambah_tanam'],
                $value['produksi'],
                $value['luas_panen'],
                $value['produktivitas'],
                $value['vrf_kota'] == 1 ? "Sudah" : "Belum",
                $value['vrf_kec'] == 1 ? "Sudah" : "Belum",
            ];
        }
        $download['last_row'] = $no + 2;

        dispatch(new BaseJob($download, $createOne->id));
        return true;
    }
}