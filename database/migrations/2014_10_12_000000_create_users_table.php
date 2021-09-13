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
            $table->string('employeeID')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('contactNumber');
            $table->date('dateOfBirth');
            $table->string('gender');
            $table->string('address');
            $table->integer('department');
            $table->integer('position');
            $table->integer('role');
            $table->integer('reportingManager');
            $table->integer('delegateManager')->nullable();
            $table->string('ic');
            $table->string('nationality');
            $table->string('citizenship');
            $table->string('religion');
            $table->string('race');
            $table->string('emergencyContactName');
            $table->string('emergencyContactNumber');
            $table->string('emergencyContactAddress');
            $table->string('maritalStatus');
            $table->string('spouseName')->nullable();
            $table->date('spouseDateOfBirth')->nullable();
            $table->string('spouseIC')->nullable();
            $table->date('dateOfMarriage')->nullable();
            $table->string('spouseOccupation')->nullable();
            $table->string('spouseContactNumber')->nullable();
            $table->string('spouseResidentStatus')->nullable();
            // $table->integer('children')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        //Super Admin
        User::create([
            "username" => "superadmin",
            "email" => "superadmin@gmail.com",
            "employeeID" => "E001",
            "firstname" => "Super",
            "lastname" => "Admin",
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
            "maritalStatus" => "Single",
            "department" => 2,
            "position" => 1,
            "password" => Hash::make("123"),
            "role" => 0
        ]);
        
        //HR Manager
        User::create([
            "username" => "hrmanager",
            "email" => "hrmanager@gmail.com",
            "employeeID" => "E002",
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
            "maritalStatus" => "Single",
            "department" => 1,
            "position" => 2,
            "password" => Hash::make("123"),
            "role" => 1
        ]);

        User::create([
            "username" => "hrmanager2",
            "email" => "hrmanager2@gmail.com",
            "employeeID" => "E003",
            "firstname" => "HR",
            "lastname" => "Manager2",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "reportingManager" => 2,
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Single",
            "department" => 1,
            "position" => 2,
            "password" => Hash::make("123"),
            "role" => 1
        ]);

        //IT manager
        User::create([
            "username" => "itmanager",
            "email" => "ITmanager@gmail.com",
            "employeeID" => "E004",
            "firstname" => "IT",
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
            "maritalStatus" => "Single",
            "department" => 2,
            "position" => 3,
            "password" => Hash::make("123"),
            "role" => 2
        ]);

        User::create([
            "username" => "itmanager2",
            "email" => "ITmanager2@gmail.com",
            "employeeID" => "E005",
            "firstname" => "IT",
            "lastname" => "Manager2",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "reportingManager" => 4,
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Single",
            "department" => 2,
            "position" => 3,
            "password" => Hash::make("123"),
            "role" => 2
        ]);

        //employee
        User::create([
            "username" => "emp",
            "email" => "emp@gmail.com",
            "employeeID" => "E006",
            "firstname" => "Employee",
            "lastname" => "1",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "reportingManager" => 4,
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Married",
            "spouseName" => "Veronica",
            "spouseDateOfBirth" => "2000-6-1",
            "spouseIC" => "000601020058",
            "dateOfMarriage" => "2020-6-15",
            "spouseOccupation" => "Lecturer",
            "spouseContactNumber" => "012-4783998",
            "spouseResidentStatus" => "Resident",
            "department" => 2,
            "position" => 5,
            "password" => Hash::make("123"),
            "role" => 3
        ]);

        User::create([
            "username" => "emp2",
            "email" => "emp2@gmail.com",
            "employeeID" => "E007",
            "firstname" => "Employee",
            "lastname" => "2",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "reportingManager" => 4,
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Single",
            "department" => 2,
            "position" => 5,
            "password" => Hash::make("123"),
            "role" => 3
        ]);

        User::create([
            "username" => "emp3",
            "email" => "emp3@gmail.com",
            "employeeID" => "E008",
            "firstname" => "Employee",
            "lastname" => "3",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "reportingManager" => 4,
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Married",
            "spouseName" => "Veronica",
            "spouseDateOfBirth" => "2000-6-1",
            "spouseIC" => "000601020058",
            "dateOfMarriage" => "2020-6-15",
            "spouseOccupation" => "Lecturer",
            "spouseContactNumber" => "012-4783998",
            "spouseResidentStatus" => "Resident",
            "department" => 2,
            "position" => 5,
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
