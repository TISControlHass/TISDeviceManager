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
        Schema::create('appliance_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appliance_id');
            $table->integer('channel_number')->nullable()->default(null);
            $table->string('channel_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appliance_channels');
    }
};
