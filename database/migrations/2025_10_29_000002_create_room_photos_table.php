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
        Schema::create('room_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('photo_path');
            $table->string('original_filename')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('captured_at');
            $table->integer('photo_number')->default(1); // 1-8 for the 8 photos per room
            $table->timestamps();

            $table->index(['checklist_id', 'room_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_photos');
    }
};
