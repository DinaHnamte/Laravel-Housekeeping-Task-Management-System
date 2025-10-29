<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates checklists table to match the new structure expected by the model
     */
    public function up(): void
    {
        Schema::table('checklists', function (Blueprint $table) {
            // Add assignment_date if it doesn't exist
            if (!Schema::hasColumn('checklists', 'assignment_date')) {
                $table->date('assignment_date')->nullable()->after('user_id');
            }

            // Migrate time_date_stamp_start to start_time if needed
            if (Schema::hasColumn('checklists', 'time_date_stamp_start') && !Schema::hasColumn('checklists', 'start_time')) {
                $table->time('start_time')->nullable()->after('assignment_date');
                // Copy data from time_date_stamp_start to start_time if needed
            }

            // Migrate time_date_stamp_end to end_time if needed
            if (Schema::hasColumn('checklists', 'time_date_stamp_end') && !Schema::hasColumn('checklists', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }

            // Add status column if it doesn't exist (migrate from checked_off if needed)
            if (!Schema::hasColumn('checklists', 'status')) {
                $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending')->after('end_time');
                // Migrate checked_off to status if needed
            }
        });

        // Migrate data if old columns exist
        if (Schema::hasColumn('checklists', 'time_date_stamp_start')) {
            \DB::statement("UPDATE checklists SET assignment_date = DATE(time_date_stamp_start) WHERE assignment_date IS NULL");
            \DB::statement("UPDATE checklists SET start_time = TIME(time_date_stamp_start) WHERE start_time IS NULL");
        }

        if (Schema::hasColumn('checklists', 'time_date_stamp_end')) {
            \DB::statement("UPDATE checklists SET end_time = TIME(time_date_stamp_end) WHERE end_time IS NULL");
        }

        if (Schema::hasColumn('checklists', 'checked_off')) {
            \DB::statement("UPDATE checklists SET status = CASE WHEN checked_off = 1 THEN 'completed' ELSE 'pending' END WHERE status IS NULL OR status = ''");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklists', function (Blueprint $table) {
            $table->dropColumn(['assignment_date', 'start_time', 'end_time', 'status']);
        });
    }
};

