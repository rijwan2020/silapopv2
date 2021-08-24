<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{

    public function migrasiData()
    {
        // $this->migrasiUser();
        // $this->migrasiBakuLahan();
        // $this->migrasiBakuLahanDetail();
        // $this->migrasiLuasTanam();
        // $this->migrasiLuasPanen();
        // $this->migrasiProduksi();
        // $this->migrasiTambahTanam();
        $this->migrasiOpsin();
        $this->migrasiOpsinDetail();
        $this->migrasiHtp();
    }

    public function migrasiUser()
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        DB::table('user')->orderBy('id')->chunk(100, function ($users) {
            foreach ($users as $key => $user) {
                $pass = $this->randomPass();
                if($user->id == 1){
                    $pass = 'uranus';
                }
                $updateData = [
                    'password' => password_hash($pass, PASSWORD_BCRYPT, ['cost' => 10]),
                    'created_at' => date("Y-m-d H:i:s", strtotime($user->created_date)),
                    'updated_at' => date("Y-m-d H:i:s", strtotime($user->created_date)),
                ];
                DB::table('user')->where('id', $user->id)->update($updateData);
            }
        });
        echo "User table updated \n";
    }

    public function randomPass()
    {
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < 8; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
    
        return $random_string;

    }

    public function migrasiBakuLahan()
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        DB::table('baku_lahan')->orderBy('id')->chunk(100, function ($bakulahan) {
            foreach ($bakulahan as $key => $value) {
                $penyuluh_id = 0;
                $user = User::with('penyuluh')->find($value->insert_by);
                if($user){
                    if ($user->penyuluh) {
                        $penyuluh_id = $user->penyuluh->id;
                    }
                }
                if($penyuluh_id == 0){
                    $wilker = DB::table('wilayah_kerja')->where('kota_id', $value->kota_id)->where('kecamatan_id', $value->kecamatan_id)->where('kelurahan_id', $value->kelurahan_id)->first();
                    if ($wilker) {
                        $penyuluh_id = $wilker->penyuluh_id;
                    }
                }
                
                $updateBakuLahan['penyuluh_id'] = $penyuluh_id;
                DB::table('baku_lahan')->where('id', $value->id)->update($updateBakuLahan);
            }
        });
        echo "Baku Lahan table updated \n";
    }

    public function migrasiBakuLahanDetail()
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        DB::table('baku_lahan_detail')->orderBy('id')->chunk(100, function ($bakulahan) {
            foreach ($bakulahan as $key => $value) {
                $penyuluh_id = 0;
                $user = User::with('penyuluh')->find($value->insert_by);
                if($user){
                    if ($user->penyuluh) {
                        $penyuluh_id = $user->penyuluh->id;
                    }
                }
                if($penyuluh_id == 0){
                    $wilker = DB::table('wilayah_kerja')->where('kota_id', $value->kota_id)->where('kecamatan_id', $value->kecamatan_id)->where('kelurahan_id', $value->kelurahan_id)->first();
                    if ($wilker) {
                        $penyuluh_id = $wilker->penyuluh_id;
                    }
                }
                
                $updateBakuLahanDetail['penyuluh_id'] = $penyuluh_id;
                DB::table('baku_lahan_detail')->where('id', $value->id)->update($updateBakuLahanDetail);
            }
        });
        echo "Baku Lahan Detail table updated \n";
    }

    public function migrasiLuasTanam()
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        DB::table('luas_tanam')->orderBy('id')->chunk(100, function ($tanam) {
            foreach ($tanam as $key => $value) {
                $penyuluh_id = 0;
                $user = User::with('penyuluh')->find($value->insert_by);
                if($user){
                    if ($user->penyuluh) {
                        $penyuluh_id = $user->penyuluh->id;
                    }
                }
                if($penyuluh_id == 0){
                    $wilker = DB::table('wilayah_kerja')->where('kota_id', $value->kota_id)->where('kecamatan_id', $value->kecamatan_id)->where('kelurahan_id', $value->kelurahan_id)->first();
                    if ($wilker) {
                        $penyuluh_id = $wilker->penyuluh_id;
                    }
                }

                $param = [
                    'kota_id' => $value->kota_id,
                    'kecamatan_id' => $value->kecamatan_id,
                    'kelurahan_id' => $value->kelurahan_id,
                    'tanggal' => $value->tanggal,
                    'jenis' => $value->jenis,
                    'komoditi_id' => $value->komoditi_id,
                ];
                // cek apakah sudah ada data pada table data_luas dengan parameter seperti di atas
                $dataLuas = DB::table('data_luas')->where($param)->first();
                if($dataLuas){
                    $updateData['luas_tanam'] = $dataLuas->luas_tanam + $value->luas;
                    $updateData['file_luas_tanam'] = $value->file;
                    $updateData['ket_file_luas_tanam'] = $value->ket_file;
                    $updateData['penyuluh_id'] = $penyuluh_id;
                    $data_luas_id = $dataLuas->id;
                    DB::table('data_luas')->where('id', $dataLuas->id)->update($updateData);
                }else{
                    $newData = (array) $value;//->toArray();
                    $newData['created_at'] = $value->insert_time;
                    $newData['updated_at'] = $value->update_time;
                    $newData['created_by'] = $value->insert_by;
                    $newData['updated_by'] = $value->update_by;
                    $newData['luas_tanam'] = $value->luas;
                    $newData['file_luas_tanam'] = $value->file;
                    $newData['ket_file_luas_tanam'] = $value->ket_file;
                    $newData['status'] = $value->tahun < 2016 ? 0 : 1;
                    $newData['penyuluh_id'] = $penyuluh_id;
                    unset(
                        $newData['id'], 
                        $newData['luas'], 
                        $newData['file'],
                        $newData['ket_file'],
                        $newData['insert_time'],
                        $newData['update_time'],
                        $newData['insert_by'],
                        $newData['update_by'],
                        $newData['data_luas_id'],
                    );
                    DB::table('data_luas')->insert($newData);
                    $data_luas_id = DB::getPdo()->lastInsertId();;
                }
                // Update table luas_tanam
                $updateLuasTanam['data_luas_id'] = $data_luas_id;
                $updateLuasTanam['penyuluh_id'] = $penyuluh_id;
                DB::table('luas_tanam')->where('id', $value->id)->update($updateLuasTanam);
            }
        });
        echo "Luas tanam table updated \n";
    }

    public function migrasiLuasPanen()
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        DB::table('luas_panen')->orderBy('id')->chunk(100, function ($panen) {
            foreach ($panen as $key => $value) {
                $penyuluh_id = 0;
                $user = User::with('penyuluh')->find($value->insert_by);
                if($user){
                    if ($user->penyuluh) {
                        $penyuluh_id = $user->penyuluh->id;
                    }
                }
                if($penyuluh_id == 0){
                    $wilker = DB::table('wilayah_kerja')->where('kota_id', $value->kota_id)->where('kecamatan_id', $value->kecamatan_id)->where('kelurahan_id', $value->kelurahan_id)->first();
                    if ($wilker) {
                        $penyuluh_id = $wilker->penyuluh_id;
                    }
                }

                $param = [
                    'kota_id' => $value->kota_id,
                    'kecamatan_id' => $value->kecamatan_id,
                    'kelurahan_id' => $value->kelurahan_id,
                    'tanggal' => $value->tanggal,
                    'jenis' => $value->jenis,
                    'komoditi_id' => $value->komoditi_id,
                ];
                // cek apakah sudah ada data pada table data_luas dengan parameter seperti di atas
                $dataLuas = DB::table('data_luas')->where($param)->first();
                if($dataLuas){
                    $updateData['luas_panen'] = $dataLuas->luas_panen + $value->luas_panen;
                    $updateData['file_luas_panen'] = $value->file;
                    $updateData['ket_file_luas_panen'] = $value->ket_file;
                    $updateData['penyuluh_id'] = $penyuluh_id;
                    $data_luas_id = $dataLuas->id;
                    DB::table('data_luas')->where('id', $dataLuas->id)->update($updateData);
                }else{
                    $newData = (array) $value;//->toArray();
                    $newData['created_at'] = $value->insert_time;
                    $newData['updated_at'] = $value->update_time;
                    $newData['created_by'] = $value->insert_by;
                    $newData['updated_by'] = $value->update_by;
                    $newData['luas_panen'] = $value->luas_panen;
                    $newData['file_luas_panen'] = $value->file;
                    $newData['ket_file_luas_panen'] = $value->ket_file;
                    $newData['status'] = $value->tahun < 2016 ? 0 : 1;
                    $newData['penyuluh_id'] = $penyuluh_id;
                    unset(
                        $newData['id'], 
                        $newData['luas'], 
                        $newData['file'],
                        $newData['ket_file'],
                        $newData['insert_time'],
                        $newData['update_time'],
                        $newData['insert_by'],
                        $newData['update_by'],
                        $newData['data_luas_id'],
                    );
                    DB::table('data_luas')->insert($newData);
                    $data_luas_id = DB::getPdo()->lastInsertId();;
                }
                // Update table luas_panen
                $updateLuasPanen['data_luas_id'] = $data_luas_id;
                $updateLuasPanen['penyuluh_id'] = $penyuluh_id;
                DB::table('luas_panen')->where('id', $value->id)->update($updateLuasPanen);
            }
        });
        echo "Luas Panen Table updated \n";
    }

    public function migrasiProduksi()
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        DB::table('produksi')->orderBy('id')->chunk(100, function ($produksi) {
            foreach ($produksi as $key => $value) {
                $penyuluh_id = 0;
                $user = User::with('penyuluh')->find($value->insert_by);
                if($user){
                    if ($user->penyuluh) {
                        $penyuluh_id = $user->penyuluh->id;
                    }
                }
                if($penyuluh_id == 0){
                    $wilker = DB::table('wilayah_kerja')->where('kota_id', $value->kota_id)->where('kecamatan_id', $value->kecamatan_id)->where('kelurahan_id', $value->kelurahan_id)->first();
                    if ($wilker) {
                        $penyuluh_id = $wilker->penyuluh_id;
                    }
                }

                $param = [
                    'kota_id' => $value->kota_id,
                    'kecamatan_id' => $value->kecamatan_id,
                    'kelurahan_id' => $value->kelurahan_id,
                    'tanggal' => $value->tanggal,
                    'jenis' => $value->jenis,
                    'komoditi_id' => $value->komoditi_id,
                ];
                // cek apakah sudah ada data pada table data_luas dengan parameter seperti di atas
                $dataLuas = DB::table('data_luas')->where($param)->first();
                if($dataLuas){
                    $updateData['produksi'] = $dataLuas->produksi + $value->luas;
                    $updateData['file_produksi'] = $value->file;
                    $updateData['ket_file_produksi'] = $value->ket_file;
                    $updateData['penyuluh_id'] = $penyuluh_id;
                    $data_luas_id = $dataLuas->id;
                    DB::table('data_luas')->where('id', $dataLuas->id)->update($updateData);
                }else{
                    $newData = (array) $value;//->toArray();
                    $newData['created_at'] = $value->insert_time;
                    $newData['updated_at'] = $value->update_time;
                    $newData['created_by'] = $value->insert_by;
                    $newData['updated_by'] = $value->update_by;
                    $newData['produksi'] = $value->luas;
                    $newData['file_produksi'] = $value->file;
                    $newData['ket_file_produksi'] = $value->ket_file;
                    $newData['status'] = $value->tahun < 2016 ? 0 : 1;
                    $newData['penyuluh_id'] = $penyuluh_id;
                    unset(
                        $newData['id'], 
                        $newData['luas'], 
                        $newData['file'],
                        $newData['ket_file'],
                        $newData['insert_time'],
                        $newData['update_time'],
                        $newData['insert_by'],
                        $newData['update_by'],
                        $newData['data_luas_id'],
                    );
                    DB::table('data_luas')->insert($newData);
                    $data_luas_id = DB::getPdo()->lastInsertId();
                }
                // Update table produksi
                $updateLuasTambahTanam['data_luas_id'] = $data_luas_id;
                $updateLuasTambahTanam['penyuluh_id'] = $penyuluh_id;
                DB::table('produksi')->where('id', $value->id)->update($updateLuasTambahTanam);
            }
        });
        echo "Produksi Table updated \n";
    }

    public function migrasiTambahTanam()
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        DB::table('luas_tambah_tanam')->orderBy('id')->chunk(100, function ($tambah_tanam) {
            foreach ($tambah_tanam as $key => $value) {
                $penyuluh_id = 0;
                $user = User::with('penyuluh')->find($value->insert_by);
                if($user){
                    if ($user->penyuluh) {
                        $penyuluh_id = $user->penyuluh->id;
                    }
                }
                if($penyuluh_id == 0){
                    $wilker = DB::table('wilayah_kerja')->where('kota_id', $value->kota_id)->where('kecamatan_id', $value->kecamatan_id)->where('kelurahan_id', $value->kelurahan_id)->first();
                    if ($wilker) {
                        $penyuluh_id = $wilker->penyuluh_id;
                    }
                }

                $param = [
                    'kota_id' => $value->kota_id,
                    'kecamatan_id' => $value->kecamatan_id,
                    'kelurahan_id' => $value->kelurahan_id,
                    'tanggal' => $value->tanggal,
                    'jenis' => $value->jenis,
                    'komoditi_id' => $value->komoditi_id,
                ];
                // cek apakah sudah ada data pada table data_luas dengan parameter seperti di atas
                $dataLuas = DB::table('data_luas')->where($param)->first();
                if($dataLuas){
                    $updateData['tambah_tanam'] = $dataLuas->tambah_tanam + $value->tambah_tanam;
                    $updateData['file_tambah_tanam'] = $value->file;
                    $updateData['ket_file_tambah_tanam'] = $value->ket_file;
                    $updateData['penyuluh_id'] = $penyuluh_id;
                    $data_luas_id = $dataLuas->id;
                    DB::table('data_luas')->where('id', $dataLuas->id)->update($updateData);
                }else{
                    $newData = (array) $value;//->toArray();
                    $newData['created_at'] = $value->insert_time;
                    $newData['updated_at'] = $value->update_time;
                    $newData['created_by'] = $value->insert_by;
                    $newData['updated_by'] = $value->update_by;
                    $newData['tambah_tanam'] = $value->tambah_tanam;
                    $newData['file_tambah_tanam'] = $value->file;
                    $newData['ket_file_tambah_tanam'] = $value->ket_file;
                    $newData['status'] = $value->tahun < 2016 ? 0 : 1;
                    $newData['penyuluh_id'] = $penyuluh_id;
                    unset(
                        $newData['id'], 
                        $newData['luas'], 
                        $newData['file'],
                        $newData['ket_file'],
                        $newData['insert_time'],
                        $newData['update_time'],
                        $newData['insert_by'],
                        $newData['update_by'],
                        $newData['data_luas_id'],
                    );
                    DB::table('data_luas')->insert($newData);
                    $data_luas_id = DB::getPdo()->lastInsertId();
                }
                // Update table tambah_tanam
                $updateLuasTambahTanam['data_luas_id'] = $data_luas_id;
                $updateLuasTambahTanam['penyuluh_id'] = $penyuluh_id;
                DB::table('luas_tambah_tanam')->where('id', $value->id)->update($updateLuasTambahTanam);
            }
        });
        echo "Tambah tanam Table updated \n";
    }
    
    public function migrasiHtp()
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        DB::table('htp')->orderBy('id')->chunk(100, function ($htp) {
            foreach ($htp as $key => $value) {
                $penyuluh_id = 0;
                $user = User::with('penyuluh')->find($value->insert_by);
                if($user){
                    if ($user->penyuluh) {
                        $penyuluh_id = $user->penyuluh->id;
                    }
                }
                if($penyuluh_id == 0){
                    $wilker = DB::table('wilayah_kerja')->where('kota_id', $value->kota_id)->where('kecamatan_id', $value->kecamatan_id)->where('kelurahan_id', $value->kelurahan_id)->first();
                    if ($wilker) {
                        $penyuluh_id = $wilker->penyuluh_id;
                    }
                }
                
                $updateHtp['penyuluh_id'] = $penyuluh_id;
                DB::table('htp')->where('id', $value->id)->update($updateHtp);
            }
        });
        echo "Htp table updated \n";
    }
    
    public function migrasiOpsin()
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        DB::table('opsin')->orderBy('id')->chunk(100, function ($opsin) {
            foreach ($opsin as $key => $value) {
                $penyuluh_id = 0;
                $user = User::with('penyuluh')->find($value->insert_by);
                if($user){
                    if ($user->penyuluh) {
                        $penyuluh_id = $user->penyuluh->id;
                    }
                }
                if($penyuluh_id == 0){
                    $wilker = DB::table('wilayah_kerja')->where('kota_id', $value->kota_id)->where('kecamatan_id', $value->kecamatan_id)->where('kelurahan_id', $value->kelurahan_id)->first();
                    if ($wilker) {
                        $penyuluh_id = $wilker->penyuluh_id;
                    }
                }
                
                $updateOpsin['penyuluh_id'] = $penyuluh_id;
                DB::table('opsin')->where('id', $value->id)->update($updateOpsin);
            }
        });
        echo "Opsin table updated \n";
    }
    
    public function migrasiOpsinDetail()
    {
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        DB::table('opsin_detail')->orderBy('id')->chunk(100, function ($OpsinDetail) {
            foreach ($OpsinDetail as $key => $value) {
                $penyuluh_id = 0;
                $user = User::with('penyuluh')->find($value->insert_by);
                if($user){
                    if ($user->penyuluh) {
                        $penyuluh_id = $user->penyuluh->id;
                    }
                }
                if($penyuluh_id == 0){
                    $wilker = DB::table('wilayah_kerja')->where('kota_id', $value->kota_id)->where('kecamatan_id', $value->kecamatan_id)->where('kelurahan_id', $value->kelurahan_id)->first();
                    if ($wilker) {
                        $penyuluh_id = $wilker->penyuluh_id;
                    }
                }
                
                $updateOpsinDetail['penyuluh_id'] = $penyuluh_id;
                DB::table('opsin_detail')->where('id', $value->id)->update($updateOpsinDetail);
            }
        });
        echo "Opsin Detail table updated \n";
    }
}
