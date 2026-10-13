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
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('subtitle')->nullable();
            $table->longText('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('hero_image_path')->nullable();
            $table->decimal('price_starting', 10, 2)->nullable();
            $table->string('pricing_unit')->default('per sq ft / callout');
            $table->unsignedSmallInteger('sla_hours')->default(4);
            $table->json('scope_included')->nullable();
            $table->json('scope_excluded')->nullable();
            $table->json('compliance_certs')->nullable();
            $table->boolean('is_emergency')->default(false);
            $table->boolean('is_featured')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
    }
};
