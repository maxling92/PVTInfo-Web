<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->id('ID_perusahaan');
            $table->string('nama_perusahaan')->unique();
            $table->string('alamat');
            $table->string('email_resmi');
            $table->string('telepon');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perusahaans');
    }
};

