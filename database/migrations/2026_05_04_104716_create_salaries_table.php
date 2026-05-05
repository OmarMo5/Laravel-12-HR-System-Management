<?php
// database/migrations/2026_05_04_000004_create_salaries_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('base_salary', 15, 2)->default(0);
            $table->decimal('advances', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0);
            $table->decimal('allowances', 15, 2)->default(0);
            $table->decimal('overtime', 15, 2)->default(0);
            $table->decimal('total_salary', 15, 2)->default(0);
            $table->enum('payment_type', ['cash', 'bank_transfer']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salaries');
    }
};