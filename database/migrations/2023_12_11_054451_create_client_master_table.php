<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_master', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->comment('Badan/Perorangan')->nullable();
            $table->string('bidang_usaha')->nullable();
            $table->string('nama_wp')->nullable();
            $table->string('npwp_wp')->unique()->nullable();
            $table->date('npwp_wp_sejak')->nullable();
            $table->string('nama_pj')->nullable();
            $table->string('npwp_pj')->nullable();
            $table->string('telp_pj')->nullable();
            $table->date('tgl_berdiri')->nullable();
            $table->date('tgl_dikukuhkan_pkp')->nullable();
            $table->string('klu')->nullable();
            $table->string('is_pkp')->comment('1=pkp ; 0=nonpkp')->nullable();
            $table->string('status_pkp')->nullable();
            $table->string('is_umkm')->comment('1=umkm ; 0=nonumkm')->nullable();
            $table->date('masa_berlaku_sertel_sejak')->nullable();
            $table->date('masa_berlaku_sertel_sampai')->nullable();
            $table->string('kppar_id')->nullable();
            $table->string('file_ktp')->nullable();
            $table->string('file_npwp')->nullable();
            $table->string('file_ktp_pj')->nullable();
            $table->string('file_npwp_pj')->nullable();
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
        Schema::dropIfExists('client_master');
    }
}
