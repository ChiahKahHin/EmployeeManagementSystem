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
            $table->string('email')->unique();
            $table->string('employeeID')->unique()->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('contactNumber');
            $table->date('dateOfBirth');
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->integer('department')->nullable();
            $table->string('password');
            $table->integer('role');
            $table->rememberToken();
            $table->timestamps();
        });

        User::create([
            "username" => "admin",
            "email" => "kahhinchiah@gmail.com",
            "firstname" => "Kah Hin",
            "lastname" => "Chiah",
            "contactNumber" => "012-4783997",
            "dateOfBirth" => "2000-7-7",
            "gender" => "male",
            "department" => 1,
            "password" => Hash::make("123"),
            "role" => 0
        ]);

        User::create([
            "username" => "admin1",
            "email" => "kahhinchiah123@gmail.com",
            "firstname" => "Kah Hin",
            "lastname" => "Chiah",
            "contactNumber" => "012-4783998",
            "dateOfBirth" => "2000-7-7",
            "gender" => "male",
            "department" => 1,
            "password" => Hash::make("123"),
            "role" => 0
        ]);
        
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
            "username" => "manager",
            "email" => "kahhinchiah12345@gmail.com",
            "employeeID" => "E002",
            "firstname" => "Peter",
            "lastname" => "Tan",
            "contactNumber" => "012-4783999",
            "dateOfBirth" => "2000-7-7",
            "gender" => "male",
            "address" => "1, Penang Road\nGeorgetown, Penang",
            "department" => 3,
            "password" => Hash::make("123"),
            "role" => 2
        ]);

        User::create([
            "username" => "emp",
            "email" => "kahhinchiah123456@gmail.com",
            "employeeID" => "E003",
            "firstname" => "Peter",
            "lastname" => "Tan",
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
