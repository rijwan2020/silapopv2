<?php

namespace App\Http\Controllers;

use App\Repositories\ConfigRepository;
use Illuminate\Http\Request;

class ConfigController extends BaseController
{
    public function __construct(ConfigRepository $repo)
    {
        $this->repo = $repo;
    }

    public function validasi(Request $request)
    {
        return $request->validate([
            'title' => 'required',
            'konten' => 'required',
        ]);
    }
}
