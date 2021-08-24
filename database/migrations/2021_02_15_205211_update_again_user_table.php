<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAgainUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            $table->string('email')->nullable()->change();
            $table->text('avatar')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->text('about')->nullable()->change();
            $table->text('social_media')->nullable()->change();
            $table->text('message')->nullable()->change();
            $table->text('other_data')->nullable()->change();
            $table->string('phone_number')->nullable()->change();
            $table->boolean('gender')->default(0)->change();
            $table->boolean('active')->default(0)->change();
            $table->boolean('verified_email')->default(0)->change();
            $table->boolean('verified_phone_number')->default(0)->change();
            $table->date('birth_date')->nullable()->change();
            // $table->dateTime('created_date')->nullable()->change();
            $table->integer('created_by')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            //
        });
    }
}
