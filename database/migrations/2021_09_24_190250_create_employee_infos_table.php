<?php

use App\Models\EmployeeInfo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_info', function (Blueprint $table) {
            $table->bigInteger('userID')->primary();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('contactNumber');
            $table->date('dateOfBirth');
            $table->string('gender');
            $table->string('address');
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
        });

        //Super Admin
        EmployeeInfo::create([
            "userID" => 1,
            "firstname" => "Hugo",
            "lastname" => "1",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Single",
        ]);
        
        //HR Manager
        EmployeeInfo::create([
            "userID" => 2,
            "firstname" => "Jeremy",
            "lastname" => "1",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Single",
        ]);

        EmployeeInfo::create([
            "userID" => 3,
            "firstname" => "Jeremy",
            "lastname" => "2",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Single",
        ]);

        //IT manager
        EmployeeInfo::create([
            "userID" => 4,
            "firstname" => "Calvin",
            "lastname" => "1",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Single",
        ]);

        EmployeeInfo::create([
            "userID" => 5,
            "username" => "itmanager2",
            "email" => "ITmanager2@gmail.com",
            "employeeID" => "E005",
            "firstname" => "Calvin",
            "lastname" => "2",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Single",
        ]);

        //employee
        EmployeeInfo::create([
            "userID" => 6,
            "username" => "emp",
            "email" => "emp@gmail.com",
            "employeeID" => "E006",
            "firstname" => "Anderson",
            "lastname" => "1",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
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
        ]);

        EmployeeInfo::create([
            "userID" => 7,
            "username" => "emp2",
            "email" => "emp2@gmail.com",
            "employeeID" => "E007",
            "firstname" => "Anderson",
            "lastname" => "2",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Single",
        ]);

        EmployeeInfo::create([
            "userID" => 8,
            "username" => "emp3",
            "email" => "emp3@gmail.com",
            "employeeID" => "E008",
            "firstname" => "Veronica",
            "lastname" => "1",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "Female",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "ic" => "000707020059",
            "nationality" => "Malaysian",
            "citizenship" => "Malaysian Citizen",
            "religion" => "Buddist",
            "race" => "Chinese",
            "emergencyContactName" => "Peter Tan",
            "emergencyContactNumber" => "012-4567890",
            "emergencyContactAddress" => "1, Penang Road\nGeorgetown, Penang",
            "maritalStatus" => "Married",
            "spouseName" => "Donald",
            "spouseDateOfBirth" => "2000-6-1",
            "spouseIC" => "000601020058",
            "dateOfMarriage" => "2020-6-15",
            "spouseOccupation" => "Lecturer",
            "spouseContactNumber" => "012-4783998",
            "spouseResidentStatus" => "Resident",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_info');
    }
}
