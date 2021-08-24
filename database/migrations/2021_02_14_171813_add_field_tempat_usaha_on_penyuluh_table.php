<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTempatUsahaOnPenyuluhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penyuluh', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->integer('tempat_tugas_kota_id')->nullable();
            $table->integer('tempat_tugas_kecamatan_id')->nullable();
            $table->integer('tempat_tugas_kelurahan_id')->nullable();
            $table->text('kelompok_tani')->nullable();
            $table->integer('kota_id')->nullable()->change();
            $table->integer('kecamatan_id')->nullable()->change();
            $table->integer('kelurahan_id')->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->date('tanggal_evaluasi')->nullable()->change();
            $table->boolean('jp')->default(0)->change();
            $table->integer('status_penyuluh')->default(0)->change();
            $table->string('nip')->nullable()->change();
            $table->boolean('jk')->default(0)->change();
            $table->string('jabatan_fungsional')->nullable()->change();
            $table->string('pangkat')->nullable()->change();
            $table->string('pendidikan')->nullable()->change();
            $table->string('nama_kelembagaan')->nullable()->change();
            $table->integer('provinsi_id')->default(32)->change();
            $table->string('bp3k')->nullable()->change();
            $table->string('pertanian_wkpp')->nullable()->change();
            $table->string('pertanian_wkpp')->nullable()->change();
            $table->integer('jml_kel_tani')->default(0)->change();
            $table->integer('user_id')->default(0)->change();
            $table->string('komoditi_unggulan')->nullable()->change();
            $table->text('alamat')->nullable()->change();
            $table->text('image')->nullable()->change();
            $table->string('no_hp')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penyuluh', function (Blueprint $table) {
            $table->dropColumn('tempat_tugas_kota_id');
            $table->dropColumn('tempat_tugas_kecamatan_id');
            $table->dropColumn('tempat_tugas_kelurahan_id');
            $table->dropColumn('kelompok_tani');
        });
    }
}
