<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marketings', function (Blueprint $table) {
            DB::statement("ALTER TABLE marketings MODIFY COLUMN status ENUM('active','pause','inActive','clientLeft') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketings', function (Blueprint $table) {
            DB::statement("ALTER TABLE marketings MODIFY COLUMN status ENUM('active','pause','inActive','clientLeft') NOT NULL");
        });
    }
};