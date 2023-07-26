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
        \Illuminate\Support\Facades\DB::statement('CREATE SCHEMA contact');
        Schema::create('contact.person', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('id_type')->nullable();
            $table->string('name', 512);
            $table->string('email', 256);
            $table->string('observation', 1024)->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('contact.person');
        \Illuminate\Support\Facades\DB::statement('DROP SCHEMA contact');
    }
};
