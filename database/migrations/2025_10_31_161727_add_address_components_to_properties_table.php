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
            $table->string('street')->nullable()->after('address');
            $table->string('house_number')->nullable()->after('street');
            $table->string('neighborhood')->nullable()->after('house_number');
            $table->string('suburb')->nullable()->after('neighborhood');
            $table->string('city')->nullable()->after('suburb');
            $table->string('state')->nullable()->after('city');
            $table->string('postcode')->nullable()->after('state');
            $table->string('country')->nullable()->after('postcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['street', 'house_number', 'neighborhood', 'suburb', 'city', 'state', 'postcode', 'country']);
        });
    }
};
