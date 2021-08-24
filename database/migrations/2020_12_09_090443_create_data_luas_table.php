<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataLuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_luas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->id();
            $table->timestamps();
            $table->bigInteger('created_by')->default(1);
            $table->bigInteger('updated_by')->default(1);
            $table->date('tanggal');
            $table->integer('tahun');
            $table->tinyInteger('bulan');
            $table->tinyInteger('hari');
            $table->tinyInteger('komoditi_id');
            $table->integer('kota_id');
            $table->integer('kecamatan_id');
            $table->integer('kelurahan_id');
            $table->integer('jenis');
            $table->boolean('vrf_kota')->default(0);
            $table->boolean('vrf_kec')->default(0);

            $table->decimal('luas_tanam', 30, 2)->default(0);
            $table->text('file_luas_tanam')->nullable();
            $table->string('ket_file_luas_tanam')->nullable();

            $table->decimal('tambah_tanam', 30, 2)->default(0);
            $table->text('file_tambah_tanam')->nullable();
            $table->string('ket_file_tambah_tanam')->nullable();

            $table->decimal('luas_panen', 30, 2)->default(0);
            $table->text('file_luas_panen')->nullable();
            $table->string('ket_file_luas_panen')->nullable();

            $table->decimal('produksi', 30, 2)->default(0);
            $table->text('file_produksi')->nullable();
            $table->string('ket_file_produksi')->nullable();
            
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->bigInteger('penyuluh_id')->default(0);
            $table->boolean('status')->default(1)->comment('Jika tanggal nya kurang dari 2016 itu data yg dipertanyakan maka statusnya 0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_luas');
    }
}
