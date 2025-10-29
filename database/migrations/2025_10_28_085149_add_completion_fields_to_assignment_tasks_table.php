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
        Schema::table('checklist_tasks', function (Blueprint $table) {
            $table->boolean('completed')->default(false)->after('task_id');
            $table->timestamp('completed_at')->nullable()->after('completed');
            $table->text('notes')->nullable()->after('completed_at');
            $table->string('photo')->nullable()->after('notes');
            $table->decimal('latitude', 10, 8)->nullable()->after('photo');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklist_tasks', function (Blueprint $table) {
            $table->dropColumn(['completed', 'completed_at', 'notes', 'photo', 'latitude', 'longitude']);
        });
    }
};
