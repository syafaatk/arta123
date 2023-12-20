<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemeriksaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->index()->nullable();
            $table->integer('masa_pajak_id')->index()->nullable();
            $table->date('tanggal_masa_pajak')->nullable();
            $table->string('diperiksa_oleh')->nullable();
            $table->string('mengetahui')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemeriksaan');
    }
}
