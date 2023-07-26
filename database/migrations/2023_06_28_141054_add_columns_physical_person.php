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
        Schema::table('contact.physical_person', function (Blueprint $table) {
            $table->string('rg', 15)->nullable();
            $table->string('issuing_agency', 6)->nullable();
            $table->date('date_issui')->nullable();
            $table->string('cei', 64)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact.physical_person', function (Blueprint $table) {
            $table->dropColumn('rg');
            $table->dropColumn('issuing_agency');
            $table->dropColumn('date_issui');
            $table->dropColumn('cei');
        });
    }
};
