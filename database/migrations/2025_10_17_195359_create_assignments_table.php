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
        // Only create if it doesn't already exist (checklists table was created in earlier migration)
        if (!Schema::hasTable('checklists')) {
            Schema::create('checklists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('property_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->date('assignment_date');
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
