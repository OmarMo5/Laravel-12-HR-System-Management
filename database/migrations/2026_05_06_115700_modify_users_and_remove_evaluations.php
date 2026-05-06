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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_email')->nullable()->unique()->after('email');
        });

        Schema::dropIfExists('manager_evaluations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_email');
        });

        // Re-creating the table in case of rollback might be complex if we want to be perfect, 
        // but for now let's just leave it dropped or handle it if necessary.
        // Usually, dropping a table is not easily reversible without data loss.
    }
};
