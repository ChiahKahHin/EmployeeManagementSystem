<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            //$table->string('email')->unique();
            $table->string('email');
            $table->string('employeeID')->unique();
            $table->integer('department');
            $table->integer('position');
            $table->integer('role');
            $table->integer('reportingManager');
            $table->integer('delegateManager')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        //Super Admin
        User::create([
            "username" => "superadmin",
            "email" => "superadmin@gmail.com",
            "employeeID" => "E001",
            "department" => 2,
            "position" => 1,
            "role" => 0,
            "reportingManager" => 1,
            "password" => Hash::make("123"),
        ]);
        
        //HR Manager
        User::create([
            "username" => "hrmanager",
            "email" => "hrmanager@gmail.com",
            "employeeID" => "E002",
            "department" => 1,
            "position" => 2,
            "role" => 1,
            "reportingManager" => 1,
            "password" => Hash::make("123"),
        ]);

        User::create([
            "username" => "hrmanager2",
            "email" => "hrmanager2@gmail.com",
            "employeeID" => "E003",
            "department" => 1,
            "position" => 2,
            "role" => 1,
            "reportingManager" => 2,
            "password" => Hash::make("123"),
        ]);

        //IT manager
        User::create([
            "username" => "itmanager",
            "email" => "ITmanager@gmail.com",
            "employeeID" => "E004",
            "department" => 2,
            "position" => 3,
            "role" => 2,
            "reportingManager" => 1,
            "password" => Hash::make("123"),
        ]);

        User::create([
            "username" => "itmanager2",
            "email" => "ITmanager2@gmail.com",
            "employeeID" => "E005",
            "department" => 2,
            "position" => 3,
            "role" => 2,
            "reportingManager" => 4,
            "password" => Hash::make("123"),
        ]);

        //employee
        User::create([
            "username" => "emp",
            "email" => "emp@gmail.com",
            "employeeID" => "E006",
            "department" => 2,
            "position" => 5,
            "role" => 3,
            "reportingManager" => 4,
            "password" => Hash::make("123"),
        ]);

        User::create([
            "username" => "emp2",
            "email" => "emp2@gmail.com",
            "employeeID" => "E007",
            "department" => 2,
            "position" => 5,
            "role" => 3,
            "reportingManager" => 4,
            "password" => Hash::make("123"),
        ]);

        User::create([
            "username" => "emp3",
            "email" => "emp3@gmail.com",
            "employeeID" => "E008",
            "department" => 2,
            "position" => 5,
            "role" => 3,
            "reportingManager" => 4,
            "password" => Hash::make("123"),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee');
    }
}
