<?php
namespace App\Repositories;

use App\Model\WilayahKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahKerjaRepository extends BaseRepository
{
    private $penyuluh;
    public function __construct(WilayahKerja $model, PenyuluhRepository $penyuluh)
    {
        $this->model = $model;
        $this->penyuluh = $penyuluh;
        $this->relation = ['penyuluh'];
    }

    public function create($request)
    {
        // $this->params = [
        //     'kota_id' => $request['kota_id'],
        //     'kecamatan_id' => $request['kecamatan_id'],
        //     'kelurahan_id' => $request['kelurahan_id'],
        // ];
        // $query = $this->withParameter($this->model)->first();
        // if($query){
        //     $this->error = 'Data sudah ada';
        //     return false;
        // }

        $penyuluh = $this->penyuluh->getOne($request['penyuluh_id']);
        $request['jenis_penyuluh'] = $penyuluh->jp;
        
        return $this->save($request);
    }

    // public function edit($id, $data = [])
    // {
    //     $this->params = [
    //         'kota_id' => $data['kota_id'],
    //         'kecamatan_id' => $data['kecamatan_id'],
    //         'kelurahan_id' => $data['kelurahan_id'],
    //     ];
    //     $query = $this->withParameter($this->model)->first();
    //     if($query && $query->id != $id){
    //         $this->error = 'Data sudah ada';
    //         return false;
    //     }
    //     return $this->update($id, $data);
    // }

    public function delete($id)
    {
        $model = $this->getOne($id);
        if (!$model) {
            $this->error = 'Data tidak ditemukan.';
            return false;
        }
        $filter = [
            'kota_id' => $model->kota_id,
            'kecamatan_id' => $model->kecamatan_id,
            'kelurahan_id' => $model->kelurahan_id,
            'penyuluh_id' => $model->penyuluh_id
        ];
        // cek apakah sudah ada data baku lahan yg input atau belum
        // $bakulahan = DB::table('baku_lahan')->where('kota_id', $model->kota_id)->where('kecamatan_id', $model->kecamatan_id)->where('kelurahan_id', $model->kelurahan_id)->count();
        $bakulahan = DB::table('baku_lahan')->where($filter)->count();
        if ($bakulahan > 0) {
            $this->error = 'Data wilayah kerja sudah digunakan sehingga tidak dapat dihapus';
            return false;
        }
        // $bakulahan_detail = DB::table('baku_lahan_detail')->where('kota_id', $model->kota_id)->where('kecamatan_id', $model->kecamatan_id)->where('kelurahan_id', $model->kelurahan_id)->count();
        $bakulahan_detail = DB::table('baku_lahan_detail')->where($filter)->count();
        if ($bakulahan_detail > 0) {
            $this->error = 'Data wilayah kerja sudah digunakan sehingga tidak dapat dihapus';
            return false;
        }
        // cek apakah sudah ada data luas yg input atau belum
        // $bakulahan = DB::table('data_luas')->where('kota_id', $model->kota_id)->where('kecamatan_id', $model->kecamatan_id)->where('kelurahan_id', $model->kelurahan_id)->count();
        $dataluas = DB::table('data_luas')->where($filter)->count();
        if ($dataluas > 0) {
            $this->error = 'Data wilayah kerja sudah digunakan sehingga tidak dapat dihapus';
            return false;
        }
        // cek apakah sudah ada htp yg input atau belum
        // $bakulahan = DB::table('htp')->where('kota_id', $model->kota_id)->where('kecamatan_id', $model->kecamatan_id)->where('kelurahan_id', $model->kelurahan_id)->count();
        $htp = DB::table('htp')->where($filter)->count();
        if ($htp > 0) {
            $this->error = 'Data wilayah kerja sudah digunakan sehingga tidak dapat dihapus';
            return false;
        }
        // cek apakah sudah ada opsin yg input atau belum
        // $opsin = DB::table('opsin')->where('kota_id', $model->kota_id)->where('kecamatan_id', $model->kecamatan_id)->where('kelurahan_id', $model->kelurahan_id)->count();
        $opsin = DB::table('opsin')->where($filter)->count();
        if ($opsin > 0) {
            $this->error = 'Data wilayah kerja sudah digunakan sehingga tidak dapat dihapus';
            return false;
        }
        
        return $model->delete();
    }
}