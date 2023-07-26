no<?php

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
        Schema::create('contact.juridical_person', function (Blueprint $table) {
            $table->id();
            $table->foreign('id')->references('id')->on('contact.person');
            $table->string('cnpj', 14)->nullable();
            $table->string('state_registration', 14)->nullable();
            $table->string('municipal_registration', 15)->nullable();
            $table->string('fantasy_name', 512)->nullable();
            $table->boolean('tax_substitution')->default(false);
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
        Schema::dropIfExists('contact.juridical_person');
    }
};
