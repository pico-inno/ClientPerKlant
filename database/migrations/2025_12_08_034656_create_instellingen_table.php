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
        Schema::create('instellingen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instelling_id')->index();
            $table->string('instelling_naam');

            $table->foreignId('license_variant_id')->constrained('license_variants')->cascadeOnDelete();
            $table->foreignId('license_id')->constrained('licenses')->cascadeOnDelete();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instellingen');
    }
};
