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
        Schema::table('contact.juridical_person', function (Blueprint $table) {
            $table->string('website', 512)->nullable();
            $table->string('name_contact', 512)->nullable();
            $table->string('telephone_contact', 16)->nullable();
            $table->string('email_contact', 256)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact.juridical_person', function (Blueprint $table) {
            $table->dropColumn('website');
            $table->dropColumn('name_contact');
            $table->dropColumn('telephone_contact');
            $table->dropColumn('email_contact');
        });
    }
};
