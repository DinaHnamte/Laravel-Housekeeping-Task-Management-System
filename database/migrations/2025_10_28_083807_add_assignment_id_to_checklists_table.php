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
        // Check if column doesn't already exist
        if (!Schema::hasColumn('checklists', 'assignment_id')) {
            Schema::table('checklists', function (Blueprint $table) {
                $table->unsignedBigInteger('assignment_id')->nullable()->after('user_id');
                // Note: No foreign key constraint since assignments table doesn't exist
                // If assignments table is created later, add the foreign key in a separate migration
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklists', function (Blueprint $table) {
            $table->dropForeign(['assignment_id']);
            $table->dropColumn('assignment_id');
        });
    }
};
