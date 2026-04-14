<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (!Schema::hasColumn('leaves', 'status')) {
                $table->string('status')->default('Pending')->after('reason');
            }
            if (!Schema::hasColumn('leaves', 'approved_by')) {
                $table->string('approved_by')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_by']);
        });
    }
};