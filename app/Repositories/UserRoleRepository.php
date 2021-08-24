<?php
namespace App\Repositories;

use App\Model\UserRole;
use Illuminate\Support\Facades\DB;

class UserRoleRepository extends BaseRepository
{
    public function __construct(UserRole $model)
    {
        $this->model = $model;
        // $this->relation = ['user'];
    }

    public function create($request)
    {
        $this->params = [
            'level' => $request['level'],
        ];
        $query = $this->withParameter($this->model)->first();
        if($query){
            $this->error = 'No level sudah digunakan';
            return false;
        }

        return $this->save($request);
    }
    
    public function edit($id, $data = [])
    {
        unset($data['update_time']);
        $this->params = [
            'level' => $data['level'],
        ];
        $query = $this->withParameter($this->model)->first();
        if($query && $query->id != $id){
            $this->error = 'No level sudah digunakan';
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
        // cek apakah data sudah digunakan atau belum
        if(DB::table('user')->where('level', $model->level)->count() > 0){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }

        return $model->delete();
    }

    public function acl($id = '')
    {
        $config = config('rules');

        $acl = [];
        // Master
        foreach ($config['master'] as $key => $value) {
            $acl[] = $value;
        }
        // Data
        foreach ($config['data'] as $key => $value) {
            $acl[] = $value;
        }
        // Data
        foreach ($config['laporan'] as $key => $value) {
            $acl[] = $value;
        }
        // Pengumuman
        foreach ($config['pengumuman'] as $key => $value) {
            $acl[] = $value;
        }
        // Syarat dan Ketentuan
        foreach ($config['syarat_ketentuan'] as $key => $value) {
            $acl[] = $value;
        }
        // Kebijakan Privasi
        foreach ($config['kebijakan_privasi'] as $key => $value) {
            $acl[] = $value;
        }
        // Download
        foreach ($config['download'] as $key => $value) {
            $acl[] = $value;
        }
        // User
        foreach ($config['user'] as $key => $value) {
            $acl[] = $value;
        }

        if (!empty($id)) {
            $role = $this->getOne($id)->rule;
            foreach ($acl as $key => $value) {
                $index = array_search($value['name'], array_column($role, 'name'));
                $rule = $role[$index];
                if($rule['name'] == $value['name']){
                    $acl[$key] = $rule;
                }
            }
        }
        return $acl;
    }
}