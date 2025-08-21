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
        // Drop existing table if it exists
        Schema::dropIfExists('brands');

        // Recreate brands table aligned with current forms/controllers
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');

            // Business relation
            $table->unsignedInteger('business_id');
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');

            // Core fields
            $table->string('name');
            $table->text('description')->nullable();

            // Image handling
            // Preferred storage-backed path (used by controller index/store/update)
            $table->string('image_path')->nullable();
            // Legacy public upload filename support (kept for backward compatibility in listing)
            $table->string('image')->nullable();

            // Optional module flag
            $table->tinyInteger('use_for_repair')->nullable()->default(0);

            // Audit
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();

            // Helpful indexes
            $table->index(['business_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
