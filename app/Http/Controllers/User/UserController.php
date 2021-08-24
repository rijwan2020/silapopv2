<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends BaseController
{
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function validasi(Request $request)
    {
        return $request->validate([
            'name' => 'required',
            'username' => 'required',
            'level' => 'required',
            'email' => 'nullable|email',
            'user_id' => 'required',
            'password' => 'nullable',
            'active' => 'required'
        ]);
    }

    public function index(Request $request)
    {
        $data = $this->repo->list($request);
        $this->output['data'] = $data;
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }

    public function show($id)
    {
        $data = $this->output['data'] = $this->repo->getOne($id);
        if (!$data) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        $this->output['data']['image_url'] = url('img/avatars/avatar.png');
        if($data->level == 49 || $data->level == 50){
            $file = Storage::exists('/profile/' . $data->penyuluh->id. '/image/' . $data->penyuluh->image);
            if($file){
                $this->output['data']['image_url'] = Storage::url('/profile/' . $data->penyuluh->id. '/image/' . $data->penyuluh->image);
            }
        }
        return $this->done();
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['created_by'] = $data['user_id'];
        unset($data['user_id']);

        $create = $this->repo->create($data);

        $this->output['message'] = 'Data berhasil ditambahkan.';
        $this->output['data'] = $create;
        // jika gagal
        if(!$create){
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }
}
