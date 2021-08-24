<?php
namespace App\Repositories;

use App\User;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        $this->model = $model;
        $this->relation = ['penyuluh', 'roles'];
        $this->search_field = ['name', 'email', 'username'];
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
            $this->params = json_decode($request['params']);
        }

        $model = $this->withParameter($this->model);

        $model = $this->cari($model);
        
        $this->withRelation($model);
        
        $model = $model->where('level', '!=', 0)->orderBy($this->order, $this->sort);

        if (isset($request['limit'])) {
            $res = $model->paginate($request['limit']);
        }else{
            $res = $model->get();
        }
        return $res;
    }

    public function create($request)
    {
        if (isset($request['email'])) {
            if ($this->model->where('email', $request['email'])->first()) {
                $this->error = 'Email sudah digunakan.';
                return false;
            }
        }
        if ($this->model->where('username', $request['username'])->first()) {
            $this->error = 'Username sudah digunakan.';
            return false;
        }
        
        $password = md5($request['password']);
        $request['imprintingcode'] = base64_encode($request['username'].'.'.$request['level'].'.'.$password);
        $request['created_date'] = date('YmdHis');
        
        return $this->save($request);
    }

    public function edit($id, $data = [])
    {
        $user = $this->getOne($id);
        $username = $user->username;
        if(isset($data['username'])){
            $username = $data['username'];
            if($this->model->where('username', $data['username'])->where('id', '!=', $id)->first()){
                $this->error = 'Username sudah digunakan.';
                return false;
            }
        }
        if(isset($data['email'])){
            if($this->model->where('email', $data['email'])->where('id', '!=', $id)->first()){
                $this->error = 'Email sudah digunakan.';
                return false;
            }
        }
        $imprinting = base64_decode($user->imprintingcode);
        $imprinting = explode('.', $imprinting);
        $password = $imprinting[2];

        if (isset($data['password']) && !empty($data['password'])) {
            $password = md5($data['password']);
        }
        $level = $user->level;
        if(isset($data['level'])){
            $level = $data['level'];
        }
        $data['imprintingcode'] = base64_encode($username.'.'.$level.'.'.$password);

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
        if(DB::table('penyuluh')->where('user_id', $id)->count() > 0){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }

        return $model->delete();
    }
}