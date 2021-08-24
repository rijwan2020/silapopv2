<?php
namespace App\Repositories;

use App\Model\Pengumuman;
use Illuminate\Support\Facades\Storage;

class PengumumanRepository extends BaseRepository
{
    public function __construct(Pengumuman $model)
    {
        $this->model = $model;
        $this->relation = ['user'];
    }

    public function create($request)
    {
        $data = [
            'subjek' => $request['subjek'],
            'pesan' => $request['pesan'],
            'status' => $request['status'],
            'created_by' => $request['insert_by'],
        ];        
        return $this->save($data);
    }

    public function edit($id, $data = [])
    {
        unset($data['update_by'], $data['update_time']);
        return $this->update($id, $data);
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
        return $model->update(['attachment' => $filename]);
    }

    public function delete($id)
    {
        $model = $this->getOne($id);
        if (!$model) {
            $this->error = 'Data tidak ditemukan.';
            return false;
        }
        if (!empty($model->attachment)) {
            $this->deleteFile($model->attachment, 'pengumuman');
        }
        return $model->delete();
    }
    
}