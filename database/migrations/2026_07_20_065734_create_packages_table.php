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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mikrotik_device_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('mikrotik_profile');
            $table->integer('duration_days');
            $table->bigInteger('price');
            $table->integer('speed_upload')->nullable();
            $table->integer('speed_download')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
