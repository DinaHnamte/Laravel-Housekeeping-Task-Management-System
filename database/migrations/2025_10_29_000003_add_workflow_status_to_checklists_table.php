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
        Schema::table('checklists', function (Blueprint $table) {
            // Check if columns don't already exist before adding
            if (!Schema::hasColumn('checklists', 'workflow_stage')) {
                $table->enum('workflow_stage', ['room_checklist', 'inventory_checklist', 'photo_upload', 'summary', 'completed'])->default('room_checklist')->after('notes');
            }
            if (!Schema::hasColumn('checklists', 'verified_latitude')) {
                $table->decimal('verified_latitude', 10, 8)->nullable()->after('workflow_stage');
            }
            if (!Schema::hasColumn('checklists', 'verified_longitude')) {
                $table->decimal('verified_longitude', 11, 8)->nullable()->after('verified_latitude');
            }
            if (!Schema::hasColumn('checklists', 'gps_verified_at')) {
                $table->timestamp('gps_verified_at')->nullable()->after('verified_longitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklists', function (Blueprint $table) {
            $table->dropColumn(['workflow_stage', 'verified_latitude', 'verified_longitude', 'gps_verified_at']);
        });
    }
};
