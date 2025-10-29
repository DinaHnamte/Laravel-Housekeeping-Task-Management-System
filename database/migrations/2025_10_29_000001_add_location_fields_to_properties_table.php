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
        Schema::table('properties', function (Blueprint $table) {
            $table->string('address')->nullable()->after('name');
            $table->decimal('latitude', 10, 8)->nullable()->after('address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->integer('gps_radius_meters')->default(50)->after('longitude'); // Allow 50 meters radius by default
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['address', 'latitude', 'longitude', 'gps_radius_meters']);
        });
    }
};
