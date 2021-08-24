<?php
namespace App\Repositories;

use App\Jobs\Laporan\BaseDetailJob;
use App\Jobs\Laporan\BaseJob;
use App\Model\Download;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProduktivitasRepository extends BaseRepository
{
    private $area, $komoditi, $produksi, $luaspanen;
    public function __construct(
        AreaRepository $area,
        KomoditasRepository $komoditi,
        ProduksiRepository $produksi,
        LuasPanenRepository $luaspanen
    ){
        $this->komoditi = $komoditi;
        $this->area = $area;
        $this->produksi = $produksi;
        $this->luaspanen = $luaspanen;
    }

    public function data(Request $request)
    {
        $produksi = $this->produksi->data($request);
        $luaspanen = $this->luaspanen->data($request);
        $data = [];
        foreach ($produksi as $key => $value) {
            $data[$key]['nama'] = $value['nama'];
            $data[$key]['kota_id'] = $value['kota_id'];
            $data[$key]['kecamatan_id'] = $value['kecamatan_id'];
            $data[$key]['kelurahan_id'] = $value['kelurahan_id'];
            $bulan = [];
            for ($i=0; $i < 12; $i++) { 
                $data[$key]['bulan'][$i] = $bulan[$i] = 0;
                if ($luaspanen[$key]['bulan'][$i] > 0) {
                    $data[$key]['bulan'][$i] = $bulan[$i] = round( ($value['bulan'][$i] * 10 / $luaspanen[$key]['bulan'][$i]), 2);
                }
            }
            $data[$key]['jumlah'] = collect($bulan)->sum();
        }
        
        return $data;
    }

    public function jumlah(Request $request)
    {
        $produksi = $this->produksi->jumlah($request);
        $luaspanen = $this->luaspanen->jumlah($request);
        $data = [];
        for ($i=1; $i <= 12; $i++) { 
            $data['data'][$i] = 0;
            if ($luaspanen['data'][$i] > 0) {
                $data['data'][$i] = round( $produksi['data'][$i] * 10 / $luaspanen['data'][$i], 2);
            }
        }
        $data['total'] = collect($data['data'])->sum();
        return $data;
    }

    public function download(Request $request)
    {
        $download['data'] = [];
        $data = $this->data($request);
        $i = 1;
        foreach ($data as $key => $value) {
            $download['data'][$key][] = $i;
            $download['data'][$key][] = $value['nama'];
            foreach ($value['bulan'] as $hsl => $hasil) {
                $download['data'][$key][] = $hasil;
            }
            $download['data'][$key][] = $value['jumlah'];
            $i++; 
        }

        $jumlah = $this->jumlah($request);
        $i = $key + 1;
        $download['data'][$i] = [
            'Jumlah',
            '',
        ];
        foreach ($jumlah['data'] as $key => $value) {
            $download['data'][$i][] = $value;
        }
        $download['data'][$i][] = $jumlah['total'];
        
        $download['judul_1'] = 'Laporan Produktivitas Tahun ' . $request->tahun;
        $download['judul_2'] = 'Semua Komoditi';
        if ($request->has('komoditi_id')) {
            $download['judul_2'] = 'Komoditi ' . $this->komoditi->getOne($request->komoditi_id)['nama'];
        }

        if($request->has('jenis')){
            $download['judul_2'] .= $request->jenis == 1 ? ' - Sawah' : ' - Ladang';
        }else{
            $download['judul_2'] .= ' - Sawah dan Ladang';
        }
        $download['judul_4'] = ['#', 'Kota/Kabupaten', 'Bulan [Kw/Ha]', '', '', '', '', '', '', '', '', '', '', '', 'Jumlah [Kw/Ha]'];
        $download['judul_5'] = [
            '',
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
            ''
        ];
        $download['judul_3'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['judul_4'][1] = 'Kecamatan';
            $download['judul_3'] = $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['judul_4'][1] = 'Kelurahan/Desa';
                $download['judul_3'] = 'Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $createOne = Download::create([
            'keterangan' => $download['judul_1'],
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new BaseJob($download, $createOne->id, 'produktivitas'));
        return true;
    }

    public function detailData(Request $request)
    {
        $tahun = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $tahun = $request->tahun;
        }

        $bulan = Carbon::now()->format('m');
        if ($request->has('bulan')) {
            $bulan = $request->bulan;
        }

        $jml_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        $produksi = $this->produksi->detailData($request);
        $luaspanen = $this->luaspanen->detailData($request);
        $data = [
            'data' => [],
            'jml_hari' => $jml_hari
        ];
        foreach ($produksi['data'] as $key => $value) {
            $data['data'][$key]['nama'] = $value['nama'];
            $data['data'][$key]['kota_id'] = $value['kota_id'];
            $data['data'][$key]['kecamatan_id'] = $value['kecamatan_id'];
            $data['data'][$key]['kelurahan_id'] = $value['kelurahan_id'];
            $hari = [];
            for ($i=0; $i < $jml_hari; $i++) { 
                $data['data'][$key]['hari'][$i] = $hari[$i] = 0;
                if ($luaspanen['data'][$key]['hari'][$i] > 0) {
                    $data['data'][$key]['hari'][$i] = $hari[$i] = round( ($value['hari'][$i] * 10 / $luaspanen['data'][$key]['hari'][$i]), 2);
                }
            }
            $data['data'][$key]['jumlah'] = collect($hari)->sum();
        }
        return $data;
    }

    public function detailJumlah(Request $request)
    {
        $tahun = Carbon::now()->format('Y');
        if ($request->has('tahun')) {
            $tahun = $request->tahun;
        }

        $bulan = Carbon::now()->format('m');
        if ($request->has('bulan')) {
            $bulan = $request->bulan;
        }

        $jml_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $produksi = $this->produksi->detailJumlah($request);
        $luaspanen = $this->luaspanen->detailJumlah($request);
        $data = [];
        for ($i=1; $i <= $jml_hari; $i++) {
            $data['data'][$i] = 0;
            if ($luaspanen['data'][$i] > 0) {
                $data['data'][$i] = round( $produksi['data'][$i] * 10 / $luaspanen['data'][$i], 2);
            }
        }
        $data['total'] = collect($data['data'])->sum();
        return $data;
    }

    public function detailDownload(Request $request)
    {
        $download['jumlah_hari'] = cal_days_in_month(CAL_GREGORIAN, $request->bulan, $request->tahun);
        $bulan = [
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $download['data'] = [];
        $data = $this->detailData($request);
        $i = 1;
        foreach ($data['data'] as $key => $value) {
            $download['data'][$key][] = $i;
            $download['data'][$key][] = $value['nama'];
            foreach ($value['hari'] as $hsl => $hasil) {
                $download['data'][$key][] = $hasil;
            }
            $download['data'][$key][] = $value['jumlah'];
            $i++; 
        }

        $jumlah = $this->detailJumlah($request);
        $i = $key + 1;
        $download['data'][$i] = [
            'Jumlah',
            '',
        ];
        foreach ($jumlah['data'] as $key => $value) {
            $download['data'][$i][] = $value;
        }
        $download['data'][$i][] = $jumlah['total'];

        
        $download['judul_1'] = 'Laporan Produktivitas ' . $bulan[$request->bulan] . ' ' .$request->tahun;

        $download['judul_2'] = 'Semua Komoditi';
        if ($request->has('komoditi_id')) {
            $download['judul_2'] = 'Komoditi ' . $this->komoditi->getOne($request->komoditi_id)['nama'];
        }

        if($request->has('jenis')){
            $download['judul_2'] .= $request->jenis == 1 ? ' - Sawah' : ' - Ladang';
        }else{
            $download['judul_2'] .= ' - Sawah dan Ladang';
        }
        
        $download['judul_4'] = ['#', 'Kota/Kabupaten', 'Tanggal [Kw/Ha]'];
        $download['judul_5'] = ['Jumlah [Kw/Ha]', ''];
        for ($i=1; $i <= $download['jumlah_hari']; $i++) { 
            $download['judul_4'][] = '';
            $download['judul_5'][] = $i;
        }
        $download['judul_4'][$i + 1] = 'Jumlah [Kw/Ha]';

        $download['judul_3'] = 'Provinsi Jawa Barat';
        if($request->has('kota_id')){
            $download['judul_4'][1] = 'Kecamatan';
            $download['judul_3'] = $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            if ($request->has('kecamatan_id')) {
                $download['judul_4'][1] = 'Kelurahan/Desa';
                $download['judul_3'] = 'Kecamatan ' . $this->area->getNamaKecamatan(32, $request->kota_id, $request->kecamatan_id) . ', ' . $this->area->getNamaKota(32, $request->kota_id) . ', Provinsi Jawa Barat';
            }
        }

        $createOne = Download::create([
            'keterangan' => $download['judul_1'],
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new BaseDetailJob($download, $createOne->id, 'produktivitas'));
    }
}