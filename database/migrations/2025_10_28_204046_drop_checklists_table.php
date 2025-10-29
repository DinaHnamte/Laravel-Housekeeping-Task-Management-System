<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Note: This migration originally dropped the checklists table,
     * but we're keeping the checklists table structure, so this is now a no-op.
     * The checklists table needs to remain as it's the core table for the system.
     */
    public function up(): void
    {
        // Skip dropping checklists table - it's needed for the system
        // The table structure is correct and should be kept
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assignment_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('time_date_stamp_start');
            $table->timestamp('time_date_stamp_end')->nullable();
            $table->boolean('checked_off')->default(false);
            $table->text('notes')->nullable();
            $table->string('image_link')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }
};
