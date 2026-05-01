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
        Schema::create('location_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_id')->constrained('panic_alerts')->onDelete('cascade');
            $table->unsignedBigInteger('trip_id')->nullable();
            $table->text('lat_encrypted');
            $table->text('lng_encrypted');
            $table->decimal('accuracy_meters', 8, 2)->nullable();
            $table->decimal('speed_kmh', 8, 2)->nullable();
            $table->integer('battery_level')->nullable();
            $table->boolean('has_gps')->default(true);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_histories');
    }
};
