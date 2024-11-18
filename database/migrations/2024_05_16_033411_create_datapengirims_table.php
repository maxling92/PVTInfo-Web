<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('datapengirims', function (Blueprint $table) {
            $table->id();
            $table->string('nama_observant')->unique();
            $table->date('tgllahir');
            $table->string('nama_perusahaan');
            $table->timestamps();

            $table->foreign('nama_perusahaan')->references('nama_perusahaan')->on('users')->onDelete('cascade');
        });

        Schema::table('datapengirims', function (Blueprint $table) {
            $table->index('nama_observant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datapengirims');
    }
};
