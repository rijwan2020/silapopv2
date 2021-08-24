<?php
namespace App\Repositories;

use App\Jobs\Data\BaseJob;
use App\Jobs\Laporan\RekapInputPenyuluhJob;
use App\Jobs\Laporan\RekapUserPenyuluhJob;
use App\Model\Bakulahan;
use App\Model\BakulahanDetail;
use App\Model\DataLuas;
use App\Model\Download;
use App\Model\Htp;
use App\Model\Opsin;
use App\Model\OpsinDetail;
use App\Model\Penyuluh;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PenyuluhRepository extends BaseRepository
{
    private $user, $area, $bakulahan, $bakulahanDetail, $dataluas, $htp, $opsin, $opsinDetail;
    public function __construct(
        Penyuluh $penyuluh,
        AreaRepository $area, 
        Bakulahan $bakulahan,
        BakulahanDetail $bakulahanDetail,
        DataLuas $dataluas,
        Htp $htp,
        Opsin $opsin,
        OpsinDetail $opsinDetail,
        UserRepository $user)
    {
        $this->model = $penyuluh;
        $this->relation = ['wilayahkerja', 'user', 'kota', 'kecamatan', 'desa'];
        $this->search_field = ['nama', 'email', 'alamat', 'tempat_lahir'];

        $this->user = $user;
        $this->area = $area;
        $this->bakulahan = $bakulahan;
        $this->bakulahanDetail = $bakulahanDetail;
        $this->dataluas = $dataluas;
        $this->htp = $htp;
        $this->opsin = $opsin;
        $this->opsinDetail = $opsinDetail;
    }

    public function data(Request $request)
    {
        $this->relation = ["user"];
        $data = $this->list($request);
        $res = [];
        $listKota = $listKecamatan = $listKelurahan = [];
        foreach($data as $key => $value){
            $res[$key] = $value->toArray();

            $keyKota = $value->kota_id;
            if(!isset($listKota[$keyKota])){
                $listKota[$keyKota] = $this->area->getNamaKota(32, $value->kota_id);
            }
            $res[$key]['kota'] = $listKota[$value->kota_id];

            $keyKecamatan = $value->kota_id . '-' . $value->kecamatan_id;
            if(!isset($listKecamatan[$keyKecamatan])){
                $listKecamatan[$keyKecamatan] = $this->area->getNamaKecamatan(32, $value->kota_id, $value->kecamatan_id);
            }
            $res[$key]['kecamatan'] = $listKecamatan[$keyKecamatan];

            $keyKelurahan = $value->kota_id . '-' . $value->kecamatan_id . '-' . $value->kelurahan_id; 
            if (!isset($listKelurahan[$keyKelurahan])) {
                $listKelurahan[$keyKelurahan] = $this->area->getNamaDesa(32, $value->kota_id, $value->kecamatan_id, $value->kelurahan_id);
            }
            $res[$key]['desa'] = $listKelurahan[$keyKelurahan];

            $tempat_tugas = '';
            if ($value->tempat_tugas_kota_id != null && $value->tempat_tugas_kota_id != 0) {
                $keyKota = $value->tempat_tugas_kota_id;
                if(!isset($listKota[$keyKota])){
                    $listKota[$keyKota] = $this->area->getNamaKota(32, $value->tempat_tugas_kota_id);
                }
                $tempat_tugas = $listKota[$keyKota];
                if ($value->tempat_tugas_kecamatan_id != null && $value->tempat_tugas_kecamatan_id != 0) {
                    $keyKecamatan = $value->tempat_tugas_kota_id . '-' . $value->tempat_tugas_kecamatan_id;
                    if (!isset($listKecamatan[$keyKecamatan])) {
                        $listKecamatan[$keyKecamatan] = $this->area->getNamaKecamatan(32, $value->tempat_tugas_kota_id, $value->tempat_tugas_kecamatan_id);
                    }
                    $tempat_tugas = 'Kecamatan '. $listKecamatan[$keyKecamatan] . ', ' . $tempat_tugas;
                    if ($value->tempat_tugas_kelurahan_id != null && $value->tempat_tugas_kelurahan_id != 0) {
                        $keyKelurahan = $value->tempat_tugas_kota_id . '-' . $value->tempat_tugas_kecamatan_id . '-' . $value->tempat_tugas_kelurahan_id;
                        if(!isset($listKelurahan[$keyKelurahan])){
                            $listKelurahan[$keyKelurahan] = $this->area->getNamaDesa(32, $value->tempat_tugas_kota_id, $value->tempat_tugas_kecamatan_id, $value->tempat_tugas_kelurahan_id);
                        }
                        $tempat_tugas = 'Desa ' . $listKelurahan[$keyKelurahan] . ', ' . $tempat_tugas;
                    }
                }
            }
            $res[$key]['tempat_tugas'] = $tempat_tugas;

            $res[$key]['file_url'] = null;
            if($value->file){
                $file = Storage::exists('/htp/'.$value->file);
                if($file){
                    $res[$key]['file_url'] = Storage::url('/htp/'.$value->file);
                }else{
                    $res[$key]['file_url'] = Storage::url('/v1/att_htp/'.$value->file);
                }
            }
            $res[$key]['username'] = $value->user->username ?? '';
        }
        return $res;
    }

    
    public function download(Request $request)
    {
        $createOne = Download::create([
            'keterangan' => "Data Penyuluh",
            'user_id' => $request->user_id ?? 0
        ]);
        $data = $this->data($request);
        $download['judul'] = 'Data Penyuluh';
        $download['header'] = [
            'No',
            'Nama',
            'Username',
            'Jenis Penyuluh',
            'Status Penyuluh',
            'NIP',
            'Alamat',
            'Kota/Kabupaten',
            'Kecamatan',
            'Kelurahan/Desa',
            'Tempat Tugas',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Jabatan Fungsional PP',
            'Pangkat/Golongan',
            'Pendidikan Terakhir',
            'Nama Kelembagaan',
            'Nama BP3K/BPP',
            'Pertanian (WKPP)',
            'Kelompok Tani',
            'Jumlah Kelompok Tani',
            'Komoditi Unggulan',
            'No Hp',
            'Email',
            'Tanggal Evaluasi'
        ];
        $download['last_col'] = 'Z';
        $download['data'] = [];
        $no = 0;
        $status_penyuluh = ['-','PNS','TBPP','TBPPD','THL-POPT','PPPK'];
        foreach ($data as $key => $value) {
            $no++;
            $download['data'][] = [
                $no,
                $value['nama'],
                $value['username'],
                $value['jp'] == 0 ? 'PPL' : 'POPT',
                $status_penyuluh[$value['status_penyuluh']],
                $value['nip'],
                $value['alamat'],
                $value['kota'],
                $value['kecamatan'],
                $value['desa'],
                $value['tempat_tugas'],
                $value['tempat_lahir'],
                $value['tanggal_lahir'],
                $value['jk'] ? "Perempuan" : "Laki - Laki",
                $value['jabatan_fungsional'],
                $value['pangkat'],
                $value['pendidikan'],
                $value['nama_kelembagaan'],
                $value['bp3k'],
                $value['pertanian_wkpp'],
                $value['kelompok_tani'],
                $value['jml_kel_tani'],
                $value['komoditi_unggulan'],
                $value['no_hp'],
                $value['email'],
                $value['tanggal_evaluasi'],
            ];
        }
        $download['last_row'] = $no + 2;

        dispatch(new BaseJob($download, $createOne->id));
    }

    public function getOne($request)
    {
        $model = $this->withParameter($this->model);
        
        $model = $this->withRelation($model);

        if(is_array($request)){
            return $model->where($request[0], $request[1])->first();
        }
        
        return $model->find($request);
    }

    public function edit($id, $data = [])
    {
        $data['user_id'] = $data['penyuluh_user_id'];
        unset($data['penyuluh_user_id']);

        if(isset($data['email']) && $this->model->where('email', $data['email'])->where('id', '!=', $id)->first()){
            $this->error = 'Email sudah digunakan.';
            return false;
        }
        
        if (isset($data['password'])) {
            if($data['password'] != $data['konfirmasi_password']){
                $this->error = 'Konfirmasi password tidak sesuai.';
                return false;
            }
        }        
        return $this->update($id, $data);
    }

    public function update($id, $data = [])
    {
        $username = '';
        if(isset($data['username'])){
            $username = $data['username'];
        }
        $password = '';
        if(isset($data['password'])){
            $password = $data['password'];
        }

        unset($data['username'], $data['password'], $data['konfirmasi_password']);

        DB::beginTransaction();
        try {
            $model = $this->getOne($id);
            if ($model == false) {
                throw new \Exception("Data tidak ditemukan.");
            }
            $model->update($data);
            $user = [
                'username' => $username,
                'password' => $password,
                'name' => $model->nama,
                'email' => $model->email,
                'phone_number' => $model->no_hp,
                'gender' => $model->jk,
                'birth_date' => $model->tanggal_lahir,
                'address' => $model->alamat,
                'updated_at' => $model->update_time
            ];
            if($this->user->edit($model->user_id, $user) == false){
                $error = $this->user->error;
                throw new \Exception($error);
            }
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            Log::info($e);
            $this->error = $e->getMessage();
            DB::rollBack();
            return false;
        }
    }

    public function file($request, $folder = '')
    {
        // if ($_FILES['file']['size'] > 2097152) {
        //     $this->error = 'File maksimal 2mb';
        //     return false;
        // }
        $filename = date('YmdHis-') . $_FILES['file']['name'];
        $request->file('file')->storeAs($folder, $filename);
        $model = $this->getOne($request->id);
        if (!empty($model->image)) {
            if (Storage::exists($folder.'/'.$model->image)) {
                Storage::delete($folder.'/'. $model->image);
            }
        }
        $this->user->update($model->user_id, ['avatar' => $filename]);
        return $model->update(['image' => $filename]);
    }

    public function delete($id)
    {
        $model = $this->getOne($id);
        if (!$model) {
            $this->error = 'Data tidak ditemukan.';
            return false;
        }
        $user = $this->user->getOne($model->user_id);
        if(DB::table('baku_lahan')->where('penyuluh_id', $id)->orWhere('insert_by', $user->id)->count() > 0){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }
        if(DB::table('baku_lahan_detail')->where('penyuluh_id', $id)->orWhere('insert_by', $user->id)->count() > 0){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }
        if(DB::table('htp')->where('penyuluh_id', $id)->orWhere('insert_by', $user->id)->count() > 0){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }
        if(DB::table('opsin')->where('penyuluh_id', $id)->orWhere('insert_by', $user->id)->count() > 0){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }
        if(DB::table('opsin_detail')->where('penyuluh_id', $id)->orWhere('insert_by', $user->id)->count() > 0){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }
        if(DB::table('opt')->where('penyuluh_id', $id)->orWhere('insert_by', $user->id)->count() > 0){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }
        if(DB::table('data_luas')->where('penyuluh_id', $id)->orWhere('created_by', $user->id)->count() > 0){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }

        DB::table('wilayah_kerja')->where('penyuluh_id', $id)->delete();
        $user->delete();
        return $model->delete();
    }

    public function create($data)
    {   
        if(isset($data['email'])){
            if ($this->model->where('email', $data['email'])->first()) {
                $this->error = 'Email sudah digunakan.';
                return false;
            }
        }
        
        if (isset($data['password'])) {
            if($data['password'] != $data['konfirmasi_password']){
                $this->error = 'Konfirmasi password tidak sesuai.';
                return false;
            }
        }

        $user = [
            'name' => $data['nama'],
            'username' => $data['username'],
            'level' => $data['jp'] == 1 ? 49 : 50,
            'email' => $data['email'],
            'password' => $data['password'],
            'active' => 1,
            'created_by' => $data['insert_by']
        ];
        if (!$this->user->create($user)) {
            $this->error = $this->user->error;
            return false;
        }

        $data['user_id'] = $this->user->last_id;

        $save = $this->save($data);
        if (!$save) {
            $this->user->delete($data['user_id']);
        } 
        return $save;
    }

    public function getAll($request = [])
    {
        return $this->model->get();
    }

    public function rekapInput(Request $request, $download = false)
    {
        $penyuluh = $this->list($request);
        $tanggal = Carbon::now()->format('Y-m-d');
        if ($request->has('tanggal')) {
            $tanggal = $request->tanggal;
        }
        $tahun = Carbon::create($tanggal)->format('Y');

        $bakulahanList = $this->dataBakuLahan($tahun);
        $bakulahanDetailList = $this->dataBakuLahanDetail($tanggal);
        $dataLuas = $this->dataLuas($tanggal);
        $dataHtp = $this->dataHtp($tanggal);
        $dataOpsin = $this->dataOpsin($tahun);
        $dataOpsinDetail = $this->dataOpsinDetail($tanggal);
        

        $data = [];
        foreach ($penyuluh as $key => $value) {
            // $kota = '';
            // if ($value->kota_id != 0) {
            //     $kota = $this->area->getNamaKota(32, $value->kota_id);
            // }
            // $kecamatan = '';
            // if ($value->kecamatan_id != 0) {
            //     $kecamatan = $this->area->getNamaKecamatan(32, $value->kota_id,$value->kecamatan_id);
            // }
            // $kelurahan = '';
            // if ($value->kelurahan_id != 0) {
            //     $kelurahan = $this->area->getNamaDesa(32, $value->kota_id,$value->kecamatan_id, $value->kelurahan_id);
            // }

            $keyBakuLahan = array_search($value->id, array_column($bakulahanList, "penyuluh_id"));
            $bakulahan = false;
            if ($keyBakuLahan !== false && $bakulahanList[$keyBakuLahan]['luas_baku_lahan'] > 0) {
                $bakulahan = true;
            }

            $keyBakuLahanDetail = array_search($value->id, array_column($bakulahanDetailList, "penyuluh_id"));
            $rencana_tanam = $realisasi_tanam = false;
            if ($keyBakuLahanDetail !== false) {
                if ($bakulahanDetailList[$keyBakuLahanDetail]['rencana_tanam'] > 0) {
                    $rencana_tanam = true;
                }
                if ($bakulahanDetailList[$keyBakuLahanDetail]['realisasi_tanam'] > 0) {
                    $realisasi_tanam = true;
                }
            }

            $keyDataLuas = array_search($value->id, array_column($dataLuas, "penyuluh_id"));
            $luas_tanam = $luas_panen = $tambah_tanam = $produksi = false;
            if ($keyDataLuas !== false) {
                if ($dataLuas[$keyDataLuas]['luas_tanam'] > 0) {
                    $luas_tanam = true;
                }
                if ($dataLuas[$keyDataLuas]['tambah_tanam'] > 0) {
                    $tambah_tanam = true;
                }
                if ($dataLuas[$keyDataLuas]['luas_panen'] > 0) {
                    $luas_panen = true;
                }
                if ($dataLuas[$keyDataLuas]['produksi'] > 0) {
                    $produksi = true;
                }
            }

            $htp = false;
            $keyHtp = array_search($value->id, array_column($dataHtp, "penyuluh_id"));
            if ($keyHtp !== false) {
                if ($dataHtp[$keyHtp]['htp'] > 0) {
                    $htp = true;
                }
            }

            $opsin = false;
            $keyOpsin = array_search($value->id, array_column($dataOpsin, "penyuluh_id"));
            if ($keyOpsin !== false) {
                if ($dataOpsin[$keyOpsin]['jumlah_alsin'] > 0) {
                    $opsin = true;
                }
            }

            $ltt_ltp = $alsin = false;
            $keyOpsinDetail = array_search($value->id, array_column($dataOpsinDetail, "penyuluh_id"));
            if ($keyOpsinDetail !== false) {
                if ($dataOpsinDetail[$keyOpsinDetail]['ltt_ltp'] > 0) {
                    $ltt_ltp = true;
                }
                if ($dataOpsinDetail[$keyOpsinDetail]['alsin'] > 0) {
                    $alsin = true;
                }
            }

            $tempat_tugas = '';
            if ($value->tempat_tugas_kota_id != null && $value->tempat_tugas_kota_id != 0) {
                $tempat_tugas = $this->area->getNamaKota(32, $value->tempat_tugas_kota_id);
                if ($value->tempat_tugas_kecamatan_id != null && $value->tempat_tugas_kecamatan_id != 0) {
                    $tempat_tugas = 'Kecamatan '. $this->area->getNamaKecamatan(32, $value->tempat_tugas_kota_id, $value->tempat_tugas_kecamatan_id) . ', ' . $tempat_tugas;
                    if ($value->tempat_tugas_kelurahan_id != null && $value->tempat_tugas_kelurahan_id != 0) {
                        $tempat_tugas = 'Desa ' . $this->area->getNamaDesa(32, $value->tempat_tugas_kota_id, $value->tempat_tugas_kecamatan_id, $value->tempat_tugas_kelurahan_id) . ', ' . $tempat_tugas;
                    }
                }
            }

            $data[$key] = [
                'id' => $value->id,
                'nama' => $value->nama,
                'no_hp' => $value->no_hp,
                'jp' => $value->jp,
                // 'kota' => $kota,
                // 'kecamatan' => $kecamatan,
                // 'kelurahan' => $kelurahan,
                'bakulahan' => $bakulahan,
                'realisasi_tanam' => $realisasi_tanam,
                'rencana_tanam' => $rencana_tanam,
                'luas_tanam' => $luas_tanam,
                'tambah_tanam' => $tambah_tanam,
                'luas_panen' => $luas_panen,
                'produksi' => $produksi,
                'htp' => $htp,
                'opsin' => $opsin,
                'ltt_ltp' => $ltt_ltp,
                'alsin' => $alsin,
                'tempat_tugas' => $tempat_tugas
            ];
        }
        $res['data'] = $data;
        if (!$download) {
            $res['total'] = $penyuluh->total();
        }
        return $res;
    }

    private function dataBakuLahan($tahun)
    {
        $data = $this->bakulahan->where('tahun', $tahun)->select('penyuluh_id', 'luas_baku_lahan')->groupBy('penyuluh_id')->get();
        return $data->toArray();
    }

    private function dataBakuLahanDetail($tanggal)
    {
        $data = $this->bakulahanDetail->where('tanggal_input', $tanggal)->select('penyuluh_id', 'rencana_tanam', 'realisasi_tanam')->groupBy('penyuluh_id')->get();
        return $data->toArray();
    }

    private function dataLuas($tanggal)
    {
        $data = $this->dataluas->where('tanggal', $tanggal)->select('penyuluh_id', 'luas_tanam', 'tambah_tanam', 'luas_panen', 'produksi')->groupBy('penyuluh_id')->get();
        return $data->toArray();
    }

    private function dataHtp($tanggal)
    {
        $data = $this->htp->where('tanggal', $tanggal)->select('penyuluh_id', 'htp')->groupBy('penyuluh_id')->get();
        return $data->toArray();
    }

    private function dataOpsin($tahun)
    {
        $data = $this->opsin->where('tahun', $tahun)->select('penyuluh_id', 'jumlah_alsin')->groupBy('penyuluh_id')->get();
        return $data->toArray();
    }
    
    private function dataOpsinDetail($tanggal)
    {
        $data = $this->opsinDetail->where('tanggal_input', $tanggal)->select('penyuluh_id', 'ltt_ltp', 'alsin')->groupBy('penyuluh_id')->get();
        return $data->toArray();
    }

    public function rekapInputDownload(Request $request)
    {
        $data = $this->rekapInput($request, true);
        $createOne = Download::create([
            'keterangan' => "Laporan Rekap Input Penyuluh " . Carbon::create($request->tanggal)->format('d M Y'),
            'user_id' => $request->user_id ?? 0
        ]);

        dispatch(new RekapInputPenyuluhJob($data, $createOne->id, $request->tanggal));
    }
    
    public function rekapUser(Request $request, $download = false)
    {
        $this->relation = ['user'];
        $penyuluh = $this->list($request);        

        $data = [];
        foreach ($penyuluh as $key => $value) {
            // $kota = '';
            // if ($value->kota_id != 0) {
            //     $kota = $this->area->getNamaKota(32, $value->kota_id);
            // }
            // $kecamatan = '';
            // if ($value->kecamatan_id != 0) {
            //     $kecamatan = $this->area->getNamaKecamatan(32, $value->kota_id,$value->kecamatan_id);
            // }
            // $kelurahan = '';
            // if ($value->kelurahan_id != 0) {
            //     $kelurahan = $this->area->getNamaDesa(32, $value->kota_id,$value->kecamatan_id, $value->kelurahan_id);
            // }

            $tempat_tugas = '';
            if ($value->tempat_tugas_kota_id != null && $value->tempat_tugas_kota_id != 0) {
                $tempat_tugas = $this->area->getNamaKota(32, $value->tempat_tugas_kota_id);
                if ($value->tempat_tugas_kecamatan_id != null && $value->tempat_tugas_kecamatan_id != 0) {
                    $tempat_tugas = 'Kecamatan '. $this->area->getNamaKecamatan(32, $value->tempat_tugas_kota_id, $value->tempat_tugas_kecamatan_id) . ', ' . $tempat_tugas;
                    if ($value->tempat_tugas_kelurahan_id != null && $value->tempat_tugas_kelurahan_id != 0) {
                        $tempat_tugas = 'Desa ' . $this->area->getNamaDesa(32, $value->tempat_tugas_kota_id, $value->tempat_tugas_kecamatan_id, $value->tempat_tugas_kelurahan_id) . ', ' . $tempat_tugas;
                    }
                }
            }

            $data[$key] = [
                'id' => $value->id,
                'nama' => $value->nama,
                'no_hp' => $value->no_hp,
                'jp' => $value->jp,
                // 'kota' => $kota,
                // 'kecamatan' => $kecamatan,
                // 'kelurahan' => $kelurahan,
                'tempat_tugas' => $tempat_tugas,
                'username' => $value->user->username
            ];
        }
        $res['data'] = $data;
        if (!$download) {
            $res['total'] = $penyuluh->total();
        }
        return $res;
    }

    public function rekapUserDownload(Request $request)
    {
        $data = $this->rekapUser($request, true);
        $createOne = Download::create([
            'keterangan' => "Laporan Rekap User Penyuluh",
            'user_id' => $request->user_id ?? 0
        ]);
        dispatch(new RekapUserPenyuluhJob($data, $createOne->id));
        return true;
    }
}