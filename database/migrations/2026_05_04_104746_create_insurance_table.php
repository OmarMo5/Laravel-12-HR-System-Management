<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('insurance_number')->nullable();
            $table->date('insurance_start_date')->nullable();
            $table->enum('insurance_status', ['insured', 'not_insured', 'willing', 'not_willing'])->default('not_insured');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance');
    }
};