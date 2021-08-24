<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BaseRepository
{
    public  $model;
    protected $sort = 'ASC';
    protected $order = 'id';
    protected $relation = [];
    protected $params = [];
    public $error = '';
    public $last_id = 0;
    protected $q = '';
    protected $search_field = [];

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function list($request = [])
    {
        if(isset($request['order_by']) && $request['order_by'] != 'false'){
            $this->order = $request['order_by'];
        }
        if(isset($request['sort_desc']) && $request['sort_desc'] != 'false'){
            $this->sort = 'DESC';
        }

        if (isset($request['q'])) {
            $this->q = $request['q'];
        }

        if (isset($request['params'])) {
            if (is_array($request['params'])) {
                $this->params = $request['params'];
            }else{
                $this->params = json_decode($request['params']);
            }
        }

        $model = $this->withParameter($this->model);

        $model = $this->cari($model);
        
        $this->withRelation($model);

        $model = $model->orderBy($this->order, $this->sort);

        if (isset($request['limit'])) {
            $res = $model->paginate($request['limit']);
        }else{
            $res = $model->get();
        }
        return $res;
    }

    public function withRelation($model)
    {
        if (empty($this->relation)) {
            return $model;
        }
        return $model->with($this->relation);
    }

    public function cari($model)
    {
        if ($this->q != "") {
            if (!empty($this->search_field)) {
                $q = $this->q;
                foreach ($this->search_field as $key => $value) {
                    $model->orWhere($value, "like", "%$q%");
                }
            }
        }
        return $model;
    }

    public function withParameter($model)
    {
        if (empty($this->params)) {
            return $model;
        }
        $where = [];
        foreach ($this->params as $key => $value) {
            $where[] = [$key, '=', $value];
            
        }
        $model = $model->where($where);
        
        return $model;
    }

    public function total($request = [])
    {
        if (isset($request['params'])) {
            if (is_array($request['params'])) {
                $this->params = $request['params'];
            }else{
                $this->params = json_decode($request['params']);
            }
        }
        $query = $this->withParameter($this->model);
        return $query->count();
    }

    public function getOne($request)
    {
        $model = $this->withParameter($this->model);
        
        $model = $this->withRelation($model);

        if(is_array($request)){
            return $model->where($request[0], $request[1])->first();
        }
        
        return $model->find($request);
    }
    
    public function create($request)
    {   
        return $this->save($request);
    }

    public function save($request)
    {
        DB::beginTransaction();
        try {
            $create = $this->model->create($request);
            DB::commit();
            $this->last_id = $create->id;
            return $create;
        } catch (\Exception $e) {
            Log::info($e);
            $this->error = $e->getMessage();
            DB::rollBack();
            return false;
        }
    }

    public function file($request, $folder = '')
    {
        // if ($_FILES['file']['size'] > 2097152) {
        //     $this->error = 'File maksimal 2mb';
        //     return false;
        // }
        $filename = date('YmdHis-') . $_FILES['file']['name'];
        $request->file('file')->storeAs($folder, $filename);
        $model = $this->getOne($request->id);
        if (!empty($model->file)) {
            if (Storage::exists($folder.'/'.$model->file)) {
                Storage::delete($folder.'/'. $model->file);
            }
        }
        return $model->update(['file' => $filename]);
    }

    public function edit($id, $data = [])
    {
        return $this->update($id, $data);
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
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            Log::info($e);
            $this->error = $e->getMessage();
            DB::rollBack();
            return false;
        }
    }

    public function deleteFile($file, $folder = '')
    {
        if (Storage::exists($folder.'/'.$file)) {
            Storage::delete($folder. '/'. $file);
        }
        return true;
    }
    
    public function verifikasi($id, $data = [])
    {
        return $this->update($id, $data);
    }
    
    protected function sumByKota($params = [], $field = 'luas', $groupBy = "id_group_tanggal")
    {
        $this->params = $params;
        $query = $this->withParameter($this->model);

        $res = DB::raw("
            kota_id, 
            kecamatan_id, 
            kelurahan_id, 
            hari, 
            bulan, 
            tahun, 
            tanggal, 
            SUM($field) AS 'jumlah', 
            CONCAT(kota_id,\"-\",tanggal) AS 'id_group_tanggal',
            CONCAT(kota_id,\"-\",tahun,\"-\",bulan) AS 'id_group_bulan'
        ");
        $data = $query->select($res)->groupBy($groupBy)->orderBy("tanggal", "ASC")->get();
        return $data->toArray();
    }

    protected function sumByKecamatan($params = [], $field = 'luas', $groupBy = "id_group_tanggal")
    {
        $this->params = $params;
        $query = $this->withParameter($this->model);
        $res = DB::raw("
            kota_id, 
            kecamatan_id, 
            kelurahan_id, 
            hari, 
            bulan, 
            tahun, 
            tanggal, 
            SUM($field) AS 'jumlah', 
            CONCAT(kota_id,\"-\",kecamatan_id,\"-\",tanggal) AS 'id_group_tanggal',
            CONCAT(kota_id,\"-\",kecamatan_id,\"-\",tahun,\"-\",bulan) AS 'id_group_bulan'
        ");
        $data = $query->select($res)->groupBy($groupBy)->orderBy("tanggal", "ASC")->get();
        return $data->toArray();
    }

    protected function sumByKelurahan($params = [], $field = 'luas', $groupBy = "id_group_tanggal")
    {
        $this->params = $params;
        $query = $this->withParameter($this->model);
        $res = DB::raw("
            kota_id, 
            kecamatan_id, 
            kelurahan_id, 
            hari, 
            bulan, 
            tahun, 
            tanggal, 
            SUM($field) AS 'jumlah', 
            CONCAT(kota_id,\"-\",kecamatan_id,\"-\",kelurahan_id,\"-\",tanggal) AS 'id_group_tanggal',
            CONCAT(kota_id,\"-\",kecamatan_id,\"-\",kelurahan_id,\"-\",tahun,\"-\",bulan) AS 'id_group_bulan'
        ");
        $data = $query->select($res)->groupBy($groupBy)->orderBy("tanggal", "ASC")->get();
        return $data->toArray();
    }

    public function verifikasiAll($data)
    {
        $this->params = $data['params'];
        unset($data['params']);
        $this->withParameter($this->model)->update($data);
        
        return true;
    }
}
