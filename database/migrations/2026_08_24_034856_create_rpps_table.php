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
        Schema::create('rpps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('mata_pelajaran');
            $table->string('kelas_semester');
            $table->string('alokasi_waktu')->default('3 JP (3 x 45 Menit)');
            $table->string('jurusan_smk');
            $table->text('capaian_pembelajaran')->nullable();
            $table->json('gaya_belajar')->nullable();
            $table->string('karakteristik_fisik')->default('Non-Inklusi (Reguler)');
            
            $table->string('model_pembelajaran')->default('Project-Based Learning (PBL)');
            $table->string('metode_pembelajaran')->nullable();
            $table->string('kemitraan_dudi')->nullable();
            $table->string('ruang_fisik')->nullable();
            $table->string('ruang_virtual')->nullable();
            $table->string('software_digital')->nullable();
            
            $table->json('dimensi_profil')->nullable();

            $table->longText('content_rpp')->nullable();
            $table->longText('content_media')->nullable();
            $table->longText('content_video_script')->nullable();
            $table->longText('content_materi')->nullable();

            $table->string('status')->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpps');
    }
};
