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
    Schema::create('ratings', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');
      $table->tinyInteger('rating')->unsigned()->default(0);
      $table->text('review')->nullable();
      $table->timestamps();

      $table->foreignId('pharmacy_id')
        ->constrained('pharmacies')
        ->cascadeOnDelete();

      $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnDelete();

      $table->unique(['user_id', 'pharmacy_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ratings');
  }
};
