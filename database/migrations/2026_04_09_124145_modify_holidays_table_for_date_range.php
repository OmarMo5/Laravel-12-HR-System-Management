<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    public function up()
    {
        // أولاً: نضيف العمودين الجدد
        Schema::table('holidays', function (Blueprint $table) {
            /* $table->date('start_date')->nullable()->after('holiday_date');
            $table->date('end_date')->nullable()->after('start_date'); */
            if (!Schema::hasColumn('holidays', 'start_date')) {
                $table->date('start_date')->nullable()->after('holiday_date');
            }

            if (!Schema::hasColumn('holidays', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });

        // ثانياً: نحول البيانات القديمة من holiday_date إلى start_date و end_date
        $holidays = \App\Models\Holiday::all();
        foreach ($holidays as $holiday) {
            if ($holiday->holiday_date) {
                // نحول الصيغة من '05 Apr, 2026' إلى '2026-04-05'
                try {
                    $parsedDate = Carbon::createFromFormat('d M, Y', $holiday->holiday_date);
                    $formattedDate = $parsedDate->format('Y-m-d');
                    
                    $holiday->start_date = $formattedDate;
                    $holiday->end_date = $formattedDate;
                    $holiday->save();
                } catch (\Exception $e) {
                    // لو فشل التحويل، نجرب طريقة تانية
                    try {
                        $parsedDate = Carbon::parse($holiday->holiday_date);
                        $formattedDate = $parsedDate->format('Y-m-d');
                        
                        $holiday->start_date = $formattedDate;
                        $holiday->end_date = $formattedDate;
                        $holiday->save();
                    } catch (\Exception $e2) {
                        \Log::error('Failed to parse date: ' . $holiday->holiday_date);
                    }
                }
            }
        }

        // ثالثاً: نشيل العمود القديم
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('holiday_date');
        });
    }

    public function down()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->string('holiday_date')->nullable();
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};