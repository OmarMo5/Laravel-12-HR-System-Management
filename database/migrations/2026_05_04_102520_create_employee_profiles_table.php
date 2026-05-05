<?php
// database/migrations/2026_05_04_000001_create_employee_profiles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('national_id')->nullable();
            $table->text('address')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('experience_years')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_profiles');
    }
};