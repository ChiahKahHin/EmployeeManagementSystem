<?php

use App\Models\Department;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('departmentCode')->unique();
            $table->string('departmentName')->unique();
            $table->timestamps();
        });

        Department::create([
            "departmentCode" => "D001",
            "departmentName" => "Human Resource"
        ]);

        Department::create([
            "departmentCode" => "D002",
            "departmentName" => "Information Technology"
        ]);
        
        Department::create([
            "departmentCode" => "D003",
            "departmentName" => "Networking"
        ]);

        Department::create([
            "departmentCode" => "D004",
            "departmentName" => "Finance"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
