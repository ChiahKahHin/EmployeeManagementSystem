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
            $table->string('password');
            $table->integer('role');
            $table->rememberToken();
            $table->timestamps();
        });

        //Admin
        User::create([
            "username" => "admin",
            "email" => "kahhinchiah@gmail.com",
            "firstname" => "admin",
            "lastname" => "1",
            "contactNumber" => "012-4783997",
            "dateOfBirth" => "2000-7-7",
            "gender" => "male",
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
            "gender" => "male",
            "department" => 1,
            "password" => Hash::make("123"),
            "role" => 0
        ]);
        
        //HR Manager
        User::create([
            "username" => "hrmanager",
            "email" => "kahhinchiah0707@gmail.com",
            "employeeID" => "E001",
            "firstname" => "HR",
            "lastname" => "Manager",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "department" => 2,
            "password" => Hash::make("123"),
            "role" => 1
        ]);

        User::create([
            "username" => "hrmanager1",
            "email" => "hrmanager1@gmail.com",
            "employeeID" => "E002",
            "firstname" => "HR",
            "lastname" => "Manager1",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "department" => 2,
            "password" => Hash::make("123"),
            "role" => 1
        ]);

        //manager
        User::create([
            "username" => "manager",
            "email" => "kahhinchiah12345@gmail.com",
            "employeeID" => "E003",
            "firstname" => "IT",
            "lastname" => "Manager",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "department" => 3,
            "password" => Hash::make("123"),
            "role" => 2
        ]);

        //employee
        User::create([
            "username" => "emp",
            "email" => "kahhin48@gmail.com",
            "employeeID" => "E004",
            "firstname" => "Employee",
            "lastname" => "1",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "department" => 3,
            "password" => Hash::make("123"),
            "role" => 3
        ]);

        User::create([
            "username" => "emp2",
            "email" => "chiahkahhin@hotmail.com",
            "employeeID" => "E005",
            "firstname" => "Employee",
            "lastname" => "2",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
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
