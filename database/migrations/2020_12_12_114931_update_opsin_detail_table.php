<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOpsinDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opsin_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->boolean('vrf_kota')->default(0)->change();
            $table->boolean('vrf_kec')->default(0)->change();
            $table->text('file')->nullable()->change();
            $table->string('ket_file')->nullable()->change();
            $table->bigInteger('penyuluh_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opsin_detail', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('long');
            $table->dropColumn('penyuluh_id');
            $table->integer('vrf_kota')->change();
            $table->integer('vrf_kec')->change();
            $table->text('file')->change();
            $table->string('ket_file')->change();
        });
    }
}
