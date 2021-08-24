<?php
namespace App\Repositories;

use App\Model\NamaOpt;
use Illuminate\Support\Facades\DB;

class NamaOptRepository extends BaseRepository
{
    public function __construct(NamaOpt $model)
    {
        $this->model = $model;
        $this->search_field = ['nama', 'keterangan'];
    }
    
    public function delete($id)
    {
        $model = $this->getOne($id);
        if (!$model) {
            $this->error = 'Data tidak ditemukan.';
            return false;
        }
        // cek apakah data sudah digunakan atau belum
        if(DB::table('opt')->where('opt_id', $id)->count() > 0){
            $this->error = 'Data tidak bisa dihapus karena sudah digunakan.';
            return false;
        }

        return $model->delete();
    }
}