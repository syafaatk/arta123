<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToClientMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_master', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('password_djp')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('efin')->nullable();
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
            $table->dropColumn('email');
            $table->dropColumn('password_djp');
            $table->dropColumn('no_hp');
            $table->dropColumn('efin');
        });
    }
}
