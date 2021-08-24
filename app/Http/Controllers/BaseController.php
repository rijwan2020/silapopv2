<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $code = 200;

    protected $output = [];

    protected $repo;

    public function done()
    {
        return response()->json(
            $this->output, $this->code
        );
    }

    public function show($id)
    {
        $data = $this->output['data'] = $this->repo->getOne($id);
        if (!$data) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }
    
    public function update(Request $request, $id)
    {
        $data = $this->validasi($request);
        $data['update_time'] = Carbon::now();
        if($request->has('user_id')){
            $data['update_by'] = $data['user_id'];
            unset($data['user_id']);
        }
        
        $update = $this->repo->edit($id, $data);

        $this->output['message'] = 'Data berhasil diperbaharui.';
        $this->output['data'] = $update;
        // jika gagal
        if(!$update){
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }
    
    public function destroy($id)
    {
        $this->output['message'] = 'Data berhasil dihapus.';
        if (!$this->repo->delete($id)) {
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }
    
    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['insert_time'] = $data['update_time'] = Carbon::now();
        if ($request->has('user_id')) {
            $data['insert_by'] = $data['update_by'] = $data['user_id'];
            unset($data['user_id']);
        }

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
    
    public function verifikasi(Request $request, $id)
    {
        $data = $request->validate([
            'vrf_kota' => 'nullable',
            'vrf_kec' => 'nullable',
            'user_id' => 'required'
        ]);
        $data['update_time'] = Carbon::now();
        if($request->has('user_id')){
            $data['update_by'] = $data['user_id'];
            unset($data['user_id']);
        }
        $update = $this->repo->verifikasi($id, $data);

        $this->output['message'] = 'Data verifikasi berhasil diperbaharui.';
        $this->output['data'] = $update;
        // jika gagal
        if(!$update){
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }

    public function verifikasiAll(Request $request)
    {
        $data = $request->validate([
            'vrf_kota' => 'nullable',
            'vrf_kec' => 'nullable',
            'params' => 'nullable',
            'user_id' => 'required'
        ]);
        $data['update_time'] = Carbon::now();
        if($request->has('user_id')){
            $data['update_by'] = $data['user_id'];
            unset($data['user_id']);
        }
        $update = $this->repo->verifikasiAll($data);

        $this->output['message'] = 'Data verifikasi berhasil diperbaharui.';
        $this->output['data'] = $update;
        // jika gagal
        if(!$update){
            $this->output['message'] = $this->repo->error;
            $this->code = 400;
        }
        return $this->done();
    }

    public function download(Request $request)
    {
        $this->repo->download($request);
        return $this->done();
    }
}
