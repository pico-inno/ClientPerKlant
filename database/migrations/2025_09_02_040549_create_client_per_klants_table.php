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
        Schema::create('client_per_klants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instelling_id');
            $table->string('instelling_naam');
            $table->unsignedBigInteger('aantal_actieve_clienten')->default(0);
            $table->unsignedBigInteger('aantal_inactieve_klanten')->default(0);
            $table->string('recorded_month');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_per_klants');
    }
};
