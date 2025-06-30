<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('ad_details', function (Blueprint $table) {
        $table->date('stats_date')->nullable()->after('budget_spent');
    });
}

public function down()
{
    Schema::table('ad_details', function (Blueprint $table) {
        $table->dropColumn('stats_date');
    });
}
};
