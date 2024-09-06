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
        // Schema::dropIfExists('devices_types');
        Schema::create('devices_types', function (Blueprint $table) {
            $table->id();
            $table->string('device_type_name')->nullable(false);
            $table->string('device_model_number')->nullable(false);
            $table->string('device_description')->nullable()->default("-");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices_types');
    }
};
