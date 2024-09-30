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
        Schema::create('datahasils', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor');
            $table->integer('waktu_milidetik');
            $table->string('namadata');
            $table->timestamps();


            $table->foreign('namadata')->references('namadata')->on('datapengukurans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datahasils');
    }
};
