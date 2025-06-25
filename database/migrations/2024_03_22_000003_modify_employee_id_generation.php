<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up()
  {
    if (Schema::hasTable('employees')) {
      // Check if employee_id column exists, if not add it
      if (!Schema::hasColumn('employees', 'employee_id')) {
        Schema::table('employees', function (Blueprint $table) {
          $table->string('employee_id')->nullable();
        });
      } else {
        // First, modify the employee_id column to be nullable temporarily
        Schema::table('employees', function (Blueprint $table) {
          $table->string('employee_id')->nullable()->change();
        });
      }

      // Create a trigger to auto-generate employee_id
      DB::unprepared('
            CREATE TRIGGER before_employee_insert 
            BEFORE INSERT ON employees
            FOR EACH ROW
            BEGIN
                IF NEW.employee_id IS NULL THEN
                    SET NEW.employee_id = CONCAT(
                        DATE_FORMAT(NOW(), "%Y"),
                        LPAD((SELECT COUNT(*) + 1 FROM employees e), 4, "0")
                    );
                END IF;
            END
        ');
    }
  }

  public function down()
  {
    if (Schema::hasTable('employees')) {
      // Drop the trigger
      DB::unprepared('DROP TRIGGER IF EXISTS before_employee_insert');

      // Make employee_id required again
      if (Schema::hasColumn('employees', 'employee_id')) {
        Schema::table('employees', function (Blueprint $table) {
          $table->string('employee_id')->nullable(false)->change();
        });
      }
    }
  }
};