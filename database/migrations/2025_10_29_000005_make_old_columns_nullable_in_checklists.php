<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make old columns nullable since they're replaced by the new structure
     */
    public function up(): void
    {
        Schema::table('checklists', function (Blueprint $table) {
            // Make task_id nullable since we now use checklist_tasks pivot table
            if (Schema::hasColumn('checklists', 'task_id')) {
                $table->foreignId('task_id')->nullable()->change();
            }

            // Make room_id nullable since rooms are accessed via tasks
            if (Schema::hasColumn('checklists', 'room_id')) {
                $table->foreignId('room_id')->nullable()->change();
            }

            // Make time_date_stamp_start nullable (keeping for backward compatibility)
            if (Schema::hasColumn('checklists', 'time_date_stamp_start')) {
                $table->timestamp('time_date_stamp_start')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Making columns non-nullable again would require data, so we skip this
    }
};

