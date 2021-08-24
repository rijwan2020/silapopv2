<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\BaseController;
use App\Repositories\TambahTanamRepository;
use Illuminate\Http\Request;

class TambahTanamController extends BaseController
{
    public function __construct(TambahTanamRepository $repo)
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
}
