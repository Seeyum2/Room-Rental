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
            $table->enum('tenant_type', [
                'bachelor',
                'job_holder',
                'student'
            ])->nullable()->after('sqft');

            $table->enum('tenant_gender', [
                'male',
                'female',
                'other'
            ])->nullable()->after('rent_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['tenant_type', 'tenant_gender']);
        });
    }
};
