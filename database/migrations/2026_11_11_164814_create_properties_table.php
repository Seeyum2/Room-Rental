
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            // Landlord relationship
            $table->foreignId('landlord_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Property information
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('property_type', [
                'apartment',
                'house',
                'studio',
                'villa',
                'commercial',
                'seat',
                'room',
            ])->default('room');

            // Rental details
            $table->decimal('rent_amount', 10, 2);
            $table->unsignedInteger('bedrooms')->default(0);
            $table->unsignedInteger('bathrooms')->default(0);
            $table->decimal('sqft', 10, 2)->nullable();

            // Location
            $table->string('address');
            $table->string('city');
            $table->string('area');
            $table->string('postal_code')->nullable();

            // Availability
            $table->date('available_from')->nullable();

            // Admin approval
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'rented'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
