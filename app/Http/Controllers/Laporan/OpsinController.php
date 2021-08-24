<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\BaseController;
use App\Repositories\OpsinDetailRepository;
use App\Repositories\OpsinRepository;
use Illuminate\Http\Request;

class OpsinController extends BaseController
{
    private $detail;
    public function __construct(
        OpsinDetailRepository $detail,
        OpsinRepository $repo)
    {
        $this->repo = $repo;
        $this->detail = $detail;
    }

    public function index(Request $request)
    {
        $this->output = $this->repo->laporan($request);
        return $this->done();
    }

    public function download(Request $request)
    {
        $this->repo->laporanDownload($request);
        return $this->done();
    }
    
    public function realisasi(Request $request)
    {
        $this->output = $this->detail->laporan($request);
        return $this->done();
    }

    
    public function realisasiDownload(Request $request)
    {
        $this->detail->laporanDownload($request);
        return $this->done();
    }
    
    public function realisasiDetail(Request $request)
    {
        $this->output = $this->detail->laporanDetail($request);
        return $this->done();
    }

    
    public function realisasiDetailDownload(Request $request)
    {
        $this->detail->laporanDetailDownload($request);
        return $this->done();
    }
}
