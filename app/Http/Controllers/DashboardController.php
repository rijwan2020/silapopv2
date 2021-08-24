<?php

namespace App\Http\Controllers;

use App\Repositories\BakulahanRepository;
use App\Repositories\DataLuasRepository;
use App\Repositories\PenyuluhRepository;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    private $penyuluh, $bakulahan, $dataluas;
    public function __construct(PenyuluhRepository $penyuluh, BakulahanRepository $bakulahan, DataLuasRepository $dataluas)
    {
        $this->penyuluh = $penyuluh;
        $this->bakulahan = $bakulahan;
        $this->dataluas = $dataluas;
    }

    public function statistikPenyuluh(Request $request)
    {
        $res['total'] = $this->penyuluh->total();
        $res['total_ppl'] = $this->penyuluh->total(['params' => ['jp' => 0]]); // PPL
        $res['total_popt'] = $this->penyuluh->total(['params' => ['jp' => 1]]); // POPPT
        $res['total_pns'] = $this->penyuluh->total(['params' => ['status_penyuluh' => 1]]); // PNS
        $res['total_tbpp'] = $this->penyuluh->total(['params' => ['status_penyuluh' => 2]]); // TBPP
        $res['total_tbppd'] = $this->penyuluh->total(['params' => ['status_penyuluh' => 3]]); // TBPPD
        $res['total_thl_popt'] = $this->penyuluh->total(['params' => ['status_penyuluh' => 4]]); // THL POPT
        $res['total_pppk'] = $this->penyuluh->total(['params' => ['status_penyuluh' => 5]]); // PPPK
        $this->output['data'] = $res;
        return $this->done();
    }

    public function statistikSawahLadang(Request $request)
    {
        $res[0] = [
            'nama' => 'Sawah',
            'bakulahan' => $this->bakulahan->sumLuas(['jenis' => 1]),
            'luas_tanam' => $this->dataluas->sumLuasTanam(['params' => ['jenis' => 1]]),
            'luas_panen' => $this->dataluas->sumLuasPanen(['params' => ['jenis' => 1]]),
            'tambah_tanam' => $this->dataluas->sumTambahTanam(['params' => ['jenis' => 1]]),
            'produksi' => $this->dataluas->sumProduksi(['params' => ['jenis' => 1]]),
        ];
        $res[0]['produktivitas'] = $res[0]['luas_panen'] > 0 ? round(($res[0]['produksi'] * 10 / $res[0]['luas_panen']), 2) : 0;

        $res[1] = [
            'nama' => 'Ladang',
            'bakulahan' => $this->bakulahan->sumLuas(['jenis' => 2]),
            'luas_tanam' => $this->dataluas->sumLuasTanam(['params' => ['jenis' => 2]]),
            'luas_panen' => $this->dataluas->sumLuasPanen(['params' => ['jenis' => 2]]),
            'tambah_tanam' => $this->dataluas->sumTambahTanam(['params' => ['jenis' => 2]]),
            'produksi' => $this->dataluas->sumProduksi(['params' => ['jenis' => 2]]),
        ];
        $res[1]['produktivitas'] = $res[1]['luas_panen'] > 0 ? round(($res[1]['produksi'] * 10 / $res[1]['luas_panen']), 2) : 0;
        $this->output['data'] = $res;
        return $this->done();
    }
}
