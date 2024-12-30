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
    Schema::create('appointments', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid');
      $table->foreignId('pharmacy_id')->constrained()->onDelete('cascade');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('status')->default('pending'); // pending, confirmed, completed, canceled
      $table->text('cancellation_reason')->nullable();
      $table->timestamp('appointment_time');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('appointments');
  }
};
