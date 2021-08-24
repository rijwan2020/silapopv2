<?php

namespace App\Http\Controllers;

use App\Repositories\PengumumanRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends BaseController
{
    public function __construct(PengumumanRepository $repo)
    {
        $this->repo = $repo;
    }

    public function validasi(Request $request)
    {
        return $request->validate([
            'pesan' => 'required',
            'subjek' => 'required',
            'status' => 'required',
            'user_id' => 'required',
        ]);
    }

    public function index(Request $request)
    {
        $data = $this->repo->list($request);
        $res = [];
        foreach($data as $key => $value){
            $res[] = $value;
            $res[$key]['pengirim'] = $value->user->name . ' ('. ($value->user->roles->name ?? 'Developer') .')';
            $res[$key]['waktu'] = $value->created_at->diffForHumans();
            $res[$key]['waktu_lengkap'] = $value->created_at->format("Y-m-d H:i");
            $res[$key]['file'] = null;
            if($value->attachment){
                if(Storage::exists('/pengumuman/'.$value->attachment)){
                    $res[$key]['file'] = Storage::url('/pengumuman/'.$value->attachment);
                }
            }
        }
        $this->output['data'] = $res;
        $this->output['total'] = $this->repo->total($request);
        $this->output['curPage'] = $request->page ?? 1;
        return $this->done();
    }

    public function file(Request $request)
    {
        return $this->repo->file($request, 'pengumuman');
    }
}
