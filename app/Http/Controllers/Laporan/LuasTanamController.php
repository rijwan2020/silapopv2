<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Repositories\LuasTanamRepository;

class LuasTanamController extends BaseController
{
    public function __construct(LuasTanamRepository $repo)
    {
        $this->repo = $repo;
    }

    protected function index(Request $request)
    {
        $this->output['data'] = $this->repo->data($request);
        $this->output['jumlah'] = $this->repo->jumlah($request);
        return $this->done();
    }

    protected function download(Request $request)
    {
        $this->repo->download($request);
        return $this->done();
    }

    protected function detail(Request $request)
    {
        $this->output['data'] = $this->repo->detailData($request);
        $this->output['jumlah'] = $this->repo->detailJumlah($request);
        return $this->done();
        
    }

    protected function detailDownload(Request $request)
    {
        $this->repo->detailDownload($request);
        return $this->done();
    }

    protected function saatini(Request $request)
    {
        $data = $this->repo->saatini($request);
        $this->output['data'] = $data['data'];
        $this->output['jumlah'] = $data['jumlah'];
        return $this->done();
        
    }

    protected function saatiniDownload(Request $request)
    {
        $this->repo->saatiniDownload($request);
        return $this->done();
    }
}
