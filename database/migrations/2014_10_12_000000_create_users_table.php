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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            //$table->string('email')->unique();
            $table->string('email');
            $table->string('employeeID')->unique()->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('contactNumber');
            $table->date('dateOfBirth');
            $table->string('gender');
            $table->string('address')->nullable();
            $table->integer('department');
            $table->integer('role');
            $table->integer('reportingManager')->nullable();
            $table->string('ic')->nullable();
            $table->string('nationality')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('religion')->nullable();
            $table->string('race')->nullable();
            $table->string('emergencyContactName')->nullable();
            $table->string('emergencyContactNumber')->nullable();
            $table->string('emergencyContactAddress')->nullable();
            // $table->string('maritalStatus')->nullable();
            // $table->string('spouseName')->nullable();
            // $table->date('spouseDateOfBirth')->nullable();
            // $table->string('spouseIC')->nullable();
            // $table->date('dateOfMarriage')->nullable();
            // $table->string('spouseOccupation')->nullable();
            // $table->string('spouseContactNumber')->nullable();
            // $table->string('spouseResidentStatus')->nullable();
            // $table->integer('children')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        //Admin
        User::create([
            "username" => "admin",
            "email" => "admin@gmail.com",
            "firstname" => "admin",
            "lastname" => "1",
            "contactNumber" => "012-4783997",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "department" => 1,
            "password" => Hash::make("123"),
            "role" => 0
        ]);

        User::create([
            "username" => "admin2",
            "email" => "admin2@gmail.com",
            "firstname" => "admin",
            "lastname" => "2",
            "contactNumber" => "012-4783998",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "department" => 1,
            "password" => Hash::make("123"),
            "role" => 0
        ]);
        
        //HR Manager
        User::create([
            "username" => "hrmanager",
            "email" => "hrmanager@gmail.com",
            "employeeID" => "E001",
            "firstname" => "HR",
            "lastname" => "Manager",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "reportingManager" => 1,
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "department" => 2,
            "password" => Hash::make("123"),
            "role" => 1
        ]);

        User::create([
            "username" => "hrmanager2",
            "email" => "hrmanager2@gmail.com",
            "employeeID" => "E002",
            "firstname" => "HR",
            "lastname" => "Manager2",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "reportingManager" => 3,
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "department" => 2,
            "password" => Hash::make("123"),
            "role" => 1
        ]);

        //manager
        User::create([
            "username" => "manager",
            "email" => "ITmanager@gmail.com",
            "employeeID" => "E003",
            "firstname" => "IT",
            "lastname" => "Manager",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "reportingManager" => 3,
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "department" => 3,
            "password" => Hash::make("123"),
            "role" => 2
        ]);

        //employee
        User::create([
            "username" => "emp",
            "email" => "emp@gmail.com",
            "employeeID" => "E004",
            "firstname" => "Employee",
            "lastname" => "1",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "reportingManager" => 5,
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "department" => 3,
            "password" => Hash::make("123"),
            "role" => 3
        ]);

        User::create([
            "username" => "emp2",
            "email" => "emp2@gmail.com",
            "employeeID" => "E005",
            "firstname" => "Employee",
            "lastname" => "2",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "reportingManager" => 5,
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "department" => 3,
            "password" => Hash::make("123"),
            "role" => 3
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
