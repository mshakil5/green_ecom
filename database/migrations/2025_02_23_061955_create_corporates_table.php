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
        Schema::create('corporates', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('sort_title')->nullable();
            $table->string('long_title')->nullable();
            $table->longText('sort_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->longText('corporate_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corporates');
    }
};
