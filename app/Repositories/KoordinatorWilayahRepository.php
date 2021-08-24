<?php
namespace App\Repositories;

use App\Model\KoordinatorWilayah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KoordinatorWilayahRepository extends BaseRepository
{
    public function __construct(KoordinatorWilayah $model, UserRepository $user)
    {
        $this->model = $model;
        $this->user = $user;
        $this->relation = ['user'];
        $this->search_field = ['nama'];
    }

    public function create($data)
    {
        if($data['password'] != $data['konfirmasi_password']){
            $this->error = 'Konfirmasi password tidak sesuai.';
            return false;
        }
        $user = [
            'name' => $data['nama'],
            'username' => $data['username'],
            'level' => $data['level'] == 1 ? 96 : 95,
            'password' => $data['password'],
            'active' => 1,
            'created_by' => $data['insert_by']
        ];
        if (!$this->user->create($user)) {
            $this->error = $this->user->error;
            return false;
        }

        $data['user_id'] = $this->user->last_id;
        unset($data['username'], $data['password'], $data['konfirmasi_password']);
        $save = $this->save($data);
        if (!$save) {
            $this->user->delete($data['user_id']);
        } 
        return $save;
    }

    public function update($id, $data = [])
    {
        $username = '';
        if(isset($data['username'])){
            $username = $data['username'];
        }
        $password = '';
        if(isset($data['password'])){
            $password = $data['password'];
        }

        unset($data['username'], $data['password'], $data['konfirmasi_password']);

        DB::beginTransaction();
        try {
            $model = $this->getOne($id);
            if ($model == false) {
                throw new \Exception("Data tidak ditemukan.");
            }
            $model->update($data);
            $user = [
                'username' => $username,
                'password' => $password,
                'name' => $model->nama,
                'level' => $model->level == 1 ? 96 : 95,
                'updated_at' => $model->update_time
            ];
            if($this->user->edit($model->user_id, $user) == false){
                $error = $this->user->error;
                throw new \Exception($error);
            }
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            Log::info($e);
            $this->error = $e->getMessage();
            DB::rollBack();
            return false;
        }
    }
    
    public function delete($id)
    {
        $model = $this->getOne($id);
        if (!$model) {
            $this->error = 'Data tidak ditemukan.';
            return false;
        }
        // cek apakah data sudah digunakan atau belum
        $this->user->delete($model->user_id);

        return $model->delete();
    }
}