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
        Schema::create('antrian', function (Blueprint $table) {
            $table->id('id_antrian');
            $table->integer('nomor_antrian');
            $table->date('tanggal');
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai']);
            $table->timestamp('waktu_ambil')->nullable();
            $table->timestamp('waktu_panggil')->nullable();
            $table->integer('jumlah_panggil')->default(0);

            $table->unsignedBigInteger('id_loket')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->timestamps();

            // Foreign Key
            $table->foreign('id_loket')
                  ->references('id_loket')
                  ->on('loket')
                  ->onDelete('set null');
            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian');
    }


};
