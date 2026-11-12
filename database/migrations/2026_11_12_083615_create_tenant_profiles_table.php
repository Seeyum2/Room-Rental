
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->unique();

            $table->string('name');
            $table->string('phone_number', 20);
            $table->string('email')->unique();

            $table->enum('gender', [
                'male',
                'female',
                'other'
            ])->nullable();

            $table->string('profile_picture')->nullable();
            $table->string('nid_number', 50)->nullable();
            $table->string('university_name')->nullable();
            $table->string('student_id_number', 100)->nullable();
            $table->string('student_id_photo')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_profiles');
    }
};
