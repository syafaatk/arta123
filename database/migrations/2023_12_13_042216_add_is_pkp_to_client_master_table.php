<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPkpToClientMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_master', function (Blueprint $table) {
            $table->string('is_pkp')->comment('1=pkp; 0=nonpkp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_master', function (Blueprint $table) {
            $table->dropColumn('is_pkp');
        });
    }
}

