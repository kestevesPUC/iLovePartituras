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
        Schema::table('contact.person', function (Blueprint $table) {
            $table->string('job', 256)->nullable();
            $table->string('company', 256)->nullable();
            $table->string('telephone_contact', 64)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact.person', function (Blueprint $table) {
            $table->dropColumn('job');
            $table->dropColumn('company');
            $table->dropColumn('telephone_contact');
        });
    }
};
