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
        Schema::create('datapengukurans', function (Blueprint $table) {
            $table->id();
            $table->string('namadata')->unique();
            $table->string('tanggal');
            $table->string('lokasi');
            $table->string('jenistest');
            $table->integer('ratarata')->default(0);  
            $table->string('hasil')->default('unknown');  
            $table->integer('gagal');
            $table->string('nama_observant');
            $table->timestamps();

            $table->foreign('nama_observant')->references('nama_observant')->on('datapengirims')->onDelete('cascade')->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datapengukurans');
    }
};
