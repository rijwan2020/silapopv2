<?php
namespace App\Repositories;

use App\Model\Area;
use Illuminate\Support\Facades\DB;

class AreaRepository extends BaseRepository
{
    private $listKota = []; 
    private $listKecamatan = [];
    private $listKelurahan = [];

    public function __construct(Area $area)
    {
        $this->model = $area;
    }

    public function getNamaProvinsi($provinsi_id)
    {
        $query = $this->model->where('provinsi_id', $provinsi_id)->first();
        if($provinsi_id == 0 || !$query){
            return '-';
        }
        return ucwords(strtolower($query->name));
    }

    public function getNamaKota($provinsi_id, $kota_id)
    {
        $kota = '-';
        $listKota = $this->listKota;
        if (isset($listKota[$kota_id])) {
            $kota = $listKota[$kota_id];
        }else{
            
            $query = $this->model->where([
                ['provinsi_id', $provinsi_id],
                ['kota_id', $kota_id],
            ])->first();
            if($kota_id == 0 || !$query){
                $kota = '-';
            }else{
                $kota = ucwords(strtolower($query->name));
                $this->listKota[$kota_id] = $kota;
            }
        }
        return $kota;
    }

    public function getNamaKecamatan($provinsi_id, $kota_id, $kecamatan_id)
    {
        $kecamatan = '-';
        $listKecamatan = $this->listKecamatan;
        if (isset($listKecamatan[$kota_id.'-'.$kecamatan_id])) {
            $kecamatan = $listKecamatan[$kota_id.'-'.$kecamatan_id];
        }else{
            
            $query = $this->model->where([
                ['provinsi_id', $provinsi_id],
                ['kota_id', $kota_id],
                ['kecamatan_id', $kecamatan_id],
            ])->first();
            if($kecamatan_id == 0 || !$query){
                $kecamatan = '-';
            }else{
                $kecamatan = ucwords(strtolower($query->name));
                $this->listKecamatan[$kota_id.'-'.$kecamatan_id] = $kecamatan;
            }
        }
        return $kecamatan;
    }

    public function getNamaDesa($provinsi_id, $kota_id, $kecamatan_id, $kelurahan_id)
    {
        $kelurahan = '-';
        $listKelurahan = $this->listKelurahan;
        if (isset($listKelurahan[$kota_id.'-'.$kecamatan_id.'-'.$kelurahan_id])) {
            $kelurahan = $listKelurahan[$kota_id.'-'.$kecamatan_id.'-'.$kelurahan_id];
        }else{
            
            $query = $this->model->where([
                ['provinsi_id', $provinsi_id],
                ['kota_id', $kota_id],
                ['kecamatan_id', $kecamatan_id],
                ['kelurahan_id', $kelurahan_id],
            ])->first();
            
            if($kelurahan_id == 0 || !$query){
                $kelurahan = '-';
            }else{
                $kelurahan = ucwords(strtolower($query->name));
                $this->listKelurahan[$kota_id.'-'.$kecamatan_id.'-'.$kelurahan_id] = $kelurahan;
            }
        }
        return $kelurahan;
    }

    public function listKota()
    {
        $param['provinsi_id'] = 32; 
        $param['kecamatan_id'] = 0; 
        $param['kelurahan_id'] = 0; 
        $this->params = $param;
        $model = $this->withParameter($this->model);
        return $model->get();
    }

    public function listKecamatan($kota_id)
    {
        $param['provinsi_id'] = 32;
        $param['kota_id'] = $kota_id;
        $param['kelurahan_id'] = 0; 
        $this->params = $param;
        $model = $this->withParameter($this->model);
        return $model->get();
    }

    public function listDesa($kota_id, $kecamatan_id)
    {
        $param['provinsi_id'] = 32;
        $param['kota_id'] = $kota_id;
        $param['kecamatan_id'] = $kecamatan_id;
        $this->params = $param;
        $model = $this->withParameter($this->model);
        return $model->get();
    }

    public function create($data)
    {
        $data = $this->generateAreaCode($data);
        return $this->save($data);
    }

    public function edit($id, $data = [])
    {
        $data = $this->generateAreaCode($data, 'edit');
        return $this->update($id, $data);
    }

    public function generateAreaCode($data, $mode = 'add')
    {
        $res = [
            'area_code' => '',
            'provinsi_id' => 32,
            'kota_id' => 0,
            'kecamatan_id' => 0,
            'kelurahan_id' => 0,
            'name' => $data['name'],
            'koordinat' => $data['koordinat']
        ];
        if($mode == 'add'){
            if($data['level'] == 'kota'){
                $kota = $this->model->where([
                    ['provinsi_id', '=', $data['provinsi_id']],
                    ['kota_id', '!=', 0],
                    ['kecamatan_id', '=', 0]
                ])->select('kota_id')->orderBy('kota_id', 'DESC')->first();
                $res['kota_id'] = $kota->kota_id + 1;
            }elseif($data['level'] == 'kecamatan'){
                $res['kota_id'] = $data['kota_id'];
                $kecamatan = $this->model->where([
                    ['provinsi_id', '=', $data['provinsi_id']],
                    ['kota_id', '=', $data['kota_id']],
                    ['kecamatan_id', '!=', 0],
                    ['kelurahan_id', '=', 0]
                ])->select('kecamatan_id')->orderBy('kecamatan_id', 'DESC')->first();
                $res['kecamatan_id'] = $kecamatan->kecamatan_id + 1;
            }else{
                $res['kota_id'] = $data['kota_id'];
                $res['kecamatan_id'] = $data['kecamatan_id'];
                $kelurahan = $this->model->where([
                    ['provinsi_id', '=', $data['provinsi_id']],
                    ['kota_id', '=', $data['kota_id']],
                    ['kecamatan_id', '=', $data['kecamatan_id']],
                    ['kelurahan_id', '!=', 0]
                ])->select('kelurahan_id')->orderBy('kelurahan_id', 'DESC')->first();
                $res['kelurahan_id'] = $kelurahan->kelurahan_id + 1;
            }
        }else{
            $res['kota_id'] = $data['kota_id'];
            $res['kecamatan_id'] = $data['kecamatan_id'];
            $res['kelurahan_id'] = $data['kelurahan_id'];
        }

        $res['area_code'] = '32.' . sprintf('%02d', $res['kota_id']) . '.' . sprintf('%02d', $res['kecamatan_id']) . '.' . sprintf('%04d', $res['kelurahan_id']);
        return $res;
    }

    public function delete($id)
    {
        $data = $this->getOne($id);
        if (!$data) {
            $this->error = 'Data tidak ditemukan.';
            return false;
        }

        if ($data->kecamatan_id == 0) {
            if(DB::table('data_luas')->where('kota_id', $data->kota_id)->count() > 0){
                $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                return false;
            }
    
            if(DB::table('baku_lahan')->where('kota_id', $data->kota_id)->count() > 0){
                $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                return false;
            }
    
            if(DB::table('baku_lahan_detail')->where('kota_id', $data->kota_id)->count() > 0){
                $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                return false;
            }
    
            if(DB::table('opsin')->where('kota_id', $data->kota_id)->count() > 0){
                $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                return false;
            }
    
            if(DB::table('opsin_detail')->where('kota_id', $data->kota_id)->count() > 0){
                $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                return false;
            }
    
            if(DB::table('htp')->where('kota_id', $data->kota_id)->count() > 0){
                $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                return false;
            }
            if(DB::table('wilayah_kerja')->where('kota_id', $data->kota_id)->count() > 0){
                $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                return false;
            }
    
            if(DB::table('penyuluh')->where('kota_id', $data->kota_id)->count() > 0){
                $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                return false;
            }
            $this->model->where('provinsi_id', 32)->where('kota_id', $data['kota_id'])->delete(); 
        }else{
            if ($data->kelurahan_id == 0) {
                if(DB::table('data_luas')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('baku_lahan')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('baku_lahan_detail')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('opsin')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('opsin_detail')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('htp')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
                if(DB::table('wilayah_kerja')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('penyuluh')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
                $this->model->where('provinsi_id', 32)->where('kota_id', $data['kota_id'])->where('kecamatan_id', $data->kecamatan_id)->delete();
            }else{
                if(DB::table('data_luas')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->where('kelurahan_id', $data['kelurahan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('baku_lahan')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->where('kelurahan_id', $data['kelurahan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('baku_lahan_detail')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->where('kelurahan_id', $data['kelurahan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('opsin')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->where('kelurahan_id', $data['kelurahan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('opsin_detail')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->where('kelurahan_id', $data['kelurahan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('htp')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->where('kelurahan_id', $data['kelurahan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
                if(DB::table('wilayah_kerja')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->where('kelurahan_id', $data['kelurahan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
        
                if(DB::table('penyuluh')->where('kota_id', $data->kota_id)->where('kecamatan_id', $data['kecamatan_id'])->where('kelurahan_id', $data['kelurahan_id'])->count() > 0){
                    $this->error = 'Data tidak dapat dihapus karena sudah digunakan.';
                    return false;
                }
            }
        }

        return $data->delete();
    }
}