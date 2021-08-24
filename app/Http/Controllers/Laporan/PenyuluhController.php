<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\BaseController;
use App\Repositories\PenyuluhRepository;
use Illuminate\Http\Request;

class PenyuluhController extends BaseController
{
    public function __construct(PenyuluhRepository $repo)
    {
        $this->repo = $repo;
    }

    public function rekapInput(Request $request)
    {
        $data = $this->repo->rekapInput($request);
        $this->output = $data;
        return $this->done();
    }

    public function rekapInputDownload(Request $request)
    {
        $this->repo->rekapInputDownload($request);
        return $this->done();
    }

    public function rekapUser(Request $request)
    {
        $data = $this->repo->rekapUser($request);
        $this->output = $data;
        return $this->done();
    }

    public function rekapUserDownload(Request $request)
    {
        $this->repo->rekapUserDownload($request);
        return $this->done();
    }
}
