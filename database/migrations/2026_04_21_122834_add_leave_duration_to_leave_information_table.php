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
        Schema::table('leave_information', function (Blueprint $table) {
            $table->string('leave_duration')->nullable()->after('leave_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_information', function (Blueprint $table) {
            $table->dropColumn('leave_duration');
        });
    }
};
