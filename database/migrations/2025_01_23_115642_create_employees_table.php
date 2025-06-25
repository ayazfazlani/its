<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('employees', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('branch_id')->constrained()->onDelete('cascade');
      $table->enum('department', ['web design', 'digital marketing', 'graphic designing', 'seo', 'customer support'])->default('web design');
      $table->string('position');
      $table->date('joining_date');
      $table->string('cnic');
      $table->string('phone')->nullable();
      $table->text('address')->nullable();
      $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('employees');
  }
};