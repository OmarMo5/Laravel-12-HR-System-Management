<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, drop the foreign key constraint on leave_id if it exists
        Schema::table('notifications', function (Blueprint $table) {
            // Drop foreign key constraint that was added manually
            try {
                $table->dropForeign('notifications_leave_id_foreign');
            } catch (\Exception $e) {
                // FK may not exist, that's fine
            }
        });

        // Add permission_id column
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id')->nullable()->after('leave_id');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('permission_id');
        });
    }
};
