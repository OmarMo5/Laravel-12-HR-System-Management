<?php
// database/migrations/2024_01_01_000001_create_attendances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // نفس الـ user_id من جدول users
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('status')->default('present'); // present, absent, late, early_departure
            $table->decimal('working_hours', 5, 2)->default(0);
            $table->decimal('overtime_hours', 5, 2)->default(0);
            $table->decimal('late_minutes', 5, 2)->default(0);
            $table->decimal('early_departure_minutes', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // منع تكرار نفس التاريخ لنفس الموظف
            $table->unique(['user_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
