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
    Schema::create('pharmacies', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');
      $table->string('name');
      $table->string('address');
      $table->decimal('latitude', 10, 8);
      $table->decimal('longitude', 11, 8);
      $table->string('website')->nullable();
      $table->string('type')->nullable();
      $table->string('email')->nullable();
      $table->string('phone')->nullable();
      $table->timestamps();

      $table->foreignId('owner_id')
        ->constrained('users')
        ->cascadeOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pharmacies');
  }
};
