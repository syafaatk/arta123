<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPemeriksaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pemeriksaan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pemeriksaan_id')->index()->nullable();
            $table->integer('item_pemeriksaan_id')->index()->nullable();
            $table->integer('quantity');
            $table->decimal('jumlah', 10, 2);
            $table->decimal('dpp_faktur_pajak', 10, 2);
            $table->decimal('dpp_gunggung', 10, 2);
            $table->decimal('ppn_pph', 10, 2);
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('detail_pemeriksaan');
    }
}
