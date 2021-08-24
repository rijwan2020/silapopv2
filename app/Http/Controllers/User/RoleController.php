<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use App\Repositories\UserRoleRepository;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    public function __construct(UserRoleRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function validasi(Request $request)
    {
        return $request->validate([
            'name' => 'required',
            'level' => 'required',
            'keterangan' => 'nullable',
            'rule' => 'required',
            'user_id' => 'required'
        ]);
    }

    public function index(Request $request)
    {
        $data = $this->repo->list($request);
        $this->output['data'] = $data;
        $this->output['total'] = $this->repo->total($request);
        return $this->done();
    }

    public function acl()
    {
        $id = $_GET['id'] ?? '';
        $this->output['data'] = $this->repo->acl($id);
        return $this->done();
    }
}
