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
        Schema::create('webdesigns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->constrained()->onDelete('cascade'); // Linked to Employee
            $table->string('project_name');                // Name of the website/project
            $table->string('website_url')->nullable();     // Final URL of the site
            $table->enum('category', ['Business', 'E-Commerce', 'Portfolio', 'Blog', 'Other'])->default('Business'); // Type
            $table->enum('status', ['in progress', 'delivered', 'in review', 'delayed'])->default('in progress'); // Status
            $table->text('description')->nullable();       // Description of the project
            $table->string('tools_used')->nullable();      // e.g., Laravel, WordPress
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->float('performance')->default(0);
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webdesigns');
    }
};