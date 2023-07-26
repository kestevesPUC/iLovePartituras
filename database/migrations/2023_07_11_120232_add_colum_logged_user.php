<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('access_control.user', function (Blueprint $table) {
            $table->boolean('logged')->default(false);
            $table->timestamp('last_longin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('access_control.user', function (Blueprint $table) {
            $table->dropColumn('logged');
            $table->dropColumn('last_longin');
        });
    }
};
