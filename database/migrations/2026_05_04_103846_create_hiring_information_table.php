<?php
// database/migrations/2026_05_04_000003_create_hiring_information_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hiring_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('join_date');
            $table->enum('contract_type', ['permanent', 'temporary', 'freelance', 'consultant', 'fixed']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hiring_information');
    }
};